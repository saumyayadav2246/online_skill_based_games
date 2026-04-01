<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HTML Sniper Arena</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@700;900&display=swap');

*{margin:0;padding:0;box-sizing:border-box;}

:root{
  --bg:#060d1a;
  --panel:#0a1628;
  --accent:#00ffe7;
  --danger:#ff3c5c;
  --warn:#ffd700;
  --correct:#00ff88;
  --code-bg:#0e1f35;
  --code-border:#1a3a5c;
  --text:#c8e6ff;
}

body{
  background:var(--bg);
  color:var(--text);
  font-family:'Share Tech Mono',monospace;
  overflow:hidden;
  height:100vh;
  width:100vw;
  user-select:none;
  cursor:none;
}

/* Scanline overlay */
body::before{
  content:'';
  position:fixed;
  inset:0;
  background:repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,231,0.015) 2px, rgba(0,255,231,0.015) 4px);
  pointer-events:none;
  z-index:1000;
}

/* Grid bg */
body::after{
  content:'';
  position:fixed;
  inset:0;
  background-image:
    linear-gradient(rgba(0,255,231,0.03) 1px,transparent 1px),
    linear-gradient(90deg,rgba(0,255,231,0.03) 1px,transparent 1px);
  background-size:40px 40px;
  pointer-events:none;
  z-index:0;
}

#hud{
  position:relative;
  z-index:10;
  background:var(--panel);
  border-bottom:2px solid var(--accent);
  padding:10px 20px;
  display:flex;
  align-items:center;
  gap:30px;
  flex-wrap:wrap;
}

.hud-title{
  font-family:'Orbitron',monospace;
  font-size:18px;
  font-weight:900;
  color:var(--accent);
  letter-spacing:3px;
  text-shadow:0 0 20px var(--accent);
  flex:1;
}

.hud-stat{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:13px;
  color:var(--warn);
  text-shadow:0 0 8px var(--warn);
}

.hud-val{
  font-family:'Orbitron',monospace;
  font-size:16px;
  font-weight:700;
}

#questionBox{
  position:relative;
  z-index:10;
  background:linear-gradient(135deg,#0a1e38,#061428);
  border:1px solid var(--accent);
  border-top:none;
  padding:12px 20px;
  text-align:center;
  box-shadow:0 4px 30px rgba(0,255,231,0.1);
}

#questionLabel{
  font-size:11px;
  color:var(--accent);
  opacity:0.7;
  letter-spacing:4px;
  text-transform:uppercase;
  margin-bottom:4px;
}

#question{
  font-family:'Orbitron',monospace;
  font-size:15px;
  color:#fff;
  text-shadow:0 0 10px rgba(255,255,255,0.3);
}

#instruction{
  font-size:11px;
  color:var(--danger);
  margin-top:4px;
  opacity:0.8;
}

#gameArea{
  position:relative;
  z-index:5;
  height:calc(100vh - 140px);
  overflow:hidden;
  background:
    radial-gradient(ellipse at 50% 100%, rgba(0,100,80,0.08) 0%, transparent 60%),
    var(--bg);
}

/* Custom cursor crosshair */
#crosshair{
  position:fixed;
  width:36px;
  height:36px;
  pointer-events:none;
  z-index:9999;
  transform:translate(-50%,-50%);
}

#crosshair::before,#crosshair::after{
  content:'';
  position:absolute;
  background:var(--accent);
  box-shadow:0 0 6px var(--accent);
}
#crosshair::before{
  width:2px;height:20px;
  top:50%;left:50%;
  transform:translate(-50%,-50%);
}
#crosshair::after{
  width:20px;height:2px;
  top:50%;left:50%;
  transform:translate(-50%,-50%);
}

.crosshair-ring{
  position:absolute;
  inset:0;
  border:1.5px solid var(--accent);
  border-radius:50%;
  opacity:0.5;
  box-shadow:0 0 10px var(--accent);
}

