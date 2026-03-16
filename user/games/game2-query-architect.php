<!DOCTYPE html>
<html>
<head>
<title>SQL Sniper Arena - Real Aim</title>
<link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
<style>

body{
background:#0f172a;
color:white;
text-align:center;
font-family:Arial;
overflow:hidden;
margin:0;
}

#gameArea{
height:500px;
background:#111;
position:relative;
overflow:hidden;
}

.query{
position:absolute;
padding:8px 14px;
background:#2563eb;
border-radius:8px;
white-space:nowrap;
font-size:13px;
}

#gun{
position:absolute;
bottom:10px;
left:50%;
transform:translateX(-50%);
font-size:40px;
transition:transform 0.1s linear;
}

.bullet{
position:absolute;
width:6px;
height:15px;
background:yellow;
border-radius:3px;
}

#hud{
padding:10px;
background:#0f172a;
}

</style>
</head>
<body>

<!-- Back Button (Top Right Clean Placement) -->
<div class="container-fluid pt-3">
    <div class="d-flex justify-content-end">
        <a href="../games.php" class="btn btn-outline-light btn-sm">
            ← Back
        </a>
    </div>
</div>

<div id="hud">
<h4 id="question"></h4>
❤️ Health: <span id="health">3</span> |
🔫 Bullets: <span id="bullets">3</span> |
🏆 Score: <span id="score">0</span>
<button class="btn btn-warning btn-sm" onclick="resetGame()">Reset</button>
</div>

<div id="gameArea">
<div id="gun">🔫</div>
</div>

<script>

let questions=[
{
question:"Select users where age > 18",
correct:"SELECT * FROM users WHERE age > 18"
},
{
question:"Delete user with id = 5",
correct:"DELETE FROM users WHERE id = 5"
},
{
question:"Update name to 'Sam' where id = 3",
correct:"UPDATE users SET name = 'Sam' WHERE id = 3"
}
];

let health=3;
let score=0;
let bullets=3;
let current;
let activeQueries=[];
let gun=document.getElementById("gun");
let gameArea=document.getElementById("gameArea");

function loadQuestion(){
current=questions[Math.floor(Math.random()*questions.length)];
document.getElementById("question").innerText=current.question;
spawnQueries();
}

function spawnQueries(){

activeQueries=[];
gameArea.querySelectorAll(".query").forEach(e=>e.remove());

let wrongQueries=[
"SELECT name FROM users",
"DELETE users WHERE id=5",
"UPDATE users name='Sam'",
"SELECT * users WHERE age > 18",
"DROP TABLE users"
];

let allQueries=[current.correct,...wrongQueries];
allQueries.sort(()=>Math.random()-0.5);

allQueries.forEach(q=>{
let div=document.createElement("div");
div.className="query";
div.innerText=q;
div.style.top=Math.random()*300+"px";
div.style.left=Math.random()*70+"%";
gameArea.appendChild(div);
activeQueries.push(div);
moveQuery(div);
});
}

function moveQuery(div){

let dx=(Math.random()>0.5?1:-1)*0.6;
let dy=(Math.random()>0.5?1:-1)*0.6;

setInterval(()=>{
let x=parseFloat(div.style.left);
let y=parseFloat(div.style.top);

x+=dx;
y+=dy;

if(x<0 || x>80) dx*=-1;
if(y<0 || y>350) dy*=-1;

div.style.left=x+"%";
div.style.top=y+"px";

},30);
}

/* AIM SYSTEM */
gameArea.addEventListener("mousemove",function(e){
let rect=gameArea.getBoundingClientRect();
let gunX=rect.width/2;
let gunY=rect.height;

let angle=Math.atan2(e.clientX - (rect.left + gunX), gunY - e.clientY) * (180/Math.PI);
gun.style.transform="translateX(-50%) rotate("+angle+"deg)";
});

/* SHOOT */
gameArea.addEventListener("click",function(e){

if(bullets<=0) return;

bullets--;
document.getElementById("bullets").innerText=bullets;

shootBullet(e);

});

function shootBullet(e){

let rect=gameArea.getBoundingClientRect();
let gunRect=gun.getBoundingClientRect();

let bullet=document.createElement("div");
bullet.className="bullet";

bullet.style.left=(gunRect.left + gunRect.width/2 - rect.left)+"px";
bullet.style.top=(gunRect.top - rect.top)+"px";

gameArea.appendChild(bullet);

let interval=setInterval(()=>{

let top=parseInt(bullet.style.top);
top-=10;
bullet.style.top=top+"px";

activeQueries.forEach(div=>{
let qRect=div.getBoundingClientRect();
let bRect=bullet.getBoundingClientRect();

if(
bRect.left < qRect.right &&
bRect.right > qRect.left &&
bRect.top < qRect.bottom &&
bRect.bottom > qRect.top
){

clearInterval(interval);
bullet.remove();
handleHit(div);
}
});

if(top<0){
clearInterval(interval);
bullet.remove();
}

},20);
}

function handleHit(div){

if(div.innerText===current.correct){

score+=20;
document.getElementById("score").innerText=score;
div.style.background="green";

setTimeout(()=>{
loadQuestion();
bullets=3;
document.getElementById("bullets").innerText=bullets;
},800);

}else{

div.style.background="red";
health--;
document.getElementById("health").innerText=health;

if(health<=0){
alert("Game Over! Score: "+score);
resetGame();
}
}
}

function resetGame(){
health=3;
score=0;
bullets=3;
document.getElementById("health").innerText=health;
document.getElementById("score").innerText=score;
document.getElementById("bullets").innerText=bullets;
loadQuestion();
}

loadQuestion();

</script>

</body>
</html>