<!DOCTYPE html>
<html>
<head>
    <title>Chat Simulator Game</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #38bdf8, #020617);
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
            width:520px;
            height:520px;
            border-radius:50%;
            filter: blur(52px);
            opacity:0.18;
            pointer-events:none;
        }
        .o1{ background:#38bdf8; top:-260px; left:-240px; animation: drift1 10s ease-in-out infinite; }
        .o2{ background:#a78bfa; bottom:-300px; right:-260px; animation: drift2 12s ease-in-out infinite; }
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
        .fill.polite{ background:linear-gradient(90deg,#38bdf8,#a78bfa); }

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
            background:linear-gradient(135deg,rgba(56,189,248,0.35),rgba(167,139,250,0.25));
            border:1px solid rgba(56,189,248,0.45);
            color:#e0f2fe;
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

        .phone{
            margin-top:12px;
            border-radius:22px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(15,23,42,0.55);
            overflow:hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .phoneTop{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:10px 12px;
            border-bottom:1px solid rgba(148,163,184,0.12);
            background: linear-gradient(135deg, rgba(56,189,248,0.10), rgba(167,139,250,0.08));
        }
        .whoBox{ display:flex; flex-direction:column; }
        .whoName{ font-weight:900; }
        .whoTag{ font-size:0.78rem; color:#a5b4fc; text-transform:uppercase; letter-spacing:0.06em; }
        .status{ font-size:0.8rem; color:#9ca3af; }

        .wallpaper{
            background:
              radial-gradient(circle at 20% 20%, rgba(56,189,248,0.14), transparent 50%),
              radial-gradient(circle at 80% 30%, rgba(167,139,250,0.12), transparent 55%),
              radial-gradient(circle at 40% 80%, rgba(34,197,94,0.10), transparent 55%);
        }
        .avatarDot{
            width:36px;
            height:36px;
            border-radius:14px;
            background: radial-gradient(circle at 30% 30%, rgba(226,232,240,0.85), rgba(148,163,184,0.45));
            border:1px solid rgba(148,163,184,0.22);
            box-shadow: 0 0 18px rgba(56,189,248,0.10);
        }

        .chat{
            padding:12px;
            min-height:220px;
            max-height:320px;
            overflow-y:auto;
            display:flex;
            flex-direction:column;
            gap:10px;
        }
        .bubble{
            max-width: 86%;
            border-radius:16px;
            padding:10px 12px;
            border:1px solid rgba(148,163,184,0.14);
            font-weight:800;
            line-height:1.35;
        }
        .b-them{ align-self:flex-start; background: rgba(56,189,248,0.10); }
        .b-you{ align-self:flex-end; background: rgba(34,197,94,0.10); display:none; }
        .meta{
            font-size:0.72rem;
            color:#9ca3af;
            text-transform:uppercase;
            letter-spacing:0.06em;
            margin-bottom:4px;
        }

        .choices{
            padding:12px;
            border-top:1px solid rgba(148,163,184,0.12);
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap:10px;
        }
        .inputBar{
            padding:12px;
            border-top:1px solid rgba(148,163,184,0.12);
            display:flex;
            gap:10px;
            align-items:center;
            background: rgba(2,6,23,0.30);
        }
        .fakeInput{
            flex:1;
            border-radius:999px;
            border:1px solid rgba(148,163,184,0.18);
            background: rgba(2,6,23,0.25);
            padding:10px 12px;
            color:#9ca3af;
            font-weight:800;
        }
        .sendBtn{
            width:44px;
            height:44px;
            border-radius:16px;
            border:1px solid rgba(34,197,94,0.30);
            background: rgba(34,197,94,0.12);
            color:#bbf7d0;
            font-weight:900;
        }
        .choice{
            border-radius:16px;
            border:1px solid rgba(148,163,184,0.22);
            background: rgba(2,6,23,0.35);
            color:#e5e7eb;
            padding:12px 12px;
            cursor:pointer;
            transition: transform .12s ease, border-color .12s ease, box-shadow .12s ease;
            user-select:none;
            font-weight:900;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
        }
        .choice:hover{
            transform: translateY(-1px);
            border-color: rgba(56,189,248,0.65);
            box-shadow: 0 0 0 1px rgba(56,189,248,0.18), 0 0 18px rgba(56,189,248,0.10);
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
            <div class="small text-uppercase text-secondary">Chat Simulator</div>
            <div class="fw-bold">🤖 Choose the Best Response</div>
        </div>
    </div>
</div>

<div id="app">
    <div class="orb o1"></div>
    <div class="orb o2"></div>

    <div id="hud">
        <div>
            <div class="title">Chat <span id="qNo">1</span> · Infinite Mode</div>
            <div class="sub">Be polite, professional, and situation‑aware.</div>
        </div>

        <div class="meter">
            <div class="label"><span>⏱ Time</span><span><span id="timeText">18</span>s</span></div>
            <div class="bar"><div id="timeFill" class="fill time"></div></div>
        </div>

        <div class="meter">
            <div class="label"><span>🤝 Politeness</span><span><span id="politeText">0</span>%</span></div>
            <div class="bar"><div id="politeFill" class="fill polite"></div></div>
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
            <span class="pill">🎯 Rule: respect + clarity</span>
        </div>

        <div class="phone wallpaper">
            <div class="phoneTop">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatarDot"></div>
                    <div class="whoBox">
                        <div class="whoName" id="whoName">Teacher</div>
                        <div class="whoTag" id="whoTag">Situation</div>
                    </div>
                </div>
                <div class="status" id="status">Typing…</div>
            </div>
            <div class="chat" id="chat"></div>
            <div class="choices" id="choices"></div>
            <div class="inputBar">
                <div class="fakeInput">Message… (choose an option below)</div>
                <button class="sendBtn" type="button">➤</button>
            </div>
        </div>

        <div id="explain"></div>
        <div id="log"></div>
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
let politeness = 55;
let current = null;

const bank = [
  {
    who:"Teacher",
    tag:"School",
    them:"Why are you late?",
    options:[
      { t:"I don’t care.", pts:0, good:false, why:"Disrespectful and escalates the situation." },
      { t:"Sorry sir, there was traffic.", pts:20, good:true, why:"Polite apology + clear reason." },
      { t:"Sorry sir, I got late because of traffic. It won’t happen again.", pts:16, good:false, why:"Good, but best answer is shorter and more direct." },
      { t:"It’s not your business.", pts:0, good:false, why:"Rude and confrontational." }
    ]
  },
  {
    who:"Team Lead",
    tag:"Work",
    them:"Can you share the update before standup?",
    options:[
      { t:"Sure — I fixed the bug and I’ll push the patch in 10 minutes.", pts:20, good:true, why:"Clear status + ETA." },
      { t:"Sure — I’m working on it and I’ll share an update soon.", pts:12, good:false, why:"Polite but vague; lacks exact ETA." },
      { t:"Later.", pts:4, good:false, why:"Too vague. No commitment." },
      { t:"Stop bothering me.", pts:0, good:false, why:"Unprofessional." }
    ]
  },
  {
    who:"Customer",
    tag:"Support",
    them:"My order is late and I’m upset.",
    options:[
      { t:"I’m sorry about the delay. I’ll check the status and update you in 5 minutes.", pts:20, good:true, why:"Empathy + action + time promise." },
      { t:"I understand. I’ll check and update you soon.", pts:12, good:false, why:"Empathetic but missing a clear time promise." },
      { t:"Wait.", pts:2, good:false, why:"Too short and not helpful." }
    ]
  },
  {
    who:"Friend",
    tag:"Personal",
    them:"You forgot our meeting again.",
    options:[
      { t:"I’m sorry. That’s on me. Can we reschedule and I’ll set a reminder?", pts:20, good:true, why:"Takes responsibility + offers solution." },
      { t:"Sorry. Can we do it later?", pts:10, good:false, why:"Apology is good but lacks responsibility and plan." },
      { t:"You should remind me.", pts:3, good:false, why:"Shifts blame instead of fixing." }
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
const politeText = document.getElementById("politeText");
const politeFill = document.getElementById("politeFill");
const soundBtn = document.getElementById("soundBtn");
const whoName = document.getElementById("whoName");
const whoTag = document.getElementById("whoTag");
const status = document.getElementById("status");
const chatDiv = document.getElementById("chat");
const choicesDiv = document.getElementById("choices");
const explainDiv = document.getElementById("explain");
const logDiv = document.getElementById("log");
const confettiCanvas = document.getElementById("confetti");

function clamp(v,a,b){ return Math.max(a, Math.min(b, v)); }
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
  politeText.textContent = Math.round(politeness);
  politeFill.style.width = Math.round(politeness) + "%";
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
  explainDiv.innerHTML = `<div class="pill bad"><strong>Time up!</strong> Choose a calm and respectful answer.</div>`;
  beep(180,160);
  Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));
  if(mistakes >= 5){
    log("<strong>Game Over:</strong> Too many mistakes. Refresh to retry.");
    return;
  }
  setTimeout(nextChat, 900);
}

function renderChat(){
  locked = false;
  explainDiv.style.display = "none";
  choicesDiv.innerHTML = "";
  timeLeft = 18;

  current = bank[Math.floor(Math.random()*bank.length)];
  whoName.textContent = current.who;
  whoTag.textContent = current.tag;
  status.textContent = "Typing…";

  chatDiv.innerHTML = `
    <div class="bubble b-them">
      <div class="meta">${current.who}</div>
      <div id="themText">…</div>
    </div>
    <div class="bubble b-you" id="youBubble">
      <div class="meta">You</div>
      <div id="youText"></div>
    </div>
  `;
  setTimeout(() => {
    document.getElementById("themText").textContent = `"${current.them}"`;
    status.textContent = "Online";
  }, 520);

  current.options.slice().sort(() => Math.random() - 0.5).forEach((opt, idx) => {
    const div = document.createElement("div");
    div.className = "choice";
    div.innerHTML = `<span>${opt.t}</span><span class="badge-pill">${String.fromCharCode(65+idx)}</span>`;
    div.onclick = () => pick(opt, div);
    choicesDiv.appendChild(div);
  });

  startTimer();
  updateHUD();
}

function pick(opt, el){
  if(locked) return;
  locked = true;
  Array.from(choicesDiv.children).forEach(c => c.classList.add("disabled"));

  document.getElementById("youText").textContent = opt.t;
  document.getElementById("youBubble").style.display = "block";

  const timeBonus = Math.max(0, timeLeft);
  const streakBonus = Math.min(60, streak * 6);
  const gain = opt.pts + timeBonus + (opt.good ? streakBonus : 0);

  if(opt.good){
    el.classList.add("correct");
    score += gain;
    streak++;
    good++;
    politeness = clamp(politeness + 10, 0, 100);
    confettiBurst();
    beep(880,120);
    explainDiv.style.display = "block";
    explainDiv.innerHTML = `<div class="pill good"><strong>Perfect response!</strong> +${gain} points</div><div class="sub mt-2"><strong>Why:</strong> ${opt.why}</div>`;
    log(`<span class="text-success"><strong>Good:</strong></span> ${current.who} (${current.tag})`);
  }else{
    el.classList.add("wrong");
    mistakes++;
    streak = 0;
    score = Math.max(0, score - 20);
    politeness = clamp(politeness - 12, 0, 100);
    beep(220,140);
    explainDiv.style.display = "block";
    explainDiv.innerHTML = `<div class="pill bad"><strong>Wrong tone.</strong></div><div class="sub mt-2"><strong>Why:</strong> ${opt.why}</div>`;
    choicesDiv.classList.add("shake");
    setTimeout(() => choicesDiv.classList.remove("shake"), 250);
    log(`<span class="text-danger"><strong>Bad:</strong></span> ${current.who} (${current.tag})`);
    if(mistakes >= 5){
      log("<strong>Game Over:</strong> Too many mistakes. Refresh to retry.");
      updateHUD();
      return;
    }
  }

  updateHUD();
  setTimeout(nextChat, 1100);
}

function nextChat(){
  qNo++;
  renderChat();
}

soundBtn.onclick = () => {
  soundOn = !soundOn;
  soundBtn.textContent = "Sound: " + (soundOn ? "ON" : "OFF");
  if(soundOn) beep(740,90);
};

renderChat();
updateHUD();
log("<span class='text-info'><strong>Start:</strong></span> Chat Simulator loaded (infinite mode).");
</script>

</body>
</html>

