<!DOCTYPE html>
<html>
<head>
<title>SQL Snake Pro</title>
<link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
<style>

body{
background:linear-gradient(135deg,#0f172a,#1e293b);
color:white;
text-align:center;
font-family:Arial;
margin:0;
}

#container{
padding:20px;
}

canvas{
background:#111;
border-radius:15px;
box-shadow:0 0 20px #38bdf8;
}

#gameOver{
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.8);
display:none;
justify-content:center;
align-items:center;
flex-direction:column;
}

#gameOver h2{
color:#f87171;
}

button{
margin-top:15px;
}

</style>
</head>
<body>

<!-- Back Button (Top Right - Clean Placement) -->
<div class="container-fluid pt-3">
    <div class="d-flex justify-content-end">
        <a href="../games.php" class="btn btn-outline-light btn-sm">
            ← Back
        </a>
    </div>
</div>

<div id="container">
<h2>🐍 SQL Snake Pro</h2>
<p>Eat only SQL keywords. Red words = Game Over.</p>

🏆 Score: <span id="score">0</span>

<br><br>

<canvas id="gameCanvas" width="500" height="500"></canvas>

</div>

<div id="gameOver">
<h2>Game Over</h2>
<p>Your Score: <span id="finalScore"></span></p>
<button class="btn btn-warning" onclick="restartGame()">Play Again</button>
</div>

<script>

const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

const gridSize = 25;
const tileCount = canvas.width / gridSize;

let snake;
let direction;
let words;
let score;
let speed;
let lastTime = 0;
let gameRunning = true;

const sqlWords = ["SELECT","DELETE","UPDATE","INSERT","WHERE","FROM","JOIN","GROUP","ORDER","HAVING","LIMIT"];
const fakeWords = ["SAVE","PRINT","REMOVE","CREATEFILE","SEARCH","EXPORT"];

function initGame(){
snake = [{x:10,y:10,word:"SQL"}];
direction = {x:1,y:0};
words = [];
score = 0;
speed = 6;
gameRunning = true;
document.getElementById("score").innerText = score;
document.getElementById("gameOver").style.display = "none";
spawnWord();
}

function spawnWord(){
let isFake = Math.random()<0.3;
let text = isFake ?
fakeWords[Math.floor(Math.random()*fakeWords.length)] :
sqlWords[Math.floor(Math.random()*sqlWords.length)];

words.push({
x: Math.floor(Math.random()*tileCount),
y: Math.floor(Math.random()*tileCount),
text: text,
fake: isFake
});

if(words.length>6) words.shift();
}

function draw(){

ctx.fillStyle="#111";
ctx.fillRect(0,0,canvas.width,canvas.height);

/* Draw snake */
snake.forEach((segment,index)=>{
ctx.fillStyle = index===0 ? "#22c55e" : "#16a34a";
ctx.fillRect(segment.x*gridSize, segment.y*gridSize, gridSize-2, gridSize-2);

if(segment.word){
ctx.fillStyle="black";
ctx.font="10px Arial";
ctx.fillText(segment.word, segment.x*gridSize+2, segment.y*gridSize+14);
}
});

/* Draw words */
words.forEach(word=>{
ctx.fillStyle = "#2563eb";
ctx.fillRect(word.x*gridSize, word.y*gridSize, gridSize-2, gridSize-2);

ctx.fillStyle="white";
ctx.font="9px Arial";
ctx.fillText(word.text, word.x*gridSize+2, word.y*gridSize+14);
});

}

function update(){

let head = {
x: snake[0].x + direction.x,
y: snake[0].y + direction.y
};

/* Wall collision */
if(head.x<0 || head.x>=tileCount || head.y<0 || head.y>=tileCount){
endGame();
return;
}

/* Self collision */
for(let segment of snake){
if(segment.x===head.x && segment.y===head.y){
endGame();
return;
}
}

snake.unshift(head);

let ate = false;

words.forEach((word,index)=>{
if(word.x===head.x && word.y===head.y){

if(word.fake){
endGame();
return;
}else{
head.word = word.text;
score+=10;
document.getElementById("score").innerText = score;
words.splice(index,1);
ate=true;

if(score%40===0 && speed<12){
speed++;
}
}
}
});

if(!ate){
snake.pop();
}

}

function gameLoop(timestamp){

if(!gameRunning) return;

if(timestamp - lastTime > 1000/speed){
update();
draw();
lastTime = timestamp;
}

requestAnimationFrame(gameLoop);
}

function endGame(){
gameRunning=false;
document.getElementById("finalScore").innerText=score;
document.getElementById("gameOver").style.display="flex";
}

function restartGame(){
initGame();
requestAnimationFrame(gameLoop);
}

/* Controls */
document.addEventListener("keydown",e=>{
if(e.key==="ArrowUp" && direction.y!==1) direction={x:0,y:-1};
if(e.key==="ArrowDown" && direction.y!==-1) direction={x:0,y:1};
if(e.key==="ArrowLeft" && direction.x!==1) direction={x:-1,y:0};
if(e.key==="ArrowRight" && direction.x!==-1) direction={x:1,y:0};
});

/* Word spawner */
setInterval(()=>{
if(gameRunning) spawnWord();
},2500);

initGame();
requestAnimationFrame(gameLoop);

</script>

</body>
</html>