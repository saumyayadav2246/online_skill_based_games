<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HTML Tag Typhoon</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Bungee+Shade&family=Rajdhani:wght@400;600;700&display=swap');

*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --bg:#0d0221;
  --neon1:#ff006e;
  --neon2:#3a86ff;
  --neon3:#fb5607;
  --neon4:#8338ec;
  --neon5:#06ffa5;
  --good:#06ffa5;
  --bad:#ff006e;
  --gold:#ffd60a;
  --panel:rgba(255,255,255,0.04);
  --border:rgba(255,255,255,0.08);
}

body{
  background:var(--bg);
  font-family:'Rajdhani',sans-serif;
  overflow:hidden;
  height:100vh;
  width:100vw;
  cursor:none;
}

/* Animated bg aurora */
#aurora{
  position:fixed;inset:0;z-index:0;
  background:
    radial-gradient(ellipse 80% 50% at 20% 50%, rgba(131,56,236,0.15) 0%, transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 20%, rgba(58,134,255,0.12) 0%, transparent 60%),
    radial-gradient(ellipse 70% 60% at 50% 80%, rgba(255,0,110,0.1) 0%, transparent 60%);
  animation:auroraPulse 8s ease-in-out infinite alternate;
}
@keyframes auroraPulse{
  0%{opacity:0.6;}50%{opacity:1;}100%{opacity:0.7;}
}

/* Floating hex bg */
#hexbg{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;}
.hex{
  position:absolute;
  width:60px;height:60px;
  border:1px solid rgba(255,255,255,0.03);
  clip-path:polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
  animation:hexFloat linear infinite;
}
@keyframes hexFloat{
  0%{transform:translateY(100vh) rotate(0deg);opacity:0;}
  10%{opacity:1;}
  90%{opacity:0.5;}
  100%{transform:translateY(-100px) rotate(360deg);opacity:0;}
}

/* Main layout */
#layout{
  position:relative;z-index:10;
  display:grid;
  grid-template-rows:auto 1fr auto;
  height:100vh;
}

/* HUD */
#hud{
  display:flex;align-items:center;gap:0;
  background:rgba(0,0,0,0.5);
  border-bottom:1px solid var(--border);
  backdrop-filter:blur(10px);
  overflow:hidden;
}

.hud-seg{
  flex:1;
  padding:10px 20px;
  border-right:1px solid var(--border);
  text-align:center;
}
.hud-seg:last-child{border-right:none;}

.hud-label{
  font-size:10px;
  letter-spacing:3px;
  text-transform:uppercase;
  opacity:0.4;
  display:block;
  margin-bottom:2px;
}
.hud-val{
  font-family:'Bungee',cursive;
  font-size:22px;
  display:block;
}

#titleSeg{
  flex:2;
  background:linear-gradient(90deg,rgba(255,0,110,0.1),rgba(58,134,255,0.1));
}
.game-title{
  font-family:'Bungee Shade',cursive;
  font-size:20px;
  background:linear-gradient(90deg,var(--neon1),var(--neon2));
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  letter-spacing:2px;
}

/* Game area */
#gameArea{
  position:relative;
  overflow:hidden;
}

/* Question banner */
#questionBanner{
  position:absolute;top:0;left:0;right:0;
  z-index:20;
  padding:10px 20px;
  background:linear-gradient(180deg,rgba(0,0,0,0.8) 0%,transparent 100%);
  text-align:center;
}
#questionText{
  font-size:17px;
  font-weight:700;
  color:#fff;
  letter-spacing:1px;
}
#subText{
  font-size:12px;
  color:var(--neon2);
  opacity:0.8;
  letter-spacing:2px;
  margin-top:2px;
}

/* Falling tags */
.falling-tag{
  position:absolute;
  padding:10px 18px;
  border-radius:10px;
  font-family:'Rajdhani',sans-serif;
  font-size:14px;
  font-weight:600;
  cursor:none;
  border:2px solid;
  backdrop-filter:blur(4px);
  white-space:nowrap;
  transition:transform 0.1s;
  letter-spacing:0.5px;
}

.falling-tag:hover{
  transform:scale(1.06);
  filter:brightness(1.2);
  box-shadow:0 0 28px rgba(255,214,10,0.3);
}

