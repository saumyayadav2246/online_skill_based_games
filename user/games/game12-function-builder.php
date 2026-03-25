<!DOCTYPE html>
<html>
<head>
<title>Game 12 - Function Builder</title>
<style>
body{
    background:#020617;
    color:white;
    text-align:center;
    font-family:sans-serif;
}

.box{
    display:inline-block;
    padding:15px 20px;
    margin:10px;
    background:#1e293b;
    border-radius:10px;
    cursor:pointer;
    font-size:18px;
}

#target{
    margin:20px;
    font-size:22px;
    min-height:40px;
}

#options{
    margin-top: 30px;
}
</style>
</head>

<body>

<h2>🧠 Function Builder</h2>
<p>Click in correct order to build function</p>

<div id="target"></div>

<div id="options"></div>

Score: <span id="score">0</span>
Level: <span id="level">1</span>

<script>

let score = 0;
let level = 1;
let currentQuestion = [];
let progress = [];

function generateQuestion(){

    let names = ["sum","calc","getData","process","compute"];
    let returns = ["1","a+b","x*y","true","value"];

    let name = names[Math.floor(Math.random()*names.length)];
    let ret = returns[Math.floor(Math.random()*returns.length)];

    return ["function", name, "()", "{", "return", ret, ";", "}"];
}

function load(){

    currentQuestion = generateQuestion();
    progress = [];

    document.getElementById("target").innerText = "";

    let shuffled = [...currentQuestion].sort(() => Math.random() - 0.5);

    let html = "";
    shuffled.forEach(w=>{
        html += `<div class="box" onclick="selectWord('${w}')">${w}</div>`;
    });

    document.getElementById("options").innerHTML = html;
}

function selectWord(word){

    if(word === currentQuestion[progress.length]){

        progress.push(word);

        document.getElementById("target").innerText = progress.join(" ");

        if(progress.length === currentQuestion.length){

            score += 50;
            level++;

            document.getElementById("score").innerText = score;
            document.getElementById("level").innerText = level;

            setTimeout(load, 400);
        }

    } else {

        score -= 10;
        if(score < 0) score = 0;

        document.getElementById("score").innerText = score;
    }
}

load();

</script>

</body>
</html>