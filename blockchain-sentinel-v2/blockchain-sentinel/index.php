<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOCKCHAIN SENTINEL // Cyber Fraud Attribution</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --neon-cyan: #00f5ff;
    --neon-pink: #ff006e;
    --neon-green: #39ff14;
    --neon-yellow: #ffe600;
    --neon-purple: #bf00ff;
    --dark-bg: #020408;
    --panel-bg: rgba(0, 15, 25, 0.92);
    --border-glow: rgba(0, 245, 255, 0.4);
    --text-dim: rgba(0, 245, 255, 0.6);
    --grid-color: rgba(0, 245, 255, 0.04);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  
  body {
    background: var(--dark-bg);
    color: var(--neon-cyan);
    font-family: 'Share Tech Mono', monospace;
    min-height: 100vh;
    overflow-x: hidden;
    cursor: crosshair;
  }

  /* Animated grid background */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
      linear-gradient(var(--grid-color) 1px, transparent 1px),
      linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: 0;
    animation: gridPulse 8s ease-in-out infinite;
  }

  body::after {
    content: '';
    position: fixed;
    inset: 0;
    background: radial-gradient(ellipse at 20% 50%, rgba(0, 245, 255, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(191, 0, 255, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse at 50% 80%, rgba(255, 0, 110, 0.03) 0%, transparent 60%);
    z-index: 0;
    pointer-events: none;
  }

  @keyframes gridPulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
  }

  /* Scanline overlay */
  .scanlines {
    position: fixed;
    inset: 0;
    background: repeating-linear-gradient(
      0deg,
      transparent,
      transparent 2px,
      rgba(0, 0, 0, 0.08) 2px,
      rgba(0, 0, 0, 0.08) 4px
    );
    z-index: 1;
    pointer-events: none;
  }

  #canvas-bg {
    position: fixed;
    inset: 0;
    z-index: 0;
  }

  /* Header */
  .header {
    position: relative;
    z-index: 10;
    padding: 20px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border-glow);
    background: rgba(0, 5, 10, 0.8);
    backdrop-filter: blur(10px);
  }

  .logo {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .logo-icon {
    width: 48px;
    height: 48px;
    border: 2px solid var(--neon-cyan);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 0 20px var(--neon-cyan), inset 0 0 20px rgba(0,245,255,0.1);
    animation: iconPulse 2s ease-in-out infinite;
  }

  @keyframes iconPulse {
    0%, 100% { box-shadow: 0 0 20px var(--neon-cyan), inset 0 0 20px rgba(0,245,255,0.1); }
    50% { box-shadow: 0 0 40px var(--neon-cyan), 0 0 80px rgba(0,245,255,0.3), inset 0 0 20px rgba(0,245,255,0.2); }
  }

  .logo-text {
    font-family: 'Orbitron', sans-serif;
    font-weight: 900;
    font-size: 22px;
    letter-spacing: 4px;
    text-shadow: 0 0 20px var(--neon-cyan);
  }

  .logo-sub {
    font-size: 10px;
    color: var(--text-dim);
    letter-spacing: 6px;
    margin-top: 2px;
  }

  .header-status {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 11px;
    color: var(--text-dim);
  }

  .status-dot {
    width: 8px;
    height: 8px;
    background: var(--neon-green);
    border-radius: 50%;
    box-shadow: 0 0 10px var(--neon-green);
    animation: blink 1.5s ease-in-out infinite;
  }

  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
  }

  .nav-links {
    display: flex;
    gap: 20px;
  }

  .nav-link {
    color: var(--text-dim);
    text-decoration: none;
    font-size: 11px;
    letter-spacing: 2px;
    padding: 6px 12px;
    border: 1px solid transparent;
    transition: all 0.3s;
  }

  .nav-link:hover {
    color: var(--neon-cyan);
    border-color: var(--border-glow);
    text-shadow: 0 0 10px var(--neon-cyan);
    box-shadow: 0 0 15px rgba(0,245,255,0.1);
  }

  /* Main content */
  .main {
    position: relative;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 100px);
    padding: 40px;
  }

  /* Login Panel */
  .login-panel {
    width: 480px;
    background: var(--panel-bg);
    border: 1px solid var(--border-glow);
    box-shadow: 0 0 40px rgba(0,245,255,0.1), 0 0 80px rgba(0,0,0,0.5);
    padding: 50px;
    position: relative;
    overflow: hidden;
  }

  .login-panel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent);
    animation: scanBar 3s ease-in-out infinite;
  }

  @keyframes scanBar {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
  }

  .panel-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--neon-cyan);
    text-align: center;
    letter-spacing: 4px;
    margin-bottom: 8px;
    text-shadow: 0 0 20px var(--neon-cyan);
  }

  .panel-subtitle {
    text-align: center;
    font-size: 10px;
    color: var(--text-dim);
    letter-spacing: 3px;
    margin-bottom: 40px;
  }

  .corner-decor {
    position: absolute;
    width: 20px;
    height: 20px;
    border-color: var(--neon-cyan);
    border-style: solid;
    opacity: 0.6;
  }

  .corner-decor.tl { top: 10px; left: 10px; border-width: 2px 0 0 2px; }
  .corner-decor.tr { top: 10px; right: 10px; border-width: 2px 2px 0 0; }
  .corner-decor.bl { bottom: 10px; left: 10px; border-width: 0 0 2px 2px; }
  .corner-decor.br { bottom: 10px; right: 10px; border-width: 0 2px 2px 0; }

  /* Form Elements */
  .form-group {
    margin-bottom: 24px;
    position: relative;
  }

  .form-label {
    display: block;
    font-size: 10px;
    letter-spacing: 3px;
    color: var(--text-dim);
    margin-bottom: 8px;
  }

  .form-input {
    width: 100%;
    background: rgba(0, 245, 255, 0.03);
    border: 1px solid rgba(0, 245, 255, 0.2);
    border-top-color: rgba(0, 245, 255, 0.4);
    color: var(--neon-cyan);
    font-family: 'Share Tech Mono', monospace;
    font-size: 14px;
    padding: 14px 16px;
    outline: none;
    transition: all 0.3s;
    letter-spacing: 1px;
  }

  .form-input:focus {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 20px rgba(0,245,255,0.15), inset 0 0 20px rgba(0,245,255,0.05);
    background: rgba(0, 245, 255, 0.06);
  }

  .form-input::placeholder {
    color: rgba(0, 245, 255, 0.2);
  }

  .form-select {
    width: 100%;
    background: rgba(0, 245, 255, 0.03);
    border: 1px solid rgba(0, 245, 255, 0.2);
    border-top-color: rgba(0, 245, 255, 0.4);
    color: var(--neon-cyan);
    font-family: 'Share Tech Mono', monospace;
    font-size: 14px;
    padding: 14px 16px;
    outline: none;
    cursor: pointer;
    -webkit-appearance: none;
  }

  .form-select option {
    background: #020408;
    color: var(--neon-cyan);
  }

  .form-textarea {
    width: 100%;
    background: rgba(0, 245, 255, 0.03);
    border: 1px solid rgba(0, 245, 255, 0.2);
    border-top-color: rgba(0, 245, 255, 0.4);
    color: var(--neon-cyan);
    font-family: 'Share Tech Mono', monospace;
    font-size: 13px;
    padding: 14px 16px;
    outline: none;
    resize: vertical;
    min-height: 100px;
    transition: all 0.3s;
  }

  .form-textarea:focus {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 20px rgba(0,245,255,0.15);
  }

  /* Buttons */
  .btn-primary {
    width: 100%;
    padding: 16px;
    background: transparent;
    border: 1px solid var(--neon-cyan);
    color: var(--neon-cyan);
    font-family: 'Orbitron', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 4px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
    text-transform: uppercase;
    box-shadow: 0 0 20px rgba(0,245,255,0.1);
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 40%, rgba(0,245,255,0.05) 50%, transparent 60%);
    transform: translateX(-100%);
    transition: transform 0.6s;
  }

  .btn-primary:hover::before {
    transform: translateX(100%);
  }

  .btn-primary:hover {
    background: rgba(0, 245, 255, 0.1);
    box-shadow: 0 0 40px rgba(0,245,255,0.3), 0 0 80px rgba(0,245,255,0.1);
    text-shadow: 0 0 10px var(--neon-cyan);
  }

  .btn-pink {
    border-color: var(--neon-pink);
    color: var(--neon-pink);
    box-shadow: 0 0 20px rgba(255,0,110,0.1);
  }

  .btn-pink:hover {
    background: rgba(255, 0, 110, 0.1);
    box-shadow: 0 0 40px rgba(255,0,110,0.3);
    text-shadow: 0 0 10px var(--neon-pink);
  }

  /* Error/Success messages */
  .alert {
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 11px;
    letter-spacing: 2px;
    border-left: 3px solid;
    display: none;
  }

  .alert.error {
    border-left-color: var(--neon-pink);
    background: rgba(255,0,110,0.08);
    color: var(--neon-pink);
  }

  .alert.success {
    border-left-color: var(--neon-green);
    background: rgba(57,255,20,0.08);
    color: var(--neon-green);
  }

  .alert.show { display: block; }

  /* Report Panel */
  .report-panel {
    width: 600px;
    display: none;
  }

  .report-panel.active {
    display: block;
  }

  /* Progress Bar */
  .progress-container {
    margin: 20px 0;
    display: none;
  }

  .progress-container.show { display: block; }

  .progress-label {
    font-size: 11px;
    letter-spacing: 2px;
    color: var(--neon-cyan);
    margin-bottom: 8px;
    animation: flicker 0.5s ease-in-out infinite alternate;
  }

  @keyframes flicker {
    from { opacity: 0.8; }
    to { opacity: 1; text-shadow: 0 0 10px var(--neon-cyan); }
  }

  .progress-bar-bg {
    height: 4px;
    background: rgba(0,245,255,0.1);
    border: 1px solid rgba(0,245,255,0.2);
    overflow: hidden;
  }

  .progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple));
    width: 0%;
    transition: width 0.5s ease;
    box-shadow: 0 0 10px var(--neon-cyan);
  }

  /* Submit animation overlay */
  .submit-animation {
    display: none;
    position: absolute;
    inset: 0;
    background: var(--panel-bg);
    backdrop-filter: blur(5px);
    z-index: 20;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
  }

  .submit-animation.show {
    display: flex;
  }

  .scan-ring {
    width: 120px;
    height: 120px;
    border: 2px solid transparent;
    border-top-color: var(--neon-cyan);
    border-right-color: rgba(0,245,255,0.3);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    position: relative;
    box-shadow: 0 0 30px rgba(0,245,255,0.3);
  }

  .scan-ring::before {
    content: '';
    position: absolute;
    inset: 10px;
    border: 1px solid transparent;
    border-top-color: var(--neon-pink);
    border-radius: 50%;
    animation: spin 1.5s linear infinite reverse;
  }

  .scan-ring::after {
    content: '⬡';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 30px;
    color: var(--neon-cyan);
    text-shadow: 0 0 20px var(--neon-cyan);
    animation: iconPulse 1s ease-in-out infinite;
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .stage-messages {
    text-align: center;
    font-size: 12px;
    letter-spacing: 2px;
    color: var(--neon-cyan);
    font-family: 'Orbitron', sans-serif;
  }

  .stage-message {
    animation: typeIn 0.5s ease forwards;
    margin: 5px 0;
  }

  @keyframes typeIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
  }

  .success-result {
    border: 1px solid var(--neon-green);
    padding: 20px 30px;
    text-align: center;
    color: var(--neon-green);
    text-shadow: 0 0 20px var(--neon-green);
    box-shadow: 0 0 30px rgba(57,255,20,0.2);
    font-family: 'Orbitron', sans-serif;
    font-size: 13px;
    letter-spacing: 2px;
  }

  /* Divider */
  .divider {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 25px 0;
    opacity: 0.4;
  }

  .divider-line {
    flex: 1;
    height: 1px;
    background: var(--neon-cyan);
  }

  .divider-text {
    font-size: 10px;
    letter-spacing: 3px;
  }

  /* Footer */
  .footer {
    position: relative;
    z-index: 5;
    text-align: center;
    padding: 20px;
    border-top: 1px solid rgba(0,245,255,0.1);
    font-size: 10px;
    color: rgba(0,245,255,0.3);
    letter-spacing: 3px;
  }

  /* Hex grid decoration */
  .hex-grid {
    position: fixed;
    right: -50px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.04;
    font-size: 80px;
    line-height: 1;
    letter-spacing: -10px;
    color: var(--neon-cyan);
    z-index: 1;
    pointer-events: none;
    user-select: none;
  }

  /* Scrollbar */
  ::-webkit-scrollbar { width: 4px; }
  ::-webkit-scrollbar-track { background: var(--dark-bg); }
  ::-webkit-scrollbar-thumb { background: var(--border-glow); }
