<!DOCTYPE html>
<html>
<head>
<title>Game 11 - Bug Hunter</title>
<style>
body{
    margin:0;
    background:#0f172a;
    color:white;
    text-align:center;
    font-family:sans-serif;
}

canvas{
    background:#020617;
    display:block;
    margin:auto;
    border:2px solid #334155;
}

#hud{
    padding:10px;
    font-size:20px;
}
</style>
</head>

<body>

<div id="hud">
🎯 Score: <span id="score">0</span> | ⏱ Time: <span id="time">30</span>
</div>

<canvas id="game" width="600" height="400"></canvas>

<script>
const canvas = document.getElementById("game");
const ctx = canvas.getContext("2d");

let bugs = [];
let score = 0;
let time = 30;
let running = true;

const realPatterns = [
    () => "NULL",
    () => "UNDEFINED",
    () => "NaN",
    () => "TYPE_ERROR",
    () => "SYNTAX_ERR",
    () => "INDEX_OUT"
];

const fakePatterns = [
    () => "RUN()",
    () => "PRINT()",
    () => "EXECUTE()",
    () => "SAVE_FILE()",
    () => "EXPORT_DATA()"
];

function spawn(){
    let isReal = Math.random() < 0.5;

    let text = isReal
        ? realPatterns[Math.floor(Math.random()*realPatterns.length)]()
        : fakePatterns[Math.floor(Math.random()*fakePatterns.length)]();

    bugs.push({
        x: Math.random()*520,
        y: Math.random()*350,
        text,
        real:isReal
    });

    if(bugs.length > 10) bugs.shift();
}
let spawnRate = 1000;

setInterval(()=>{
    if(running) spawn();
}, spawnRate);

setInterval(()=>{
    if(spawnRate > 400) spawnRate -= 50;
}, 5000);

canvas.addEventListener("click",e=>{
    let rect = canvas.getBoundingClientRect();
    let x = e.clientX - rect.left;
    let y = e.clientY - rect.top;

    bugs.forEach((b,i)=>{
        if(x>b.x && x<b.x+80 && y>b.y && y<b.y+30){

            if(b.real){
                score += 20;
            }else{
                score -= 10;
            }

            bugs.splice(i,1);
            document.getElementById("score").innerText=score;
        }
    });
});

function draw(){
    ctx.fillStyle="#020617";
    ctx.fillRect(0,0,canvas.width,canvas.height);

    bugs.forEach(b=>{
        ctx.fillStyle = "#38bdf8";
        ctx.fillRect(b.x,b.y,80,30);

        ctx.fillStyle="white";
        ctx.fillText(b.text,b.x+5,b.y+20);
    });
}

function loop(){
    if(!running) return;

    draw();
    requestAnimationFrame(loop);
}

setInterval(spawn,1000);

let timer = setInterval(()=>{
    time--;
    document.getElementById("time").innerText=time;

    if(time<=0){
        running=false;
        alert("Game Over! Score: "+score);
        clearInterval(timer);
    }
},1000);

loop();
</script>

</body>
</html>