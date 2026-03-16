<!DOCTYPE html>
<html>
<head>
    <title>Email Response Challenge</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #6366f1, #020617);
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
        .o1{ background:#6366f1; top:-240px; left:-210px; animation: drift1 10s ease-in-out infinite; }
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
        .fill.prof{ background:linear-gradient(90deg,#38bdf8,#6366f1); }

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
        .pill.info{ border-color:rgba(56,189,248,0.55); color:#bae6fd; }

        .client{
            display:grid;
            grid-template-columns: 280px 1fr;
            gap:12px;
            margin-top:12px;
        }
        @media (max-width: 900px){
            .client{ grid-template-columns: 1fr; }
        }
        .gmailTop{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:10px 12px;
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.16);
            background: rgba(15,23,42,0.55);
        }
        .brand{
            display:flex;
            align-items:center;
            gap:10px;
            font-weight:900;
            letter-spacing:0.02em;
        }
        .logoDot{
            width:18px;height:18px;border-radius:7px;
            background: conic-gradient(from 180deg, #38bdf8, #6366f1, #22c55e, #f59e0b, #38bdf8);
            box-shadow: 0 0 18px rgba(56,189,248,0.16);
        }
        .search{
            flex:1;
            max-width:480px;
            border-radius:999px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(2,6,23,0.35);
            padding:8px 12px;
            color:#cbd5f5;
            font-weight:800;
            display:flex;
            gap:10px;
            align-items:center;
        }
        .search input{
            border:0;
            outline:none;
            background: transparent;
            color:#e5e7eb;
            width:100%;
            font-weight:800;
        }
        .chip{
            border-radius:999px;
            padding:6px 10px;
            font-size:0.78rem;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(2,6,23,0.25);
            color:#e5e7eb;
            font-weight:900;
        }
        .layout{
            display:flex;
            flex-direction:column;
            gap:12px;
            margin-top:12px;
        }
        .mainGrid{
            display:grid;
            grid-template-columns: 240px 280px 1fr;
            gap:12px;
        }
        @media (max-width: 980px){
            .mainGrid{ grid-template-columns: 1fr; }
        }
        .sidebar{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            padding:10px;
        }
        .composeBtn{
            width:100%;
            border-radius:16px;
            padding:12px 14px;
            font-weight:900;
            border:1px solid rgba(34,197,94,0.35);
            background: linear-gradient(135deg, rgba(34,197,94,0.20), rgba(56,189,248,0.14));
            color:#e5e7eb;
            transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
        }
        .composeBtn:hover{
            transform: translateY(-1px);
            border-color: rgba(34,197,94,0.60);
            box-shadow: 0 0 0 1px rgba(34,197,94,0.18), 0 0 22px rgba(34,197,94,0.10);
        }
        .navItem{
            margin-top:10px;
            border-radius:14px;
            border:1px solid rgba(148,163,184,0.14);
            padding:10px 12px;
            background: rgba(2,6,23,0.25);
            color:#e5e7eb;
            font-weight:900;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
        }
        .navItem.active{ border-color: rgba(99,102,241,0.55); background: rgba(99,102,241,0.10); }
        .inbox{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            padding:10px;
            overflow:hidden;
        }
        .inboxHdr{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            padding:6px 6px 10px;
            border-bottom:1px solid rgba(148,163,184,0.10);
            margin-bottom:8px;
        }
        .folder{
            font-weight:900;
            letter-spacing:0.04em;
            text-transform:uppercase;
            font-size:0.8rem;
            color:#c7d2fe;
        }
        .msgList{ display:flex; flex-direction:column; gap:8px; }
        .msg{
            border-radius:14px;
            border:1px solid rgba(148,163,184,0.16);
            background: rgba(2,6,23,0.25);
            padding:10px;
            cursor:pointer;
            transition: transform .12s ease, border-color .12s ease, background .12s ease;
        }
        .msg:hover{ transform: translateY(-1px); border-color: rgba(99,102,241,0.55); }
        .msg.active{
            border-color: rgba(34,197,94,0.65);
            background: rgba(34,197,94,0.08);
        }
        .msgTop{
            display:flex;
            justify-content:space-between;
            gap:8px;
            color:#9ca3af;
            font-size:0.78rem;
            text-transform:uppercase;
            letter-spacing:0.06em;
        }
        .msgSub{ font-weight:900; margin-top:6px; color:#e5e7eb; }
        .msgPrev{ margin-top:4px; color:#cbd5f5; font-size:0.84rem; opacity:0.9; }

        .reader{
            border-radius:18px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            padding:12px;
        }
        .mail{
            margin-top:0;
            border-radius:16px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(2,6,23,0.25);
            padding:12px;
        }
        .mail .hdr{
            display:flex;
            justify-content:space-between;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
            color:#9ca3af;
            font-size:0.82rem;
            text-transform:uppercase;
            letter-spacing:0.06em;
        }
        .mail .body{
            margin-top:10px;
            font-size:0.95rem;
            color:#e5e7eb;
            line-height:1.45;
        }

        .replyBox{
            margin-top:10px;
            border-radius:16px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(2,6,23,0.25);
            padding:12px;
        }
        .replyHdr{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            color:#9ca3af;
            font-size:0.78rem;
            text-transform:uppercase;
            letter-spacing:0.06em;
        }
        .draft{
            margin-top:8px;
            font-weight:800;
            min-height:44px;
            color:#e5e7eb;
        }
        .sig{
            margin-top:8px;
            font-size:0.85rem;
            color:#cbd5f5;
            opacity:0.95;
        }

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
            border-color: rgba(99,102,241,0.65);
            box-shadow: 0 0 0 1px rgba(99,102,241,0.18), 0 0 18px rgba(99,102,241,0.10);
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
            <div class="small text-uppercase text-secondary">Email Challenge</div>
            <div class="fw-bold">📧 Reply Like a Pro</div>
        </div>
    </div>
</div>

<div id="app">
    <div class="orb o1"></div>
    <div class="orb o2"></div>

    <div id="hud">
        <div>
            <div class="title">Email <span id="qNo">1</span> · Infinite Mode</div>
            <div class="sub">Pick the best reply: polite, clear, and committed.</div>
        </div>

        <div class="meter">
            <div class="label"><span>⏱ Time</span><span><span id="timeText">18</span>s</span></div>
            <div class="bar"><div id="timeFill" class="fill time"></div></div>
        </div>

        <div class="meter">
            <div class="label"><span>✍ Professionalism</span><span><span id="profText">0</span>%</span></div>
            <div class="bar"><div id="profFill" class="fill prof"></div></div>
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
            <span class="pill good">✅ Great Replies: <span id="good">0</span></span>
            <span class="pill bad">❌ Mistakes: <span id="mistakes">0</span>/5</span>
            <span class="pill info">📌 Tip: confirm time + action</span>
            <span class="pill">📬 Mailbox Health: <span id="healthText">40</span>%</span>
        </div>

        <div class="layout">
            <div class="gmailTop">
                <div class="brand">
                    <div class="logoDot"></div>
                    <div>Company Mail</div>
                    <span class="badge-pill">Work Mode</span>
                </div>
                <div class="search">
                    <span style="opacity:.9">🔎</span>
                    <input value="Search in mail" readonly>
                </div>
                <div class="chip">👤 Intern</div>
            </div>

            <div class="mainGrid">
                <div class="sidebar">
                    <button class="composeBtn" id="composeBtn" type="button">✉ Compose</button>
                    <div class="navItem active"><span>📥 Inbox</span><span class="badge-pill" id="newCount">4</span></div>
                    <div class="navItem"><span>⭐ Starred</span><span class="badge-pill">1</span></div>
                    <div class="navItem"><span>📤 Sent</span><span class="badge-pill">—</span></div>
                    <div class="navItem"><span>🗑 Trash</span><span class="badge-pill">0</span></div>
                    <div class="sub mt-3">You’re replying as a company employee. Keep it polite and clear.</div>
                </div>

                <div class="inbox">
                    <div class="inboxHdr">
                        <div class="folder">Inbox</div>
                        <div class="badge-pill">New</div>
                    </div>
                    <div class="msgList" id="inbox"></div>
                </div>

                <div class="reader">
                    <div class="mail" id="mail"></div>
                    <div class="replyBox">
                        <div class="replyHdr">
                            <span>Reply Draft</span>
                            <span id="toneTag" class="badge-pill">Tone: Neutral</span>
                        </div>
                        <div id="draft" class="draft">Choose an option to fill the draft…</div>
                        <div class="sig">— Signature: <strong>Regards, Intern</strong></div>
                    </div>
                    <div class="choices" id="choices"></div>
                    <div id="explain"></div>
                    <div id="log"></div>
                </div>
            </div>
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
let professionalism = 40;
let currentItem = null;

const bank = [
  {
    from:"Manager",
    subject:"Project report",
    body:"Please send the project report before 5 PM today.",
    options:[
      { t:"Sure, I’ll send the report before 5 PM today. Regards, Intern", pts:20, good:true, why:"Confirms action + deadline + professional closing." },
      { t:"Sure, I will send the report today. Regards, Intern", pts:12, good:false, why:"Professional, but doesn’t confirm the exact deadline." },
      { t:"Sure, I’ll share the report soon. Regards, Intern", pts:7, good:false, why:"Sounds polite but vague (no deadline)." },
      { t:"Ok.", pts:4, good:false, why:"Too short. No confirmation or professionalism." }
    ]
  },
  {
    from:"Client",
    subject:"Meeting confirmation",
    body:"Can we meet tomorrow at 11 AM to discuss the requirements?",
    options:[
      { t:"Yes, tomorrow 11 AM works for me. Looking forward to it. Regards, Intern", pts:20, good:true, why:"Polite + confirms time + professional closing." },
      { t:"Yes, tomorrow works. Regards, Intern", pts:12, good:false, why:"Polite but missing the exact time confirmation." },
      { t:"Yes, 11 AM is fine. Regards, Intern", pts:16, good:false, why:"Good, but lacks warmth/clarity compared to best option." },
      { t:"No, busy.", pts:1, good:false, why:"Too blunt. Suggest an alternative." }
    ]
  },
  {
    from:"Team Lead",
    subject:"Bug fix status",
    body:"What’s the status of the login bug fix?",
    options:[
      { t:"I’m fixing it now and will update you by 3 PM with a tested patch. Regards, Intern", pts:20, good:true, why:"Status + exact ETA + quality signal." },
      { t:"I’m fixing it now and will update you today. Regards, Intern", pts:12, good:false, why:"Professional but no exact ETA." },
      { t:"In progress. Will update soon. Regards, Intern", pts:7, good:false, why:"Sounds polite but too vague." },
      { t:"I’m working on it.", pts:8, good:false, why:"Better, but no ETA." }
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
const profText = document.getElementById("profText");
const profFill = document.getElementById("profFill");
const healthText = document.getElementById("healthText");
const soundBtn = document.getElementById("soundBtn");
const composeBtn = document.getElementById("composeBtn");
const newCountEl = document.getElementById("newCount");
const inboxDiv = document.getElementById("inbox");
const mailDiv = document.getElementById("mail");
const choicesDiv = document.getElementById("choices");
const explainDiv = document.getElementById("explain");
const logDiv = document.getElementById("log");
const confettiCanvas = document.getElementById("confetti");
const draftDiv = document.getElementById("draft");
const toneTag = document.getElementById("toneTag");

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
  const pieces = Array.from({length: 120}).map(() => ({
    x: Math.random()*c.width,
    y: -20 - Math.random()*c.height*0.2,
    r: 2 + Math.random()*5,
    vx: -2 + Math.random()*4,
    vy: 2 + Math.random()*5,
    a: Math.random()*Math.PI*2,
    va: -0.2 + Math.random()*0.4,
    col: ["#6366f1","#38bdf8","#a78bfa","#22c55e","#f97316","#ef4444"][Math.floor(Math.random()*6)]
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
    if(t > 155){
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
  profText.textContent = Math.round(professionalism);
  profFill.style.width = Math.round(professionalism) + "%";
  healthText.textContent = Math.round(professionalism);
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
  explainDiv.style.display = "block";
  explainDiv.innerHTML = `<div class="pill bad"><strong>Time up!</strong> Reply quickly but professionally.</div>`;
  beep(180,160);
  Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));
  if(mistakes >= 5){
    log("<strong>Game Over:</strong> Too many mistakes. Refresh to retry.");
    return;
  }
  setTimeout(nextEmail, 900);
}

function renderEmail(){
  locked = false;
  explainDiv.style.display = "none";
  choicesDiv.innerHTML = "";
  timeLeft = 18;
  draftDiv.textContent = "Choose an option to fill the draft…";
  toneTag.textContent = "Tone: Neutral";

  const item = bank[Math.floor(Math.random()*bank.length)];
  currentItem = item;

  // Fake inbox list for visuals
  const fillers = [
    { from:"HR", subject:"Interview follow-up", prev:"Can you share your available slots?" },
    { from:"Finance", subject:"Invoice details", prev:"Please confirm the billing address." },
    { from:"Teammate", subject:"Quick question", prev:"Can you review my PR today?" },
    { from:"Client", subject:"Requirement update", prev:"Small change request before launch." }
  ];
  const list = fillers.slice().sort(() => Math.random()-0.5).slice(0,3);
  list.splice(Math.floor(Math.random()*4), 0, { from:item.from, subject:item.subject, prev:item.body.slice(0,46)+"…" });

  inboxDiv.innerHTML = "";
  list.slice(0,4).forEach(m => {
    const div = document.createElement("div");
    div.className = "msg" + (m.subject === item.subject ? " active" : "");
    div.innerHTML = `
      <div class="msgTop"><span>${m.from}</span><span>Now</span></div>
      <div class="msgSub">${m.subject}</div>
      <div class="msgPrev">${m.prev}</div>
    `;
    inboxDiv.appendChild(div);
  });
  if(newCountEl) newCountEl.textContent = String(3 + Math.floor(Math.random()*5));

  mailDiv.innerHTML = `
    <div class="hdr">
      <span><strong>From:</strong> ${item.from}</span>
      <span><strong>Subject:</strong> ${item.subject}</span>
    </div>
    <div class="body">${item.body}</div>
  `;

  item.options.slice().sort(() => Math.random() - 0.5).forEach((opt, idx) => {
    const div = document.createElement("div");
    div.className = "choice";
    div.innerHTML = `<span>${opt.t}</span><span class="badge-pill">${String.fromCharCode(65+idx)}</span>`;
    div.onclick = () => pick(opt, div, item.subject);
    choicesDiv.appendChild(div);
  });

  startTimer();
  updateHUD();
}

function pick(opt, el, subject){
  if(locked) return;
  locked = true;
  Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));

  draftDiv.textContent = opt.t;
  toneTag.textContent = opt.good ? "Tone: Professional" : (opt.t.length <= 4 ? "Tone: Too short" : "Tone: Risky");

  const timeBonus = Math.max(0, timeLeft);
  const streakBonus = Math.min(60, streak * 6);
  const gain = opt.pts + timeBonus + (opt.good ? streakBonus : 0);

  if(opt.good){
    el.classList.add("correct");
    score += gain;
    streak++;
    good++;
    professionalism = clamp(professionalism + 10, 0, 100);
    confettiBurst();
    beep(880,120);
    explainDiv.style.display = "block";
    explainDiv.innerHTML = `<div class="pill good"><strong>Sent!</strong> ✨ +${gain} points</div><div class="sub mt-2"><strong>Why:</strong> ${opt.why}</div>`;
    log(`<span class="text-success"><strong>Good:</strong></span> ${subject}`);
  }else{
    el.classList.add("wrong");
    mistakes++;
    streak = 0;
    score = Math.max(0, score - 20);
    professionalism = clamp(professionalism - 8, 0, 100);
    beep(220,140);
    explainDiv.style.display = "block";
    explainDiv.innerHTML = `<div class="pill bad"><strong>Bounced!</strong> 💥</div><div class="sub mt-2"><strong>Why:</strong> ${opt.why}</div>`;
    choicesDiv.classList.add("shake");
    setTimeout(() => choicesDiv.classList.remove("shake"), 250);
    log(`<span class="text-danger"><strong>Weak:</strong></span> ${subject}`);
    if(mistakes >= 5){
      log("<strong>Game Over:</strong> Too many mistakes. Refresh to retry.");
      updateHUD();
      return;
    }
  }

  updateHUD();
  setTimeout(nextEmail, 1100);
}

function nextEmail(){
  qNo++;
  renderEmail();
}

soundBtn.onclick = () => {
  soundOn = !soundOn;
  soundBtn.textContent = "Sound: " + (soundOn ? "ON" : "OFF");
  if(soundOn) beep(740,90);
};

if(composeBtn){
  composeBtn.onclick = () => {
    beep(740,90);
    log("<span class='text-info'><strong>Compose:</strong></span> In this game you reply to real inbox emails. (Auto loads next)");
  };
}

// init
renderEmail();
updateHUD();
log("<span class='text-info'><strong>Start:</strong></span> Infinite email challenges loaded.");
</script>

</body>
</html>