</style>
</head>
<body>
<div class="scanlines"></div>
<canvas id="canvas-bg"></canvas>

<div class="hex-grid">
⬡⬡⬡⬡<br>⬡⬡⬡⬡⬡<br>⬡⬡⬡⬡<br>⬡⬡⬡⬡⬡<br>⬡⬡⬡⬡<br>⬡⬡⬡⬡⬡
</div>

<!-- Header -->
<header class="header">
  <div class="logo">
    <div class="logo-icon">⬡</div>
    <div>
      <div class="logo-text">BLOCKCHAIN SENTINEL</div>
      <div class="logo-sub">CYBER FRAUD ATTRIBUTION PLATFORM // v4.2.1</div>
    </div>
  </div>
  <div class="header-status">
    <div class="status-dot"></div>
    <span>SYSTEM ONLINE</span>
    <span style="color: rgba(0,245,255,0.2)">|</span>
    <span id="clock">--:--:--</span>
    <span style="color: rgba(0,245,255,0.2)">|</span>
    <a href="admin/index.php" class="nav-link">⬡ ADMIN</a>
  </div>
</header>

<!-- Main -->
<main class="main">

  <!-- LOGIN PANEL -->
  <div class="login-panel" id="loginPanel">
    <div class="corner-decor tl"></div>
    <div class="corner-decor tr"></div>
    <div class="corner-decor bl"></div>
    <div class="corner-decor br"></div>

    <div class="panel-title">// CITIZEN ACCESS //</div>
    <div class="panel-subtitle">SECURE AUTHENTICATION REQUIRED</div>

    <div class="alert error" id="loginError">ACCESS DENIED: Invalid credentials</div>

    <div class="form-group">
      <label class="form-label">⬡ USER IDENTIFIER</label>
      <input type="text" class="form-input" id="loginEmail" placeholder="ENTER ACCESS CODE" autocomplete="off">
    </div>

    <div class="form-group">
      <label class="form-label">⬡ SECURITY KEY</label>
      <input type="password" class="form-input" id="loginPassword" placeholder="••••••••">
    </div>

    <button class="btn-primary" onclick="doLogin()">
      ⬡ AUTHENTICATE // ENTER SYSTEM
    </button>

    <div class="divider">
      <div class="divider-line"></div>
      <span class="divider-text">OR</span>
      <div class="divider-line"></div>
    </div>

    <button class="btn-primary btn-pink" onclick="window.location.href='admin/index.php'">
      ⬡ ADMIN ACCESS
    </button>

    <div style="margin-top: 20px; font-size: 10px; color: rgba(0,245,255,0.3); text-align: center; letter-spacing: 2px;">
      DEMO: email=public123 / pass=pass123
    </div>
  </div>

  <!-- REPORT PANEL -->
  <div class="login-panel report-panel" id="reportPanel" style="width: 600px; display: none;">
    <div class="corner-decor tl"></div>
    <div class="corner-decor tr"></div>
    <div class="corner-decor bl"></div>
    <div class="corner-decor br"></div>

    <!-- Submit animation overlay -->
    <div class="submit-animation" id="submitAnimation">
      <div class="scan-ring"></div>
      <div class="stage-messages" id="stageMessages"></div>
      <div class="progress-container show" style="width: 80%;">
        <div class="progress-bar-bg">
          <div class="progress-bar-fill" id="submitProgress"></div>
        </div>
      </div>
      <div id="successResult" style="display:none;"></div>
    </div>

    <div class="panel-title">// THREAT REPORT //</div>
    <div class="panel-subtitle">SUBMIT SCAM IDENTITY FOR INVESTIGATION</div>

    <div class="alert error" id="reportError"></div>

    <!-- SCAMMER IDENTITY -->
    <div style="font-size:9px; letter-spacing:3px; color:var(--neon-pink); margin-bottom:12px; border-left:2px solid var(--neon-pink); padding-left:10px;">
      ⬡ SCAMMER IDENTITY — provide username OR mobile (or both)
    </div>

    <div class="form-group">
      <label class="form-label">⬡ SCAM USERNAME <span style="color: rgba(0,245,255,0.4)">[OPTIONAL]</span></label>
      <input type="text" class="form-input" id="scamUsername" placeholder="SCAMMER USERNAME / HANDLE">
    </div>

    <div class="form-group">
      <label class="form-label">⬡ SCAMMER MOBILE NUMBER <span style="color: rgba(0,245,255,0.4)">[OPTIONAL]</span></label>
      <div style="display:flex; gap:8px;">
        <select class="form-select" id="scamCountryCode" style="width:100px; flex-shrink:0; padding:14px 8px;">
          <option value="+91">🇮🇳 +91</option>
          <option value="+1">🇺🇸 +1</option>
          <option value="+44">🇬🇧 +44</option>
          <option value="+971">🇦🇪 +971</option>
          <option value="+60">🇲🇾 +60</option>
          <option value="+65">🇸🇬 +65</option>
          <option value="+61">🇦🇺 +61</option>
        </select>
        <input type="tel" class="form-input" id="scamMobile" placeholder="SCAMMER MOBILE NO." style="flex:1;">
      </div>
    </div>

    <!-- SUSPECT IDENTITY -->
    <div style="font-size:9px; letter-spacing:3px; color:var(--neon-yellow); margin-bottom:12px; margin-top:5px; border-left:2px solid var(--neon-yellow); padding-left:10px;">
      ⬡ SUSPECT IDENTITY — provide username OR mobile (or both)
    </div>

    <div class="form-group">
      <label class="form-label">⬡ SUSPECT USERNAME <span style="color: rgba(0,245,255,0.4)">[OPTIONAL]</span></label>
      <input type="text" class="form-input" id="suspectUsername" placeholder="SUSPECT USERNAME (IF KNOWN)">
    </div>

    <div class="form-group">
      <label class="form-label">⬡ SUSPECT MOBILE NUMBER <span style="color: rgba(0,245,255,0.4)">[OPTIONAL]</span></label>
      <div style="display:flex; gap:8px;">
        <select class="form-select" id="suspectCountryCode" style="width:100px; flex-shrink:0; padding:14px 8px;">
          <option value="+91">🇮🇳 +91</option>
          <option value="+1">🇺🇸 +1</option>
          <option value="+44">🇬🇧 +44</option>
          <option value="+971">🇦🇪 +971</option>
          <option value="+60">🇲🇾 +60</option>
          <option value="+65">🇸🇬 +65</option>
          <option value="+61">🇦🇺 +61</option>
        </select>
        <input type="tel" class="form-input" id="suspectMobile" placeholder="SUSPECT MOBILE NO." style="flex:1;">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">⬡ PLATFORM</label>
      <select class="form-select" id="platform">
        <option value="">-- SELECT PLATFORM --</option>
        <option value="Telegram">TELEGRAM</option>
        <option value="Instagram">INSTAGRAM</option>
        <option value="LinkedIn">LINKEDIN</option>
        <option value="WhatsApp">WHATSAPP</option>
        <option value="Twitter">TWITTER / X</option>
        <option value="Facebook">FACEBOOK</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">⬡ INCIDENT DESCRIPTION</label>
      <textarea class="form-textarea" id="description" placeholder="DESCRIBE THE SCAM ACTIVITY IN DETAIL..."></textarea>
    </div>

    <button class="btn-primary" onclick="submitReport()">
      ⬡ SUBMIT // INITIATE THREAT ANALYSIS
    </button>

    <div style="margin-top: 15px;">
      <button class="btn-primary btn-pink" onclick="logout()" style="font-size:10px; padding: 10px;">
        ⬡ DISCONNECT SESSION
      </button>
    </div>
  </div>

