<!DOCTYPE html>
<html>
<head>
  <title>Logic Grid Puzzle</title>
  <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
  <style>
    body{
      margin:0;
      font-family: Arial, sans-serif;
      background: radial-gradient(circle at top, #7c3aed, #020617);
      color:#e5e7eb;
    }
    .top-bar{ padding:12px 20px; }

    #track{
      max-width: 980px;
      margin: 10px auto 0;
      padding: 16px;
      border-radius: 22px;
      background: rgba(15,23,42,0.92);
      box-shadow: 0 0 25px rgba(15,23,42,0.95);
      position:relative;
      overflow:hidden;
    }
    .bg-orb{
      position:absolute;
      width:440px;
      height:440px;
      border-radius:50%;
      filter: blur(42px);
      opacity:0.22;
      pointer-events:none;
    }
    .orb1{ background:#a78bfa; top:-200px; left:-180px; animation: drift1 8s ease-in-out infinite; }
    .orb2{ background:#22c55e; bottom:-240px; right:-200px; animation: drift2 10s ease-in-out infinite; }
    @keyframes drift1{ 0%,100%{ transform:translate(0,0);} 50%{ transform:translate(32px,18px);} }
    @keyframes drift2{ 0%,100%{ transform:translate(0,0);} 50%{ transform:translate(-28px,-22px);} }

    #statusRow{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:16px;
      flex-wrap:wrap;
    }
    #meters{ flex:1; min-width:240px; }
    .meter{ margin-top:8px; }
    .meter-label{
      font-size:0.85rem;
      text-transform:uppercase;
      letter-spacing:0.05em;
      margin-bottom:4px;
      color:#9ca3af;
    }
    .meter-bar{
      height:10px;
      border-radius:999px;
      background:#111827;
      overflow:hidden;
    }
    .meter-fill{
      height:100%;
      width:0;
      border-radius:inherit;
      transition: width 0.35s ease-out;
    }
    .meter-fill.power{ background: linear-gradient(90deg,#22c55e,#a3e635); }
    .meter-fill.heat{ background: linear-gradient(90deg,#f97316,#ef4444); }
    .meter-fill.progress{ background: linear-gradient(90deg,#38bdf8,#6366f1); }

    #problemCard{
      margin-top:14px;
      border-radius:16px;
      background: linear-gradient(135deg,rgba(15,23,42,0.9),rgba(124,58,237,0.35));
      padding: 14px 16px;
      border:1px solid rgba(148,163,184,0.18);
    }
    #problemTitle{ font-size:1rem; font-weight:700; }
    #problemText{ font-size:0.9rem; color:#cbd5f5; }

    .chip-row{ margin-top:10px; display:flex; flex-wrap:wrap; gap:8px; }
    .chip{
      font-size:0.76rem;
      border-radius:999px;
      padding:6px 10px;
      border:1px solid rgba(148,163,184,0.35);
      background:rgba(2,6,23,0.45);
      color:#e5e7eb;
    }
    .chip.good{ border-color:rgba(34,197,94,0.55); color:#bbf7d0; }
    .chip.bad{ border-color:rgba(239,68,68,0.55); color:#fecaca; }
    .chip.info{ border-color:rgba(56,189,248,0.55); color:#bae6fd; }

    .mini-actions{
      margin-top:12px;
      display:flex;
      justify-content:space-between;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
    }
    .toggle-pill{
      border:1px solid rgba(148,163,184,0.35);
      background:rgba(15,23,42,0.65);
      color:#e5e7eb;
      border-radius:999px;
      padding:6px 10px;
      font-size:0.78rem;
    }
    .badge-pill{
      border-radius:999px;
      padding:6px 10px;
      font-size:0.78rem;
      background:linear-gradient(135deg,rgba(99,102,241,0.35),rgba(56,189,248,0.25));
      border:1px solid rgba(99,102,241,0.45);
      color:#e0e7ff;
    }

    .grid-wrap{
      margin-top:14px;
      display:grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap:14px;
    }
    @media (max-width: 980px){
      .grid-wrap{ grid-template-columns:1fr; }
    }
    .panel{
      border:1px solid rgba(148,163,184,0.22);
      background:rgba(2,6,23,0.35);
      border-radius:16px;
      padding:12px;
    }
    .panel h6{
      margin:0 0 10px 0;
      font-weight:700;
      letter-spacing:0.03em;
      color:#e0e7ff;
      text-transform:uppercase;
      font-size:0.78rem;
    }

    table.logic{
      width:100%;
      border-collapse:separate;
      border-spacing:8px;
    }
    table.logic th{
      font-size:0.78rem;
      color:#c7d2fe;
      font-weight:800;
      text-transform:uppercase;
      letter-spacing:0.06em;
    }
    .rowhdr{
      text-align:left;
      padding-left:6px;
      color:#e5e7eb !important;
      text-transform:none !important;
      letter-spacing:0 !important;
    }
    .cell{
      height:46px;
      border-radius:14px;
      background:rgba(15,23,42,0.75);
      border:1px solid rgba(148,163,184,0.22);
      cursor:pointer;
      transition:transform .12s ease, box-shadow .12s ease, border-color .12s ease;
      user-select:none;
      position:relative;
      overflow:hidden;
    }
    .cell:hover{
      transform:translateY(-1px);
      border-color:rgba(167,139,250,0.6);
      box-shadow:0 0 0 1px rgba(167,139,250,0.25), 0 0 18px rgba(167,139,250,0.18);
    }
    .cell .mark{
      display:flex;
      align-items:center;
      justify-content:center;
      height:100%;
      font-weight:900;
      font-size:1.2rem;
    }
    .cell.state-yes{
      background:linear-gradient(135deg, rgba(34,197,94,0.35), rgba(15,23,42,0.75));
      border-color:rgba(34,197,94,0.65);
    }
    .cell.state-no{
      background:linear-gradient(135deg, rgba(239,68,68,0.25), rgba(15,23,42,0.75));
      border-color:rgba(239,68,68,0.55);
    }
    .cell.flash{ animation:flash .25s ease-in-out; }
    @keyframes flash{ 0%{transform:scale(1);} 50%{transform:scale(1.03);} 100%{transform:scale(1);} }
    .shake{ animation:shake .25s ease-in-out; }
    @keyframes shake{
      0%{ transform:translateX(0); }
      25%{ transform:translateX(-5px); }
      50%{ transform:translateX(5px); }
      75%{ transform:translateX(-3px); }
      100%{ transform:translateX(0); }
    }

    .clue{
      padding:10px 10px;
      border-radius:14px;
      border:1px solid rgba(148,163,184,0.18);
      background:rgba(15,23,42,0.55);
      margin-bottom:8px;
      transition:transform .12s ease;
    }
    .clue:hover{ transform:translateY(-1px); }
    .clue strong{ color:#a78bfa; }
    .mini{ font-size:0.78rem; color:#9ca3af; }

    #log{
      margin-top: 10px;
      font-size: 0.8rem;
      color: #9ca3af;
      max-height: 160px;
      overflow-y: auto;
    }

    #resultBanner{
      margin-top: 12px;
      padding: 10px 14px;
      border-radius: 999px;
      font-size: 0.9rem;
      display: none;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    #resultBanner.win{
      background: rgba(22,163,74,0.12);
      border: 1px solid rgba(22,163,74,0.5);
      color: #bbf7d0;
    }
    #resultBanner.lose{
      background: rgba(239,68,68,0.12);
      border: 1px solid rgba(239,68,68,0.5);
      color: #fecaca;
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
      <div class="small text-uppercase text-secondary">Logic Grid Puzzle</div>
      <div class="fw-bold">🧠 Deduction & Clues</div>
    </div>
  </div>
</div>

<div id="track">
  <div class="bg-orb orb1"></div>
  <div class="bg-orb orb2"></div>

  <div id="statusRow">
    <div>
      <div class="fw-semibold">Puzzle <span id="lap">1</span> / <span id="lapTotal">1</span></div>
      <div class="small text-secondary">Tap a cell to cycle: blank → ✔ → ❌. Use clues to deduce.</div>
    </div>
    <div id="meters">
      <div class="meter">
        <div class="meter-label">Deduction Power</div>
        <div class="meter-bar"><div id="powerFill" class="meter-fill power"></div></div>
      </div>
      <div class="meter">
        <div class="meter-label">Mistake Heat</div>
        <div class="meter-bar"><div id="heatFill" class="meter-fill heat"></div></div>
      </div>
      <div class="meter">
        <div class="meter-label">Solution Progress</div>
        <div class="meter-bar"><div id="progressFill" class="meter-fill progress"></div></div>
      </div>
    </div>
    <div class="text-end">
      <div>🏆 Score: <span id="score">0</span></div>
      <div>⏱️ Time: <span id="time">90</span>s</div>
    </div>
  </div>

  <div id="problemCard">
    <div id="problemTitle">4 students · 4 languages · 4 ranks</div>
    <div id="problemText" class="mt-1 small">
      Goal: find each student’s programming language and rank using pure logic.
    </div>
    <div id="effectChips" class="chip-row"></div>
    <div class="mini-actions">
      <div class="d-flex gap-2 flex-wrap align-items-center">
        <button id="hintBtn" class="btn btn-sm btn-outline-info rounded-pill">Hint (2)</button>
        <button id="resetBtn" class="btn btn-sm btn-outline-light rounded-pill">Reset Grid</button>
      </div>
      <div class="d-flex gap-2 flex-wrap align-items-center">
        <span class="badge-pill">🔥 Streak: <span id="streak">0</span></span>
        <button id="soundBtn" class="toggle-pill" type="button">Sound: ON</button>
      </div>
    </div>
  </div>

  <div class="grid-wrap">
    <div class="panel">
      <h6>Students × Languages</h6>
      <table class="logic" id="gridLang"></table>
      <div class="mini mt-2">Each student has exactly 1 language. Each language belongs to exactly 1 student.</div>
    </div>
    <div class="panel">
      <h6>Clues</h6>
      <div id="clues"></div>
      <div class="mt-2 d-flex gap-2 flex-wrap">
        <button id="checkBtn" class="btn btn-sm btn-success rounded-pill">Check Solution</button>
        <button id="autoBtn" class="btn btn-sm btn-outline-warning rounded-pill">Auto-check contradictions</button>
        <button id="skipBtn" class="btn btn-sm btn-outline-light rounded-pill">Skip Puzzle</button>
      </div>
      <div class="mini mt-2">No guessing: deduce with clues. Use auto-check to spot conflicts.</div>
    </div>
    <div class="panel">
      <h6>Students × Ranks</h6>
      <table class="logic" id="gridRank"></table>
      <div class="mini mt-2">Rank 1 = highest. One rank per student.</div>
    </div>
    <div class="panel">
      <h6>Run Log</h6>
      <div id="log"></div>
    </div>
  </div>

  <div id="resultBanner" class="mt-2">
    <div id="resultText"></div>
    <button id="nextLapBtn" class="btn btn-sm btn-light text-dark">Keep Solving</button>
  </div>
</div>

<canvas id="confetti"></canvas>

<script>
  let score = 0;
  let timeLeft = 90;
  let timerId = null;
  let canPlay = true;
  let power = 65;
  let heat = 10;
  let solvedProgress = 0;
  let streak = 0;
  let hintLeft = 2;
  let soundOn = true;
  let puzzleIndex = 0;

  const scoreSpan = document.getElementById("score");
  const timeSpan = document.getElementById("time");
  const powerFill = document.getElementById("powerFill");
  const heatFill = document.getElementById("heatFill");
  const progressFill = document.getElementById("progressFill");
  const effectChips = document.getElementById("effectChips");
  const hintBtn = document.getElementById("hintBtn");
  const resetBtn = document.getElementById("resetBtn");
  const streakSpan = document.getElementById("streak");
  const soundBtn = document.getElementById("soundBtn");
  const gridLang = document.getElementById("gridLang");
  const gridRank = document.getElementById("gridRank");
  const cluesDiv = document.getElementById("clues");
  const checkBtn = document.getElementById("checkBtn");
  const autoBtn = document.getElementById("autoBtn");
  const skipBtn = document.getElementById("skipBtn");
  const logDiv = document.getElementById("log");
  const resultBanner = document.getElementById("resultBanner");
  const resultText = document.getElementById("resultText");
  const nextLapBtn = document.getElementById("nextLapBtn");
  const confettiCanvas = document.getElementById("confetti");
  const lapSpan = document.getElementById("lap");
  const lapTotalSpan = document.getElementById("lapTotal");

  function log(msg){
    const p = document.createElement("div");
    p.innerHTML = msg;
    logDiv.prepend(p);
  }

  function clamp(v){ return Math.max(0, Math.min(100, v)); }

  function setMeters(){
    powerFill.style.width = clamp(power) + "%";
    heatFill.style.width = clamp(heat) + "%";
    progressFill.style.width = clamp(solvedProgress) + "%";
  }

  function setEffectChips(items){
    effectChips.innerHTML = "";
    items.forEach(it => {
      const span = document.createElement("span");
      span.className = "chip " + (it.kind || "info");
      span.textContent = it.text;
      effectChips.appendChild(span);
    });
  }

  function beep(freq, ms){
    if(!soundOn) return;
    try{
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator();
      const g = ctx.createGain();
      o.type = "triangle";
      o.frequency.value = freq;
      g.gain.value = 0.05;
      o.connect(g);
      g.connect(ctx.destination);
      o.start();
      setTimeout(() => { o.stop(); ctx.close(); }, ms);
    }catch(e){}
  }

  // Puzzle pack: multiple levels (each has fixed unique solution + clues)
  const puzzles = [
    {
      title: "Students · Languages · Ranks",
      subtitle: "Warm-up puzzle to learn the grid.",
      students: ["Aman","Ravi","Neha","Sara"],
      languages: ["Python","Java","C++","JavaScript"],
      ranks: ["1st","2nd","3rd","4th"],
      solution: {
        Aman: { lang: "Python", rank: "1st" },
        Ravi: { lang: "Java", rank: "2nd" },
        Neha: { lang: "JavaScript", rank: "3rd" },
        Sara: { lang: "C++", rank: "4th" }
      },
      clues: [
        { text: "<strong>Ravi</strong> does not use <strong>Python</strong>." },
        { text: "The <strong>Java</strong> user ranked <strong>2nd</strong>." },
        { text: "<strong>Aman</strong> ranked higher than the <strong>C++</strong> user." },
        { text: "<strong>Neha</strong> uses <strong>JavaScript</strong>." },
        { text: "<strong>Sara</strong> ranked <strong>4th</strong>." },
        { text: "<strong>Ravi</strong> uses <strong>Java</strong>." }
      ]
    },
    {
      title: "Hackathon Day",
      subtitle: "Different stack choices, different leaderboard ranks.",
      students: ["Ravi","Aman","Isha","Karan"],
      languages: ["Go","Python","Java","Rust"],
      ranks: ["1st","2nd","3rd","4th"],
      solution: {
        Ravi: { lang: "Rust", rank: "3rd" },
        Aman: { lang: "Python", rank: "1st" },
        Isha: { lang: "Go", rank: "4th" },
        Karan: { lang: "Java", rank: "2nd" }
      },
      clues: [
        { text: "<strong>Aman</strong> ranked <strong>1st</strong>." },
        { text: "The <strong>Java</strong> user ranked <strong>2nd</strong>." },
        { text: "<strong>Isha</strong> did not use <strong>Python</strong> and ranked <strong>4th</strong>." },
        { text: "<strong>Ravi</strong> did not use <strong>Go</strong> and ranked <strong>3rd</strong>." },
        { text: "<strong>Karan</strong> did not use <strong>Rust</strong>." },
        { text: "The <strong>Go</strong> user ranked <strong>4th</strong>." }
      ]
    },
    {
      title: "Campus Placement",
      subtitle: "Match each student to their language + rank.",
      students: ["Neha","Sara","Omar","Priya"],
      languages: ["C#","C++","Python","JavaScript"],
      ranks: ["1st","2nd","3rd","4th"],
      solution: {
        Neha: { lang: "C#", rank: "2nd" },
        Sara: { lang: "JavaScript", rank: "1st" },
        Omar: { lang: "C++", rank: "4th" },
        Priya: { lang: "Python", rank: "3rd" }
      },
      clues: [
        { text: "The <strong>JavaScript</strong> user ranked <strong>1st</strong>." },
        { text: "<strong>Omar</strong> ranked <strong>4th</strong>." },
        { text: "<strong>Neha</strong> uses <strong>C#</strong>." },
        { text: "The <strong>Python</strong> user ranked <strong>3rd</strong>." },
        { text: "<strong>Sara</strong> ranked higher than <strong>Priya</strong>." },
        { text: "The <strong>C++</strong> user ranked <strong>4th</strong>." }
      ]
    }
  ];

  // Current puzzle data (loaded from puzzles[puzzleIndex])
  let students = [];
  let languages = [];
  let ranks = [];
  let solution = {};
  let clueList = [];

  // 0 blank, 1 yes, 2 no
  const stateLang = {};
  const stateRank = {};

  function initState(){
    // clear old
    Object.keys(stateLang).forEach(k => delete stateLang[k]);
    Object.keys(stateRank).forEach(k => delete stateRank[k]);

    students.forEach(s => {
      stateLang[s] = {};
      stateRank[s] = {};
      languages.forEach(l => stateLang[s][l] = 0);
      ranks.forEach(r => stateRank[s][r] = 0);
    });
  }

  function renderClues(){
    cluesDiv.innerHTML = "";
    clueList.forEach((c, i) => {
      const div = document.createElement("div");
      div.className = "clue";
      div.innerHTML = `<div class="fw-semibold">Clue ${i+1}</div><div class="mini">${c.text}</div>`;
      cluesDiv.appendChild(div);
    });
  }

  function updateCellVisual(cell, v){
    cell.classList.remove("state-yes","state-no");
    const mark = cell.querySelector(".mark");
    if(v === 1){ cell.classList.add("state-yes"); mark.textContent = "✔"; }
    else if(v === 2){ cell.classList.add("state-no"); mark.textContent = "✖"; }
    else { mark.textContent = ""; }
  }

  function renderGrid(tableEl, cols, stateObj, type){
    tableEl.innerHTML = "";
    const thead = document.createElement("thead");
    const hr = document.createElement("tr");
    const blank = document.createElement("th");
    blank.textContent = "Student";
    hr.appendChild(blank);
    cols.forEach(c => {
      const th = document.createElement("th");
      th.textContent = c;
      hr.appendChild(th);
    });
    thead.appendChild(hr);
    tableEl.appendChild(thead);

    const tbody = document.createElement("tbody");
    students.forEach(s => {
      const tr = document.createElement("tr");
      const th = document.createElement("th");
      th.className = "rowhdr";
      th.textContent = s;
      tr.appendChild(th);

      cols.forEach(c => {
        const td = document.createElement("td");
        const cell = document.createElement("div");
        cell.className = "cell";
        cell.dataset.student = s;
        cell.dataset.col = c;
        cell.dataset.type = type;
        const mark = document.createElement("div");
        mark.className = "mark";
        cell.appendChild(mark);
        updateCellVisual(cell, stateObj[s][c]);
        cell.addEventListener("click", () => cycleCell(cell));
        td.appendChild(cell);
        tr.appendChild(td);
      });

      tbody.appendChild(tr);
    });
    tableEl.appendChild(tbody);
  }

  function pulse(el){
    el.classList.add("flash");
    setTimeout(() => el.classList.remove("flash"), 260);
  }

  function redraw(){
    document.querySelectorAll(".cell").forEach(cell => {
      const s = cell.dataset.student;
      const c = cell.dataset.col;
      const type = cell.dataset.type;
      const v = (type === "lang" ? stateLang : stateRank)[s][c];
      updateCellVisual(cell, v);
    });
  }

  function cycleCell(cell){
    if(!canPlay) return;
    const s = cell.dataset.student;
    const c = cell.dataset.col;
    const type = cell.dataset.type;
    const target = type === "lang" ? stateLang : stateRank;

    let v = target[s][c];
    v = (v + 1) % 3;

    // auto-unique if ✔ (helps learning)
    if(v === 1){
      Object.keys(target[s]).forEach(k => { if(k !== c) target[s][k] = 2; });
      students.forEach(other => { if(other !== s && target[other][c] === 1) target[other][c] = 2; });
    }

    target[s][c] = v;
    redraw();
    pulse(cell);
    beep(v === 1 ? 760 : v === 2 ? 260 : 520, 70);
    computeProgress();
  }

  function computeProgress(){
    let correct = 0;
    students.forEach(s => {
      languages.forEach(l => { if(stateLang[s][l] === 1 && solution[s].lang === l) correct++; });
      ranks.forEach(r => { if(stateRank[s][r] === 1 && solution[s].rank === r) correct++; });
    });
    solvedProgress = clamp(Math.round((correct / 8) * 100));
    power = clamp(60 + correct * 6 - heat * 0.7);
    setMeters();
    streakSpan.textContent = streak;
    setEffectChips([
      { text:`✔ Correct picks: ${correct}/8`, kind: correct >= 5 ? "good" : "info" },
      { text:`🧠 Power: ${Math.round(power)}%`, kind:"info" },
      { text:`🔥 Heat: ${Math.round(heat)}%`, kind: heat >= 55 ? "bad" : "info" }
    ]);
  }

  function contradictionCheck(){
    let contradictions = 0;
    function checkMatrix(stateObj, cols){
      students.forEach(s => {
        const yes = cols.filter(c => stateObj[s][c] === 1);
        if(yes.length > 1) contradictions += yes.length - 1;
      });
      cols.forEach(c => {
        const yes = students.filter(s => stateObj[s][c] === 1);
        if(yes.length > 1) contradictions += yes.length - 1;
      });
    }
    checkMatrix(stateLang, languages);
    checkMatrix(stateRank, ranks);

    if(contradictions > 0){
      heat = clamp(heat + contradictions * 8);
      log(`<span class="text-warning">Contradiction:</span> Found ${contradictions} conflict(s). Fix them.`);
      beep(200,140);
      gridLang.classList.add("shake");
      gridRank.classList.add("shake");
      setTimeout(() => { gridLang.classList.remove("shake"); gridRank.classList.remove("shake"); }, 250);
    }else{
      heat = clamp(heat - 8);
      log(`<span class="text-success">Clean:</span> No contradictions detected. Nice!`);
      beep(720,90);
    }
    computeProgress();
  }

  function isSolved(){
    for(const s of students){
      const langYes = languages.filter(l => stateLang[s][l] === 1);
      const rankYes = ranks.filter(r => stateRank[s][r] === 1);
      if(langYes.length !== 1 || rankYes.length !== 1) return false;
      if(langYes[0] !== solution[s].lang) return false;
      if(rankYes[0] !== solution[s].rank) return false;
    }
    for(const l of languages){
      if(students.filter(s => stateLang[s][l] === 1).length !== 1) return false;
    }
    for(const r of ranks){
      if(students.filter(s => stateRank[s][r] === 1).length !== 1) return false;
    }
    return true;
  }

  function addScore(points){
    score = Math.max(0, score + points);
    scoreSpan.textContent = score;
  }

  function solveCelebration(){
    canPlay = false;
    resultBanner.classList.remove("lose");
    resultBanner.classList.add("win");
    const lastPuzzle = puzzleIndex >= puzzles.length - 1;
    resultText.textContent = lastPuzzle
      ? "Solved! You cleared the whole puzzle pack. Legendary!"
      : "Solved! Nice deduction. +200 points! Tap 'Keep Solving' for next puzzle.";
    resultBanner.style.display = "flex";
    addScore(200 + Math.max(0, timeLeft));
    streak++;
    streakSpan.textContent = streak;
    heat = clamp(heat - 30);
    power = clamp(power + 20);
    solvedProgress = 100;
    setMeters();
    beep(880,160);
    confettiBurst();
    log(`<span class="text-success">Solved:</span> Puzzle ${puzzleIndex + 1} completed.`);
  }

  function startTimer(){
    // Uses whatever timeLeft is currently set to (per puzzle / continue)
    if(timeLeft <= 0) timeLeft = 90;
    timeSpan.textContent = timeLeft;
    if(timerId) clearInterval(timerId);
    timerId = setInterval(() => {
      if(!canPlay) return;
      timeLeft--;
      timeSpan.textContent = timeLeft;
      if(timeLeft % 10 === 0){
        heat = clamp(heat + 2);
        computeProgress();
      }
      if(timeLeft <= 0){
        clearInterval(timerId);
        canPlay = false;
        heat = clamp(heat + 25);
        computeProgress();
        resultBanner.classList.remove("win");
        resultBanner.classList.add("lose");
        resultText.textContent = "Time up! Reset and try again — faster logic next run.";
        resultBanner.style.display = "flex";
        beep(180,160);
      }
    }, 1000);
  }

  function confettiBurst(){
    const c = confettiCanvas;
    const ctx = c.getContext("2d");
    c.width = window.innerWidth;
    c.height = window.innerHeight;
    c.style.display = "block";
    const pieces = Array.from({length: 140}).map(() => ({
      x: Math.random()*c.width,
      y: -20 - Math.random()*c.height*0.3,
      r: 2 + Math.random()*5,
      vx: -2 + Math.random()*4,
      vy: 2 + Math.random()*5,
      a: Math.random()*Math.PI*2,
      va: -0.2 + Math.random()*0.4,
      col: ["#a78bfa","#22c55e","#38bdf8","#f97316","#fbbf24","#ef4444"][Math.floor(Math.random()*6)]
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

  autoBtn.addEventListener("click", contradictionCheck);
  checkBtn.addEventListener("click", () => {
    if(isSolved()) solveCelebration();
    else{
      heat = clamp(heat + 12);
      addScore(-5);
      streak = 0;
      computeProgress();
      resultBanner.classList.remove("win");
      resultBanner.classList.add("lose");
      resultText.textContent = "Not solved yet. Use clues and remove contradictions.";
      resultBanner.style.display = "flex";
      beep(240,120);
      log("<span class='text-warning'>Check:</span> Not correct yet.");
    }
  });

  hintBtn.addEventListener("click", () => {
    if(hintLeft <= 0 || !canPlay) return;
    hintLeft--;
    hintBtn.textContent = `Hint (${hintLeft})`;

    const candidates = [];
    students.forEach(s => {
      if(languages.some(l => stateLang[s][l] === 1) === false) candidates.push({ type:"lang", s, c: solution[s].lang });
      if(ranks.some(r => stateRank[s][r] === 1) === false) candidates.push({ type:"rank", s, c: solution[s].rank });
    });
    const pick = candidates[Math.floor(Math.random()*candidates.length)];
    if(!pick) return;

    if(pick.type === "lang"){
      Object.keys(stateLang[pick.s]).forEach(k => stateLang[pick.s][k] = (k===pick.c ? 1 : 2));
    }else{
      Object.keys(stateRank[pick.s]).forEach(k => stateRank[pick.s][k] = (k===pick.c ? 1 : 2));
    }
    redraw();
    computeProgress();
    setEffectChips([{ text:`🧠 Hint revealed: ${pick.s} → ${pick.c}`, kind:"good" }]);
    addScore(15);
    beep(520,100);
  });

  resetBtn.addEventListener("click", () => {
    initState();
    renderAll();
    canPlay = true;
    resultBanner.style.display = "none";
    streak = 0;
    hintLeft = 2;
    hintBtn.textContent = `Hint (${hintLeft})`;
    heat = 10;
    power = 65;
    solvedProgress = 0;
    addScore(-10);
    computeProgress();
    startTimer();
    log("<span class='text-secondary'>Reset:</span> Grid cleared. Start fresh.");
  });

  soundBtn.addEventListener("click", () => {
    soundOn = !soundOn;
    soundBtn.textContent = "Sound: " + (soundOn ? "ON" : "OFF");
    if(soundOn) beep(740,90);
  });

  nextLapBtn.addEventListener("click", () => {
    // If solved, go to next puzzle. If time-up, restart same puzzle.
    const solved = solvedProgress >= 100;
    const timeUp = timeLeft <= 0;

    if(solved){
      if(puzzleIndex < puzzles.length - 1){
        puzzleIndex++;
        loadPuzzle(puzzleIndex);
        log("<span class='text-info'>Next:</span> New puzzle loaded.");
      }else{
        log("<span class='text-success'>Pack:</span> All puzzles completed. You can replay using Reset or Skip.");
      }
    }else if(timeUp && !canPlay){
      // restart same puzzle with a comeback timer
      canPlay = true;
      timeLeft = 60;
      timeSpan.textContent = timeLeft;
      startTimer();
      log("<span class='text-info'>Continue:</span> Timer restarted. Keep solving!");
    }

    resultBanner.style.display = "none";
  });

  function renderAll(){
    renderClues();
    renderGrid(gridLang, languages, stateLang, "lang");
    renderGrid(gridRank, ranks, stateRank, "rank");
  }

  function loadPuzzle(idx){
    const p = puzzles[idx];
    students = p.students.slice();
    languages = p.languages.slice();
    ranks = p.ranks.slice();
    solution = p.solution;
    clueList = p.clues.slice();

    lapSpan.textContent = String(idx + 1);
    lapTotalSpan.textContent = String(puzzles.length);

    document.getElementById("problemTitle").textContent = p.title;
    document.getElementById("problemText").textContent = p.subtitle;

    // reset run state for each puzzle
    canPlay = true;
    heat = 10;
    power = 65;
    solvedProgress = 0;
    hintLeft = 2;
    hintBtn.textContent = `Hint (${hintLeft})`;
    resultBanner.style.display = "none";

    timeLeft = 90;
    timeSpan.textContent = timeLeft;
    initState();
    renderAll();
    computeProgress();
    startTimer();
  }

  skipBtn.addEventListener("click", () => {
    if(puzzleIndex < puzzles.length - 1){
      puzzleIndex++;
    }else{
      puzzleIndex = 0;
    }
    loadPuzzle(puzzleIndex);
    log("<span class='text-secondary'>Skip:</span> Jumped to another puzzle.");
  });

  // init
  streakSpan.textContent = streak;
  setEffectChips([{ text:"Tap cells to mark ✔ / ❌. Use clues. Then check.", kind:"info" }]);
  loadPuzzle(0);
  log("<span class='text-info'>Start:</span> Puzzle pack loaded. Solve them one by one.");
</script>

</body>
</html>

