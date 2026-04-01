<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HTML Code Crusher</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=VT323&family=Fira+Code:wght@400;600;700&family=Black+Han+Sans&display=swap');

*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --bg:#0a0e0a;
  --terminal:#0d1a0d;
  --green:#00ff41;
  --green2:#39ff14;
  --amber:#ffb700;
  --red:#ff2222;
  --cyan:#00fff5;
  --bug:#ff6b00;
  --fixed:#39ff14;
  --line:rgba(0,255,65,0.07);
  --panel:rgba(0,255,65,0.05);
  --border:rgba(0,255,65,0.15);
}

body{
  background:var(--bg);
  font-family:'Fira Code',monospace;
  overflow:hidden;
  height:100vh;width:100vw;
  cursor:none;
}

/* CRT effect */
body::before{
  content:'';
  position:fixed;inset:0;z-index:9999;pointer-events:none;
  background:repeating-linear-gradient(0deg,
    transparent,transparent 2px,
    rgba(0,0,0,0.08) 2px,rgba(0,0,0,0.08) 4px);
}
body::after{
  content:'';
  position:fixed;inset:0;z-index:9998;pointer-events:none;
  background:radial-gradient(ellipse at center,transparent 60%,rgba(0,0,0,0.5) 100%);
}

/* Animated matrix rain bg */
#matrixBg{
  position:fixed;inset:0;z-index:0;
  opacity:0.06;
  pointer-events:none;
}

/* Custom cursor — bug squasher */
#cursor{
  position:fixed;z-index:10000;pointer-events:none;
  transform:translate(-50%,-50%);
  font-size:26px;
  line-height:1;
  filter:drop-shadow(0 0 8px var(--bug));
  transition:transform 0.05s;
}
#cursor.squash{
  transform:translate(-50%,-50%) scale(1.8);
}

/* Main layout */
#app{
  position:relative;z-index:10;
  display:grid;
  grid-template-rows:auto 1fr auto;
  height:100vh;
}

/* Top bar */
#topBar{
  display:grid;
  grid-template-columns:1fr auto 1fr;
  align-items:center;
  padding:0 20px;
  height:52px;
  background:rgba(0,0,0,0.7);
  border-bottom:1px solid var(--border);
}

.tb-title{
  font-family:'VT323',monospace;
  font-size:26px;
  color:var(--green);
  text-shadow:0 0 20px var(--green);
  letter-spacing:3px;
  text-align:center;
}

.tb-stats{
  display:flex;gap:20px;align-items:center;
}
.tb-stat{
  display:flex;flex-direction:column;align-items:center;
  font-size:11px;letter-spacing:2px;color:rgba(0,255,65,0.5);
}
.tb-stat-val{
  font-family:'VT323',monospace;
  font-size:22px;color:var(--green);
  text-shadow:0 0 10px var(--green);
  line-height:1;
}

.lives-display{font-size:18px;letter-spacing:3px;}

/* Code viewer */
#codeViewer{
  position:relative;
  overflow:hidden;
  background:var(--terminal);
  border-left:3px solid var(--border);
  border-right:3px solid var(--border);
}

/* Line number gutter */
#gutter{
  position:absolute;
  left:0;top:0;bottom:0;
  width:48px;
  background:rgba(0,0,0,0.4);
  border-right:1px solid var(--border);
  overflow:hidden;
  pointer-events:none;
  z-index:5;
}

.gutter-num{
  font-family:'Fira Code',monospace;
  font-size:12px;
  color:rgba(0,255,65,0.2);
  line-height:36px;
  text-align:right;
  padding-right:8px;
  display:block;
}

/* Code lines */
#codeScroll{
  position:absolute;
  left:48px;right:0;top:0;
  padding:0 16px;
}

.code-line{
  height:36px;
  display:flex;
  align-items:center;
  border-bottom:1px solid var(--line);
  font-size:14px;
  line-height:36px;
  white-space:nowrap;
  position:relative;
  gap:6px;
  cursor:none;
}

.code-line.has-bug{
  background:transparent;
}