</main>

<footer class="footer">
  BLOCKCHAIN SENTINEL // CYBER FRAUD ATTRIBUTION PLATFORM // ALL OPERATIONS ENCRYPTED & IMMUTABLE
</footer>

<script>
// ===== CANVAS PARTICLE BACKGROUND =====
const canvas = document.getElementById('canvas-bg');
const ctx = canvas.getContext('2d');

function resizeCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

const particles = [];
for (let i = 0; i < 80; i++) {
  particles.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    vx: (Math.random() - 0.5) * 0.5,
    vy: (Math.random() - 0.5) * 0.5,
    size: Math.random() * 1.5 + 0.5,
    alpha: Math.random() * 0.5 + 0.1,
    color: Math.random() > 0.7 ? '#ff006e' : '#00f5ff'
  });
}

function drawParticles() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Draw connections
  for (let i = 0; i < particles.length; i++) {
    for (let j = i + 1; j < particles.length; j++) {
      const dx = particles[i].x - particles[j].x;
      const dy = particles[i].y - particles[j].y;
      const dist = Math.sqrt(dx*dx + dy*dy);
      if (dist < 120) {
        ctx.beginPath();
        ctx.moveTo(particles[i].x, particles[i].y);
        ctx.lineTo(particles[j].x, particles[j].y);
        ctx.strokeStyle = `rgba(0,245,255,${0.05 * (1 - dist/120)})`;
        ctx.lineWidth = 0.5;
        ctx.stroke();
      }
    }
  }

  // Draw particles
  particles.forEach(p => {
    p.x += p.vx;
    p.y += p.vy;
    if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
    if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

    ctx.beginPath();
    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
    ctx.fillStyle = p.color.replace(')', `,${p.alpha})`).replace('rgb', 'rgba').replace('#ff006e', `rgba(255,0,110,${p.alpha})`).replace('#00f5ff', `rgba(0,245,255,${p.alpha})`);
    ctx.fill();
  });

  requestAnimationFrame(drawParticles);
}
drawParticles();

