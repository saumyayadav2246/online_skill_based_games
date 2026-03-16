<!DOCTYPE html>
<html>
<head>
    <title>Pattern Prediction Game</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #f59e0b, #020617);
            color:#e5e7eb;
        }
        .top-bar{ padding:12px 20px; }

        #arena{
            max-width: 980px;
            margin: 10px auto 0;
            padding: 16px;
            border-radius: 22px;
            background: rgba(15,23,42,0.92);
            box-shadow: 0 0 30px rgba(15,23,42,0.95);
            position:relative;
            overflow:hidden;
        }
        .bg-orb{
            position:absolute;
            width:460px;
            height:460px;
            border-radius:50%;
            filter: blur(44px);
            opacity:0.20;
            pointer-events:none;
        }
        .orb1{ background:#fbbf24; top:-220px; left:-190px; animation: drift1 9s ease-in-out infinite; }
        .orb2{ background:#38bdf8; bottom:-260px; right:-220px; animation: drift2 11s ease-in-out infinite; }
        @keyframes drift1{ 0%,100%{ transform:translate(0,0);} 50%{ transform:translate(35px,18px);} }
        @keyframes drift2{ 0%,100%{ transform:translate(0,0);} 50%{ transform:translate(-30px,-22px);} }

        #hud{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            flex-wrap:wrap;
        }
        .meter{
            min-width:240px;
            flex:1;
        }
        .meter-label{
            font-size:0.85rem;
            text-transform:uppercase;
            letter-spacing:0.05em;
            margin-bottom:4px;
            color:#9ca3af;
            display:flex;
            justify-content:space-between;
        }
        .bar{
            height:10px;
            border-radius:999px;
            background:#111827;
            overflow:hidden;
        }
        .fill{
            height:100%;
            width:0%;
            border-radius:inherit;
            transition: width .25s ease-out;
        }
        .fill.time{ background: linear-gradient(90deg,#22c55e,#facc15,#ef4444); }
        .fill.combo{ background: linear-gradient(90deg,#38bdf8,#6366f1); }

        .panel{
            margin-top:14px;
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.20);
            background: rgba(2,6,23,0.35);
            padding:14px;
        }
        .title{
            font-weight:900;
            font-size:1.1rem;
            letter-spacing:0.02em;
        }
        .sub{
            color:#cbd5f5;
            font-size:0.9rem;
            margin-top:4px;
        }
        .badge-pill{
            border-radius:999px;
            padding:6px 10px;
            font-size:0.78rem;
            background:linear-gradient(135deg,rgba(99,102,241,0.35),rgba(56,189,248,0.25));
            border:1px solid rgba(99,102,241,0.45);
            color:#e0e7ff;
        }
        .pill{
            border-radius:999px;
            padding:6px 10px;
            font-size:0.78rem;
            border:1px solid rgba(148,163,184,0.35);
            background: rgba(2,6,23,0.35);
            color:#e5e7eb;
        }
        .pill.good{ border-color:rgba(34,197,94,0.55); color:#bbf7d0; }
        .pill.bad{ border-color:rgba(239,68,68,0.55); color:#fecaca; }
        .pill.info{ border-color:rgba(56,189,248,0.55); color:#bae6fd; }

        .seq-box{
            margin-top:12px;
            padding:14px;
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            font-size:1.25rem;
            font-weight:900;
            letter-spacing:0.03em;
            text-align:center;
        }
        .choices{
            margin-top:14px;
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap:10px;
        }
        .choice{
            border-radius:16px;
            border:1px solid rgba(148,163,184,0.22);
            background: rgba(15,23,42,0.65);
            color:#e5e7eb;
            padding:12px 12px;
            cursor:pointer;
            transition: transform .12s ease, border-color .12s ease, box-shadow .12s ease;
            user-select:none;
            font-weight:800;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
        }
        .choice:hover{
            transform: translateY(-1px);
            border-color: rgba(251,191,36,0.65);
            box-shadow: 0 0 0 1px rgba(251,191,36,0.18), 0 0 18px rgba(251,191,36,0.10);
        }
        .choice.correct{
            border-color: rgba(34,197,94,0.7);
            box-shadow: 0 0 0 1px rgba(34,197,94,0.25), 0 0 18px rgba(34,197,94,0.15);
        }
        .choice.wrong{
            border-color: rgba(239,68,68,0.7);
            box-shadow: 0 0 0 1px rgba(239,68,68,0.22), 0 0 18px rgba(239,68,68,0.12);
        }
        .choice.disabled{
            opacity:0.45;
            pointer-events:none;
        }
        .shake{ animation: shake .25s ease-in-out; }
        @keyframes shake{
            0%{ transform:translateX(0); }
            25%{ transform:translateX(-6px); }
            50%{ transform:translateX(6px); }
            75%{ transform:translateX(-4px); }
            100%{ transform:translateX(0); }
        }
        .pop{ animation: pop .22s ease-in-out; }
        @keyframes pop{
            0%{ transform:scale(1); }
            50%{ transform:scale(1.03); }
            100%{ transform:scale(1); }
        }
        #explain{
            margin-top:12px;
            font-size:0.9rem;
            color:#cbd5f5;
            display:none;
        }
        #log{
            margin-top:10px;
            font-size:0.8rem;
            color:#9ca3af;
            max-height:140px;
            overflow-y:auto;
        }
        #confetti{
            position:fixed;
            inset:0;
            pointer-events:none;
            z-index:9999;
            display:none;
        }
    </style>
</head>
<body>

<div class="container-fluid top-bar">
    <div class="d-flex justify-content-between align-items-center">
        <a href="../games.php" class="btn btn-outline-light btn-sm">← Back</a>
        <div class="text-end">
            <div class="small text-uppercase text-secondary">Pattern Prediction</div>
            <div class="fw-bold">🔮 Find the Rule</div>
        </div>
    </div>
</div>

<div id="arena">
    <div class="bg-orb orb1"></div>
    <div class="bg-orb orb2"></div>

    <div id="hud">
        <div>
            <div class="title">Question <span id="qNo">1</span> · Infinite Mode</div>
            <div class="sub">Identify the hidden rule and predict the next value.</div>
        </div>

        <div class="meter">
            <div class="meter-label">
                <span>⏱ Time</span>
                <span><span id="timeText">20</span>s</span>
            </div>
            <div class="bar"><div id="timeFill" class="fill time"></div></div>
        </div>

        <div class="meter">
            <div class="meter-label">
                <span>🔥 Combo Power</span>
                <span><span id="comboText">0</span>%</span>
            </div>
            <div class="bar"><div id="comboFill" class="fill combo"></div></div>
        </div>

        <div class="text-end">
            <div>🏆 Score: <span id="score">0</span></div>
            <div class="d-flex gap-2 justify-content-end flex-wrap mt-1">
                <span class="badge-pill">🔥 Streak: <span id="streak">0</span></span>
                <button id="soundBtn" class="pill" type="button">Sound: ON</button>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div class="title" id="patternTitle">Pattern Lock</div>
                <div class="sub" id="patternHint">Solve fast for bonus points.</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button id="hintBtn" class="btn btn-sm btn-outline-info btn-glow">Hint (2)</button>
                <button id="skipBtn" class="btn btn-sm btn-outline-light btn-glow">Skip (-10)</button>
            </div>
        </div>

        <div class="seq-box" id="sequence">—</div>
        <div class="choices" id="choices"></div>
        <div id="explain"></div>

        <div class="mt-3 d-flex gap-2 flex-wrap">
            <span class="pill good">✅ Correct: <span id="correctCount">0</span></span>
            <span class="pill bad">❌ Mistakes: <span id="mistakes">0</span>/5</span>
            <span class="pill info">🧠 Difficulty: <span id="diff">Easy</span></span>
        </div>

        <div id="log"></div>
    </div>
</div>

<canvas id="confetti"></canvas>

<script>
let soundOn = true;
let qNo = 1;
let score = 0;
let streak = 0;
let correctCount = 0;
let mistakes = 0;
let hintLeft = 2;
let timeLeft = 20;
let timerId = null;
let locked = false;

let current = null; // { seq:[], answer:number, explain:string, title:string, diff:string }

const qNoSpan = document.getElementById("qNo");
const scoreSpan = document.getElementById("score");
const streakSpan = document.getElementById("streak");
const timeText = document.getElementById("timeText");
const timeFill = document.getElementById("timeFill");
const comboText = document.getElementById("comboText");
const comboFill = document.getElementById("comboFill");
const soundBtn = document.getElementById("soundBtn");
const hintBtn = document.getElementById("hintBtn");
const skipBtn = document.getElementById("skipBtn");
const patternTitle = document.getElementById("patternTitle");
const patternHint = document.getElementById("patternHint");
const sequenceDiv = document.getElementById("sequence");
const choicesDiv = document.getElementById("choices");
const explainDiv = document.getElementById("explain");
const correctCountSpan = document.getElementById("correctCount");
const mistakesSpan = document.getElementById("mistakes");
const diffSpan = document.getElementById("diff");
const logDiv = document.getElementById("log");
const confettiCanvas = document.getElementById("confetti");

function log(msg){
  const p = document.createElement("div");
  p.innerHTML = msg;
  logDiv.prepend(p);
}

function clamp(v,a,b){ return Math.max(a, Math.min(b, v)); }

function beep(freq, ms){
  if(!soundOn) return;
  try{
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    o.type = "triangle";
    o.frequency.value = freq;
    g.gain.value = 0.045;
    o.connect(g);
    g.connect(ctx.destination);
    o.start();
    setTimeout(() => { o.stop(); ctx.close(); }, ms);
  }catch(e){}
}

function confettiBurst(){
  const c = confettiCanvas;
  const ctx = c.getContext("2d");
  c.width = window.innerWidth;
  c.height = window.innerHeight;
  c.style.display = "block";
  const pieces = Array.from({length: 140}).map(() => ({
    x: Math.random()*c.width,
    y: -20 - Math.random()*c.height*0.2,
    r: 2 + Math.random()*5,
    vx: -2 + Math.random()*4,
    vy: 2 + Math.random()*5,
    a: Math.random()*Math.PI*2,
    va: -0.2 + Math.random()*0.4,
    col: ["#fbbf24","#38bdf8","#a78bfa","#22c55e","#f97316","#ef4444"][Math.floor(Math.random()*6)]
  }));
  let t = 0;
  const id = setInterval(() => {
    t += 1;
    ctx.clearRect(0,0,c.width,c.height);
    pieces.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;
      p.vy += 0.04;
      p.a += p.va;
      ctx.save();
      ctx.translate(p.x,p.y);
      ctx.rotate(p.a);
      ctx.fillStyle = p.col;
      ctx.fillRect(-p.r, -p.r, p.r*2, p.r*2);
      ctx.restore();
    });
    if(t > 170){
      clearInterval(id);
      c.style.display = "none";
    }
  }, 16);
}

function updateHUD(){
  qNoSpan.textContent = qNo;
  scoreSpan.textContent = score;
  streakSpan.textContent = streak;
  correctCountSpan.textContent = correctCount;
  mistakesSpan.textContent = mistakes;
  hintBtn.textContent = `Hint (${hintLeft})`;

  timeText.textContent = timeLeft;
  timeFill.style.width = (timeLeft / 20 * 100) + "%";

  const comboPct = clamp(streak * 10, 0, 100);
  comboText.textContent = comboPct;
  comboFill.style.width = comboPct + "%";
}

function startTimer(){
  if(timerId) clearInterval(timerId);
  timerId = setInterval(() => {
    if(locked) return;
    timeLeft--;
    updateHUD();
    if(timeLeft <= 0){
      clearInterval(timerId);
      timerId = null;
      handleTimeout();
    }
  }, 1000);
}

function handleTimeout(){
  mistakes++;
  streak = 0;
  locked = true;
  showExplain(false, "Time up! Try to identify the rule faster next time.");
  beep(180, 160);
  if(mistakes >= 5){
    gameOver("Too many misses. Pattern lock failed.");
  }else{
    setTimeout(nextQuestion, 900);
  }
}

function gameOver(msg){
  locked = true;
  explainDiv.style.display = "block";
  explainDiv.innerHTML = `<div class="pill bad"><strong>Game Over:</strong> ${msg} Final score: ${score}</div>`;
  log(`<strong>Game Over:</strong> ${msg}`);
  Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));
}

