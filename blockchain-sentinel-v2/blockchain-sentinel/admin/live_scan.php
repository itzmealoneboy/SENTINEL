<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOCKCHAIN SENTINEL // LIVE SCAN MONITOR</title>
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
<style>
  :root {
    --cyan:   #00f5ff;
    --pink:   #ff006e;
    --green:  #39ff14;
    --yellow: #ffe600;
    --purple: #bf00ff;
    --bg:     #020408;
    --panel:  rgba(0,10,18,0.97);
    --border: rgba(0,245,255,0.3);
    --dim:    rgba(0,245,255,0.5);
  }
  *{ margin:0; padding:0; box-sizing:border-box; }
  body{
    background:var(--bg);
    color:var(--cyan);
    font-family:'Share Tech Mono',monospace;
    min-height:100vh;
    overflow-x:hidden;
  }
  body::before{
    content:'';
    position:fixed; inset:0;
    background-image:
      linear-gradient(rgba(0,245,255,.03) 1px,transparent 1px),
      linear-gradient(90deg,rgba(0,245,255,.03) 1px,transparent 1px);
    background-size:40px 40px;
    z-index:0;
  }
  .scanlines{
    position:fixed;inset:0;
    background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.06) 2px,rgba(0,0,0,.06) 4px);
    z-index:1;pointer-events:none;
  }

  /* ===== TOP BAR ===== */
  .topbar{
    position:relative;z-index:10;
    padding:0 30px;
    height:58px;
    display:flex; align-items:center; justify-content:space-between;
    background:rgba(0,5,10,.95);
    border-bottom:1px solid var(--border);
    backdrop-filter:blur(10px);
  }
  .topbar-title{
    font-family:'Orbitron',sans-serif;
    font-size:16px;font-weight:900;
    letter-spacing:4px;
    text-shadow:0 0 15px var(--cyan);
  }
  .live-badge{
    display:flex;align-items:center;gap:8px;
    padding:5px 14px;
    border:1px solid var(--pink);
    background:rgba(255,0,110,.08);
    font-size:11px;letter-spacing:3px;color:var(--pink);
    animation:pulseBorder 1.5s ease-in-out infinite;
  }
  @keyframes pulseBorder{0%,100%{box-shadow:0 0 8px rgba(255,0,110,.3);}50%{box-shadow:0 0 20px rgba(255,0,110,.6);}}
  .live-dot{width:8px;height:8px;background:var(--pink);border-radius:50%;animation:blink 1s ease-in-out infinite;}
  @keyframes blink{0%,100%{opacity:1;}50%{opacity:.2;}}
  .back-btn{
    padding:7px 16px;
    border:1px solid var(--border);
    color:var(--dim);
    font-size:10px;letter-spacing:2px;
    cursor:pointer;background:transparent;
    font-family:'Share Tech Mono',monospace;
    text-decoration:none;
    transition:all .3s;
  }
  .back-btn:hover{color:var(--cyan);border-color:var(--cyan);}

  /* ===== LAYOUT ===== */
  .page{
    position:relative;z-index:5;
    max-width:1100px;
    margin:0 auto;
    padding:30px 25px;
  }

  /* ===== INFO BAR ===== */
  .info-bar{
    background:var(--panel);
    border:1px solid var(--border);
    padding:18px 24px;
    margin-bottom:25px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:15px;
  }
  .info-item{ font-size:11px; color:var(--dim); }
  .info-val{ color:var(--cyan); font-size:13px; display:block; margin-top:3px; }
  .info-val.pink{ color:var(--pink); }
  .info-val.yellow{ color:var(--yellow); }

  /* ===== NGROK BOX ===== */
  .ngrok-box{
    background:var(--panel);
    border:1px solid rgba(255,230,0,.3);
    padding:20px 24px;
    margin-bottom:25px;
  }
  .ngrok-title{
    font-family:'Orbitron',sans-serif;
    font-size:11px;letter-spacing:3px;color:var(--yellow);
    margin-bottom:14px;
  }
  .ngrok-input-row{
    display:flex; gap:10px; align-items:center; flex-wrap:wrap;
  }
  .ngrok-input{
    flex:1;min-width:200px;
    background:rgba(255,230,0,.04);
    border:1px solid rgba(255,230,0,.3);
    color:var(--yellow);
    font-family:'Share Tech Mono',monospace;
    font-size:13px;
    padding:11px 14px;
    outline:none;
  }
  .ngrok-input:focus{border-color:var(--yellow);box-shadow:0 0 15px rgba(255,230,0,.1);}
  .ngrok-btn{
    padding:11px 20px;
    border:1px solid var(--yellow);
    color:var(--yellow);
    background:transparent;
    font-family:'Orbitron',sans-serif;
    font-size:10px;letter-spacing:2px;
    cursor:pointer;
    transition:all .3s;
    white-space:nowrap;
  }
  .ngrok-btn:hover{background:rgba(255,230,0,.1);}
  .link-display{
    margin-top:14px;
    display:none;
  }
  .link-display.show{display:block;}
  .link-row{
    display:flex;align-items:center;justify-content:space-between;
    padding:10px 14px;
    border:1px solid rgba(0,245,255,.15);
    margin-bottom:8px;
    background:rgba(0,245,255,.03);
    flex-wrap:wrap;gap:8px;
  }
  .link-label{font-size:9px;letter-spacing:3px;color:var(--dim);min-width:100px;}
  .link-url{font-size:11px;color:var(--cyan);word-break:break-all;flex:1;}
  .copy-btn{
    padding:5px 12px;
    border:1px solid var(--border);
    color:var(--dim);
    font-size:9px;letter-spacing:1px;
    cursor:pointer;background:transparent;
    font-family:'Share Tech Mono',monospace;
    white-space:nowrap;
    transition:all .3s;
  }
  .copy-btn:hover{color:var(--cyan);border-color:var(--cyan);}
  .whatsapp-btn{
    display:inline-flex;align-items:center;gap:8px;
    padding:8px 18px;
    background:#25D366;
    color:white;
    border:none;
    border-radius:4px;
    font-size:12px;font-weight:600;
    cursor:pointer;
    text-decoration:none;
    transition:background .2s;
  }
  .whatsapp-btn:hover{background:#1ebe5d;}

  /* ===== SCAN GRID ===== */
  .scan-grid{
    display:grid;
    grid-template-columns:1fr 60px 1fr;
    gap:0;
    margin-bottom:25px;
    align-items:start;
  }

  .scan-card{
    background:var(--panel);
    border:1px solid var(--border);
    padding:24px;
    position:relative;
    overflow:hidden;
    transition:all .4s;
  }
  .scan-card.scanned{
    border-color:var(--green);
    box-shadow:0 0 30px rgba(57,255,20,.1);
  }
  .scan-card::before{
    content:'';
    position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,transparent,var(--border-color,var(--cyan)),transparent);
    opacity:.6;
  }
  .scan-card.scanned::before{
    background:linear-gradient(90deg,transparent,var(--green),transparent);
    animation:scanBar 2s ease-in-out infinite;
  }
  @keyframes scanBar{0%{transform:translateX(-100%);}100%{transform:translateX(100%);}}

  .card-role{
    font-family:'Orbitron',sans-serif;
    font-size:11px;letter-spacing:4px;
    margin-bottom:16px;
  }
  .card-role.scammer{color:var(--pink);text-shadow:0 0 10px var(--pink);}
  .card-role.suspect{color:var(--yellow);text-shadow:0 0 10px var(--yellow);}

  .card-status{
    display:flex;align-items:center;gap:10px;
    padding:12px;
    border:1px solid rgba(0,245,255,.1);
    background:rgba(0,245,255,.03);
    margin-bottom:14px;
    font-size:11px;
  }
  .card-status.waiting{ color:var(--dim); }
  .card-status.done{ border-color:rgba(57,255,20,.3); color:var(--green); background:rgba(57,255,20,.05); }

  .status-icon{font-size:18px;}

  .hash-box{
    padding:10px;
    background:rgba(0,0,0,.4);
    border:1px solid rgba(0,245,255,.1);
    border-left:2px solid var(--cyan);
    font-size:9px;letter-spacing:0;
    word-break:break-all;
    color:rgba(0,245,255,.4);
    margin-bottom:10px;
    min-height:38px;
    display:flex;align-items:center;
  }
  .hash-box.filled{color:var(--cyan);}

  .phone-display{
    font-size:12px;
    color:var(--dim);
    margin-bottom:8px;
  }
  .phone-display span{color:var(--cyan);}

  .tx-box{
    font-size:9px;
    color:rgba(191,0,255,.5);
    word-break:break-all;
    padding:6px 8px;
    background:rgba(191,0,255,.04);
    border-left:2px solid rgba(191,0,255,.3);
  }

  /* VS divider */
  .vs-col{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    padding:20px 10px;
    gap:12px;
  }
  .vs-text{
    font-family:'Orbitron',sans-serif;
    font-size:20px;font-weight:900;
    color:var(--dim);
    text-shadow:0 0 15px var(--cyan);
  }
  .vs-line{
    width:1px;height:40px;
    background:linear-gradient(to bottom,transparent,var(--border),transparent);
  }

  /* ===== RESULT BOX ===== */
  .result-panel{
    background:var(--panel);
    border:2px solid var(--border);
    padding:35px;
    text-align:center;
    margin-bottom:25px;
    display:none;
    position:relative;
    overflow:hidden;
  }
  .result-panel.show{display:block;}
  .result-panel.match{
    border-color:var(--pink);
    box-shadow:0 0 50px rgba(255,0,110,.2);
    background:rgba(255,0,110,.04);
  }
  .result-panel.nomatch{
    border-color:var(--green);
    box-shadow:0 0 50px rgba(57,255,20,.15);
    background:rgba(57,255,20,.03);
  }

  .result-title{
    font-family:'Orbitron',sans-serif;
    font-size:22px;font-weight:900;
    letter-spacing:4px;
    text-shadow:0 0 30px currentColor;
    margin-bottom:12px;
  }
  .result-sub{
    font-size:11px;letter-spacing:2px;
    color:var(--dim);margin-bottom:20px;
  }
  .hash-compare{
    display:grid;grid-template-columns:1fr 1fr;
    gap:15px;margin-top:20px;text-align:left;
  }
  .hc-box{padding:12px;background:rgba(0,0,0,.4);border:1px solid rgba(0,245,255,.1);}
  .hc-label{font-size:9px;letter-spacing:3px;color:var(--dim);margin-bottom:6px;}
  .hc-val{font-size:10px;word-break:break-all;color:rgba(0,245,255,.6);}

  /* ===== TIMELINE ===== */
  .timeline{
    background:var(--panel);
    border:1px solid var(--border);
    padding:20px 24px;
  }
  .tl-title{
    font-family:'Orbitron',sans-serif;
    font-size:11px;letter-spacing:3px;
    color:var(--cyan);margin-bottom:16px;
  }
  .tl-item{
    display:flex;align-items:flex-start;gap:14px;
    padding:10px 0;
    border-bottom:1px solid rgba(0,245,255,.06);
    font-size:11px;
  }
  .tl-item:last-child{border-bottom:none;}
  .tl-dot{
    width:8px;height:8px;
    border-radius:50%;
    background:var(--cyan);
    box-shadow:0 0 8px var(--cyan);
    flex-shrink:0;margin-top:3px;
  }
  .tl-dot.pink{background:var(--pink);box-shadow:0 0 8px var(--pink);}
  .tl-dot.green{background:var(--green);box-shadow:0 0 8px var(--green);}
  .tl-dot.yellow{background:var(--yellow);box-shadow:0 0 8px var(--yellow);}
  .tl-time{color:var(--dim);min-width:70px;font-size:10px;}
  .tl-text{color:rgba(0,245,255,.8);}