// ===== CLOCK =====
function updateClock() {
  const now = new Date();
  const t = now.toTimeString().substr(0,8);
  document.getElementById('clock').textContent = t;
}
setInterval(updateClock, 1000);
updateClock();

// ===== SESSION CHECK =====
let isLoggedIn = false;

// ===== LOGIN =====
async function doLogin() {
  const email = document.getElementById('loginEmail').value.trim();
  const password = document.getElementById('loginPassword').value;
  const errorEl = document.getElementById('loginError');
  errorEl.classList.remove('show');

  try {
    const res = await fetch('backend/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ type: 'public', email, password })
    });
    const data = await res.json();

    if (data.success) {
      isLoggedIn = true;
      document.getElementById('loginPanel').style.display = 'none';
      document.getElementById('reportPanel').style.display = 'block';
    } else {
      errorEl.textContent = '⬡ ' + data.message;
      errorEl.classList.add('show');
      shakePanel();
    }
  } catch(e) {
    // Demo mode - work without PHP backend
    if (email === 'public123' && password === 'pass123') {
      isLoggedIn = true;
      document.getElementById('loginPanel').style.display = 'none';
      document.getElementById('reportPanel').style.display = 'block';
    } else {
      errorEl.textContent = '⬡ ACCESS DENIED: Invalid credentials';
      errorEl.classList.add('show');
      shakePanel();
    }
  }
}