.code-token{
  display:inline-block;
  padding:2px 4px;
  border-radius:3px;
  cursor:none;
}
.tok-tag{color:#ff79c6;}
.tok-attr{color:#50fa7b;}
.tok-val{color:#f1fa8c;}
.tok-text{color:#f8f8f2;}
.tok-comment{color:#6272a4;font-style:italic;}
.tok-bracket{color:#ccc;}

/* Bug token — looks IDENTICAL to normal tags, no visual hint */
.tok-bug{
  color:#ff79c6 !important;
  background:transparent;
  border:none;
  border-radius:3px;
  cursor:pointer !important;
}

/* Squashed bug */
.tok-squashed{
  color:var(--fixed) !important;
  background:rgba(57,255,20,0.1);
  border:1px solid var(--fixed);
  animation:squashAnim 0.4s forwards;
}
@keyframes squashAnim{
  0%{transform:scale(1);}
  30%{transform:scale(1.5,0.5);}
  60%{transform:scale(0.8,1.2);}
  100%{transform:scale(1);}
}

/* Escaped bug effect */
.line-escaped{
  animation:lineEscape 0.3s forwards;
}
@keyframes lineEscape{
  0%{background:rgba(255,0,0,0.1);}
  100%{background:transparent;}
}

/* Scan line cursor */
#scanLine{
  position:absolute;
  left:0;right:0;
  height:36px;
  background:rgba(0,255,65,0.04);
  border-top:1px solid rgba(0,255,65,0.1);
  border-bottom:1px solid rgba(0,255,65,0.1);
  pointer-events:none;
  z-index:3;
  transition:top 0.08s;
}

/* Spawn zone indicator */
#spawnZone{
  position:absolute;
  right:10px;top:10px;
  font-family:'VT323',monospace;
  font-size:13px;
  color:rgba(0,255,65,0.3);
  letter-spacing:1px;
  z-index:6;
}

/* Score popups */
.score-pop{
  position:fixed;
  font-family:'VT323',monospace;
  font-size:26px;
  pointer-events:none;
  z-index:5000;
  animation:scorePop 0.8s forwards;
  text-shadow:0 0 10px currentColor;
}
@keyframes scorePop{
  0%{opacity:1;transform:translateY(0) scale(1);}
  100%{opacity:0;transform:translateY(-60px) scale(1.3);}
}

/* Combo bar */
#comboArea{
  position:fixed;
  right:16px;top:100px;
  z-index:100;
  text-align:right;
  font-family:'VT323',monospace;
}
#comboCount{
  font-size:48px;
  color:var(--amber);
  text-shadow:0 0 20px var(--amber);
  line-height:1;
  transition:all 0.1s;
}
#comboLabel{
  font-size:14px;
  color:rgba(255,183,0,0.6);
  letter-spacing:3px;
}

/* Bottom bar */
#bottomBar{
  padding:8px 20px;
  background:rgba(0,0,0,0.7);
  border-top:1px solid var(--border);
  display:flex;
  align-items:center;
  justify-content:space-between;
  font-size:11px;
  color:rgba(0,255,65,0.4);
  letter-spacing:2px;
}

#progressWrap{
  flex:1;
  max-width:300px;
  height:6px;
  background:rgba(0,255,65,0.1);
  border:1px solid var(--border);
  border-radius:3px;
  overflow:hidden;
  margin:0 20px;
}
#progressBar{
  height:100%;
  background:linear-gradient(90deg,var(--green),var(--cyan));
  box-shadow:0 0 10px var(--green);
  transition:width 0.3s;
  border-radius:3px;
}

/* Overlay */
#overlay{
  position:fixed;inset:0;
  z-index:2000;
  background:rgba(0,0,0,0.92);
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  gap:18px;
  font-family:'Fira Code',monospace;
}
#overlay.hidden{display:none;}

.ov-title{
  font-family:'VT323',monospace;
  font-size:62px;
  color:var(--green);
  text-shadow:0 0 40px var(--green),0 0 80px rgba(0,255,65,0.3);
  letter-spacing:6px;
  animation:termBlink 2s step-end infinite;
}
@keyframes termBlink{
  0%,100%{opacity:1;}50%{opacity:0.7;}
}

.ov-sub{
  font-size:13px;color:rgba(0,255,65,0.6);
  letter-spacing:2px;text-transform:uppercase;
}

.ov-card{
  background:rgba(0,255,65,0.05);
  border:1px solid var(--border);
  border-radius:8px;
  padding:16px 28px;
  font-size:13px;
  color:rgba(255,255,255,0.7);
  line-height:2;
  text-align:center;
  max-width:420px;
}
.ov-card .hi{color:var(--green);font-weight:600;}
.ov-card .bad{color:var(--red);}
.ov-card .bug-col{color:var(--bug);}

.ov-score{
  font-family:'VT323',monospace;
  font-size:52px;
  color:var(--amber);
  text-shadow:0 0 30px var(--amber);
}