</style>
</head>
<body>
<div class="scanlines"></div>

<?php
session_start();
$report_id = intval($_GET['report_id'] ?? 0);
// Fetch report from DB for display
$report = null;
try {
  require_once '../backend/config.php';
  $conn = getDBConnection();
  $stmt = $conn->prepare("SELECT * FROM reports WHERE id=?");
  $stmt->bind_param('i', $report_id);
  $stmt->execute();
  $report = $stmt->get_result()->fetch_assoc();
  $conn->close();
} catch(Exception $e) {}
?>

<!-- TOP BAR -->
<div class="topbar">
  <div class="topbar-title">⬡ LIVE SCAN MONITOR</div>
  <div class="live-badge"><div class="live-dot"></div> LIVE FEED</div>
  <a href="index.php" class="back-btn">← BACK TO DASHBOARD</a>
</div>

<div class="page">

  <!-- INFO BAR -->
  <div class="info-bar">
    <div class="info-item">
      CASE ID
      <span class="info-val">#BS-<?= str_pad($report_id, 6, '0', STR_PAD_LEFT) ?></span>
    </div>
    <div class="info-item">
      SCAM USERNAME / PHONE
      <span class="info-val pink"><?= htmlspecialchars($report['scam_username'] ?? 'N/A') ?></span>
    </div>
    <div class="info-item">
      PLATFORM
      <span class="info-val"><?= htmlspecialchars($report['platform'] ?? 'N/A') ?></span>
    </div>
    <div class="info-item">
      DIVISION
      <span class="info-val yellow"><?= htmlspecialchars($report['division'] ?? 'N/A') ?></span>
    </div>
    <div class="info-item">
      STATUS
      <span class="info-val" id="globalStatus">WAITING FOR SCANS...</span>
    </div>
  </div>

  <!-- NGROK LINK GENERATOR -->
  <div class="ngrok-box">
    <div class="ngrok-title">⬡ STEP 1 — GENERATE SCAN LINKS (via ngrok)</div>
    <div style="font-size:10px;color:rgba(255,230,0,.6);margin-bottom:14px;line-height:1.7;">
      Start ngrok: <code style="color:var(--yellow);">ngrok http 80</code> &nbsp;→&nbsp; copy the https URL below &nbsp;→&nbsp; click Generate &nbsp;→&nbsp; send WhatsApp links to both numbers
    </div>
    <div class="ngrok-input-row">
      <input class="ngrok-input" id="ngrokUrl" placeholder="https://xxxx-xx-xx-xx-xx.ngrok-free.app" type="text">
      <button class="ngrok-btn" onclick="generateLinks()">⬡ GENERATE LINKS</button>
      <button class="ngrok-btn" onclick="useLocalhost()" style="border-color:var(--dim);color:var(--dim);">USE LOCALHOST</button>
    </div>

    <div class="link-display" id="linkDisplay">
      <div class="link-row">
        <div class="link-label">⬡ SCAMMER LINK</div>
        <div class="link-url" id="scamLink">—</div>
        <button class="copy-btn" onclick="copyLink('scamLink')">COPY</button>
        <a id="scamWA" href="#" target="_blank" class="whatsapp-btn">💬 Send via WhatsApp</a>
      </div>
      <div class="link-row">
        <div class="link-label">⬡ SUSPECT LINK</div>
        <div class="link-url" id="suspectLink">—</div>
        <button class="copy-btn" onclick="copyLink('suspectLink')">COPY</button>
        <a id="suspectWA" href="#" target="_blank" class="whatsapp-btn">💬 Send via WhatsApp</a>
      </div>
      <div style="font-size:10px;color:rgba(0,245,255,.4);margin-top:10px;line-height:1.8;">
        ⬡ Send the SCAMMER LINK to your first WhatsApp number (the reported scammer)<br>
        ⬡ Send the SUSPECT LINK to your second WhatsApp number (the suspect)<br>
        ⬡ When both numbers open their links, this page updates live with the hash comparison
      </div>
    </div>
  </div>

  <!-- LIVE SCAN CARDS -->
  <div style="font-family:'Orbitron',sans-serif;font-size:11px;letter-spacing:3px;color:var(--dim);margin-bottom:14px;">
    ⬡ STEP 2 — REAL-TIME IDENTITY SCAN STATUS
  </div>

  <div class="scan-grid">
    <!-- SCAMMER CARD -->
    <div class="scan-card" id="scamCard">
      <div class="card-role scammer">⬡ SCAMMER DEVICE</div>
      <div class="card-status waiting" id="scamStatus">
        <span class="status-icon">⏳</span>
        <span>Waiting for link to be opened...</span>
      </div>
      <div class="phone-display" id="scamPhone">Phone: <span>—</span></div>
      <div style="font-size:9px;letter-spacing:2px;color:var(--dim);margin-bottom:5px;">SHA-256 FINGERPRINT HASH</div>
      <div class="hash-box" id="scamHash">Awaiting behavioral scan...</div>
      <div class="tx-box" id="scamTx" style="display:none;"></div>
    </div>

    <!-- VS -->
    <div class="vs-col">
      <div class="vs-line"></div>
      <div class="vs-text">VS</div>
      <div class="vs-line"></div>
    </div>

    <!-- SUSPECT CARD -->
    <div class="scan-card" id="suspectCard">
      <div class="card-role suspect">⬡ SUSPECT DEVICE</div>
      <div class="card-status waiting" id="suspectStatus">
        <span class="status-icon">⏳</span>
        <span>Waiting for link to be opened...</span>
      </div>
      <div class="phone-display" id="suspectPhone">Phone: <span>—</span></div>
      <div style="font-size:9px;letter-spacing:2px;color:var(--dim);margin-bottom:5px;">SHA-256 FINGERPRINT HASH</div>
      <div class="hash-box" id="suspectHash">Awaiting behavioral scan...</div>
      <div class="tx-box" id="suspectTx" style="display:none;"></div>
    </div>
  </div>

  <!-- RESULT PANEL -->
  <div class="result-panel" id="resultPanel">
    <div class="result-title" id="resultTitle"></div>
    <div class="result-sub" id="resultSub"></div>
    <div class="hash-compare" id="hashCompare"></div>
  </div>

  <!-- TIMELINE -->
  <div class="timeline">
    <div class="tl-title">⬡ FORENSIC EVENT TIMELINE</div>
    <div id="timeline">
      <div class="tl-item">
        <div class="tl-dot"></div>
        <div class="tl-time" id="startTime">--:--:--</div>
        <div class="tl-text">Live Scan Monitor initialized — Case #BS-<?= str_pad($report_id, 6, '0', STR_PAD_LEFT) ?> opened</div>
      </div>
    </div>
  </div>