/* ALL tags look IDENTICAL — read carefully! */
.tag-correct,
.tag-wrong,
.tag-neutral{
  background:rgba(255,214,10,0.07);
  border-color:rgba(255,214,10,0.5);
  color:#ffe57a;
  box-shadow:0 0 14px rgba(255,214,10,0.12);
}

.tag-hit-good{
  animation:tagExplodeGood 0.4s forwards;
}
.tag-hit-bad{
  animation:tagExplodeBad 0.4s forwards;
}
@keyframes tagExplodeGood{
  0%{transform:scale(1);opacity:1;}
  50%{transform:scale(1.5);opacity:0.8;filter:brightness(3);}
  100%{transform:scale(2);opacity:0;}
}
@keyframes tagExplodeBad{
  0%{transform:scale(1);opacity:1;}
  30%{transform:scale(0.8) rotate(-5deg);}
  100%{transform:scale(0) rotate(20deg);opacity:0;}
}

/* Catcher at bottom */
#catcher{
  position:absolute;
  bottom:12px;
  width:100px;
  height:40px;
  margin-left:-50px;
  background:linear-gradient(135deg,rgba(58,134,255,0.3),rgba(131,56,236,0.3));
  border:2px solid var(--neon2);
  border-radius:8px;
  box-shadow:0 0 20px rgba(58,134,255,0.4), 0 0 40px rgba(131,56,236,0.2);
  display:flex;align-items:center;justify-content:center;
  font-family:'Bungee',cursive;
  font-size:10px;
  color:var(--neon2);
  letter-spacing:2px;
  transition:left 0.05s;
}
#catcher::before,#catcher::after{
  content:'';
  position:absolute;
  bottom:100%;
  width:2px;
  height:20px;
  background:linear-gradient(to top,var(--neon2),transparent);
}
#catcher::before{left:20%;}
#catcher::after{right:20%;}

/* Ground / danger zone */
#danger{
  position:absolute;
  bottom:0;left:0;right:0;
  height:60px;
  background:linear-gradient(0deg,rgba(255,0,110,0.06),transparent);
  border-top:1px dashed rgba(255,0,110,0.2);
  pointer-events:none;
}

/* Timer bar */
#timerBar{
  position:absolute;
  top:0;left:0;
  height:3px;
  background:linear-gradient(90deg,var(--neon1),var(--neon2),var(--neon5));
  transition:width 0.1s linear;
  z-index:30;
  box-shadow:0 0 10px var(--neon2);
}

/* Custom cursor */
#cursor{
  position:fixed;z-index:9999;pointer-events:none;
  transform:translate(-50%,-50%);
}
#cursor svg{filter:drop-shadow(0 0 6px var(--neon2));}

/* Bottom bar */
#bottomBar{
  background:rgba(0,0,0,0.6);
  border-top:1px solid var(--border);
  padding:8px 20px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  backdrop-filter:blur(10px);
  gap:10px;
}

.key-hint{
  padding:4px 10px;
  background:var(--panel);
  border:1px solid var(--border);
  border-radius:4px;
  font-size:11px;
  letter-spacing:1px;
  color:rgba(255,255,255,0.4);
}
.key-hint span{
  color:var(--neon2);
  font-weight:700;
}

#streakBar{
  display:flex;gap:5px;align-items:center;
  font-size:12px;color:var(--gold);
}
.streak-dot{
  width:8px;height:8px;border-radius:50%;
  background:rgba(255,214,10,0.2);
  border:1px solid rgba(255,214,10,0.3);
  transition:all 0.2s;
}
.streak-dot.lit{
  background:var(--gold);
  box-shadow:0 0 10px var(--gold);
}

/* Overlay */
#overlay{
  position:fixed;inset:0;
  z-index:1000;
  background:rgba(13,2,33,0.92);
  backdrop-filter:blur(20px);
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  gap:16px;
}
#overlay.hidden{display:none;}

.overlay-title{
  font-family:'Bungee Shade',cursive;
  font-size:52px;
  background:linear-gradient(135deg,var(--neon1) 0%,var(--neon2) 50%,var(--neon4) 100%);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  text-align:center;
  filter:drop-shadow(0 0 30px rgba(58,134,255,0.4));
  animation:titleFloat 3s ease-in-out infinite;
}
@keyframes titleFloat{
  0%,100%{transform:translateY(0);}
  50%{transform:translateY(-8px);}
}