.start-btn{
  padding:12px 44px;
  background:transparent;
  border:2px solid var(--green);
  color:var(--green);
  font-family:'VT323',monospace;
  font-size:22px;
  letter-spacing:4px;
  cursor:pointer;
  text-transform:uppercase;
  position:relative;
  overflow:hidden;
  box-shadow:0 0 20px rgba(0,255,65,0.2);
  transition:all 0.3s;
}
.start-btn:hover{
  background:var(--green);
  color:#000;
  box-shadow:0 0 40px var(--green);
}

/* Screen shake */
@keyframes shake{
  0%,100%{transform:translateX(0);}
  20%{transform:translateX(-4px);}
  40%{transform:translateX(4px);}
  60%{transform:translateX(-3px);}
  80%{transform:translateX(3px);}
}

/* Matrix canvas */
#matrixCanvas{position:absolute;inset:0;}

/* Multiplier flash */
#multFlash{
  position:fixed;top:50%;left:50%;
  transform:translate(-50%,-50%);
  font-family:'VT323',monospace;
  font-size:70px;
  pointer-events:none;
  z-index:5000;
  opacity:0;
  color:var(--amber);
  text-shadow:0 0 40px var(--amber);
}
</style>
</head>
<body>

<div id="matrixBg"><canvas id="matrixCanvas"></canvas></div>
<div id="cursor">🔍</div>
<div id="multFlash"></div>

<div id="app">

  <div id="topBar">
    <div class="tb-stats">
      <div class="tb-stat">
        <div class="tb-stat-val" id="scoreVal">0</div>
        <div>SCORE</div>
      </div>
      <div class="tb-stat">
        <div class="tb-stat-val" id="crushedVal">0</div>
        <div>CRUSHED</div>
      </div>
    </div>
    <div class="tb-title">⚡ CODE CRUSHER ⚡</div>
    <div class="tb-stats" style="justify-content:flex-end">
      <div class="tb-stat">
        <div class="tb-stat-val lives-display" id="livesVal">💚💚💚</div>
        <div>LIVES</div>
      </div>
      <div class="tb-stat">
        <div class="tb-stat-val" id="waveVal" style="color:var(--cyan)">1</div>
        <div>WAVE</div>
      </div>
    </div>
  </div>

  <div id="codeViewer">
    <div id="gutter"></div>
    <div id="scanLine"></div>
    <div id="codeScroll"></div>
    <div id="spawnZone">[ LIVE CODE STREAM — READ CAREFULLY ]</div>
  </div>

  <div id="bottomBar">
    <span>👁 SPOT & CLICK THE WRONG HTML TOKENS</span>
    <div id="progressWrap"><div id="progressBar" style="width:0%"></div></div>
    <span id="waveProgress">0 / 0 BUGS THIS WAVE</span>
  </div>

</div>

<!-- Combo overlay -->
<div id="comboArea">
  <div id="comboCount" style="opacity:0"></div>
  <div id="comboLabel" style="opacity:0">COMBO</div>
</div>

<!-- Overlay -->
<div id="overlay">
  <div class="ov-title">CODE CRUSHER</div>
  <div class="ov-sub">HTML Bug Hunter</div>
  <div class="ov-card">
    Code streams down the terminal.<br>
    <span class="hi">Wrong/typo HTML tokens</span> are hidden among real ones.<br>
    <span class="hi">Read carefully</span> — <span style="color:var(--amber)">no colour hints!</span><br>
    Click the broken token before it scrolls off.<br>
    Miss one? <span class="bad">-1 Life</span> &nbsp;|&nbsp; 5 combo = <span style="color:var(--amber)">2x Multiplier!</span>
  </div>
  <button class="start-btn" onclick="startGame()">▶ BOOT UP</button>
</div>

<script>
// ─── MATRIX RAIN ─────────────────────────────────────────────────────
(function(){
  const canvas=document.getElementById("matrixCanvas");
  const ctx=canvas.getContext("2d");
  function resize(){canvas.width=window.innerWidth;canvas.height=window.innerHeight;}
  resize();
  window.addEventListener("resize",resize);
  const chars="HTML</>divbodyheadscript01";
  const cols=Math.floor(window.innerWidth/16);
  const drops=Array(cols).fill(1);
  setInterval(()=>{
    ctx.fillStyle="rgba(0,0,0,0.05)";
    ctx.fillRect(0,0,canvas.width,canvas.height);
    ctx.fillStyle="#00ff41";
    ctx.font="14px 'Fira Code'";
    drops.forEach((y,i)=>{
      const ch=chars[Math.floor(Math.random()*chars.length)];
      ctx.fillText(ch,i*16,y*16);
      if(y*16>canvas.height && Math.random()>0.975) drops[i]=0;
      drops[i]++;
    });
  },80);
})();

