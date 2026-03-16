<!DOCTYPE html>
<html>
<head>
<title>SQL Build Arena PRO</title>
<link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">

<style>

body{
margin:0;
background:linear-gradient(135deg,#0f172a,#1e293b);
font-family:Arial;
color:white;
overflow:hidden;
}

h3{
text-align:center;
margin-top:10px;
font-weight:600;
letter-spacing:1px;
}

#mission{
text-align:center;
color:#38bdf8;
margin-top:10px;
font-size:18px;
font-weight:500;
}

#hud{
text-align:center;
margin-top:15px;
font-weight:bold;
}

#builder{
width:85%;
min-height:80px;
margin:25px auto;
border:2px dashed #38bdf8;
border-radius:18px;
display:flex;
align-items:center;
padding:15px;
gap:10px;
transition:0.3s;
background:rgba(255,255,255,0.03);
}

.block{
padding:10px 20px;
background:#22c55e;
color:black;
border-radius:30px;
cursor:grab;
font-weight:bold;
user-select:none;
transition:0.2s;
box-shadow:0 4px 10px rgba(0,0,0,0.3);
}

.block:hover{
transform:translateY(-3px);
}

.block:active{
cursor:grabbing;
transform:scale(1.05);
}

#options{
display:flex;
justify-content:center;
flex-wrap:wrap;
gap:18px;
margin-top:40px;
padding:0 20px;
}

.shake{
animation:shake 0.3s;
}

@keyframes shake{
0%{transform:translateX(0);}
25%{transform:translateX(-6px);}
50%{transform:translateX(6px);}
75%{transform:translateX(-6px);}
100%{transform:translateX(0);}
}

</style>
</head>

<body>

<!-- Top Right Back Button -->
<div class="container-fluid pt-3">
    <div class="d-flex justify-content-end">
        <a href="../games.php" class="btn btn-outline-light btn-sm">
            ← Back
        </a>
    </div>
</div>

<h3>⚡ SQL Build Arena PRO</h3>

<div id="mission"></div>

<div id="hud">
🔥 Combo: <span id="combo">0</span> |
🏆 Score: <span id="score">0</span>
</div>

<div id="builder"></div>

<div id="options"></div>

<script>

/* -----------------------------
   GAME STATE
--------------------------------*/

let combo = 0;
let score = 0;
let expected = [];

/* -----------------------------
   DOM REFERENCES
--------------------------------*/

const missionText = document.getElementById("mission");
const builder = document.getElementById("builder");
const options = document.getElementById("options");

/* -----------------------------
   DATA POOLS
--------------------------------*/

const tables = ["users","students","employees","products"];
const columns = {
users:["age","id"],
students:["marks","age"],
employees:["salary","age"],
products:["price","stock"]
};
const operators = [">","<","="];

/* -----------------------------
   MISSION GENERATOR
--------------------------------*/

function generateMission(){

let table = tables[Math.floor(Math.random()*tables.length)];
let column = columns[table][Math.floor(Math.random()*columns[table].length)];
let operator = operators[Math.floor(Math.random()*operators.length)];
let value = Math.floor(Math.random()*100)+1;

missionText.innerText = `Fetch ${table} where ${column} ${operator} ${value}`;

expected = [
"SELECT",
"FROM",
table,
"WHERE",
column,
operator,
value.toString()
];

builder.innerHTML = "";
generateOptions();
}

/* -----------------------------
   OPTIONS GENERATOR
--------------------------------*/

function generateOptions(){

options.innerHTML="";

let pool = [...expected,"JOIN","GROUP","LIMIT","UPDATE","DELETE","PRINT"];

pool.sort(()=>Math.random()-0.5);

pool.forEach(word=>{
let div=document.createElement("div");
div.className="block";
div.innerText=word;
div.draggable=true;

div.addEventListener("dragstart",e=>{
e.dataTransfer.setData("text/plain",word);
});

options.appendChild(div);
});
}

/* -----------------------------
   DRAG & DROP LOGIC
--------------------------------*/

builder.addEventListener("dragover",e=>{
e.preventDefault();
});

builder.addEventListener("drop",e=>{
e.preventDefault();

let word = e.dataTransfer.getData("text/plain");

if(word === expected[0]){

expected.shift();

let block=document.createElement("div");
block.className="block";
block.innerText=word;
builder.appendChild(block);

score += 10;
combo++;

if(expected.length === 0){
score += 50;
combo += 2;
generateMission();
}

}else{

combo = 0;
builder.classList.add("shake");
setTimeout(()=>builder.classList.remove("shake"),300);
}

updateHUD();
});

/* -----------------------------
   HUD UPDATE
--------------------------------*/

function updateHUD(){
document.getElementById("score").innerText = score;
document.getElementById("combo").innerText = combo;
}

/* -----------------------------
   START GAME
--------------------------------*/

updateHUD();
generateMission();

</script>

</body>
</html>  