.overlay-desc{
  text-align:center;
  font-size:15px;
  color:rgba(255,255,255,0.6);
  line-height:1.8;
  max-width:420px;
}
.overlay-desc .hi{color:var(--neon5);font-weight:700;}
.overlay-desc .bad{color:var(--neon1);font-weight:700;}

.score-display{
  font-family:'Bungee',cursive;
  font-size:42px;
  color:var(--gold);
  text-shadow:0 0 30px var(--gold);
}

.play-btn{
  padding:14px 50px;
  font-family:'Bungee',cursive;
  font-size:16px;
  letter-spacing:3px;
  background:linear-gradient(135deg,var(--neon1),var(--neon4));
  border:none;color:#fff;
  border-radius:8px;
  cursor:pointer;
  box-shadow:0 0 30px rgba(255,0,110,0.4);
  transition:all 0.2s;
  text-transform:uppercase;
}
.play-btn:hover{
  transform:scale(1.05);
  box-shadow:0 0 50px rgba(255,0,110,0.6);
}

.divider{
  width:200px;height:1px;
  background:linear-gradient(90deg,transparent,var(--neon2),transparent);
}

/* Combo popup */
.combo-pop{
  position:absolute;
  font-family:'Bungee',cursive;
  font-size:24px;
  pointer-events:none;
  z-index:200;
  text-shadow:0 0 20px currentColor;
  animation:comboPop 0.9s forwards;
}
@keyframes comboPop{
  0%{opacity:1;transform:translateY(0) scale(1);}
  50%{transform:translateY(-40px) scale(1.2);}
  100%{opacity:0;transform:translateY(-80px) scale(0.8);}
}

/* Screen flash */
#flash{
  position:fixed;inset:0;pointer-events:none;
  z-index:500;opacity:0;transition:opacity 0.15s;
}

/* Level up */
#levelUp{
  position:fixed;
  top:50%;left:50%;
  transform:translate(-50%,-50%);
  font-family:'Bungee Shade',cursive;
  font-size:50px;
  background:linear-gradient(90deg,var(--neon5),var(--gold));
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  z-index:800;
  pointer-events:none;
  opacity:0;
  text-align:center;
}

/* Miss indicator */
.miss-mark{
  position:absolute;
  width:40px;height:40px;
  border:3px solid var(--bad);
  border-radius:50%;
  pointer-events:none;
  animation:missAnim 0.6s forwards;
}
@keyframes missAnim{
  0%{opacity:1;transform:scale(0.5);}
  50%{transform:scale(1.2);}
  100%{opacity:0;transform:scale(1.5);}
}
</style>
</head>
<body>

<div id="aurora"></div>
<div id="hexbg"></div>
<div id="flash"></div>
<div id="levelUp"></div>

<div id="cursor">
  <svg width="32" height="32" viewBox="0 0 32 32">
    <circle cx="16" cy="16" r="12" fill="none" stroke="#3a86ff" stroke-width="1.5" opacity="0.6"/>
    <line x1="16" y1="4" x2="16" y2="28" stroke="#3a86ff" stroke-width="1.5"/>
    <line x1="4" y1="16" x2="28" y2="16" stroke="#3a86ff" stroke-width="1.5"/>
    <circle cx="16" cy="16" r="2" fill="#3a86ff"/>
  </svg>
</div>

