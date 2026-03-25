<!DOCTYPE html>
<html>
<head>
<title>Game 10 - Code Collector</title>
<link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">

<style>
body{
    background:#020617;
    color:#e2e8f0;
    text-align:center;
    font-family: 'Segoe UI', sans-serif;
    margin:0;
}

/* HUD */
#topbar h2{
    font-size:32px;
    font-weight:700;
}

#topbar{
    font-size:20px;
    letter-spacing:1px;
}

canvas{
    background:#020617;
    border:2px solid rgba(255,255,255,0.1);
    border-radius:12px;
    box-shadow:0 0 40px rgba(99,102,241,0.3);
}

/* overlay */
#gameOver{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(2,6,23,0.95);
    display:none;
    justify-content:center;
    align-items:center;
    flex-direction:column;
}

#gameOver h2{
    font-size:40px;
    font-weight:700;
}

#gameOver p{
    font-size:22px;
}

#gameOver button{
    font-size:18px;
    padding:10px 20px;
}
</style>
</head>

<body>

<div class="container-fluid pt-3">
    <div class="d-flex justify-content-end">
        <a href="../games.php" class="btn btn-outline-light btn-sm">
            ← Back
        </a>
    </div>
</div>

<div id="topbar">
    <h2>💻 Code Collector</h2>
    Score: <span id="score">0</span> |
    Level: <span id="level">1</span>
</div>

<canvas id="canvas" width="500" height="500"></canvas>

<div id="gameOver">
    <h2>Session Terminated</h2>
    <p>Score: <span id="final"></span></p>
    <button class="btn btn-light" onclick="restart()">Restart</button>
</div>

<script>

const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

const grid = 20;
const count = canvas.width / grid;

let chain, dir, tokens, score, level, speed, running, last=0;

/* vocabulary */
const valid = ["API","LOOP","FUNC","OBJ","VAR","CLASS","ARRAY"];
const invalid = ["ERR","NULL","BUG","404","NaN","CRASH"];

/* init */
function init(){
    chain=[{x:10,y:10,label:"DEV"}];
    dir={x:1,y:0};
    tokens=[];
    score=0;
    level=1;
    speed=6;
    running=true;

    document.getElementById("score").innerText=score;
    document.getElementById("level").innerText=level;
    document.getElementById("gameOver").style.display="none";

    spawn();
}

/* spawn */
function spawn(){
    let bad = Math.random()<0.4;
    let txt = bad ?
        invalid[Math.floor(Math.random()*invalid.length)] :
        valid[Math.floor(Math.random()*valid.length)];

    tokens.push({
        x:Math.floor(Math.random()*count),
        y:Math.floor(Math.random()*count),
        text:txt,
        bad:bad
    });

    if(tokens.length>5) tokens.shift();
}

/* draw grid */
function drawGrid(){
    ctx.strokeStyle="rgba(255,255,255,0.05)";
    for(let i=0;i<count;i++){
        ctx.beginPath();
        ctx.moveTo(i*grid,0);
        ctx.lineTo(i*grid,canvas.height);
        ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(0,i*grid);
        ctx.lineTo(canvas.width,i*grid);
        ctx.stroke();
    }
}

/* draw */
function draw(){

    ctx.fillStyle="#020617";
    ctx.fillRect(0,0,canvas.width,canvas.height);

    drawGrid();

    /* chain */
    chain.forEach((c,i)=>{
        ctx.fillStyle = i===0 ? "#6366f1" : "#4338ca";
        ctx.fillRect(c.x*grid,c.y*grid,grid-3,grid-3);

        if(c.label){
            ctx.fillStyle="white";
            ctx.font="14px monospace";
            ctx.fillText(c.label,c.x*grid+2,c.y*grid+14);
        }
    });

    /* tokens (same color style) */
    tokens.forEach(t=>{
        ctx.fillStyle="#0ea5e9";
        ctx.fillRect(t.x*grid,t.y*grid,grid-3,grid-3);

        ctx.fillStyle="white";
        ctx.font="13px monospace";
        ctx.fillText(t.text,t.x*grid+3,t.y*grid+17);
    });
}

/* update */
function update(){

    let head={
        x:chain[0].x + dir.x,
        y:chain[0].y + dir.y
    };

    /* wall */
    if(head.x<0 || head.y<0 || head.x>=count || head.y>=count){
        end();
        return;
    }

    /* self */
    for(let c of chain){
        if(c.x===head.x && c.y===head.y){
            end();
            return;
        }
    }

    chain.unshift(head);

    let got=false;

    tokens.forEach((t,i)=>{
        if(t.x===head.x && t.y===head.y){

            if(t.bad){
                end();
                return;
            }

            head.label = t.text;

            score += 15;
            level = Math.floor(score/120)+1;

            document.getElementById("score").innerText=score;
            document.getElementById("level").innerText=level;

            tokens.splice(i,1);
            got=true;

            if(speed<14) speed += 0.3;
        }
    });

    if(!got){
        chain.pop();
    }
}

/* loop */
function loop(t){
    if(!running) return;

    if(t-last > 1000/speed){
        update();
        draw();
        last=t;
    }

    requestAnimationFrame(loop);
}

/* end */
function end(){
    running=false;
    document.getElementById("final").innerText=score;
    document.getElementById("gameOver").style.display="flex";
}

function restart(){
    init();
    requestAnimationFrame(loop);
}

/* controls */
document.addEventListener("keydown",e=>{
    if(e.key==="ArrowUp" && dir.y!==1) dir={x:0,y:-1};
    if(e.key==="ArrowDown" && dir.y!==-1) dir={x:0,y:1};
    if(e.key==="ArrowLeft" && dir.x!==1) dir={x:-1,y:0};
    if(e.key==="ArrowRight" && dir.x!==-1) dir={x:1,y:0};
});

setInterval(()=>{ if(running) spawn(); },2000);

init();
requestAnimationFrame(loop);

</script>

</body>
</html>