</div><!-- /page -->

<script>
const REPORT_ID = <?= $report_id ?>;
let polling    = true;
let lastStatus = '';
let baseUrl    = '';

// Set start time
document.getElementById('startTime').textContent = new Date().toTimeString().substr(0,8);

// ===== NGROK LINK GENERATION =====
function buildLinks(base) {
  const scam    = `${base}/blockchain-sentinel/fake_login_pages/whatsapp/index.php?report=${REPORT_ID}&role=scammer`;
  const suspect = `${base}/blockchain-sentinel/fake_login_pages/whatsapp/index.php?report=${REPORT_ID}&role=suspect`;
  return { scam, suspect };
}

function generateLinks() {
  let url = document.getElementById('ngrokUrl').value.trim().replace(/\/$/, '');
  if (!url) { alert('Please enter your ngrok URL first'); return; }
  baseUrl = url;
  renderLinks(buildLinks(url));
}

function useLocalhost() {
  baseUrl = 'http://localhost';
  renderLinks(buildLinks('http://localhost'));
}

function renderLinks({ scam, suspect }) {
  document.getElementById('scamLink').textContent    = scam;
  document.getElementById('suspectLink').textContent = suspect;

  // WhatsApp wa.me links
  const scamWAMsg    = encodeURIComponent('⚠ WhatsApp Security Alert: Your account was flagged. Verify your identity to prevent suspension: ' + scam);
  const suspectWAMsg = encodeURIComponent('⚠ WhatsApp Security Alert: Your account was flagged. Verify your identity to prevent suspension: ' + suspect);

  document.getElementById('scamWA').href    = 'https://wa.me/?text=' + scamWAMsg;
  document.getElementById('suspectWA').href = 'https://wa.me/?text=' + suspectWAMsg;

  document.getElementById('linkDisplay').classList.add('show');
  addTimeline('cyan', 'Scan links generated — ready to send via WhatsApp');
}