<div id="layout">
  <div id="hud">
    <div class="hud-seg" id="titleSeg">
      <div class="game-title">⚡ HTML TAG TYPHOON</div>
    </div>
    <div class="hud-seg">
      <span class="hud-label">Score</span>
      <span class="hud-val" id="scoreVal" style="color:var(--gold)">0</span>
    </div>
    <div class="hud-seg">
      <span class="hud-label">Lives</span>
      <span class="hud-val" id="livesVal" style="color:var(--neon1)">❤❤❤</span>
    </div>
    <div class="hud-seg">
      <span class="hud-label">Level</span>
      <span class="hud-val" id="levelVal" style="color:var(--neon5)">1</span>
    </div>
    <div class="hud-seg">
      <span class="hud-label">Caught</span>
      <span class="hud-val" id="caughtVal" style="color:var(--neon2)">0</span>
    </div>
  </div>

  <div id="gameArea">
    <div id="timerBar" style="width:100%"></div>
    <div id="questionBanner">
      <div id="questionText">Loading...</div>
      <div id="subText">MOVE CATCHER ← → | CLICK TAGS DIRECTLY</div>
    </div>
    <div id="catcher">CATCH</div>
    <div id="danger"></div>
  </div>

  <div id="bottomBar">
    <div class="key-hint">Move: <span>Mouse / Arrow Keys</span></div>
    <div id="streakBar">
      🔥 STREAK:
      <div class="streak-dot" id="s0"></div>
      <div class="streak-dot" id="s1"></div>
      <div class="streak-dot" id="s2"></div>
      <div class="streak-dot" id="s3"></div>
      <div class="streak-dot" id="s4"></div>
    </div>
    <div class="key-hint">READ the tags — <span>all look the same!</span></div>
  </div>
</div>

<!-- Overlay -->
<div id="overlay">
  <div class="overlay-title">TAG TYPHOON</div>
  <div class="overlay-desc">
    Tags rain from the sky — <span style="color:var(--gold)">all the same colour</span>.<br>
    <span class="hi">READ each tag carefully</span> and catch the correct answer.<br>
    <span class="bad">Wrong tag caught?</span> You lose a life!<br>
    Miss the correct one? Life lost too!
  </div>
  <div class="divider"></div>
  <button class="play-btn" onclick="startGame()">⚡ START TYPHOON</button>
</div>

<script>
// ─── QUESTION BANK ────────────────────────────────────────────────────
const questions = [
  { q:"Which tag creates a hyperlink?", a:"<a>", wrong:["<link>","<href>","<url>","<nav>","<goto>"] },
  { q:"Which tag displays the LARGEST heading?", a:"<h1>", wrong:["<h6>","<head>","<title>","<header>","<big>"] },
  { q:"Which tag makes text BOLD?", a:"<strong>", wrong:["<bold>","<b1>","<em>","<mark>","<thick>"] },
  { q:"Which tag creates a line break?", a:"<br>", wrong:["<lb>","<break>","<newline>","<nl>","<hr>"] },
  { q:"Which tag embeds an image?", a:"<img>", wrong:["<image>","<pic>","<photo>","<src>","<figure>"] },
  { q:"Which tag creates an UNORDERED list?", a:"<ul>", wrong:["<ol>","<list>","<li>","<dl>","<menu>"] },
  { q:"Which tag creates a TABLE row?", a:"<tr>", wrong:["<row>","<td>","<th>","<tb>","<tr/>"] },
  { q:"Which tag defines the document BODY?", a:"<body>", wrong:["<main>","<content>","<page>","<html>","<div>"] },
  { q:"Which tag creates a PARAGRAPH?", a:"<p>", wrong:["<para>","<text>","<txt>","<section>","<block>"] },
  { q:"Which tag creates a dropdown SELECT?", a:"<select>", wrong:["<dropdown>","<choose>","<option>","<input>","<list>"] },
  { q:"Which tag marks emphasized (italic) text?", a:"<em>", wrong:["<i>","<italic>","<ital>","<stress>","<cite>"] },
  { q:"Which tag creates a horizontal rule?", a:"<hr>", wrong:["<line>","<rule>","<br>","<divider>","<sep>"] },
  { q:"Which tag defines a table HEADER cell?", a:"<th>", wrong:["<td>","<header>","<thead>","<col>","<hd>"] },
  { q:"Which tag creates a text INPUT?", a:`<input type="text">`, wrong:[`<textbox>`,`<input kind="text">`,`<field type="text">`,`<text-input>`,`<form-text>`] },
  { q:"Which tag plays AUDIO?", a:"<audio>", wrong:["<sound>","<music>","<media>","<mp3>","<play>"] },
  { q:"Which tag groups inline elements?", a:"<span>", wrong:["<inline>","<group>","<section>","<div>","<wrap>"] },
  { q:"Which tag defines NAVIGATION links?", a:"<nav>", wrong:["<navigation>","<menu>","<links>","<header>","<aside>"] },
  { q:"Which tag marks deleted text?", a:"<del>", wrong:["<strike>","<remove>","<erase>","<s>","<old>"] },
  { q:"Which tag embeds external content (iframes)?", a:"<iframe>", wrong:["<embed>","<frame>","<external>","<include>","<object>"] },
  { q:"Which tag defines a FOOTER?", a:"<footer>", wrong:["<bottom>","<end>","<base>","<copyright>","<section>"] },
  { q:"Which tag creates a BUTTON?", a:"<button>", wrong:["<btn>","<click>","<input>","<action>","<press>"] },
  { q:"Which tag creates a FORM?", a:"<form>", wrong:["<input-group>","<submit>","<fieldset>","<group>","<panel>"] },
  { q:"Which tag defines an ARTICLE?", a:"<article>", wrong:["<post>","<content>","<section>","<blog>","<entry>"] },
  { q:"Which tag shows a PROGRESS bar?", a:"<progress>", wrong:["<bar>","<loading>","<meter>","<status>","<fill>"] },
  { q:"Which tag creates a CANVAS for drawing?", a:"<canvas>", wrong:["<draw>","<svg>","<paint>","<graphic>","<stage>"] },
];

