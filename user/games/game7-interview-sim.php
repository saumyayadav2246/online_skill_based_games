<!DOCTYPE html>
<html>
<head>
    <title>Interview Conversation Simulator</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #22c55e, #020617);
            color:#e5e7eb;
        }
        .top-bar{ padding:12px 20px; }

        #app{
            max-width: 980px;
            margin: 10px auto 0;
            padding: 16px;
            border-radius: 22px;
            background: rgba(15,23,42,0.92);
            box-shadow: 0 0 30px rgba(15,23,42,0.95);
            position:relative;
            overflow:hidden;
        }
        .orb{
            position:absolute;
            width:480px;
            height:480px;
            border-radius:50%;
            filter: blur(46px);
            opacity:0.18;
            pointer-events:none;
        }
        .o1{ background:#22c55e; top:-240px; left:-210px; animation: drift1 10s ease-in-out infinite; }
        .o2{ background:#38bdf8; bottom:-280px; right:-240px; animation: drift2 12s ease-in-out infinite; }
        @keyframes drift1{ 0%,100%{ transform:translate(0,0);} 50%{ transform:translate(34px,18px);} }
        @keyframes drift2{ 0%,100%{ transform:translate(0,0);} 50%{ transform:translate(-30px,-22px);} }

        #hud{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            flex-wrap:wrap;
        }
        .meter{ min-width:240px; flex:1; }
        .label{
            font-size:0.85rem;
            text-transform:uppercase;
            letter-spacing:0.05em;
            color:#9ca3af;
            display:flex;
            justify-content:space-between;
            margin-bottom:4px;
        }
        .bar{
            height:10px;
            border-radius:999px;
            background:#111827;
            overflow:hidden;
        }
        .fill{ height:100%; width:0%; border-radius:inherit; transition: width .25s ease-out; }
        .fill.time{ background:linear-gradient(90deg,#22c55e,#facc15,#ef4444); }
        .fill.quality{ background:linear-gradient(90deg,#38bdf8,#6366f1); }

        .panel{
            margin-top:14px;
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.20);
            background: rgba(2,6,23,0.35);
            padding:14px;
        }
        .title{ font-weight:900; font-size:1.1rem; }
        .sub{ color:#cbd5f5; font-size:0.9rem; margin-top:4px; }
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

        .sceneWrap{
            margin-top:12px;
            border-radius:20px;
            border:1px solid rgba(148,163,184,0.16);
            overflow:hidden;
            background: linear-gradient(180deg, rgba(2,6,23,0.25), rgba(2,6,23,0.45));
            position:relative;
        }
        .officeBg{
            position:absolute;
            inset:0;
            pointer-events:none;
            opacity:0.75;
            background:
                radial-gradient(circle at 20% 20%, rgba(56,189,248,0.22), transparent 46%),
                radial-gradient(circle at 80% 35%, rgba(34,197,94,0.18), transparent 52%),
                linear-gradient(180deg, rgba(15,23,42,0.10), rgba(2,6,23,0.65));
        }
        .officeSil{
            position:absolute;
            left:0; right:0; bottom:-10px;
            height:160px;
            background:
                linear-gradient(180deg, transparent, rgba(2,6,23,0.85)),
                radial-gradient(circle at 20% 100%, rgba(0,0,0,0.0), rgba(0,0,0,0.35));
            pointer-events:none;
            opacity:0.9;
        }
        .officeSil:before{
            content:"";
            position:absolute;
            left:10%;
            bottom:26px;
            width:56%;
            height:44px;
            border-radius: 14px;
            background: rgba(2,6,23,0.65);
            border:1px solid rgba(148,163,184,0.10);
            box-shadow: 0 -14px 40px rgba(0,0,0,0.35) inset;
        }
        .officeSil:after{
            content:"";
            position:absolute;
            right:10%;
            bottom:18px;
            width:110px;
            height:70px;
            border-radius: 16px;
            background: rgba(2,6,23,0.55);
            border:1px solid rgba(148,163,184,0.08);
            box-shadow: 0 -10px 32px rgba(0,0,0,0.35) inset;
        }
        .scene{
            position:relative;
            padding:14px;
            min-height: 260px;
        }
        .sceneTitle{
            font-weight:900;
            letter-spacing:0.02em;
            font-size:1.05rem;
        }
        .sceneSub{
            color:#cbd5f5;
            font-size:0.92rem;
            margin-top:4px;
        }
        .stageRow{
            margin-top:12px;
            display:grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap:12px;
            align-items:stretch;
        }
        @media (max-width: 900px){
            .stageRow{ grid-template-columns: 1fr; }
        }
        .cardy{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            padding:12px;
        }
        .door{
            height: 210px;
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.16);
            background:
                radial-gradient(circle at 30% 20%, rgba(56,189,248,0.16), rgba(2,6,23,0.15)),
                linear-gradient(180deg, rgba(2,6,23,0.25), rgba(2,6,23,0.55));
            position:relative;
            overflow:hidden;
        }
        .door:before{
            content:"";
            position:absolute;
            left:50%;
            transform:translateX(-50%);
            top:10px;
            width:78%;
            height:86%;
            border-radius:16px;
            border:1px solid rgba(148,163,184,0.14);
            background: rgba(2,6,23,0.25);
        }
        .knob{
            position:absolute;
            right: 24%;
            top: 48%;
            width:16px;
            height:16px;
            border-radius:999px;
            background: radial-gradient(circle at 30% 30%, #fde68a, #f59e0b);
            box-shadow: 0 0 18px rgba(245,158,11,0.25);
        }
        .student{
            position:absolute;
            left: 20%;
            bottom: 12px;
            width: 120px;
            height: 170px;
            transform: translateX(-220px);
            opacity:0;
        }
        .student .head{
            width:54px; height:54px; border-radius:999px;
            background: radial-gradient(circle at 30% 30%, rgba(226,232,240,0.85), rgba(148,163,184,0.45));
            border:1px solid rgba(148,163,184,0.25);
            margin: 0 auto;
        }
        .student .body{
            width:86px; height:96px; border-radius:18px;
            background: linear-gradient(135deg, rgba(34,197,94,0.20), rgba(56,189,248,0.12));
            border:1px solid rgba(34,197,94,0.25);
            margin: 10px auto 0;
            box-shadow: 0 18px 40px rgba(0,0,0,0.25);
        }
        .student.enter{ animation: enterRoom 1.0s cubic-bezier(.2,.9,.2,1) forwards; }
        @keyframes enterRoom{
            0%{ transform: translateX(-220px); opacity:0; }
            60%{ opacity:1; }
            100%{ transform: translateX(0); opacity:1; }
        }

        .btn-glow{
            border-radius:14px;
            padding:10px 14px;
            font-weight:900;
            border:1px solid rgba(34,197,94,0.35);
            background: linear-gradient(135deg, rgba(34,197,94,0.20), rgba(56,189,248,0.14));
            color:#e5e7eb;
            transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
        }
        .btn-glow:hover{
            transform: translateY(-1px);
            border-color: rgba(34,197,94,0.60);
            box-shadow: 0 0 0 1px rgba(34,197,94,0.18), 0 0 22px rgba(34,197,94,0.10);
        }

        .room{
            display:grid;
            grid-template-columns: 320px 1fr;
            gap:12px;
            margin-top:12px;
        }
        @media (max-width: 900px){
            .room{ grid-template-columns: 1fr; }
        }
        .side{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            padding:12px;
        }
        .avatar{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: radial-gradient(circle at 30% 20%, rgba(56,189,248,0.20), rgba(2,6,23,0.15));
            height:160px;
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            padding:12px;
            overflow:hidden;
            position:relative;
        }
        .avatar:after{
            content:"";
            position:absolute;
            inset:auto -40px -60px -40px;
            height:140px;
            background: linear-gradient(180deg, transparent, rgba(2,6,23,0.55));
        }
        .name{ font-weight:900; font-size:1.05rem; position:relative; z-index:1; }
        .role{ font-size:0.78rem; color:#a5b4fc; text-transform:uppercase; letter-spacing:0.06em; position:relative; z-index:1; }
        .mic{
            width:42px; height:42px; border-radius:14px;
            background: rgba(34,197,94,0.12);
            border:1px solid rgba(34,197,94,0.30);
            display:flex; align-items:center; justify-content:center;
            font-size:1.15rem;
            position:relative; z-index:1;
        }
        .dots{ display:inline-flex; gap:4px; }
        .dot{
            width:7px; height:7px; border-radius:999px;
            background: rgba(226,232,240,0.55);
            animation: bounce 1.1s infinite;
        }
        .dot:nth-child(2){ animation-delay: .12s; }
        .dot:nth-child(3){ animation-delay: .24s; }
        @keyframes bounce{
            0%, 80%, 100%{ transform: translateY(0); opacity:.55; }
            40%{ transform: translateY(-6px); opacity:1; }
        }

        .chat{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            padding:12px;
        }
        .bubble{
            border-radius:16px;
            padding:10px 12px;
            border:1px solid rgba(148,163,184,0.18);
            margin-bottom:10px;
        }
        .bubble.q{ background: rgba(56,189,248,0.10); }
        .bubble.a{ background: rgba(34,197,94,0.10); }
        .bubble .who{ font-size:0.78rem; color:#9ca3af; text-transform:uppercase; letter-spacing:0.06em; }
        .bubble .txt{ font-weight:800; margin-top:4px; }

        .choices{
            margin-top:12px;
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            border-color: rgba(34,197,94,0.65);
            box-shadow: 0 0 0 1px rgba(34,197,94,0.18), 0 0 18px rgba(34,197,94,0.10);
        }
        .choice.disabled{ opacity:0.45; pointer-events:none; }
        .choice.correct{ border-color: rgba(34,197,94,0.75); }
        .choice.wrong{ border-color: rgba(239,68,68,0.75); }
        .shake{ animation: shake .25s ease-in-out; }
        @keyframes shake{
            0%{ transform:translateX(0); }
            25%{ transform:translateX(-6px); }
            50%{ transform:translateX(6px); }
            75%{ transform:translateX(-4px); }
            100%{ transform:translateX(0); }
        }
        #explain{ display:none; margin-top:12px; }

        #confetti{
            position:fixed;
            inset:0;
            pointer-events:none;
            z-index:9999;
            display:none;
        }
        #log{
            margin-top:10px;
            font-size:0.8rem;
            color:#9ca3af;
            max-height:140px;
            overflow-y:auto;
        }
    </style>
</head>
<body>

<div class="container-fluid top-bar">
    <div class="d-flex justify-content-between align-items-center">
        <a href="../games.php" class="btn btn-outline-light btn-sm">← Back</a>
        <div class="text-end">
            <div class="small text-uppercase text-secondary">Interview Simulator</div>
            <div class="fw-bold">🎙️ Speak Smart, Stay Confident</div>
        </div>
    </div>
</div>

<div id="app">
    <div class="orb o1"></div>
    <div class="orb o2"></div>

    <div id="hud">
        <div>
            <div class="title">Interview <span id="qNo">1</span> · Room Simulation</div>
            <div class="sub">Enter the room, greet politely, then answer like a pro. (Auto continues)</div>
        </div>

        <div class="meter">
            <div class="label"><span>⏱ Time</span><span><span id="timeText">18</span>s</span></div>
            <div class="bar"><div id="timeFill" class="fill time"></div></div>
        </div>

        <div class="meter">
            <div class="label"><span>💬 Quality</span><span><span id="qualityText">0</span>%</span></div>
            <div class="bar"><div id="qualityFill" class="fill quality"></div></div>
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
        <div class="d-flex gap-2 flex-wrap">
            <span class="pill good">✅ Great Answers: <span id="good">0</span></span>
            <span class="pill bad">❌ Mistakes: <span id="mistakes">0</span>/5</span>
            <span class="pill">🎯 Role: <span id="jobRole">Web Developer</span></span>
            <span class="pill">🧠 Impression: <span id="moodText">50</span>%</span>
        </div>

        <div class="sceneWrap" id="sceneWrap">
            <div class="officeBg"></div>
            <div class="scene" id="scene"></div>
            <div class="officeSil"></div>
        </div>
    </div>
</div>

<canvas id="confetti"></canvas>

<script>
let soundOn = true;
let qNo = 1;
let score = 0;
let streak = 0;
let good = 0;
let mistakes = 0;
let timeLeft = 18;
let timerId = null;
let locked = false;
let quality = 0;
let mood = 50;
let currentItem = null;
let stage = "enter";
let asked = 0;
let maxQuestions = 6;
let pickedThisStage = false;

const bank = [
  {
    q: "Tell me about yourself.",
    options: [
      { t: "I’m a student who likes coding. That’s it.", pts: 8, good:false, why:"Some info, but too short and not structured." },
      { t: "I’m a student passionate about web development and problem-solving. I build projects, learn fast, and enjoy teamwork.", pts: 20, good:true, why:"Strong structure: identity + interest + proof + attitude." },
      { t: "I don’t know what to say.", pts: 0, good:false, why:"Shows low confidence and no structure." },
      { t: "I’m just normal, nothing special.", pts: 2, good:false, why:"Downplays yourself and stops the conversation." }
    ]
  },
  {
    q: "Why do you want this role?",
    options: [
      { t: "Because I need a job urgently.", pts: 3, good:false, why:"Honest, but doesn’t show fit." },
      { t: "I enjoy building products, and this role matches my interest in clean UI and reliable backend APIs. I want to grow here.", pts: 20, good:true, why:"Shows alignment + growth mindset." },
      { t: "No reason.", pts: 0, good:false, why:"Signals no interest." },
      { t: "Your company is famous so I applied.", pts: 7, good:false, why:"Better, but still not role-specific." }
    ]
  },
  {
    q: "How do you handle deadlines?",
    options: [
      { t: "I work without planning and hope it finishes.", pts: 3, good:false, why:"Risky. No process." },
      { t: "I break tasks into steps, prioritize, and communicate early if there’s risk.", pts: 20, good:true, why:"Professional: decomposition + prioritization + communication." },
      { t: "I work hard at the last moment.", pts: 6, good:false, why:"Effort is good, but last-minute is risky." },
      { t: "I delay if it gets hard.", pts: 1, good:false, why:"Avoids responsibility." }
    ]
  },
  {
    q: "Describe a project you built.",
    options: [
      { t: "I built a small website.", pts: 8, good:false, why:"Okay start, but lacks details and impact." },
      { t: "I built a web app with auth, CRUD, and performance improvements. I can explain my decisions and trade-offs.", pts: 20, good:true, why:"Shows scope + impact + confidence." },
      { t: "I don’t remember.", pts: 0, good:false, why:"Bad signal." },
      { t: "I used a template and changed colors.", pts: 2, good:false, why:"Sounds shallow; doesn’t show skills." }
    ]
  }
];

const qNoSpan = document.getElementById("qNo");
const scoreSpan = document.getElementById("score");
const streakSpan = document.getElementById("streak");
const goodSpan = document.getElementById("good");
const mistakesSpan = document.getElementById("mistakes");
const timeText = document.getElementById("timeText");
const timeFill = document.getElementById("timeFill");
const qualityText = document.getElementById("qualityText");
const qualityFill = document.getElementById("qualityFill");
const moodText = document.getElementById("moodText");
const jobRole = document.getElementById("jobRole");
const soundBtn = document.getElementById("soundBtn");
const sceneDiv = document.getElementById("scene");
const logDiv = document.createElement("div");
const confettiCanvas = document.getElementById("confetti");

function clamp(v,a,b){ return Math.max(a, Math.min(b, v)); }

function log(msg){
  // In this version we write logs into the scene (when available)
  const holder = document.getElementById("log");
  if(!holder) return;
  const p = document.createElement("div");
  p.innerHTML = msg;
  holder.prepend(p);
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

function confettiBurst(){
  const c = confettiCanvas;
  const ctx = c.getContext("2d");
  c.width = window.innerWidth;
  c.height = window.innerHeight;
  c.style.display = "block";
  const pieces = Array.from({length: 130}).map(() => ({
    x: Math.random()*c.width,
    y: -20 - Math.random()*c.height*0.2,
    r: 2 + Math.random()*5,
    vx: -2 + Math.random()*4,
    vy: 2 + Math.random()*5,
    a: Math.random()*Math.PI*2,
    va: -0.2 + Math.random()*0.4,
    col: ["#22c55e","#38bdf8","#a78bfa","#f97316","#fbbf24","#ef4444"][Math.floor(Math.random()*6)]
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
    if(t > 165){
      clearInterval(id);
      c.style.display = "none";
    }
  }, 16);
}

function updateHUD(){
  qNoSpan.textContent = qNo;
  scoreSpan.textContent = score;
  streakSpan.textContent = streak;
  goodSpan.textContent = good;
  mistakesSpan.textContent = mistakes;

  timeText.textContent = timeLeft;
  timeFill.style.width = (timeLeft / 18 * 100) + "%";

  qualityText.textContent = Math.round(quality);
  qualityFill.style.width = Math.round(quality) + "%";

  moodText.textContent = Math.round(mood);
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
      timeout();
    }
  }, 1000);
}

function timeout(){
  mistakes++;
  streak = 0;
  locked = true;
  beep(180,160);
  const choicesDiv = document.getElementById("choices");
  if(choicesDiv) Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));
  if(mistakes >= 5){
    log("<strong>Game Over:</strong> Too many misses. Try again.");
    return;
  }
  setTimeout(nextQuestion, 900);
}

function renderStage(){
  locked = false;
  pickedThisStage = false;
  timeLeft = 18;
  updateHUD();

  const people = [
    { n:"Ayesha Khan", r:"HR Specialist" },
    { n:"Rohan Mehta", r:"Tech Lead" },
    { n:"Neha Sharma", r:"Hiring Manager" },
    { n:"Sara Ali", r:"Product Manager" }
  ];
  const roles = ["Web Developer","Frontend Intern","Backend Intern","Full‑Stack Trainee"];
  const p = people[Math.floor(Math.random()*people.length)];

  // keep stable interviewer for whole interview run
  if(!window.__iv){
    window.__iv = p;
    window.__job = roles[Math.floor(Math.random()*roles.length)];
  }

  const iv = window.__iv;
  const job = window.__job;

  if(stage === "enter"){
    sceneDiv.innerHTML = `
      <div class="sceneTitle">🚪 Outside the Interview Room</div>
      <div class="sceneSub">You’re a student. Take a breath. Enter confidently.</div>
      <div class="stageRow">
        <div class="cardy">
          <div class="door">
            <div class="knob"></div>
            <div class="student" id="student">
              <div class="head"></div>
              <div class="body"></div>
            </div>
          </div>
          <div class="sub mt-3">Tip: calm greeting + permission sets a strong first impression.</div>
          <div class="mt-3 d-flex gap-2 flex-wrap">
            <button class="btn-glow" id="enterBtn">Knock & Enter</button>
            <span class="pill">🎯 First impression matters</span>
          </div>
        </div>
        <div class="cardy">
          <div class="label"><span>Interviewer</span><span class="badge-pill">${iv.r}</span></div>
          <div class="title">${iv.n}</div>
          <div class="sub mt-2">Role: <strong>${job}</strong></div>
          <div class="mt-3">
            <div class="label"><span>🙂 Mood</span><span>${Math.round(mood)}%</span></div>
            <div class="bar"><div class="fill quality" style="width:${Math.round(mood)}%"></div></div>
          </div>
          <div class="sub mt-3">Speak politely and with structure to boost mood.</div>
        </div>
      </div>
      <div id="log"></div>
    `;
    const btn = document.getElementById("enterBtn");
    btn.onclick = () => {
      if(pickedThisStage) return;
      pickedThisStage = true;
      const s = document.getElementById("student");
      s.classList.add("enter");
      beep(740,90);
      setTimeout(() => { stage = "permission"; renderStage(); }, 950);
    };
    return;
  }

  if(stage === "permission"){
    sceneDiv.innerHTML = `
      <div class="sceneTitle">🪑 Inside the Room</div>
      <div class="sceneSub">Start with respect: ask permission and greet properly.</div>
      <div class="stageRow">
        <div class="cardy">
          <div class="chat" id="chat">
            <div class="bubble q">
              <div class="who">${iv.n} · Interviewer</div>
              <div class="txt">Typing <span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
            </div>
            <div class="bubble q" id="qBubble" style="display:none;">
              <div class="who">${iv.n} · Interviewer</div>
              <div class="txt" id="qText"></div>
            </div>
            <div class="bubble a" id="yourBubble" style="display:none;">
              <div class="who">You</div>
              <div class="txt" id="yourText"></div>
            </div>
          </div>
          <div class="choices" id="choices"></div>
          <div id="explain" style="display:none;"></div>
        </div>
        <div class="cardy">
          <div class="label"><span>Role</span><span class="badge-pill">Round 1</span></div>
          <div class="title">Polite Opening</div>
          <div class="sub mt-2">Pick an opening that sounds real and respectful.</div>
          <div class="mt-3">
            <div class="label"><span>🙂 Mood</span><span>${Math.round(mood)}%</span></div>
            <div class="bar"><div class="fill quality" style="width:${Math.round(mood)}%"></div></div>
          </div>
          <div class="sub mt-3">Bonus: “May I come in?” + “Good morning” + “Thank you”.</div>
        </div>
      </div>
      <div id="log"></div>
    `;
    setTimeout(() => {
      document.getElementById("qBubble").style.display = "block";
      document.getElementById("qText").textContent = `"Please come in. Are you ready to begin?"`;
    }, 520);

    const openOpts = [
      { t:"Good morning ma’am/sir. May I come in? Thank you for the opportunity.", pts: 20, good:true, why:"Respectful, confident, and professional." },
      { t:"Hi. I came for interview.", pts: 8, good:false, why:"Okay but too casual and not polished." },
      { t:"Start fast, I’m in hurry.", pts: 0, good:false, why:"Rude and creates a bad impression." },
      { t:"Umm… can we start?", pts: 5, good:false, why:"Nervous and unclear." }
    ].sort(() => Math.random()-0.5);

    const choicesDiv = document.getElementById("choices");
    openOpts.forEach((opt, idx) => {
      const div = document.createElement("div");
      div.className = "choice";
      div.innerHTML = `<span>${opt.t}</span><span class="badge-pill">${String.fromCharCode(65+idx)}</span>`;
      div.onclick = () => pick(opt, div, "Opening");
      choicesDiv.appendChild(div);
    });
    startTimer();
    updateHUD();
    return;
  }

  if(stage === "qa"){
    sceneDiv.innerHTML = `
      <div class="sceneTitle">🎙️ Interview Questions</div>
      <div class="sceneSub">Answer clearly. Make choices that sound almost the same (hard mode).</div>
      <div class="room">
        <div class="side">
          <div class="avatar">
            <div>
              <div class="name">${iv.n}</div>
              <div class="role">${iv.r}</div>
            </div>
            <div class="mic">🎙️</div>
          </div>
          <div class="sub mt-3">Real interview vibe: confident, structured, specific.</div>
          <div class="mt-3">
            <div class="label"><span>🙂 Mood</span><span><span id="moodPct">${Math.round(mood)}</span>%</span></div>
            <div class="bar"><div id="moodFill" class="fill quality" style="width:${Math.round(mood)}%"></div></div>
          </div>
          <div class="mt-3 d-flex gap-2 flex-wrap">
            <span class="pill">⭐ Bonus: fast + structured</span>
            <span class="pill">🧩 Options look similar</span>
          </div>
        </div>
        <div>
          <div class="chat" id="chat"></div>
          <div class="choices" id="choices"></div>
          <div id="explain"></div>
          <div id="log"></div>
        </div>
      </div>
    `;

    const chatDiv = document.getElementById("chat");
    const choicesDiv = document.getElementById("choices");
    const explainDiv = document.getElementById("explain");
    choicesDiv.innerHTML = "";
    explainDiv.style.display = "none";

    currentItem = bank[Math.floor(Math.random()*bank.length)];
    chatDiv.innerHTML = `
      <div class="bubble q">
        <div class="who">${iv.n} · Interviewer</div>
        <div class="txt">Typing <span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
      </div>
      <div class="bubble q" id="qBubble" style="display:none;">
        <div class="who">${iv.n} · Interviewer</div>
        <div class="txt" id="qText"></div>
      </div>
      <div class="bubble a" id="yourBubble" style="display:none;">
        <div class="who">You</div>
        <div class="txt" id="yourText"></div>
      </div>
    `;
    setTimeout(() => {
      document.getElementById("qBubble").style.display = "block";
      document.getElementById("qText").textContent = `"${currentItem.q}"`;
    }, 520);

    // Harder: options already similar; shuffle to confuse
    currentItem.options.slice().sort(() => Math.random() - 0.5).forEach((opt, idx) => {
      const div = document.createElement("div");
      div.className = "choice";
      div.innerHTML = `<span>${opt.t}</span><span class="badge-pill">${String.fromCharCode(65+idx)}</span>`;
      div.onclick = () => pick(opt, div, currentItem.q);
      choicesDiv.appendChild(div);
    });

    startTimer();
    updateHUD();
    return;
  }

  // end stage
  sceneDiv.innerHTML = `
    <div class="sceneTitle">🏁 Interview Finished</div>
    <div class="sceneSub">You completed the interview. Review your performance and start a new run.</div>
    <div class="stageRow">
      <div class="cardy">
        <div class="title">Result</div>
        <div class="sub mt-2">Score: <strong>${score}</strong> · Great: <strong>${good}</strong> · Mistakes: <strong>${mistakes}</strong></div>
        <div class="mt-3">
          <div class="label"><span>💬 Quality</span><span>${Math.round(quality)}%</span></div>
          <div class="bar"><div class="fill quality" style="width:${Math.round(quality)}%"></div></div>
        </div>
        <div class="mt-3">
          <div class="label"><span>🙂 Impression</span><span>${Math.round(mood)}%</span></div>
          <div class="bar"><div class="fill time" style="width:${Math.round(mood)}%"></div></div>
        </div>
        <div class="sub mt-3">Tip: Be specific, confirm timelines, and keep calm tone.</div>
        <div class="mt-3 d-flex gap-2 flex-wrap">
          <button class="btn-glow" id="againBtn">Start New Interview</button>
          <a class="btn btn-outline-light" href="../games.php">Back to Games</a>
        </div>
      </div>
      <div class="cardy">
        <div class="label"><span>Interviewer</span><span class="badge-pill">${iv.r}</span></div>
        <div class="title">${iv.n}</div>
        <div class="sub mt-2">Role: <strong>${job}</strong></div>
        <div class="sub mt-3">Your last impression is shown above. Improve by keeping answers structured:</div>
        <div class="sub mt-2">- Situation → Action → Result</div>
        <div class="sub">- Confirm deadline + next step</div>
        <div class="sub">- Polite opening/closing</div>
      </div>
    </div>
    <div id="log"></div>
  `;
  document.getElementById("againBtn").onclick = () => resetInterview();
}

function resetInterview(){
  if(timerId) clearInterval(timerId);
  window.__iv = null;
  window.__job = null;
  qNo = 1;
  asked = 0;
  score = 0;
  streak = 0;
  good = 0;
  mistakes = 0;
  quality = 35;
  mood = 50;
  stage = "enter";
  renderStage();
}

function pick(opt, el, qText){
  if(locked) return;
  locked = true;
  const choicesDiv = document.getElementById("choices");
  const explainDiv = document.getElementById("explain");
  if(choicesDiv) Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));

  const yt = document.getElementById("yourText");
  const yb = document.getElementById("yourBubble");
  if(yt) yt.textContent = opt.t;
  if(yb) yb.style.display = "block";

  const timeBonus = Math.max(0, timeLeft);
  const streakBonus = Math.min(60, streak * 6);
  const gain = opt.pts + timeBonus + (opt.good ? streakBonus : 0);

  if(opt.good){
    el.classList.add("correct");
    score += gain;
    streak++;
    good++;
    quality = clamp(quality + 12, 0, 100);
    mood = clamp(mood + 8, 0, 100);
    confettiBurst();
    beep(880,120);
    if(explainDiv){
      explainDiv.style.display = "block";
      explainDiv.innerHTML = `<div class="pill good"><strong>Great!</strong> +${gain} points</div><div class="sub mt-2"><strong>Why:</strong> ${opt.why}</div>`;
    }
    log(`<span class="text-success"><strong>Great:</strong></span> "${qText}"`);
  }else{
    el.classList.add("wrong");
    mistakes++;
    streak = 0;
    score = Math.max(0, score - 20);
    quality = clamp(quality - 8, 0, 100);
    mood = clamp(mood - 12, 0, 100);
    beep(220,140);
    if(explainDiv){
      explainDiv.style.display = "block";
      explainDiv.innerHTML = `<div class="pill bad"><strong>Not ideal.</strong></div><div class="sub mt-2"><strong>Why:</strong> ${opt.why}</div>`;
    }
    if(choicesDiv){
      choicesDiv.classList.add("shake");
      setTimeout(() => choicesDiv.classList.remove("shake"), 250);
    }
    log(`<span class="text-danger"><strong>Weak:</strong></span> "${qText}"`);
    if(mistakes >= 5){
      log("<strong>Game Over:</strong> Too many mistakes. Refresh to retry.");
      updateHUD();
      return;
    }
  }

  updateHUD();
  setTimeout(nextQuestion, 1100);
}

function nextQuestion(){
  if(stage === "permission"){
    stage = "qa";
    asked = 0;
    qNo = 1;
    renderStage();
    return;
  }
  if(stage === "qa"){
    asked++;
    qNo = asked + 1;
    if(asked >= maxQuestions){
      stage = "end";
      renderStage();
      return;
    }
    renderStage();
    return;
  }
  // fallback
  renderStage();
}

soundBtn.onclick = () => {
  soundOn = !soundOn;
  soundBtn.textContent = "Sound: " + (soundOn ? "ON" : "OFF");
  if(soundOn) beep(740,90);
};

// init
quality = 35;
mood = 50;
renderStage();
log("<span class='text-info'><strong>Start:</strong></span> Infinite interview questions loaded.");
</script>

</body>
</html>

