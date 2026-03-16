<!DOCTYPE html>
<html>
<head>
    <title>Escape Room Logic Game</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #0ea5e9, #020617);
            color:#e5e7eb;
        }
        .top-bar{ padding:12px 20px; }

        #room{
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
        .orb1{ background:#38bdf8; top:-220px; left:-190px; animation: drift1 9s ease-in-out infinite; }
        .orb2{ background:#a78bfa; bottom:-260px; right:-220px; animation: drift2 11s ease-in-out infinite; }
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
        .fill.progress{ background: linear-gradient(90deg,#38bdf8,#6366f1); }

        .panel{
            margin-top:14px;
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.20);
            background: rgba(2,6,23,0.35);
            padding:14px;
        }
        .title{
            font-weight:800;
            font-size:1.05rem;
        }
        .sub{
            color:#cbd5f5;
            font-size:0.9rem;
            margin-top:4px;
        }

        .stage-row{
            margin-top:14px;
            display:grid;
            grid-template-columns: 1fr 0.9fr;
            gap:14px;
        }
        @media (max-width: 980px){
            .stage-row{ grid-template-columns:1fr; }
        }

        .cardy{
            border-radius:16px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.6);
            padding:12px;
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

        .input{
            border-radius:999px;
            border:1px solid rgba(148,163,184,0.25);
            background: rgba(2,6,23,0.45);
            color:#e5e7eb;
            padding:10px 14px;
            width:100%;
            outline:none;
        }
        .btn-glow{
            border-radius:999px !important;
            box-shadow: 0 0 0 1px rgba(56,189,248,0.18), 0 0 22px rgba(56,189,248,0.15);
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

        .tiles{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-top:10px;
        }
        .tile{
            padding:10px 12px;
            border-radius:14px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            cursor:pointer;
            user-select:none;
            transition: transform .12s ease, border-color .12s ease, box-shadow .12s ease;
        }
        .tile:hover{
            transform: translateY(-1px);
            border-color: rgba(56,189,248,0.55);
            box-shadow: 0 0 0 1px rgba(56,189,248,0.18);
        }
        .tile.disabled{
            opacity:0.45;
            pointer-events:none;
        }
        .slotbar{
            margin-top:10px;
            padding:10px;
            border-radius:16px;
            border:1px dashed rgba(148,163,184,0.35);
            background: rgba(2,6,23,0.35);
            min-height:54px;
            display:flex;
            gap:8px;
            align-items:center;
            flex-wrap:wrap;
        }
        .slot{
            padding:8px 10px;
            border-radius:12px;
            background: rgba(56,189,248,0.15);
            border:1px solid rgba(56,189,248,0.25);
            font-weight:800;
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
            <div class="small text-uppercase text-secondary">Escape Room Logic</div>
            <div class="fw-bold">🚪 Escape the Room</div>
        </div>
    </div>
</div>

<div id="room">
    <div class="bg-orb orb1"></div>
    <div class="bg-orb orb2"></div>

    <div id="hud">
        <div>
            <div class="title">Room <span id="stageNo">1</span> / <span id="stageTotal">6</span></div>
            <div class="sub">Solve puzzles to unlock the exit. Each room unlocks the next.</div>
        </div>

        <div class="meter">
            <div class="meter-label">
                <span>⏱ Time Left</span>
                <span><span id="timeText">180</span>s</span>
            </div>
            <div class="bar"><div id="timeFill" class="fill time"></div></div>
        </div>

        <div class="meter">
            <div class="meter-label">
                <span>🔓 Escape Progress</span>
                <span><span id="progText">0</span>%</span>
            </div>
            <div class="bar"><div id="progFill" class="fill progress"></div></div>
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
                <div class="title" id="stageTitle">Room 1: Decode the Password</div>
                <div class="sub" id="stageDesc">Crack the code to open the first lock.</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button id="hintBtn" class="btn btn-sm btn-outline-info btn-glow">Hint (2)</button>
                <button id="resetBtn" class="btn btn-sm btn-outline-light btn-glow">Restart</button>
            </div>
        </div>

        <div class="stage-row">
            <div class="cardy" id="stageArea"></div>
            <div class="cardy">
                <div class="d-flex gap-2 flex-wrap">
                    <span class="pill good">✅ Solved: <span id="solvedCount">0</span>/3</span>
                    <span class="pill bad">❌ Strikes: <span id="strikes">0</span>/4</span>
                </div>
                <div id="log"></div>
            </div>
        </div>
    </div>
</div>

<canvas id="confetti"></canvas>

<script>
let soundOn = true;
let timeLeft = 240;
let timerId = null;
let score = 0;
let streak = 0;
let strikes = 0;
let hintLeft = 2;
let stage = 0;
let solved = [];

const stages = [
  {
    title: "Room 1: Decode the Password",
    desc: "Decode this message. Enter the 4-letter password (UPPERCASE).",
    render: renderStage1,
    check: checkStage1,
  },
  {
    title: "Room 2: Number Lock",
    desc: "Solve the number puzzle to unlock the next door.",
    render: renderStage2,
    check: checkStage2,
  },
  {
    title: "Room 3: Arrange the Sequence",
    desc: "Arrange the steps in the correct order to escape.",
    render: renderStage3,
    check: checkStage3,
  }
];

const stageNo = document.getElementById("stageNo");
const stageTotal = document.getElementById("stageTotal");
const stageTitle = document.getElementById("stageTitle");
const stageDesc = document.getElementById("stageDesc");
const stageArea = document.getElementById("stageArea");
const timeText = document.getElementById("timeText");
const timeFill = document.getElementById("timeFill");
const progText = document.getElementById("progText");
const progFill = document.getElementById("progFill");
const scoreSpan = document.getElementById("score");
const streakSpan = document.getElementById("streak");
const strikesSpan = document.getElementById("strikes");
const solvedCountSpan = document.getElementById("solvedCount");
const hintBtn = document.getElementById("hintBtn");
const resetBtn = document.getElementById("resetBtn");
const soundBtn = document.getElementById("soundBtn");
const logDiv = document.getElementById("log");
const confettiCanvas = document.getElementById("confetti");

function log(msg){
  const p = document.createElement("div");
  p.innerHTML = msg;
  logDiv.prepend(p);
}

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

function clamp(v, a, b){ return Math.max(a, Math.min(b, v)); }

function updateHUD(){
  timeText.textContent = timeLeft;
  timeFill.style.width = (timeLeft / 240 * 100) + "%";
  const solvedCount = solved.filter(Boolean).length;
  solvedCountSpan.textContent = solvedCount;
  const pct = Math.round((solvedCount / stages.length) * 100);
  progText.textContent = pct;
  progFill.style.width = pct + "%";
  scoreSpan.textContent = score;
  streakSpan.textContent = streak;
  strikesSpan.textContent = strikes;
  hintBtn.textContent = `Hint (${hintLeft})`;
}

function startTimer(){
  if(timerId) clearInterval(timerId);
  timerId = setInterval(() => {
    timeLeft--;
    updateHUD();
    if(timeLeft <= 0){
      clearInterval(timerId);
      timerId = null;
      gameOver("Time’s up! The room locks permanently.");
    }
  }, 1000);
}

function gameOver(msg){
  stageArea.innerHTML = `
    <div class="text-center">
      <div class="title">Game Over</div>
      <div class="sub mt-2">${msg}</div>
      <div class="sub mt-2">Final score: <strong>${score}</strong></div>
      <button class="btn btn-light btn-glow mt-3" onclick="location.reload()">Play Again</button>
    </div>
  `;
  log(`<strong>Game Over:</strong> ${msg}`);
  beep(180, 180);
}

function winGame(){
  confettiBurst();
  stageArea.innerHTML = `
    <div class="text-center">
      <div class="title">🚪 ESCAPED!</div>
      <div class="sub mt-2">You solved all rooms. Critical thinking unlocked.</div>
      <div class="sub mt-2">Final score: <strong>${score}</strong></div>
      <a href="../games.php" class="btn btn-light btn-glow mt-3">Back to Games</a>
    </div>
  `;
  log("<span class='text-success'><strong>Victory:</strong></span> You escaped!");
  beep(880, 180);
}

function confettiBurst(){
  const c = confettiCanvas;
  const ctx = c.getContext("2d");
  c.width = window.innerWidth;
  c.height = window.innerHeight;
  c.style.display = "block";
  const pieces = Array.from({length: 150}).map(() => ({
    x: Math.random()*c.width,
    y: -20 - Math.random()*c.height*0.2,
    r: 2 + Math.random()*5,
    vx: -2 + Math.random()*4,
    vy: 2 + Math.random()*5,
    a: Math.random()*Math.PI*2,
    va: -0.2 + Math.random()*0.4,
    col: ["#38bdf8","#a78bfa","#22c55e","#f97316","#fbbf24","#ef4444"][Math.floor(Math.random()*6)]
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
    if(t > 180){
      clearInterval(id);
      c.style.display = "none";
    }
  }, 16);
}

function nextStage(){
  const solvedCount = solved.filter(Boolean).length;
  if(solvedCount >= stages.length){
    winGame();
    return;
  }
  stage = clamp(stage, 0, stages.length - 1);
  stageNo.textContent = String(stage + 1);
  stageTotal.textContent = String(stages.length);
  stageTitle.textContent = stages[stage].title;
  stageDesc.textContent = stages[stage].desc;
  stages[stage].render();
  updateHUD();
}

function markSolved(){
  if(solved[stage]) return;
  solved[stage] = true;
  score += 120 + Math.max(0, timeLeft);
  streak++;
  beep(740, 120);
  confettiBurst();
  log(`<span class="text-success"><strong>Solved:</strong></span> ${stages[stage].title} (+score)`);
  updateHUD();

  // Move to next unsolved room
  const next = solved.indexOf(false);
  if(next === -1){
    winGame();
  }else{
    stage = next;
    setTimeout(nextStage, 400);
  }
}

function strike(msg){
  strikes++;
  streak = 0;
  score = Math.max(0, score - 25);
  log(`<span class="text-danger"><strong>Wrong:</strong></span> ${msg}`);
  updateHUD();
  beep(220, 140);
  stageArea.classList.add("shake");
  setTimeout(() => stageArea.classList.remove("shake"), 250);
  if(strikes >= 4){
    gameOver("Too many wrong attempts. The locks jammed.");
  }
}

// ---------------------------
// Stage 1: Decode password
// Password: CODE
// ---------------------------
function renderStage1(){
  stageArea.innerHTML = `
    <div class="title">🔐 Cipher Note</div>
    <div class="sub mt-2">A note says: <span class="badge-pill">"FRGH"</span></div>
    <div class="sub mt-2">Clue: <strong>Shift each letter back by 3</strong> (Caesar cipher).</div>
    <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
      <input id="s1Input" class="input" maxlength="4" placeholder="Enter 4-letter password">
      <button id="s1Btn" class="btn btn-info text-dark btn-glow">Unlock</button>
    </div>
    <div class="mini mt-2">Tip: A→X, B→Y, C→Z wrap-around.</div>
  `;
  document.getElementById("s1Btn").onclick = () => {
    const v = (document.getElementById("s1Input").value || "").trim().toUpperCase();
    stages[0].check(v);
  };
}
function checkStage1(v){
  if(v === "CODE") markSolved();
  else strike("Cipher wrong. Try decoding again.");
}

// ---------------------------
// Stage 2: Number lock
// 2 → 6 → 7 → 21 → 22 → ? = 66
// ---------------------------
function renderStage2(){
  stageArea.innerHTML = `
    <div class="title">🧮 Number Lock</div>
    <div class="sub mt-2">Sequence: <span class="badge-pill">2 → 6 → 7 → 21 → 22 → ?</span></div>
    <div class="sub mt-2">Pick the correct next value:</div>
    <div class="tiles" id="s2Tiles"></div>
  `;
  const opts = [44, 66, 23, 24].sort(() => Math.random() - 0.5);
  const box = document.getElementById("s2Tiles");
  opts.forEach(n => {
    const div = document.createElement("div");
    div.className = "tile";
    div.textContent = n;
    div.onclick = () => stages[1].check(n);
    box.appendChild(div);
  });
}
function checkStage2(n){
  if(n === 66) markSolved();
  else strike("Wrong number. Find the pattern and try again.");
}

// ---------------------------
// Stage 3: Arrange sequence
// Correct order: Analyze -> Plan -> Execute -> Review
// ---------------------------
let s3Picked = [];
function renderStage3(){
  s3Picked = [];
  stageArea.innerHTML = `
    <div class="title">🧩 Sequence Panel</div>
    <div class="sub mt-2">Arrange the steps to escape (tap tiles to build the order).</div>
    <div class="tiles" id="s3Tiles"></div>
    <div class="slotbar" id="s3Slots"></div>
    <div class="mt-3 d-flex gap-2 flex-wrap">
      <button id="s3Back" class="btn btn-sm btn-outline-light btn-glow">Undo</button>
      <button id="s3Clear" class="btn btn-sm btn-outline-warning btn-glow">Clear</button>
      <button id="s3Check" class="btn btn-sm btn-success btn-glow">Submit Order</button>
    </div>
    <div class="mini mt-2">Think: critical thinking → multi-step reasoning → decomposition.</div>
  `;
  const steps = ["Review","Execute","Analyze","Plan"].sort(() => Math.random() - 0.5);
  const tiles = document.getElementById("s3Tiles");
  const slots = document.getElementById("s3Slots");
  function renderSlots(){
    slots.innerHTML = "";
    if(s3Picked.length === 0){
      slots.innerHTML = `<span class="mini">Your order will appear here…</span>`;
      return;
    }
    s3Picked.forEach(s => {
      const el = document.createElement("div");
      el.className = "slot";
      el.textContent = s;
      slots.appendChild(el);
    });
  }
  function pick(step, tileEl){
    if(s3Picked.length >= 4) return;
    s3Picked.push(step);
    tileEl.classList.add("disabled");
    tileEl.classList.add("pop");
    setTimeout(() => tileEl.classList.remove("pop"), 220);
    beep(520, 80);
    renderSlots();
  }
  steps.forEach(s => {
    const div = document.createElement("div");
    div.className = "tile";
    div.textContent = s;
    div.onclick = () => pick(s, div);
    tiles.appendChild(div);
  });
  document.getElementById("s3Back").onclick = () => {
    if(s3Picked.length === 0) return;
    const last = s3Picked.pop();
    Array.from(tiles.children).forEach(t => {
      if(t.textContent === last) t.classList.remove("disabled");
    });
    beep(330, 80);
    renderSlots();
  };
  document.getElementById("s3Clear").onclick = () => {
    s3Picked = [];
    Array.from(tiles.children).forEach(t => t.classList.remove("disabled"));
    beep(300, 80);
    renderSlots();
  };
  document.getElementById("s3Check").onclick = () => {
    stages[2].check(s3Picked.slice());
  };
  renderSlots();
}
function checkStage3(arr){
  const correct = ["Analyze","Plan","Execute","Review"];
  if(arr.length !== 4){
    strike("Order incomplete. Add all 4 steps.");
    return;
  }
  const ok = correct.every((v,i) => arr[i] === v);
  if(ok) markSolved();
  else strike("Order wrong. Think about the best problem-solving flow.");
}

// ---------------------------
// Stage 4: Word Lock (Anagram)
// Answer: TEAM
// ---------------------------
function renderStage4(){
  stageArea.innerHTML = `
    <div class="title">🧷 Word Lock</div>
    <div class="sub mt-2">Unscramble the letters to form a word:</div>
    <div class="sub mt-2"><span class="badge-pill">A M E T</span></div>
    <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
      <input id="s4Input" class="input" maxlength="4" placeholder="Type the word (UPPERCASE)">
      <button id="s4Btn" class="btn btn-info text-dark btn-glow">Unlock</button>
    </div>
  `;
  document.getElementById("s4Btn").onclick = () => {
    const v = (document.getElementById("s4Input").value || "").trim().toUpperCase();
    stages[3].check(v);
  };
}
function checkStage4(v){
  if(v === "TEAM") markSolved();
  else strike("Anagram wrong. Try rearranging the letters.");
}

// ---------------------------
// Stage 5: Math Door (Quick equation)
// Answer: 42
// ---------------------------
function renderStage5(){
  stageArea.innerHTML = `
    <div class="title">🧠 Math Door</div>
    <div class="sub mt-2">Solve: <span class="badge-pill">(6 × 7) + 0</span></div>
    <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
      <input id="s5Input" class="input" inputmode="numeric" placeholder="Enter the number">
      <button id="s5Btn" class="btn btn-info text-dark btn-glow">Unlock</button>
    </div>
  `;
  document.getElementById("s5Btn").onclick = () => {
    const v = (document.getElementById("s5Input").value || "").trim();
    stages[4].check(v);
  };
}
function checkStage5(v){
  if(v === "42") markSolved();
  else strike("Wrong number. Re-check the equation.");
}

// ---------------------------
// Stage 6: Logic Switches (Deduction)
// Correct switch: B
// ---------------------------
function renderStage6(){
  stageArea.innerHTML = `
    <div class="title">🔌 Logic Switch Panel</div>
    <div class="sub mt-2">Only one switch opens the exit. Clues:</div>
    <div class="sub mt-2 mini">
      - If <strong>A</strong> is ON → door stays locked.<br>
      - If <strong>C</strong> is ON → alarm triggers.<br>
      - Exactly <strong>one</strong> switch should be ON.
    </div>
    <div class="tiles mt-3" id="s6Tiles"></div>
  `;
  const opts = ["A","B","C"].sort(() => Math.random() - 0.5);
  const box = document.getElementById("s6Tiles");
  opts.forEach(x => {
    const div = document.createElement("div");
    div.className = "tile";
    div.textContent = "Switch " + x;
    div.onclick = () => stages[5].check(x);
    box.appendChild(div);
  });
}
function checkStage6(x){
  if(x === "B") markSolved();
  else strike("Wrong switch. Use the clues carefully.");
}

hintBtn.onclick = () => {
  if(hintLeft <= 0) return;
  hintLeft--;
  updateHUD();
  if(stage === 0){
    log("<span class='text-info'><strong>Hint:</strong></span> Caesar -3 means F→C, R→O, G→D, H→E.");
  }else if(stage === 1){
    log("<span class='text-info'><strong>Hint:</strong></span> Pattern alternates ×3 then +1.");
  }else{
    if(stage === 2) log("<span class='text-info'><strong>Hint:</strong></span> Strong flow: Analyze → Plan → Execute → Review.");
    if(stage === 3) log("<span class='text-info'><strong>Hint:</strong></span> It’s a team word. Think collaboration.");
    if(stage === 4) log("<span class='text-info'><strong>Hint:</strong></span> 6×7 is a famous number.");
    if(stage === 5) log("<span class='text-info'><strong>Hint:</strong></span> A and C are clearly bad. What's left?");
  }
  beep(640, 90);
};

resetBtn.onclick = () => location.reload();

soundBtn.onclick = () => {
  soundOn = !soundOn;
  soundBtn.textContent = "Sound: " + (soundOn ? "ON" : "OFF");
  if(soundOn) beep(740, 90);
};

function init(){
  // Expand stage pack after function declarations exist
  stages.push(
    { title: "Room 4: Word Lock", desc: "Unscramble letters to unlock.", render: renderStage4, check: checkStage4 },
    { title: "Room 5: Math Door", desc: "Solve the equation to open the door.", render: renderStage5, check: checkStage5 },
    { title: "Room 6: Logic Switches", desc: "Pick the correct switch using clues.", render: renderStage6, check: checkStage6 }
  );

  solved = Array(stages.length).fill(false);
  updateHUD();
  startTimer();
  nextStage();
  log("<span class='text-info'><strong>Start:</strong></span> Escape Room loaded. Solve all rooms before time ends.");
}
init();
</script>

</body>
</html>