/* Code snippets */
.snippet{
  position:absolute;
  background:var(--code-bg);
  border:1.5px solid var(--code-border);
  border-radius:6px;
  padding:10px 14px;
  font-family:'Share Tech Mono',monospace;
  font-size:12px;
  line-height:1.6;
  white-space:pre;
  cursor:none;
  transition:box-shadow 0.1s;
  min-width:180px;
  max-width:320px;
  box-shadow:0 2px 20px rgba(0,0,0,0.4);
}

.snippet .tag{color:#e06c75;}
.snippet .attr{color:#d19a66;}
.snippet .val{color:#98c379;}
.snippet .text{color:#abb2bf;}

.snippet:hover{
  box-shadow:0 0 20px rgba(255,60,92,0.3);
  border-color:var(--danger);
}

.snippet.hit-correct{
  background:rgba(0,255,136,0.15);
  border-color:var(--correct);
  box-shadow:0 0 30px rgba(0,255,136,0.5);
  animation:flashGreen 0.5s forwards;
}
.snippet.hit-wrong{
  background:rgba(255,60,92,0.15);
  border-color:var(--danger);
  box-shadow:0 0 30px rgba(255,60,92,0.5);
  animation:flashRed 0.5s forwards;
}

@keyframes flashGreen{
  0%{opacity:1;} 100%{opacity:0;transform:scale(1.1);}
}
@keyframes flashRed{
  0%{opacity:1;} 100%{opacity:0;transform:scale(0.9);}
}

/* Bullet */
.bullet{
  position:absolute;
  width:4px;
  height:18px;
  background:linear-gradient(to bottom,#fff,var(--warn));
  border-radius:2px;
  box-shadow:0 0 8px var(--warn), 0 0 20px rgba(255,215,0,0.4);
  z-index:50;
}

/* Muzzle flash */
.muzzle{
  position:fixed;
  pointer-events:none;
  z-index:200;
  border-radius:50%;
  background:radial-gradient(circle,#fff 0%,var(--warn) 40%,transparent 70%);
  animation:muzzleFade 0.12s forwards;
  transform:translate(-50%,-50%);
}
@keyframes muzzleFade{
  0%{opacity:1;width:40px;height:40px;}
  100%{opacity:0;width:80px;height:80px;}
}

/* Hit particles */
.particle{
  position:absolute;
  width:5px;height:5px;
  border-radius:50%;
  pointer-events:none;
  z-index:100;
}

/* Damage flash */
#damageFlash{
  position:fixed;inset:0;
  background:rgba(255,0,50,0.25);
  pointer-events:none;z-index:900;
  opacity:0;
  transition:opacity 0.1s;
}

#overlay{
  position:fixed;inset:0;
  background:rgba(6,13,26,0.95);
  z-index:2000;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:20px;
  font-family:'Orbitron',monospace;
}

#overlay.hidden{display:none;}

#overlay h1{
  font-size:48px;
  font-weight:900;
  color:var(--accent);
  text-shadow:0 0 40px var(--accent),0 0 80px rgba(0,255,231,0.3);
  letter-spacing:8px;
  animation:glow 2s infinite alternate;
}

@keyframes glow{
  from{text-shadow:0 0 20px var(--accent);}
  to{text-shadow:0 0 60px var(--accent),0 0 100px rgba(0,255,231,0.4);}
}

#overlay p{
  font-family:'Share Tech Mono',monospace;
  font-size:15px;
  color:var(--text);
  text-align:center;
  max-width:400px;
  line-height:1.8;
  opacity:0.8;
}

#finalScore{
  font-size:28px;
  color:var(--warn);
  text-shadow:0 0 20px var(--warn);
}

.btn-start{
  padding:14px 40px;
  background:transparent;
  border:2px solid var(--accent);
  color:var(--accent);
  font-family:'Orbitron',monospace;
  font-size:14px;
  font-weight:700;
  letter-spacing:4px;
  cursor:pointer;
  text-transform:uppercase;
  position:relative;
  overflow:hidden;
  transition:all 0.3s;
  margin-top:10px;
}

.btn-start::before{
  content:'';
  position:absolute;
  inset:0;
  background:var(--accent);
  transform:translateX(-100%);
  transition:transform 0.3s;
  z-index:-1;
}

.btn-start:hover::before{transform:translateX(0);}
.btn-start:hover{color:#000;}

.lives-display{font-size:22px;}

#reloadBar{
  position:fixed;
  bottom:16px;
  left:50%;
  transform:translateX(-50%);
  z-index:50;
  display:flex;
  gap:10px;
  align-items:center;
}

.bullet-pip{
  width:10px;height:24px;
  background:var(--warn);
  border-radius:2px;
  box-shadow:0 0 8px var(--warn);
  transition:opacity 0.2s,background 0.2s;
}
.bullet-pip.spent{
  background:#333;
  box-shadow:none;
  opacity:0.4;
}

/* Score popup */
.score-popup{
  position:absolute;
  font-family:'Orbitron',monospace;
  font-size:18px;
  font-weight:700;
  pointer-events:none;
  z-index:200;
  animation:popUp 0.8s forwards;
}
@keyframes popUp{
  0%{opacity:1;transform:translateY(0);}
  100%{opacity:0;transform:translateY(-60px);}
}

/* Combo */
#comboDisplay{
  position:fixed;
  top:120px;
  right:20px;
  font-family:'Orbitron',monospace;
  font-size:14px;
  color:var(--warn);
  text-shadow:0 0 12px var(--warn);
  z-index:100;
  opacity:0;
  transition:opacity 0.3s;
}

