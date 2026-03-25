<!DOCTYPE html>
<html>
<head>
<title>Game 13 - Code Tap Rush</title>
<style>
body{
    margin:0;
    background:#020617;
    color:white;
    text-align:center;
    font-family:sans-serif;
}

canvas{
    display:block;
    margin:auto;
    background:#020617;
}

#hud{
    padding:10px;
    font-size:20px;
}
</style>
</head>

<body>

<div id="hud">
⚡ Score: <span id="score">0</span> | ❤️ Lives: <span id="lives">3</span>
</div>

<canvas id="game" width="500" height="500"></canvas>

<script>
const canvas = document.getElementById("game");
const ctx = canvas.getContext("2d");

let items=[], score=0, lives=3, speed=2;

const good = ["LOOP","ARRAY","API","CLASS","FUNC","VAR"];
const bad = ["RUN","CLICK","SAVE","PRINT","EXEC"];

function spawn(){
    let isGood = Math.random()<0.5;
    let text = isGood ? good[Math.floor(Math.random()*good.length)]
                      : bad[Math.floor(Math.random()*bad.length)];

    items.push({
        x:Math.random()*450,
        y:0,
        text,
        good:isGood
    });
}

canvas.onclick = e=>{
    let rect = canvas.getBoundingClientRect();
    let x = e.clientX - rect.left;
    let y = e.clientY - rect.top;

    items.forEach((it,i)=>{
        if(x>it.x && x<it.x+80 && y>it.y && y<it.y+30){

            if(it.good) score+=10;
            else lives--;

            items.splice(i,1);
        }
    });
};

function update(){
    items.forEach(it=> it.y += speed);

    items = items.filter(it=>{
        if(it.y > 500){
            if(it.good) lives--;
            return false;
        }
        return true;
    });

    speed += 0.002;

    document.getElementById("score").innerText=score;
    document.getElementById("lives").innerText=lives;

    if(lives<=0){
        alert("Game Over! Score: "+score);
        location.reload();
    }
}

function draw(){
    ctx.clearRect(0,0,500,500);

    items.forEach(it=>{
        ctx.fillStyle="#38bdf8";
        ctx.fillRect(it.x,it.y,80,30);

        ctx.fillStyle="white";
        ctx.fillText(it.text,it.x+5,it.y+20);
    });
}

setInterval(spawn,1000);

function loop(){
    update();
    draw();
    requestAnimationFrame(loop);
}
loop();
</script>

</body>
</html>