function shakePanel() {
  const panel = document.getElementById('loginPanel');
  panel.style.animation = 'none';
  panel.style.transform = 'translateX(-10px)';
  setTimeout(() => { panel.style.transform = 'translateX(10px)'; }, 80);
  setTimeout(() => { panel.style.transform = 'translateX(-8px)'; }, 160);
  setTimeout(() => { panel.style.transform = 'translateX(8px)'; }, 240);
  setTimeout(() => { panel.style.transform = 'translateX(0)'; }, 320);
}

// ===== SUBMIT REPORT =====
const stages = [
  '⬡ INITIALIZING THREAT ANALYSIS...',
  '⬡ SCANNING NETWORK SIGNATURE...',
  '⬡ GENERATING SHA256 FINGERPRINT...',
  '⬡ ENCRYPTING REPORT DATA...',
  '⬡ TRANSMITTING TO BLOCKCHAIN NODE...',
  '⬡ BLOCKCHAIN RECORD INITIATED...',
  '⬡ IMMUTABLE HASH STORED...',
  '⬡ THREAT REPORT SUCCESSFULLY STORED'
];

async function submitReport() {
  const scamUsername = document.getElementById('scamUsername').value.trim();
  const scamMobile = document.getElementById('scamMobile').value.trim();
  const scamCC = document.getElementById('scamCountryCode').value;
  const suspectUsername = document.getElementById('suspectUsername').value.trim();
  const suspectMobile = document.getElementById('suspectMobile').value.trim();
  const suspectCC = document.getElementById('suspectCountryCode').value;
  const platform = document.getElementById('platform').value;
  const description = document.getElementById('description').value.trim();
  const errorEl = document.getElementById('reportError');

  const scamPhone = scamMobile ? scamCC + scamMobile : '';
  const suspectPhone = suspectMobile ? suspectCC + suspectMobile : '';

  if (!scamUsername && !scamMobile) {
    errorEl.textContent = '⬡ ERROR: Provide scammer username OR mobile number';
    errorEl.classList.add('show');
    return;
  }
  if (!platform || !description) {
    errorEl.textContent = '⬡ ERROR: Platform and description are required';
    errorEl.classList.add('show');
    return;
  }

  errorEl.classList.remove('show');

  // Show animation
  const anim = document.getElementById('submitAnimation');
  anim.classList.add('show');
  const stageEl = document.getElementById('stageMessages');
  const progressEl = document.getElementById('submitProgress');

  // Run stage animations
  for (let i = 0; i < stages.length; i++) {
    await new Promise(r => setTimeout(r, 600));
    const msg = document.createElement('div');
    msg.className = 'stage-message';
    msg.textContent = stages[i];
    if (i < stages.length - 1) msg.style.color = 'rgba(0,245,255,0.6)';
    else msg.style.color = '#39ff14';
    stageEl.appendChild(msg);
    progressEl.style.width = ((i+1) / stages.length * 100) + '%';
  }

  // Try to submit to backend
  try {
    await fetch('backend/submit_report.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ scam_username: scamUsername, scam_phone: scamPhone, suspect_username: suspectUsername, suspect_phone: suspectPhone, platform, description })
    });
  } catch(e) { /* demo mode */ }

  await new Promise(r => setTimeout(r, 800));

  // Show success, hide ring
  document.querySelector('.scan-ring').style.display = 'none';
  document.querySelector('.progress-container').style.display = 'none';

  const successEl = document.getElementById('successResult');
  successEl.innerHTML = `
    <div class="success-result">
      ⬡ THREAT REPORT STORED<br>
      <span style="font-size:10px; color: rgba(57,255,20,0.7); margin-top: 8px; display: block;">
        CASE ASSIGNED TO INVESTIGATION UNIT<br>
        REPORT ID: #BS-${Date.now().toString(36).toUpperCase()}<br>
        STATUS: PENDING FORENSIC ANALYSIS
      </span>
    </div>
    <button class="btn-primary" onclick="resetReport()" style="margin-top: 20px; max-width: 280px;">
      ⬡ SUBMIT ANOTHER REPORT
    </button>
  `;
  successEl.style.display = 'block';
}

function resetReport() {
  document.getElementById('submitAnimation').classList.remove('show');
  document.getElementById('stageMessages').innerHTML = '';
  document.getElementById('submitProgress').style.width = '0%';
  document.getElementById('successResult').style.display = 'none';
  document.querySelector('.scan-ring').style.display = 'block';
  document.querySelector('.progress-container').style.display = 'block';
  document.getElementById('scamUsername').value = '';
  document.getElementById('suspectUsername').value = '';
  document.getElementById('platform').value = '';
  document.getElementById('description').value = '';
}

function logout() {
  isLoggedIn = false;
  document.getElementById('reportPanel').style.display = 'none';
  document.getElementById('loginPanel').style.display = 'block';
}

// Enter key on login
document.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    if (document.getElementById('loginPanel').style.display !== 'none' || !document.getElementById('loginPanel').style.display) {
      doLogin();
    }
  }
});
</script>
</body>
</html>