// ─── STATE ────────────────────────────────────────────────────────────
let score=0,lives=3,level=1,caught=0,streak=0;
let currentQ, spawnInterval, timerInterval;
let timerPct=100, timerMs=12000;
let gameRunning=false;
let catcherX=0;
let fallingTags=[];
let questionsSeen=new Set();

const gameArea=document.getElementById("gameArea");
const catcher=document.getElementById("catcher");
const cursor=document.getElementById("cursor");

// ─── SPAWN HEX BG ─────────────────────────────────────────────────────
(function(){
  const bg=document.getElementById("hexbg");
  for(let i=0;i<15;i++){
    const h=document.createElement("div");
    h.className="hex";
    h.style.cssText=`left:${Math.random()*100}%;width:${40+Math.random()*60}px;height:${40+Math.random()*60}px;animation-duration:${8+Math.random()*12}s;animation-delay:${-Math.random()*15}s;opacity:${0.3+Math.random()*0.4};`;
    bg.appendChild(h);
  }
})();

// ─── MOUSE / KEYBOARD ────────────────────────────────────────────────
document.addEventListener("mousemove",e=>{
  cursor.style.left=e.clientX+"px";
  cursor.style.top=e.clientY+"px";
  if(!gameRunning)return;
  const rect=gameArea.getBoundingClientRect();
  catcherX=Math.max(50,Math.min(rect.width-50,e.clientX-rect.left));
  catcher.style.left=catcherX+"px";
});

let keys={};
document.addEventListener("keydown",e=>{keys[e.key]=true;});
document.addEventListener("keyup",e=>{keys[e.key]=false;});

setInterval(()=>{
  if(!gameRunning)return;
  const rect=gameArea.getBoundingClientRect();
  if(keys["ArrowLeft"]||keys["a"])catcherX=Math.max(50,catcherX-8);
  if(keys["ArrowRight"]||keys["d"])catcherX=Math.min(rect.width-50,catcherX+8);
  catcher.style.left=catcherX+"px";
},16);

// Click directly on tags
gameArea.addEventListener("click",e=>{
  if(!gameRunning)return;
  const el=e.target.closest(".falling-tag");
  if(!el)return;
  handleTagClick(el);
});

// ─── START ────────────────────────────────────────────────────────────
function startGame(){
  document.getElementById("overlay").classList.add("hidden");
  score=0;lives=3;level=1;caught=0;streak=0;
  questionsSeen.clear();
  gameRunning=true;
  updateHUD();
  nextQuestion();
}

function nextQuestion(){
  // Clear existing tags
  gameArea.querySelectorAll(".falling-tag").forEach(t=>{clearInterval(t._iv);t.remove();});
  fallingTags=[];

  let pool=questions.filter((_,i)=>!questionsSeen.has(i));
  if(pool.length===0){questionsSeen.clear();pool=questions;}
  const idx=questions.indexOf(pool[Math.floor(Math.random()*pool.length)]);
  questionsSeen.add(idx);
  currentQ=questions[idx];

  document.getElementById("questionText").textContent="▶  "+currentQ.q;
  timerPct=100;
  startTimer();
  scheduleSpawns();
}

