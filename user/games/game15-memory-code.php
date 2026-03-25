<!DOCTYPE html>
<html>
<head>
<title>Game 15 - Memory Code</title>
<style>
body{
    background:#020617;
    color:white;
    text-align:center;
    font-family:monospace;
}

#display{
    margin-top:50px;
    font-size:28px;
}

input{
    padding:10px;
    font-size:18px;
}
</style>
</head>

<body>

<h2>🧠 Memory Flash Code</h2>

<div id="display"></div>

<br>
<input id="input" placeholder="Type code here">

<br><br>

Score: <span id="score">0</span>

<script>

let score=0;
let current="";

function generate(){

    let parts = ["let","x","=","10",";"];
    current = parts.sort(()=>Math.random()-0.5).join(" ");

    document.getElementById("display").innerText=current;

    setTimeout(()=>{
        document.getElementById("display").innerText="???";
    },2000);
}

document.getElementById("input").addEventListener("change",function(){

    if(this.value === current){
        score += 30;
    }else{
        score -= 10;
    }

    document.getElementById("score").innerText=score;
    this.value="";
    generate();
});

generate();

</script>

</body>
</html>