/* Round badge */
#roundBadge{
  position:fixed;
  top:110px;left:20px;
  font-family:'Orbitron',monospace;
  font-size:11px;
  color:var(--accent);
  opacity:0.6;
  z-index:100;
  letter-spacing:2px;
}
</style>
</head>
<body>

<div id="crosshair"><div class="crosshair-ring"></div></div>
<div id="damageFlash"></div>

<!-- HUD -->
<div id="hud">
  <div class="hud-title">⟨ HTML SNIPER ⟩</div>
  <div class="hud-stat">❤️ <span class="hud-val" id="health">3</span></div>
  <div class="hud-stat">🏆 <span class="hud-val" id="score">0</span></div>
  <div class="hud-stat" style="color:#00ffe7;text-shadow:0 0 8px #00ffe7;">WAVE <span class="hud-val" id="wave">1</span></div>
  <button onclick="resetGame()" style="background:transparent;border:1px solid #ff3c5c;color:#ff3c5c;padding:6px 16px;font-family:'Share Tech Mono',monospace;cursor:pointer;font-size:12px;letter-spacing:2px;">⟳ RESET</button>
</div>

<!-- Question -->
<div id="questionBox">
  <div id="questionLabel">▶ TARGET OBJECTIVE</div>
  <div id="question">Loading...</div>
  <div id="instruction">⚠ SHOOT THE INCORRECT HTML — Destroy ALL bugs to advance</div>
</div>

<div id="gameArea"></div>

<!-- Bullet pips -->
<div id="reloadBar"></div>

<!-- Combo display -->
<div id="comboDisplay" id="comboDisplay">🔥 COMBO x<span id="comboVal">0</span></div>
<div id="roundBadge">ROUND <span id="roundNum">1</span></div>

<!-- Overlay -->
<div id="overlay">
  <h1>HTML SNIPER</h1>
  <p>A correct HTML snippet floats among <span style="color:var(--danger)">BUGS</span>.<br>
  Shoot every <span style="color:var(--danger)">incorrect snippet</span> to clear the wave.<br>
  Hit the correct one? You lose a life ❤️</p>
  <button class="btn-start" onclick="startGame()">▶ START MISSION</button>
</div>