function showExplain(isCorrect, extra){
  explainDiv.style.display = "block";
  explainDiv.innerHTML = `
    <div class="pill ${isCorrect ? "good" : "bad"}">
      <strong>${isCorrect ? "Correct!" : "Wrong!"}</strong>
      ${extra ? " " + extra : ""}
    </div>
    <div class="sub mt-2"><strong>Rule:</strong> ${current.explain}</div>
  `;
}

function makeChoices(answer){
  const set = new Set([answer]);
  while(set.size < 4){
    const delta = (Math.random() < 0.5 ? -1 : 1) * (2 + Math.floor(Math.random()*12));
    set.add(answer + delta);
  }
  const arr = Array.from(set).sort(() => Math.random() - 0.5);
  return arr;
}

// Random pattern generator
function genPattern(){
  const type = Math.floor(Math.random()*6);

  // 0: n(n+1) like 2,6,12,20,...
  if(type === 0){
    const startN = 1 + Math.floor(Math.random()*3);
    const terms = [];
    for(let i = 0; i < 4; i++){
      const n = startN + i;
      terms.push(n*(n+1));
    }
    const nextN = startN + 4;
    const ans = nextN*(nextN+1);
    return { seq: terms, answer: ans, explain: "Sequence is n(n+1).", title: "n(n+1) Pattern", diff:"Easy" };
  }

  // 1: arithmetic progression
  if(type === 1){
    const a = 1 + Math.floor(Math.random()*20);
    const d = 2 + Math.floor(Math.random()*9);
    const terms = [a, a+d, a+2*d, a+3*d];
    return { seq: terms, answer: a+4*d, explain: `Add ${d} each time.`, title: "Arithmetic Pattern", diff:"Easy" };
  }

  // 2: geometric progression
  if(type === 2){
    const a = 1 + Math.floor(Math.random()*6);
    const r = 2 + Math.floor(Math.random()*3);
    const terms = [a, a*r, a*r*r, a*r*r*r];
    return { seq: terms, answer: a*r*r*r*r, explain: `Multiply by ${r} each time.`, title: "Geometric Pattern", diff:"Medium" };
  }

  // 3: alternating +a, +b
  if(type === 3){
    const a = 2 + Math.floor(Math.random()*8);
    const b = 2 + Math.floor(Math.random()*10);
    const s = 5 + Math.floor(Math.random()*20);
    const terms = [s, s+a, s+a+b, s+a+b+a];
    return { seq: terms, answer: s+a+b+a+b, explain: `Alternate +${a}, then +${b}.`, title: "Alternating Pattern", diff:"Medium" };
  }

  // 4: second difference constant (quadratic)
  if(type === 4){
    const base = 1 + Math.floor(Math.random()*10);
    const first = 1 + Math.floor(Math.random()*6);
    const second = 1 + Math.floor(Math.random()*4); // constant second diff
    const terms = [];
    let v = base;
    let d = first;
    for(let i=0;i<4;i++){
      terms.push(v);
      v += d;
      d += second;
    }
    const ans = v;
    return { seq: terms, answer: ans, explain: `Differences increase by ${second} each step (quadratic pattern).`, title:"Quadratic Differences", diff:"Hard" };
  }

  // 5: triangular numbers (n(n+1)/2)
  {
    const startN = 3 + Math.floor(Math.random()*4);
    const tri = n => (n*(n+1))/2;
    const terms = [tri(startN), tri(startN+1), tri(startN+2), tri(startN+3)];
    const ans = tri(startN+4);
    return { seq: terms, answer: ans, explain: "Triangular numbers: n(n+1)/2.", title:"Triangular Pattern", diff:"Easy" };
  }
}