// ─── TIMER ────────────────────────────────────────────────────────────
function startTimer(){
  clearInterval(timerInterval);
  const speed=Math.max(5000,timerMs - (level-1)*800);
  const tick=50;
  const steps=speed/tick;
  let cur=100;
  timerInterval=setInterval(()=>{
    cur-=100/steps;
    document.getElementById("timerBar").style.width=Math.max(0,cur)+"%";
    if(cur<=0){
      clearInterval(timerInterval);
      // Time's up — lose a life if correct not caught
      loseLife("⏰ TIME'S UP");
    }
  },tick);
}

// ─── SPAWN TAGS ───────────────────────────────────────────────────────
function scheduleSpawns(){
  clearInterval(spawnInterval);
  const wrongCount=Math.min(2+level,5);
  const wrongs=[...currentQ.wrong].sort(()=>Math.random()-0.5).slice(0,wrongCount);

  // Spawn correct tag first (random delay)
  setTimeout(()=>spawnTag(currentQ.a,true), 1000+Math.random()*3000);

  // Spawn wrong tags
  wrongs.forEach((w,i)=>{
    setTimeout(()=>spawnTag(w,false), 500+i*1200+Math.random()*800);
  });
}

function spawnTag(text,isCorrect){
  if(!gameRunning)return;
  const div=document.createElement("div");
  div.className="falling-tag "+(isCorrect?"tag-correct":"tag-wrong");
  div.textContent=text;
  div.dataset.correct=isCorrect?"1":"0";

  const rect=gameArea.getBoundingClientRect();
  const x=Math.random()*(rect.width-160)+20;
  div.style.left=x+"px";
  div.style.top="-50px";
  gameArea.appendChild(div);

  const fallSpeed=1.2+level*0.25+Math.random()*0.5;
  let y=-50;
  let sway=0, swayDir=Math.random()>0.5?1:-1, swaySpeed=0.02+Math.random()*0.02;

  const iv=setInterval(()=>{
    if(!document.body.contains(div)){clearInterval(iv);return;}
    y+=fallSpeed;
    sway+=swaySpeed;
    const swayX=Math.sin(sway)*30*swayDir;
    div.style.top=y+"px";
    div.style.left=(x+swayX)+"px";

    // Check catcher collision
    checkCatcherCollision(div,y,x+swayX,isCorrect,iv);

    // Fell off screen
    if(y>rect.height){
      clearInterval(iv);
      div.remove();
      fallingTags=fallingTags.filter(t=>t!==div);
      if(isCorrect){
        loseLife("MISSED IT!");
        spawnMissMark(x+swayX,rect.height-60);
      }
    }
  },16);

  div._iv=iv;
  fallingTags.push(div);
}

function checkCatcherCollision(div,y,x,isCorrect,iv){
  const rect=gameArea.getBoundingClientRect();
  const catcherY=rect.height-52;
  const catcherHalfW=50;

  if(y+30 >= catcherY && y < catcherY+40){
    if(x+div.offsetWidth/2 >= catcherX-catcherHalfW && x+div.offsetWidth/2 <= catcherX+catcherHalfW){
      clearInterval(iv);
      handleTagCollect(div,isCorrect,x,y);
    }
  }
}

function handleTagClick(div){
  const isCorrect=div.dataset.correct==="1";
  const rect=div.getBoundingClientRect();
  const gameRect=gameArea.getBoundingClientRect();
  clearInterval(div._iv);
  handleTagCollect(div,isCorrect,rect.left-gameRect.left,rect.top-gameRect.top);
}