<script>
// ── DATA ──────────────────────────────────────────────────────────────
const questionBank = [
  {
    concept: "Correct way to add a CSS stylesheet",
    correct: `<link rel="stylesheet"\n  href="style.css">`,
    wrongs: [
      `<style src="style.css">`,
      `<css href="style.css">`,
      `<link src="style.css"\n  type="css">`,
      `<stylesheet href="style.css">`,
      `<link rel="css"\n  src="style.css">`,
    ]
  },
  {
    concept: "Correct HTML image tag",
    correct: `<img src="photo.jpg"\n  alt="A photo">`,
    wrongs: [
      `<img src="photo.jpg">`,
      `<image href="photo.jpg"\n  alt="A photo">`,
      `<img href="photo.jpg"\n  alt="A photo">`,
      `<img src="photo.jpg"\n  title="A photo">`,
      `<photo src="photo.jpg"\n  alt="A photo">`,
    ]
  },
  {
    concept: "Correct anchor (link) tag",
    correct: `<a href="https://example.com"\n  target="_blank">Visit</a>`,
    wrongs: [
      `<a link="https://example.com"\n  target="_blank">Visit</a>`,
      `<a href="https://example.com"\n  open="_blank">Visit</a>`,
      `<link href="https://example.com"\n  target="_blank">Visit</link>`,
      `<a src="https://example.com"\n  target="_blank">Visit</a>`,
      `<a href="https://example.com">Visit</a\n  target="_blank">`,
    ]
  },
  {
    concept: "Correct HTML form input",
    correct: `<input type="text"\n  name="username"\n  placeholder="Enter name">`,
    wrongs: [
      `<input type="text"\n  id="username"\n  value="Enter name">`,
      `<textbox name="username"\n  placeholder="Enter name">`,
      `<input kind="text"\n  name="username">`,
      `<field type="text"\n  name="username">`,
      `<input type="textfield"\n  name="username">`,
    ]
  },
  {
    concept: "Correct ordered list structure",
    correct: `<ol>\n  <li>First item</li>\n  <li>Second item</li>\n</ol>`,
    wrongs: [
      `<ul type="ordered">\n  <li>First item</li>\n</ul>`,
      `<list type="ol">\n  <item>First</item>\n</list>`,
      `<ol>\n  <item>First item</item>\n</ol>`,
      `<ordered-list>\n  <li>First</li>\n</ordered-list>`,
      `<ol>\n  <li>First item\n  <li>Second item\n`,
    ]
  },
  {
    concept: "Correct HTML table cell",
    correct: `<table>\n  <tr>\n    <td>Cell data</td>\n  </tr>\n</table>`,
    wrongs: [
      `<table>\n  <row>\n    <cell>Data</cell>\n  </row>\n</table>`,
      `<table>\n  <tr>\n    <th>Cell data</th>\n  </tr>\n</table>`,
      `<table>\n  <tr />\n    <td>Data</td>\n  </tr>\n</table>`,
      `<table>\n  <tr>\n    <col>Data</col>\n  </tr>\n</table>`,
      `<grid>\n  <tr>\n    <td>Data</td>\n  </tr>\n</grid>`,
    ]
  },
  {
    concept: "Correct HTML5 doctype",
    correct: `<!DOCTYPE html>`,
    wrongs: [
      `<!DOCTYPE HTML5>`,
      `<DOCTYPE html>`,
      `<!DOCTYPE html>`,
      `<!DOCTYPE html5>`,
      `<!HTML DOCTYPE>`,
    ]
  },
  {
    concept: "Correct button submit in form",
    correct: `<button type="submit">Send</button>`,
    wrongs: [
      `<button kind="submit">Send</button>`,
      `<input submit="true">Send</input>`,
      `<btn type="submit">Send</btn>`,
      `<button type="send">Send</button>`,
      `<submit><button>Send</button></submit>`,
    ]
  },
  {
    concept: "Correct video embed",
    correct: `<video src="clip.mp4"\n  controls\n  width="640">`,
    wrongs: [
      `<video href="clip.mp4"\n  controls\n  width="640">`,
      `<media src="clip.mp4"\n  controls>`,
      `<video file="clip.mp4"\n  controls>`,
      `<embed src="clip.mp4"\n  type="video/mp4">`,
      `<video src="clip.mp4"\n  play\n  width="640">`,
    ]
  },
  {
    concept: "Correct meta charset tag",
    correct: `<meta charset="UTF-8">`,
    wrongs: [
      `<meta encoding="UTF-8">`,
      `<charset>UTF-8</charset>`,
      `<meta type="charset"\n  value="UTF-8">`,
      `<meta charset="utf8">`,
      `<encode charset="UTF-8">`,
    ]
  },
  {
    concept: "Correct semantic section tag",
    correct: `<section>\n  <h2>Title</h2>\n  <p>Content here</p>\n</section>`,
    wrongs: [
      `<div class="section">\n  <h2>Title</h2>\n</div>`,
      `<segment>\n  <h2>Title</h2>\n  <p>Content</p>\n</segment>`,
      `<sec>\n  <h2>Title</h2>\n  <p>Content</p>\n</sec>`,
      `<section>\n  <title>Title</title>\n  <p>Content</p>\n</section>`,
      `<section>\n  <h2>Title</h2>\n  <text>Content</text>\n</section>`,
    ]
  },
  {
    concept: "Correct checkbox input",
    correct: `<input type="checkbox"\n  name="agree"\n  value="yes">`,
    wrongs: [
      `<input type="check"\n  name="agree">`,
      `<checkbox name="agree"\n  value="yes">`,
      `<input type="checkbox"\n  id="agree"\n  checked="yes">`,
      `<input type="bool"\n  name="agree">`,
      `<check name="agree"\n  value="yes">`,
    ]
  },
];