// ─── CODE BANK ────────────────────────────────────────────────────────
// Each entry: array of tokens per line. token: {text, type, isBug, fix}
// types: tag, attr, val, text, comment, bracket, bug

const codeChunks = [
  // Chunk 1: Basic page
  {
    title:"Basic HTML Page",
    lines:[
      [{t:"<!DOCTYPE html>",tp:"comment"}],
      [{t:"<",tp:"bracket"},{t:"html",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"head",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"tittle",tp:"tag",bug:true,fix:"title"},{t:">",tp:"bracket"},{t:"My Page",tp:"text"},{t:"</",tp:"bracket"},{t:"title",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"meta",tp:"tag"},{t:" ",tp:"text"},{t:"charset",tp:"attr"},{t:'="UTF-8"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  </",tp:"bracket"},{t:"head",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"body",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"h1",tp:"tag"},{t:">",tp:"bracket"},{t:"Hello World",tp:"text"},{t:"</",tp:"bracket"},{t:"h1",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"p",tp:"tag"},{t:">",tp:"bracket"},{t:"Welcome!",tp:"text"},{t:"</",tp:"bracket"},{t:"p",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  </",tp:"bracket"},{t:"bode",tp:"tag",bug:true,fix:"body"},{t:">",tp:"bracket"}],
      [{t:"</",tp:"bracket"},{t:"html",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 2: Image gallery
  {
    title:"Image Gallery",
    lines:[
      [{t:"<!-- Gallery Section -->",tp:"comment"}],
      [{t:"<",tp:"bracket"},{t:"section",tp:"tag"},{t:" ",tp:"text"},{t:"class",tp:"attr"},{t:'="gallery"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"h2",tp:"tag"},{t:">",tp:"bracket"},{t:"Photos",tp:"text"},{t:"</",tp:"bracket"},{t:"h2",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"img",tp:"tag"},{t:" ",tp:"text"},{t:"src",tp:"attr"},{t:'="cat.jpg"',tp:"val"},{t:" ",tp:"text"},{t:"alt",tp:"attr"},{t:'=""',tp:"val",bug:true,fix:'="A cute cat"'},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"img",tp:"tag"},{t:" ",tp:"text"},{t:"href",tp:"attr",bug:true,fix:"src"},{t:'="dog.jpg"',tp:"val"},{t:" ",tp:"text"},{t:"alt",tp:"attr"},{t:'="Dog"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"img",tp:"tag"},{t:" ",tp:"text"},{t:"src",tp:"attr"},{t:'="bird.jpg"',tp:"val"},{t:" ",tp:"text"},{t:"alt",tp:"attr"},{t:'="Bird"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"</",tp:"bracket"},{t:"section",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 3: Navigation
  {
    title:"Navigation Menu",
    lines:[
      [{t:"<",tp:"bracket"},{t:"nav",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"ul",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"li",tp:"tag"},{t:">",tp:"bracket"},{t:"<",tp:"bracket"},{t:"a",tp:"tag"},{t:" ",tp:"text"},{t:"href",tp:"attr"},{t:'="#home"',tp:"val"},{t:">",tp:"bracket"},{t:"Home",tp:"text"},{t:"</a>",tp:"tag"},{t:"</li>",tp:"tag"}],
      [{t:"    <",tp:"bracket"},{t:"li",tp:"tag"},{t:">",tp:"bracket"},{t:"<",tp:"bracket"},{t:"a",tp:"tag"},{t:" ",tp:"text"},{t:"link",tp:"attr",bug:true,fix:"href"},{t:'="#about"',tp:"val"},{t:">",tp:"bracket"},{t:"About",tp:"text"},{t:"</a>",tp:"tag"},{t:"</li>",tp:"tag"}],
      [{t:"    <",tp:"bracket"},{t:"li",tp:"tag"},{t:">",tp:"bracket"},{t:"<",tp:"bracket"},{t:"a",tp:"tag"},{t:" ",tp:"text"},{t:"href",tp:"attr"},{t:'="#contact"',tp:"val"},{t:">",tp:"bracket"},{t:"Contact",tp:"text"},{t:"</a>",tp:"tag"},{t:"</li>",tp:"tag"}],
      [{t:"  </",tp:"bracket"},{t:"ul",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"</",tp:"bracket"},{t:"nave",tp:"tag",bug:true,fix:"nav"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 4: Form
  {
    title:"Contact Form",
    lines:[
      [{t:"<",tp:"bracket"},{t:"form",tp:"tag"},{t:" ",tp:"text"},{t:"action",tp:"attr"},{t:'="/submit"',tp:"val"},{t:" ",tp:"text"},{t:"method",tp:"attr"},{t:'="post"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"label",tp:"tag"},{t:" ",tp:"text"},{t:"for",tp:"attr"},{t:'="name"',tp:"val"},{t:">",tp:"bracket"},{t:"Name:",tp:"text"},{t:"</",tp:"bracket"},{t:"label",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"input",tp:"tag"},{t:" ",tp:"text"},{t:"type",tp:"attr"},{t:'="textbox"',tp:"val",bug:true,fix:'="text"'},{t:" ",tp:"text"},{t:"id",tp:"attr"},{t:'="name"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"label",tp:"tag"},{t:" ",tp:"text"},{t:"for",tp:"attr"},{t:'="email"',tp:"val"},{t:">",tp:"bracket"},{t:"Email:",tp:"text"},{t:"</",tp:"bracket"},{t:"label",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"input",tp:"tag"},{t:" ",tp:"text"},{t:"type",tp:"attr"},{t:'="email"',tp:"val"},{t:" ",tp:"text"},{t:"id",tp:"attr"},{t:'="email"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"buttton",tp:"tag",bug:true,fix:"button"},{t:" ",tp:"text"},{t:"type",tp:"attr"},{t:'="submit"',tp:"val"},{t:">",tp:"bracket"},{t:"Send",tp:"text"},{t:"</button>",tp:"tag"}],
      [{t:"</",tp:"bracket"},{t:"form",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 5: Table
  {
    title:"Data Table",
    lines:[
      [{t:"<",tp:"bracket"},{t:"table",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"thead",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"tr",tp:"tag"},{t:">",tp:"bracket"},{t:"<",tp:"bracket"},{t:"th",tp:"tag"},{t:">",tp:"bracket"},{t:"Name",tp:"text"},{t:"</th><th>",tp:"tag"},{t:"Age",tp:"text"},{t:"</th>",tp:"tag"},{t:"</tr>",tp:"tag"}],
      [{t:"  </",tp:"bracket"},{t:"thead",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"tobdy",tp:"tag",bug:true,fix:"tbody"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"tr",tp:"tag"},{t:">",tp:"bracket"},{t:"<",tp:"bracket"},{t:"td",tp:"tag"},{t:">",tp:"bracket"},{t:"Alice",tp:"text"},{t:"</td><td>",tp:"tag"},{t:"25",tp:"text"},{t:"</td>",tp:"tag"},{t:"</tr>",tp:"tag"}],
      [{t:"    <",tp:"bracket"},{t:"tr",tp:"tag"},{t:">",tp:"bracket"},{t:"<",tp:"bracket"},{t:"col",tp:"tag",bug:true,fix:"td"},{t:">",tp:"bracket"},{t:"Bob",tp:"text"},{t:"</td><td>",tp:"tag"},{t:"30",tp:"text"},{t:"</td>",tp:"tag"},{t:"</tr>",tp:"tag"}],
      [{t:"  </",tp:"bracket"},{t:"tbody",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"</",tp:"bracket"},{t:"table",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 6: Video / Media
  {
    title:"Media Player",
    lines:[
      [{t:"<!-- Media Section -->",tp:"comment"}],
      [{t:"<",tp:"bracket"},{t:"figure",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"video",tp:"tag"},{t:" ",tp:"text"},{t:"src",tp:"attr"},{t:'="intro.mp4"',tp:"val"},{t:" ",tp:"text"},{t:"controls",tp:"attr"},{t:" ",tp:"text"},{t:"autoplay",tp:"attr"},{t:">",tp:"bracket"}],
      [{t:"  </",tp:"bracket"},{t:"video",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"audio",tp:"tag"},{t:" ",tp:"text"},{t:"href",tp:"attr",bug:true,fix:"src"},{t:'="bg.mp3"',tp:"val"},{t:" ",tp:"text"},{t:"controls",tp:"attr"},{t:">",tp:"bracket"}],
      [{t:"  </",tp:"bracket"},{t:"audio",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"figcapshun",tp:"tag",bug:true,fix:"figcaption"},{t:">",tp:"bracket"},{t:"My Media",tp:"text"},{t:"</figcaption>",tp:"tag"}],
      [{t:"</",tp:"bracket"},{t:"figure",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 7: CSS Link / Script
  {
    title:"Head Resources",
    lines:[
      [{t:"<",tp:"bracket"},{t:"head",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"meta",tp:"tag"},{t:" ",tp:"text"},{t:"charset",tp:"attr"},{t:'="UTF-8"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"link",tp:"tag"},{t:" ",tp:"text"},{t:"rel",tp:"attr"},{t:'="style"',tp:"val",bug:true,fix:'="stylesheet"'},{t:" ",tp:"text"},{t:"href",tp:"attr"},{t:'="app.css"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"link",tp:"tag"},{t:" ",tp:"text"},{t:"rel",tp:"attr"},{t:'="icon"',tp:"val"},{t:" ",tp:"text"},{t:"href",tp:"attr"},{t:'="favicon.ico"',tp:"val"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"script",tp:"tag"},{t:" ",tp:"text"},{t:"src",tp:"attr"},{t:'="app.js"',tp:"val"},{t:" ",tp:"text"},{t:"defer",tp:"attr"},{t:">",tp:"bracket"},{t:"<\\/script>",tp:"tag"}],
      [{t:"  <",tp:"bracket"},{t:"script",tp:"tag"},{t:" ",tp:"text"},{t:"link",tp:"attr",bug:true,fix:"src"},{t:'="vendor.js"',tp:"val"},{t:">",tp:"bracket"},{t:"<\\/script>",tp:"tag"}],
      [{t:"</",tp:"bracket"},{t:"head",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
  // Chunk 8: Semantic layout
  {
    title:"Semantic Layout",
    lines:[
      [{t:"<",tp:"bracket"},{t:"body",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"header",tp:"tag"},{t:">",tp:"bracket"},{t:"<h1>Site</h1>",tp:"text"},{t:"</header>",tp:"tag"}],
      [{t:"  <",tp:"bracket"},{t:"mane",tp:"tag",bug:true,fix:"main"},{t:">",tp:"bracket"}],
      [{t:"    <",tp:"bracket"},{t:"article",tp:"tag"},{t:">",tp:"bracket"},{t:"Content",tp:"text"},{t:"</article>",tp:"tag"}],
      [{t:"    <",tp:"bracket"},{t:"asside",tp:"tag",bug:true,fix:"aside"},{t:">",tp:"bracket"},{t:"Sidebar",tp:"text"},{t:"</aside>",tp:"tag"}],
      [{t:"  </",tp:"bracket"},{t:"main",tp:"tag"},{t:">",tp:"bracket"}],
      [{t:"  <",tp:"bracket"},{t:"footer",tp:"tag"},{t:">",tp:"bracket"},{t:"(c) 2024",tp:"text"},{t:"</footer>",tp:"tag"}],
      [{t:"</",tp:"bracket"},{t:"body",tp:"tag"},{t:">",tp:"bracket"}],
    ]
  },
];

// ─── STATE ────────────────────────────────────────────────────────────
let score=0,lives=3,wave=1,crushed=0,combo=0;
let bestCombo=0;
let totalBugsThisWave=0,crushedThisWave=0;
let gameRunning=false,scrolling=false;
let scrollY=0,scrollSpeed=0.6;
let lineEls=[],bugEls=[];
let seenChunks=new Set();
let scrollAnim=null;
let pendingBugs=new Set(); // DOM elements of uncrushed bugs

const codeScroll=document.getElementById("codeScroll");
const gutter=document.getElementById("gutter");
const scanLine=document.getElementById("scanLine");
const viewer=document.getElementById("codeViewer");

// ─── CURSOR ───────────────────────────────────────────────────────────
const cursorEl=document.getElementById("cursor");
document.addEventListener("mousemove",e=>{
  cursorEl.style.left=e.clientX+"px";
  cursorEl.style.top=e.clientY+"px";
});

// ─── START ────────────────────────────────────────────────────────────
function startGame(){
  document.getElementById("overlay").classList.add("hidden");
  score=0;lives=3;wave=1;crushed=0;combo=0;
  seenChunks.clear();
  gameRunning=true;
  updateHUD();
  loadWave();
}

function loadWave(){
  codeScroll.innerHTML="";gutter.innerHTML="";
  lineEls=[];bugEls=[];pendingBugs=new Set();
  scrollY=0;
  scrollSpeed=0.5+wave*0.06;

  // Pick chunk
  let pool=[...Array(codeChunks.length).keys()].filter(i=>!seenChunks.has(i));
  if(pool.length===0){seenChunks.clear();pool=[...Array(codeChunks.length).keys()];}
  const idx=pool[Math.floor(Math.random()*pool.length)];
  seenChunks.add(idx);
  const chunk=codeChunks[idx];

  // Build DOM lines
  const viewH=viewer.clientHeight;
  const lineH=36;
  const totalH=chunk.lines.length*lineH;

  // Start lines below viewport
  chunk.lines.forEach((tokens,i)=>{
    const row=document.createElement("div");
    row.className="code-line";
    const startY=viewH+i*lineH;
    row.style.top=startY+"px";
    row.style.position="absolute";
    row.style.left="0";row.style.right="0";
    row.style.paddingLeft="16px";

    // Line number
    const gnum=document.createElement("span");
    gnum.className="gutter-num";
    gnum.textContent=i+1;
    gnum.style.top=startY+"px";
    gnum.style.position="absolute";
    gnum.style.width="40px";
    gutter.appendChild(gnum);
    row._gnum=gnum;

    let hasBug=false;
    tokens.forEach(tok=>{
      const span=document.createElement("span");
      span.className="code-token";
      if(tok.bug){
        span.className+=" tok-bug";
        span.textContent=tok.t;
        span.dataset.fix=tok.fix||tok.t;
        span.dataset.bugId=Math.random();
        span.title="BUG: Should be "+tok.fix;
        span.addEventListener("click",handleBugClick);
        span.addEventListener("touchstart",e=>{e.preventDefault();handleBugClick({currentTarget:span,clientX:e.touches[0].clientX,clientY:e.touches[0].clientY});});
        bugEls.push(span);
        pendingBugs.add(span);
        hasBug=true;
      } else {
        span.className+=` tok-${tok.tp||"text"}`;
        span.textContent=tok.t;
      }
      row.appendChild(span);
    });

    if(hasBug) row.classList.add("has-bug");
    codeScroll.appendChild(row);
    lineEls.push({row,gnum,baseY:viewH+i*lineH});
  });

  totalBugsThisWave=bugEls.length;
  crushedThisWave=0;
  updateProgress();

  startScroll(viewH);
}

// ─── SCROLL ───────────────────────────────────────────────────────────
function startScroll(viewH){
  cancelAnimationFrame(scrollAnim);
  scrolling=true;
  let lastTime=null;

  function tick(ts){
    if(!gameRunning){return;}
    if(!lastTime)lastTime=ts;
    const dt=(ts-lastTime)/16;
    lastTime=ts;

    scrollY+=scrollSpeed*dt;

    lineEls.forEach(({row,gnum,baseY})=>{
      const y=baseY-scrollY;
      row.style.top=y+"px";
      gnum.style.top=y+"px";
    });

    // Scan line follows center-ish
    scanLine.style.top=(viewH*0.5)+"px";

    // Check for escaped bugs (scrolled above -40px)
    pendingBugs.forEach(bugSpan=>{
      const rect=bugSpan.getBoundingClientRect();
      const vRect=viewer.getBoundingClientRect();
      if(rect.bottom < vRect.top-5){
        // Bug escaped!
        pendingBugs.delete(bugSpan);
        bugSpan.classList.remove("tok-bug");
        bugSpan.classList.add("tok-tag");
        bugEscaped(bugSpan);
      }
    });

    // Check if all lines scrolled off
    const lastLine=lineEls[lineEls.length-1];
    if(lastLine){
      const y=lastLine.baseY-scrollY;
      if(y < -40){
        scrolling=false;
        // Wave done — any remaining bugs = escaped
        pendingBugs.forEach(bugSpan=>{
          pendingBugs.delete(bugSpan);
          bugEscaped(bugSpan);
        });
        if(gameRunning) setTimeout(nextWave,1000);
        return;
      }
    }

    if(scrolling) scrollAnim=requestAnimationFrame(tick);
  }
  scrollAnim=requestAnimationFrame(tick);
}

// ─── BUG CLICK ────────────────────────────────────────────────────────
function handleBugClick(e){
  const span=e.currentTarget||e.target;
  if(!pendingBugs.has(span))return;
  pendingBugs.delete(span);

  const fix=span.dataset.fix;
  span.textContent=fix;
  span.classList.remove("tok-bug");
  span.classList.add("tok-squashed","tok-attr");
  span.style.cursor="default";
  span.removeEventListener("click",handleBugClick);

  // Squash cursor
  cursorEl.classList.add("squash");
  setTimeout(()=>cursorEl.classList.remove("squash"),200);

  // Combo & score
  combo++;
  if(combo > bestCombo) bestCombo = combo;
  const mult=combo>=5?2:1;
  const pts=(50+wave*10)*mult;
  console.log("score update:", score, "combo:", combo);
  score+=pts;
  crushed++;
  crushedThisWave++;
  updateHUD();
  updateProgress();

  showScorePop("+"+(pts)+(mult>1?" x2":""), e.clientX, e.clientY, mult>1?"var(--amber)":"var(--green)");
  if(mult>1) showMultFlash("x2 COMBO!");
  updateCombo();
  flashGreen();
}

// ─── BUG ESCAPED ─────────────────────────────────────────────────────
function bugEscaped(span){
  combo=0;
  updateCombo();
  lives--;
  updateHUD();
  flashRed();
  if(lives<=0 && gameRunning){
    gameRunning=false;
    cancelAnimationFrame(scrollAnim);
    setTimeout(gameOver,600);
  }
}

// ─── NEXT WAVE ────────────────────────────────────────────────────────
function nextWave(){
  if(!gameRunning)return;
  wave++;
  combo=0;
  updateCombo();
  updateHUD();
  showMultFlash("WAVE "+wave+"!");
  setTimeout(loadWave,800);
}

// ─── SCORE POPUP ─────────────────────────────────────────────────────
function showScorePop(text,x,y,color){
  const el=document.createElement("div");
  el.className="score-pop";
  el.textContent=text;
  el.style.cssText=`left:${x}px;top:${y-20}px;color:${color};`;
  document.body.appendChild(el);
  setTimeout(()=>el.remove(),800);
}

function showMultFlash(text){
  const el=document.getElementById("multFlash");
  el.textContent=text;
  el.style.opacity=1;
  el.style.animation="none";
  setTimeout(()=>{
    el.style.transition="opacity 0.6s";
    el.style.opacity=0;
  },700);
  setTimeout(()=>{el.style.transition="";},1400);
}

// ─── COMBO ────────────────────────────────────────────────────────────
function updateCombo(){
  const cc=document.getElementById("comboCount");
  const cl=document.getElementById("comboLabel");
  if(combo>=2){
    cc.textContent="x"+combo;
    cc.style.opacity=1;
    cl.style.opacity=1;
    cc.style.color=combo>=5?"var(--amber)":"var(--cyan)";
    cc.style.textShadow=combo>=5?"0 0 20px var(--amber)":"0 0 15px var(--cyan)";
  } else {
    cc.style.opacity=0;
    cl.style.opacity=0;
  }
}

// ─── FLASH ────────────────────────────────────────────────────────────
function flashGreen(){
  viewer.style.borderColor="var(--green2)";
  setTimeout(()=>viewer.style.borderColor="var(--border)",200);
}
function flashRed(){
  viewer.style.borderColor="var(--red)";
  viewer.style.animation="shake 0.3s";
  setTimeout(()=>{viewer.style.borderColor="var(--border)";viewer.style.animation="";},400);
}

// ─── PROGRESS ────────────────────────────────────────────────────────
function updateProgress(){
  const pct=totalBugsThisWave?crushedThisWave/totalBugsThisWave*100:0;
  document.getElementById("progressBar").style.width=pct+"%";
  document.getElementById("waveProgress").textContent=`${crushedThisWave} / ${totalBugsThisWave} BUGS`;
}

// ─── HUD ─────────────────────────────────────────────────────────────
function updateHUD(){
  document.getElementById("scoreVal").textContent=score;
  document.getElementById("crushedVal").textContent=crushed;
  document.getElementById("livesVal").textContent="💚".repeat(Math.max(0,lives));
  document.getElementById("waveVal").textContent=wave;
}

// ─── GAME OVER ────────────────────────────────────────────────────────
function gameOver(){
  const ov=document.getElementById("overlay");
  ov.innerHTML=`
    <div class="ov-title" style="color:var(--red);text-shadow:0 0 40px var(--red)">CRASHED</div>
    <div class="ov-score">${score} pts</div>
    <div class="ov-card">
      Bugs Crushed: <span class="hi">${crushed}</span><br>
      Waves Cleared: <span class="hi">${wave-1}</span><br>
      Best Combo: <span style="color:var(--amber)">x${bestCombo}</span>
    </div>
    <button class="start-btn" onclick="startGame()" style="border-color:var(--red);color:var(--red);">⟳ REBOOT</button>
  `;
  ov.classList.remove("hidden");
}
</script>
</body>
</html>