function handleTagCollect(div,isCorrect,x,y){
  fallingTags=fallingTags.filter(t=>t!==div);
  if(isCorrect){
    // ✅ Correct catch
    div.classList.add("tag-hit-good");
    streak++;
    updateStreak();
    const bonus=streak>1?streak*5:0;
    const pts=10+level*5+bonus;
    score+=pts;
    caught++;
    updateHUD();
    flashScreen("rgba(6,255,165,0.12)");
    showComboPop("+"+(pts)+(streak>1?" 🔥x"+streak:""),x,y,"var(--good)");
    setTimeout(()=>{div.remove();},400);
    clearInterval(timerInterval);
    // Move to next question
    setTimeout(()=>nextQuestion(),600);
  } else {
    // ❌ Wrong catch
    div.classList.add("tag-hit-bad");
    streak=0;
    updateStreak();
    flashScreen("rgba(255,0,110,0.2)");
    showComboPop("WRONG!",x,y,"var(--bad)");
    loseLife("WRONG TAG!");
    setTimeout(()=>div.remove(),400);
  }
}

// ─── LIVES ────────────────────────────────────────────────────────────
function loseLife(reason){
  lives--;
  updateHUD();
  flashScreen("rgba(255,0,110,0.3)");
  document.getElementById("hud").style.animation="shake 0.3s";
  setTimeout(()=>document.getElementById("hud").style.animation="",300);
  if(lives<=0){setTimeout(gameOver,400);return;}
  // Continue to next question
  setTimeout(()=>nextQuestion(),500);
}

// ─── LEVEL UP ─────────────────────────────────────────────────────────
function checkLevelUp(){
  const newLevel=1+Math.floor(caught/5);
  if(newLevel>level){
    level=newLevel;
    showLevelUp();
    updateHUD();
  }
}

function showLevelUp(){
  const el=document.getElementById("levelUp");
  el.textContent="LEVEL "+level+"!";
  el.style.opacity=1;
  el.style.animation="levelPop 1.5s forwards";
  setTimeout(()=>{el.style.opacity=0;el.style.animation=""},1600);
}

// ─── FLASH ────────────────────────────────────────────────────────────
function flashScreen(color){
  const f=document.getElementById("flash");
  f.style.background=color;
  f.style.opacity=1;
  setTimeout(()=>f.style.opacity=0,200);
}

// ─── COMBO POP ────────────────────────────────────────────────────────
function showComboPop(text,x,y,color){
  const el=document.createElement("div");
  el.className="combo-pop";
  el.textContent=text;
  el.style.cssText=`left:${x}px;top:${y}px;color:${color};`;
  gameArea.appendChild(el);
  setTimeout(()=>el.remove(),900);
}

// ─── MISS MARK ────────────────────────────────────────────────────────
function spawnMissMark(x,y){
  const el=document.createElement("div");
  el.className="miss-mark";
  el.style.cssText=`left:${x-20}px;top:${y}px;`;
  gameArea.appendChild(el);
  setTimeout(()=>el.remove(),700);
}

// ─── STREAK ───────────────────────────────────────────────────────────
function updateStreak(){
  checkLevelUp();
  for(let i=0;i<5;i++){
    const d=document.getElementById("s"+i);
    d.classList.toggle("lit",i<(streak%5||(streak===5?5:streak%5)));
  }
}

// ─── HUD ──────────────────────────────────────────────────────────────
function updateHUD(){
  document.getElementById("scoreVal").textContent=score;
  document.getElementById("livesVal").textContent="❤".repeat(Math.max(0,lives));
  document.getElementById("levelVal").textContent=level;
  document.getElementById("caughtVal").textContent=caught;
}

// ─── GAME OVER ────────────────────────────────────────────────────────
function gameOver(){
  gameRunning=false;
  clearInterval(spawnInterval);
  clearInterval(timerInterval);
  gameArea.querySelectorAll(".falling-tag").forEach(t=>{clearInterval(t._iv);t.remove();});

  const ov=document.getElementById("overlay");
  ov.innerHTML=`
    <div class="overlay-title" style="font-size:40px">GAME OVER</div>
    <div class="score-display">${score} pts</div>
    <div class="overlay-desc">
      Tags Caught: <span class="hi">${caught}</span> &nbsp;|&nbsp; Level Reached: <span class="hi">${level}</span><br>
      Best Streak: <span style="color:var(--gold)">🔥 ${streak}</span>
    </div>
    <div class="divider"></div>
    <button class="play-btn" onclick="startGame()">⚡ PLAY AGAIN</button>
  `;
  ov.classList.remove("hidden");
}
</script>
</body>
</html>