// ── STATE ────────────────────────────────────────────────────────────
let health, score, bullets, maxBullets;
let currentQ, activeSnippets = [];
let combo = 0;
let wave = 1;
let round = 1;
let incorrectLeft = 0;
let gameRunning = false;
let usedQuestions = new Set();

const gameArea = document.getElementById("gameArea");
const crosshair = document.getElementById("crosshair");

// ── CURSOR ────────────────────────────────────────────────────────────
document.addEventListener("mousemove", e => {
  crosshair.style.left = e.clientX + "px";
  crosshair.style.top = e.clientY + "px";
});

// ── START / RESET ─────────────────────────────────────────────────────
function startGame() {
  document.getElementById("overlay").classList.add("hidden");
  health = 3;
  score = 0;
  bullets = maxBullets = 5;
  combo = 0;
  wave = 1;
  round = 1;
  usedQuestions.clear();
  updateHUD();
  gameRunning = true;
  loadQuestion();
}

function resetGame() {
  gameArea.querySelectorAll(".snippet,.bullet").forEach(e => e.remove());
  activeSnippets = [];
  startGame();
}

// ── LOAD QUESTION ─────────────────────────────────────────────────────
function loadQuestion() {
  // Pick unused question
  let available = questionBank.filter((_,i) => !usedQuestions.has(i));
  if (available.length === 0) { usedQuestions.clear(); available = questionBank; }
  const idx = questionBank.indexOf(available[Math.floor(Math.random() * available.length)]);
  usedQuestions.add(idx);
  currentQ = questionBank[idx];

  document.getElementById("question").textContent = "✦ " + currentQ.concept;

  // Clear old
  gameArea.querySelectorAll(".snippet").forEach(e => e.remove());
  activeSnippets = [];

  // Pick 3–4 wrong answers + 1 correct
  const wrongPool = [...currentQ.wrongs].sort(() => Math.random() - 0.5);
  const wrongCount = Math.min(3 + Math.floor(wave / 2), wrongPool.length);
  const selected = wrongPool.slice(0, Math.min(wrongCount, wrongPool.length));
  incorrectLeft = selected.length;

  const all = [...selected, currentQ.correct].sort(() => Math.random() - 0.5);

  all.forEach(code => {
    spawnSnippet(code, code !== currentQ.correct);
  });

  updateBulletPips();
  updateRound();
}