function renderQuestion(){
  locked = false;
  explainDiv.style.display = "none";
  choicesDiv.innerHTML = "";
  patternTitle.textContent = current.title;
  diffSpan.textContent = current.diff;
  patternHint.textContent = current.diff === "Hard" ? "Hard mode: watch the differences." : "Solve fast for bonus points.";
  sequenceDiv.textContent = current.seq.join(", ") + ", ?";

  const options = makeChoices(current.answer);
  options.forEach((val, idx) => {
    const div = document.createElement("div");
    div.className = "choice";
    div.innerHTML = `<span>${val}</span><span class="badge-pill">${String.fromCharCode(65+idx)}</span>`;
    div.onclick = () => pickAnswer(val, div);
    choicesDiv.appendChild(div);
  });
}

function pickAnswer(val, el){
  if(locked) return;
  locked = true;
  Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));

  const isCorrect = val === current.answer;
  if(isCorrect){
    el.classList.add("correct");
    const timeBonus = Math.max(0, timeLeft);
    const streakBonus = Math.min(60, streak * 6);
    const gain = 50 + timeBonus + streakBonus;
    score += gain;
    streak++;
    correctCount++;
    confettiBurst();
    beep(880, 120);
    showExplain(true, `+${gain} points`);
    log(`<span class="text-success"><strong>Correct:</strong></span> ${current.seq.join(", ")} → <strong>${current.answer}</strong>`);
  }else{
    el.classList.add("wrong");
    mistakes++;
    streak = 0;
    score = Math.max(0, score - 20);
    beep(220, 140);
    showExplain(false, `Correct answer was ${current.answer}`);
    log(`<span class="text-danger"><strong>Wrong:</strong></span> You chose ${val}, answer was ${current.answer}`);
    choicesDiv.classList.add("shake");
    setTimeout(() => choicesDiv.classList.remove("shake"), 250);
    if(mistakes >= 5){
      gameOver("Too many mistakes. Patterns defeated you.");
      updateHUD();
      return;
    }
  }

  updateHUD();
  setTimeout(nextQuestion, 1100);
}

