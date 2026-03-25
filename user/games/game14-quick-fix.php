<!DOCTYPE html>
<html>
<head>
<title>Game 14 - Quick Fix Sprint</title>

<style>
body{
    background:#020617;
    color:white;
    text-align:center;
    font-family:monospace;
}

/* Code box */
#box{
    margin-top:80px;
    font-size:28px;
    padding:20px;
    border-radius:12px;
    background:#1e293b;
    display:inline-block;
    animation:pulse 1.5s infinite;
}

/* pulse animation */
@keyframes pulse{
    0%{transform:scale(1);}
    50%{transform:scale(1.05);}
    100%{transform:scale(1);}
}

/* options */
.option{
    margin:10px;
    padding:12px 20px;
    background:#334155;
    display:inline-block;
    border-radius:8px;
    cursor:pointer;
    transition:0.2s;
}

.option:hover{
    background:#475569;
}

/* timer bar */
#timer{
    width:300px;
    height:10px;
    background:#1e293b;
    margin:20px auto;
    border-radius:10px;
    overflow:hidden;
}

#bar{
    height:100%;
    width:100%;
    background:#38bdf8;
}

/* feedback */
#feedback{
    font-size:20px;
    height:25px;
}
</style>
</head>

<body>

<h2>⚡ Quick Fix Sprint</h2>

<div id="box"></div>

<div id="timer"><div id="bar"></div></div>

<div id="options"></div>

<div id="feedback"></div>

Score: <span id="score">0</span> |
🔥 Streak: <span id="streak">0</span>

<script>

let score = 0;
let streak = 0;
let currentAnswer = "";
let timeLeft = 100;
let timerInterval;

/* dynamic question generator */
function generate(){

    let questions = [
        {q:"print('hello'", a:"Missing )"},
        {q:"if(x=5)", a:"Use =="},
        {q:"function test {", a:"Missing ()"},
        {q:"let x", a:"Missing = value"},
        {q:"return a+b", a:"Missing ;"}
    ];

    let wrongOptions = [
        "Wrong syntax",
        "No issue",
        "Missing {}",
        "Extra comma",
        "Typo"
    ];

    let q = questions[Math.floor(Math.random()*questions.length)];

    currentAnswer = q.a;

    document.getElementById("box").innerText = q.q;

    /* mix options */
    let opts = [q.a];

    while(opts.length < 3){
        let w = wrongOptions[Math.floor(Math.random()*wrongOptions.length)];
        if(!opts.includes(w)) opts.push(w);
    }

    opts = opts.sort(()=>Math.random()-0.5);

    let html="";
    opts.forEach(o=>{
        html += `<div class="option" onclick="selectAnswer('${o}')">${o}</div>`;
    });

    document.getElementById("options").innerHTML = html;

    resetTimer();
}

/* answer logic */
function selectAnswer(ans){

    clearInterval(timerInterval);

    if(ans === currentAnswer){
        score += 20;
        streak++;
        document.getElementById("feedback").innerText = "✅ Correct!";
    }else{
        score -= 10;
        streak = 0;
        document.getElementById("feedback").innerText = "❌ Wrong!";
    }

    document.getElementById("score").innerText = score;
    document.getElementById("streak").innerText = streak;

    setTimeout(()=>{
        document.getElementById("feedback").innerText="";
        generate();
    },500);
}

/* timer */
function resetTimer(){
    timeLeft = 100;
    document.getElementById("bar").style.width = "100%";

    timerInterval = setInterval(()=>{
        timeLeft--;

        document.getElementById("bar").style.width = timeLeft + "%";

        if(timeLeft <= 0){
            clearInterval(timerInterval);
            streak = 0;
            document.getElementById("streak").innerText = streak;
            generate();
        }
    },50);
}

generate();

</script>

</body>
</html>