// ── SPAWN SNIPPET ─────────────────────────────────────────────────────
function spawnSnippet(code, isWrong) {
  const div = document.createElement("div");
  div.className = "snippet";
  div.innerHTML = syntaxHighlight(code);
  div.dataset.isWrong = isWrong ? "1" : "0";

  const areaW = gameArea.clientWidth;
  const areaH = gameArea.clientHeight;

  div.style.left = (Math.random() * (areaW - 280)) + "px";
  div.style.top = (Math.random() * (areaH - 120)) + "px";

  gameArea.appendChild(div);

  const speed = 0.3 + wave * 0.08 + Math.random() * 0.3;
  let dx = (Math.random() > 0.5 ? 1 : -1) * speed;
  let dy = (Math.random() > 0.5 ? 1 : -1) * speed;

  const id = setInterval(() => {
    if (!document.body.contains(div)) { clearInterval(id); return; }
    let x = parseFloat(div.style.left);
    let y = parseFloat(div.style.top);
    const w = div.offsetWidth, h = div.offsetHeight;
    x += dx; y += dy;
    if (x < 0) { x = 0; dx = Math.abs(dx); }
    if (x + w > areaW) { x = areaW - w; dx = -Math.abs(dx); }
    if (y < 0) { y = 0; dy = Math.abs(dy); }
    if (y + h > areaH) { y = areaH - h; dy = -Math.abs(dy); }
    div.style.left = x + "px";
    div.style.top = y + "px";
  }, 16);

  div._intervalId = id;
  activeSnippets.push(div);
}