function nextQuestion(){
  qNo++;
  timeLeft = 20;
  current = genPattern();
  renderQuestion();
  updateHUD();
  startTimer();
}

hintBtn.onclick = () => {
  if(hintLeft <= 0 || locked) return;
  hintLeft--;
  updateHUD();
  // disable one wrong option
  const wrong = Array.from(choicesDiv.children).filter(c => !c.textContent.includes(String(current.answer)) && !c.classList.contains("disabled"));
  if(wrong.length > 0){
    const pick = wrong[Math.floor(Math.random()*wrong.length)];
    pick.classList.add("disabled");
    pick.style.opacity = "0.25";
    pick.style.pointerEvents = "none";
    log("<span class='text-info'><strong>Hint:</strong></span> Removed one wrong option.");
    beep(520, 90);
  }
};

skipBtn.onclick = () => {
  if(locked) return;
  score = Math.max(0, score - 10);
  streak = 0;
  mistakes++;
  updateHUD();
  log("<span class='text-secondary'><strong>Skip:</strong></span> You skipped this pattern (-10).");
  if(mistakes >= 5){
    gameOver("Too many skips/mistakes. Game over.");
    return;
  }
  nextQuestion();
};

soundBtn.onclick = () => {
  soundOn = !soundOn;
  soundBtn.textContent = "Sound: " + (soundOn ? "ON" : "OFF");
  if(soundOn) beep(740, 90);
};

// init
current = genPattern();
renderQuestion();
updateHUD();
startTimer();
log("<span class='text-info'><strong>Start:</strong></span> Infinite patterns generated. Keep predicting!");
</script>

</body>
</html>