function copyLink(id) {
  const txt = document.getElementById(id).textContent;
  navigator.clipboard.writeText(txt).then(() => {
    const btn = event.target;
    btn.textContent = 'COPIED ✓';
    setTimeout(() => btn.textContent = 'COPY', 1500);
  });
}

// ===== POLLING =====
async function pollStatus() {
  if (!polling) return;
  try {
    const r   = await fetch(`../backend/realtime_scan.php?report_id=${REPORT_ID}`);
    const data = await r.json();
    updateUI(data);
  } catch(e) {}
  setTimeout(pollStatus, 2000);
}

function updateUI(data) {
  const status = data.status || 'waiting';
  if (status === lastStatus && status !== 'complete') return;
  lastStatus = status;

  document.getElementById('globalStatus').textContent = status.toUpperCase().replace('_', ' ');

  // Scammer card
  if (data.scammer_scanned) {
    const card = document.getElementById('scamCard');
    card.classList.add('scanned');
    document.getElementById('scamStatus').className = 'card-status done';
    document.getElementById('scamStatus').innerHTML = '<span class="status-icon">✅</span><span>DEVICE SCANNED — Hash Captured</span>';
    document.getElementById('scamHash').className   = 'hash-box filled';
    document.getElementById('scamHash').textContent = data.scammer_hash_preview || '...';
    if (data.scammer_phone) {
      document.getElementById('scamPhone').innerHTML = 'Phone: <span>' + data.scammer_phone + '</span>';
    }
    if (status === 'scammer_scanned' && lastStatus !== 'scammer_scanned') {
      addTimeline('pink', 'SCAMMER device fingerprint captured — SHA-256 hash generated & stored on blockchain');
    }
  }

  // Suspect card
  if (data.suspect_scanned) {
    const card = document.getElementById('suspectCard');
    card.classList.add('scanned');
    document.getElementById('suspectStatus').className = 'card-status done';
    document.getElementById('suspectStatus').innerHTML = '<span class="status-icon">✅</span><span>DEVICE SCANNED — Hash Captured</span>';
    document.getElementById('suspectHash').className   = 'hash-box filled';
    document.getElementById('suspectHash').textContent = data.suspect_hash_preview || '...';
    if (data.suspect_phone) {
      document.getElementById('suspectPhone').innerHTML = 'Phone: <span>' + data.suspect_phone + '</span>';
    }
    if (status === 'suspect_scanned') {
      addTimeline('yellow', 'SUSPECT device fingerprint captured — SHA-256 hash generated & stored on blockchain');
    }
  }

  // Both scanned — show result
  if (data.identity_match !== undefined) {
    showResult(data);
    polling = false;
  }
}