// ── SYNTAX HIGHLIGHT ─────────────────────────────────────────────────
function syntaxHighlight(code) {
  return code
    .replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")
    .replace(/(&lt;\/?[\w-]+)/g,'<span class="tag">$1</span>')
    .replace(/([\w-]+=)/g,'<span class="attr">$1</span>')
    .replace(/(&quot;[^&]*&quot;|"[^"]*")/g,'<span class="val">$1</span>')
    .replace(/(&gt;)([^&<\n]+)(&lt;)/g,'$1<span class="text">$2</span>$3');
}

// ── SHOOT ─────────────────────────────────────────────────────────────
gameArea.addEventListener("click", e => {
  if (!gameRunning) return;
  if (bullets <= 0) return;
  bullets--;
  updateBulletPips();
  fireBullet(e);

  if (bullets <= 0) {
    setTimeout(() => {
      bullets = maxBullets;
      updateBulletPips();
    }, 600);
  }
});

function fireBullet(e) {
  const rect = gameArea.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;

  // Muzzle flash
  const mf = document.createElement("div");
  mf.className = "muzzle";
  mf.style.cssText = `width:40px;height:40px;left:${e.clientX}px;top:${e.clientY}px;position:fixed;`;
  document.body.appendChild(mf);
  setTimeout(() => mf.remove(), 150);

  // Bullet from click point upward
  const bullet = document.createElement("div");
  bullet.className = "bullet";
  bullet.style.left = x + "px";
  bullet.style.top = y + "px";
  gameArea.appendChild(bullet);

  // Check hit immediately at click location
  let hit = false;
  activeSnippets.forEach(div => {
    if (hit || !div.parentNode) return;
    const dr = div.getBoundingClientRect();
    if (e.clientX >= dr.left && e.clientX <= dr.right &&
        e.clientY >= dr.top && e.clientY <= dr.bottom) {
      hit = true;
      clearInterval(div._intervalId);
      handleHit(div, e.clientX - rect.left, e.clientY - rect.top);
    }
  });

  // Animate bullet upward
  let by = y;
  const bi = setInterval(() => {
    by -= 12;
    bullet.style.top = by + "px";
    if (by < -20) { clearInterval(bi); bullet.remove(); }
  }, 16);
  setTimeout(() => { clearInterval(bi); if (bullet.parentNode) bullet.remove(); }, 400);
}

// ── HANDLE HIT ────────────────────────────────────────────────────────
function handleHit(div, px, py) {
  const isWrong = div.dataset.isWrong === "1";

  if (isWrong) {
    // ✅ GOOD — destroy the bug
    div.classList.add("hit-correct");
    combo++;
    const pts = 20 + (combo - 1) * 5;
    score += pts;
    showScorePopup("+" + pts, px, py, "#00ff88");
    spawnParticles(px, py, "#00ff88");
    updateHUD();
    showCombo();

    // Remove from active
    activeSnippets = activeSnippets.filter(s => s !== div);
    setTimeout(() => div.remove(), 500);

    incorrectLeft--;
    if (incorrectLeft <= 0) {
      // Wave cleared!
      setTimeout(() => nextWave(), 700);
    }
  } else {
    // ❌ HIT CORRECT ONE — penalty
    div.classList.add("hit-wrong");
    combo = 0;
    health--;
    showScorePopup("❌ WRONG!", px, py, "#ff3c5c");
    spawnParticles(px, py, "#ff3c5c");
    triggerDamage();
    updateHUD();
    hideCombo();

    setTimeout(() => {
      if (div.parentNode) div.remove();
    }, 500);

    if (health <= 0) {
      setTimeout(() => gameOver(), 600);
    }
  }
}

// ── NEXT WAVE ─────────────────────────────────────────────────────────
function nextWave() {
  wave++;
  round++;
  bullets = maxBullets;
  updateHUD();
  loadQuestion();
}

// ── PARTICLES ─────────────────────────────────────────────────────────
function spawnParticles(x, y, color) {
  for (let i = 0; i < 8; i++) {
    const p = document.createElement("div");
    p.className = "particle";
    p.style.left = x + "px";
    p.style.top = y + "px";
    p.style.background = color;
    p.style.boxShadow = `0 0 6px ${color}`;
    gameArea.appendChild(p);
    const angle = (i / 8) * Math.PI * 2;
    const speed = 3 + Math.random() * 4;
    let px2 = x, py2 = y;
    let vx = Math.cos(angle) * speed, vy = Math.sin(angle) * speed;
    let life = 1;
    const pi = setInterval(() => {
      px2 += vx; py2 += vy; vy += 0.3; life -= 0.06;
      p.style.left = px2 + "px"; p.style.top = py2 + "px";
      p.style.opacity = life;
      if (life <= 0) { clearInterval(pi); p.remove(); }
    }, 16);
  }
}

// ── SCORE POPUP ───────────────────────────────────────────────────────
function showScorePopup(text, x, y, color) {
  const el = document.createElement("div");
  el.className = "score-popup";
  el.textContent = text;
  el.style.cssText = `left:${x}px;top:${y}px;color:${color};text-shadow:0 0 10px ${color};`;
  gameArea.appendChild(el);
  setTimeout(() => el.remove(), 800);
}

// ── DAMAGE FLASH ──────────────────────────────────────────────────────
function triggerDamage() {
  const f = document.getElementById("damageFlash");
  f.style.opacity = 1;
  setTimeout(() => f.style.opacity = 0, 200);
}

// ── COMBO DISPLAY ─────────────────────────────────────────────────────
function showCombo() {
  if (combo < 2) return;
  const el = document.getElementById("comboDisplay");
  document.getElementById("comboVal").textContent = combo;
  el.style.opacity = 1;
}
function hideCombo() {
  document.getElementById("comboDisplay").style.opacity = 0;
}

// ── BULLET PIPS ───────────────────────────────────────────────────────
function updateBulletPips() {
  const bar = document.getElementById("reloadBar");
  bar.innerHTML = "";
  for (let i = 0; i < maxBullets; i++) {
    const pip = document.createElement("div");
    pip.className = "bullet-pip" + (i >= bullets ? " spent" : "");
    bar.appendChild(pip);
  }
}

// ── HUD ───────────────────────────────────────────────────────────────
function updateHUD() {
  document.getElementById("health").textContent = "❤️".repeat(health);
  document.getElementById("score").textContent = score;
  document.getElementById("wave").textContent = wave;
}
function updateRound() {
  document.getElementById("roundNum").textContent = round;
}

// ── GAME OVER ─────────────────────────────────────────────────────────
function gameOver() {
  gameRunning = false;
  activeSnippets.forEach(d => { clearInterval(d._intervalId); });
  gameArea.querySelectorAll(".snippet,.bullet").forEach(e => e.remove());
  activeSnippets = [];

  const ov = document.getElementById("overlay");
  ov.innerHTML = `
    <h1>GAME OVER</h1>
    <div id="finalScore">SCORE: ${score}</div>
    <p>Waves Survived: <strong style="color:var(--accent)">${wave - 1}</strong><br>
    Keep shooting the bugs!</p>
    <button class="btn-start" onclick="resetGame()">▶ PLAY AGAIN</button>
  `;
  ov.classList.remove("hidden");
}
</script>
</body>
</html>