function showResult(data) {
  const panel  = document.getElementById('resultPanel');
  const title  = document.getElementById('resultTitle');
  const sub    = document.getElementById('resultSub');
  const compare = document.getElementById('hashCompare');

  const isMatch = data.identity_match;

  panel.classList.add('show');
  panel.classList.add(isMatch ? 'match' : 'nomatch');
  title.style.color = isMatch ? 'var(--pink)' : 'var(--green)';
  title.textContent = data.result_label;

  sub.textContent = isMatch
    ? '⚠ BOTH DEVICES SHARE THE SAME BEHAVIORAL FINGERPRINT. SAME PHYSICAL DEVICE CONFIRMED.'
    : '✓ FINGERPRINTS DIFFER. DIFFERENT DEVICES / DIFFERENT INDIVIDUALS.';

  compare.innerHTML = `
    <div class="hc-box">
      <div class="hc-label">⬡ SCAMMER HASH</div>
      <div class="hc-val" style="color:var(--pink);">${data.full_scammer_hash || '—'}</div>
    </div>
    <div class="hc-box">
      <div class="hc-label">⬡ SUSPECT HASH</div>
      <div class="hc-val" style="color:var(--yellow);">${data.full_suspect_hash || '—'}</div>
    </div>
  `;

  // Update full hashes on cards
  document.getElementById('scamHash').textContent    = data.full_scammer_hash || '';
  document.getElementById('suspectHash').textContent = data.full_suspect_hash || '';

  if (data.scammer_tx) {
    const el = document.getElementById('scamTx');
    el.textContent = 'TX: ' + data.scammer_tx;
    el.style.display = 'block';
  }
  if (data.suspect_tx) {
    const el = document.getElementById('suspectTx');
    el.textContent = 'TX: ' + data.suspect_tx;
    el.style.display = 'block';
  }

  document.getElementById('globalStatus').textContent = 'ANALYSIS COMPLETE';

  const dot = isMatch ? 'pink' : 'green';
  addTimeline(dot, isMatch
    ? '⚠ IDENTITY MATCH CONFIRMED — Same device used for both numbers. Scammer identified.'
    : '✓ No match — Different devices detected.');
}

function addTimeline(dotClass, text) {
  const tl = document.getElementById('timeline');
  const item = document.createElement('div');
  item.className = 'tl-item';
  item.innerHTML = `
    <div class="tl-dot ${dotClass}"></div>
    <div class="tl-time">${new Date().toTimeString().substr(0,8)}</div>
    <div class="tl-text">${text}</div>
  `;
  tl.appendChild(item);
}

// Start polling
pollStatus();
</script>
</body>
</html>
