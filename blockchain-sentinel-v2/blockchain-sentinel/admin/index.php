<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOCKCHAIN SENTINEL // ADMIN COMMAND</title>
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --neon-cyan: #00f5ff;
    --neon-pink: #ff006e;
    --neon-green: #39ff14;
    --neon-yellow: #ffe600;
    --neon-purple: #bf00ff;
    --neon-orange: #ff6b00;
    --dark-bg: #020408;
    --panel-bg: rgba(0, 10, 18, 0.95);
    --border-glow: rgba(0, 245, 255, 0.3);
    --text-dim: rgba(0, 245, 255, 0.5);
    --sidebar-w: 280px;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    background: var(--dark-bg);
    color: var(--neon-cyan);
    font-family: 'Share Tech Mono', monospace;
    min-height: 100vh;
    overflow-x: hidden;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
      linear-gradient(rgba(0,245,255,0.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,245,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: 0;
  }

  .scanlines {
    position: fixed;
    inset: 0;
    background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.06) 2px, rgba(0,0,0,0.06) 4px);
    z-index: 1;
    pointer-events: none;
  }

  /* === LOGIN SCREEN === */
  #loginScreen {
    position: fixed;
    inset: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--dark-bg);
  }

  .login-box {
    width: 440px;
    background: rgba(0, 10, 18, 0.98);
    border: 1px solid var(--border-glow);
    padding: 50px;
    position: relative;
    box-shadow: 0 0 60px rgba(0,245,255,0.08), 0 0 120px rgba(191,0,255,0.05);
  }

  .login-box::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--neon-cyan), var(--neon-purple), transparent);
  }

  .corner { position: absolute; width: 18px; height: 18px; border-color: var(--neon-cyan); border-style: solid; opacity: 0.7; }
  .corner.tl { top: 8px; left: 8px; border-width: 2px 0 0 2px; }
  .corner.tr { top: 8px; right: 8px; border-width: 2px 2px 0 0; }
  .corner.bl { bottom: 8px; left: 8px; border-width: 0 0 2px 2px; }
  .corner.br { bottom: 8px; right: 8px; border-width: 0 2px 2px 0; }

  .login-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 5px;
    text-align: center;
    text-shadow: 0 0 20px var(--neon-cyan);
    margin-bottom: 4px;
  }

  .login-sub {
    text-align: center;
    font-size: 9px;
    letter-spacing: 4px;
    color: var(--text-dim);
    margin-bottom: 35px;
  }

  .login-warn {
    text-align: center;
    font-size: 10px;
    color: var(--neon-pink);
    letter-spacing: 2px;
    margin-bottom: 30px;
    padding: 8px;
    border: 1px solid rgba(255,0,110,0.3);
    background: rgba(255,0,110,0.05);
  }

  .field { margin-bottom: 20px; }
  .field label {
    display: block;
    font-size: 9px;
    letter-spacing: 3px;
    color: var(--text-dim);
    margin-bottom: 6px;
  }

  .field input {
    width: 100%;
    background: rgba(0,245,255,0.03);
    border: 1px solid rgba(0,245,255,0.2);
    color: var(--neon-cyan);
    font-family: 'Share Tech Mono', monospace;
    font-size: 14px;
    padding: 12px 14px;
    outline: none;
    transition: all 0.3s;
  }

  .field input:focus {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 20px rgba(0,245,255,0.1);
    background: rgba(0,245,255,0.05);
  }

  .btn {
    width: 100%;
    padding: 14px;
    background: transparent;
    border: 1px solid var(--neon-cyan);
    color: var(--neon-cyan);
    font-family: 'Orbitron', sans-serif;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 4px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 0 20px rgba(0,245,255,0.1);
    position: relative;
    overflow: hidden;
  }

  .btn::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,245,255,0);
    transition: background 0.3s;
  }

  .btn:hover {
    background: rgba(0,245,255,0.1);
    box-shadow: 0 0 40px rgba(0,245,255,0.3);
    text-shadow: 0 0 10px var(--neon-cyan);
  }

  .error-msg {
    color: var(--neon-pink);
    font-size: 10px;
    letter-spacing: 2px;
    margin-top: 12px;
    display: none;
    text-align: center;
  }

  /* === DASHBOARD === */
  #dashboard {
    display: none;
    min-height: 100vh;
    position: relative;
    z-index: 5;
  }

  /* Top Bar */
  .topbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: rgba(0, 5, 10, 0.95);
    border-bottom: 1px solid var(--border-glow);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 50;
    backdrop-filter: blur(10px);
  }

  .topbar-left {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .logo-mark {
    font-family: 'Orbitron', sans-serif;
    font-size: 18px;
    font-weight: 900;
    letter-spacing: 3px;
    text-shadow: 0 0 15px var(--neon-cyan);
  }

  .badge {
    padding: 4px 10px;
    font-size: 9px;
    letter-spacing: 3px;
    border: 1px solid;
    border-radius: 2px;
  }

  .badge-admin {
    border-color: var(--neon-pink);
    color: var(--neon-pink);
    background: rgba(255,0,110,0.1);
    text-shadow: 0 0 8px var(--neon-pink);
  }

  .topbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 10px;
    color: var(--text-dim);
  }

  .status-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    animation: blink 1.5s ease-in-out infinite;
  }

  .dot.green { background: var(--neon-green); box-shadow: 0 0 8px var(--neon-green); }
  .dot.pink { background: var(--neon-pink); box-shadow: 0 0 8px var(--neon-pink); }

  @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0.3; } }

  .logout-btn {
    padding: 6px 16px;
    border: 1px solid rgba(255,0,110,0.4);
    color: var(--neon-pink);
    font-size: 10px;
    letter-spacing: 2px;
    cursor: pointer;
    background: transparent;
    font-family: 'Share Tech Mono', monospace;
    transition: all 0.3s;
  }

  .logout-btn:hover {
    background: rgba(255,0,110,0.1);
    border-color: var(--neon-pink);
  }

  /* Sidebar */
  .sidebar {
    position: fixed;
    left: 0;
    top: 60px;
    bottom: 0;
    width: var(--sidebar-w);
    background: rgba(0, 5, 12, 0.95);
    border-right: 1px solid var(--border-glow);
    z-index: 40;
    overflow-y: auto;
    padding: 20px 0;
  }

  .sidebar-section {
    padding: 0 20px;
    margin-bottom: 10px;
  }

  .sidebar-label {
    font-size: 9px;
    letter-spacing: 4px;
    color: rgba(0,245,255,0.3);
    margin-bottom: 10px;
    padding: 0 0 6px;
    border-bottom: 1px solid rgba(0,245,255,0.1);
  }

  .nav-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 14px;
    margin-bottom: 3px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.25s;
    font-size: 12px;
    letter-spacing: 1px;
    color: var(--text-dim);
    border-radius: 2px;
  }

  .nav-item:hover, .nav-item.active {
    border-color: var(--border-glow);
    background: rgba(0,245,255,0.05);
    color: var(--neon-cyan);
    text-shadow: 0 0 8px var(--neon-cyan);
  }

  .nav-item .count {
    padding: 2px 8px;
    font-size: 10px;
    border-radius: 2px;
    background: rgba(0,245,255,0.1);
    border: 1px solid rgba(0,245,255,0.2);
    min-width: 30px;
    text-align: center;
  }

  /* Main Content */
  .content {
    margin-left: var(--sidebar-w);
    padding-top: 60px;
    min-height: 100vh;
  }

  .content-inner {
    padding: 30px;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
  }

  .stat-card {
    background: rgba(0,10,18,0.9);
    border: 1px solid var(--border-glow);
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: var(--accent-color, var(--neon-cyan));
    opacity: 0.8;
  }

  .stat-card:hover {
    border-color: var(--accent-color, var(--neon-cyan));
    box-shadow: 0 0 30px rgba(0,245,255,0.1);
  }

  .stat-label {
    font-size: 9px;
    letter-spacing: 3px;
    color: var(--text-dim);
    margin-bottom: 10px;
  }

  .stat-value {
    font-family: 'Orbitron', sans-serif;
    font-size: 36px;
    font-weight: 700;
    line-height: 1;
    text-shadow: 0 0 20px currentColor;
  }

  .stat-sub {
    font-size: 9px;
    color: var(--text-dim);
    margin-top: 8px;
  }

  /* Panel */
  .panel {
    background: rgba(0,10,18,0.9);
    border: 1px solid var(--border-glow);
    margin-bottom: 20px;
    position: relative;
  }

  .panel-header {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(0,245,255,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(0,245,255,0.03);
  }

  .panel-title-sm {
    font-family: 'Orbitron', sans-serif;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 3px;
    color: var(--neon-cyan);
  }

  /* Reports Table */
  .reports-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }

  .reports-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 9px;
    letter-spacing: 3px;
    color: var(--text-dim);
    border-bottom: 1px solid rgba(0,245,255,0.1);
    font-weight: normal;
  }

  .reports-table td {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(0,245,255,0.05);
    color: rgba(0,245,255,0.8);
    vertical-align: top;
  }

  .reports-table tr:hover td {
    background: rgba(0,245,255,0.04);
  }

  .reports-table tr { cursor: pointer; transition: background 0.2s; }

  .platform-badge {
    padding: 3px 8px;
    font-size: 9px;
    letter-spacing: 1px;
    border-radius: 2px;
    display: inline-block;
  }

  .division-badge {
    padding: 3px 10px;
    font-size: 9px;
    border-radius: 2px;
    display: inline-block;
    letter-spacing: 1px;
  }

  /* Colors by type */
  .c-crypto { color: #ffe600; border: 1px solid rgba(255,230,0,0.3); background: rgba(255,230,0,0.08); }
  .c-invest { color: #39ff14; border: 1px solid rgba(57,255,20,0.3); background: rgba(57,255,20,0.08); }
  .c-money  { color: #ff006e; border: 1px solid rgba(255,0,110,0.3); background: rgba(255,0,110,0.08); }
  .c-digital{ color: #bf00ff; border: 1px solid rgba(191,0,255,0.3); background: rgba(191,0,255,0.08); }
  .c-id     { color: #ff6b00; border: 1px solid rgba(255,107,0,0.3); background: rgba(255,107,0,0.08); }

  .p-telegram  { color: #00b2ff; border: 1px solid rgba(0,178,255,0.3); background: rgba(0,178,255,0.08); }
  .p-instagram { color: #ff006e; border: 1px solid rgba(255,0,110,0.3); background: rgba(255,0,110,0.08); }
  .p-linkedin  { color: #00f5ff; border: 1px solid rgba(0,245,255,0.3); background: rgba(0,245,255,0.08); }
  .p-whatsapp  { color: #39ff14; border: 1px solid rgba(57,255,20,0.3); background: rgba(57,255,20,0.08); }
  .p-twitter   { color: #bf00ff; border: 1px solid rgba(191,0,255,0.3); background: rgba(191,0,255,0.08); }
  .p-facebook  { color: #ffe600; border: 1px solid rgba(255,230,0,0.3); background: rgba(255,230,0,0.08); }

  /* Report Detail Modal */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(8px);
    z-index: 200;
    display: none;
    align-items: center;
    justify-content: center;
  }

  .modal-overlay.show { display: flex; }

  .modal {
    width: 700px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    background: rgba(0, 8, 16, 0.99);
    border: 1px solid var(--border-glow);
    box-shadow: 0 0 60px rgba(0,245,255,0.15), 0 0 120px rgba(0,0,0,0.8);
    position: relative;
  }

  .modal::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--neon-cyan), var(--neon-purple), var(--neon-pink), transparent);
  }

  .modal-header {
    padding: 25px 30px;
    border-bottom: 1px solid rgba(0,245,255,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(0,245,255,0.03);
  }

  .modal-close {
    cursor: pointer;
    color: var(--neon-pink);
    font-size: 20px;
    transition: all 0.3s;
    padding: 4px 10px;
    border: 1px solid rgba(255,0,110,0.3);
  }

  .modal-close:hover {
    background: rgba(255,0,110,0.1);
    box-shadow: 0 0 20px rgba(255,0,110,0.2);
  }

  .modal-body { padding: 30px; }

  .detail-row {
    display: flex;
    gap: 20px;
    margin-bottom: 18px;
    align-items: flex-start;
  }

  .detail-key {
    font-size: 9px;
    letter-spacing: 3px;
    color: var(--text-dim);
    min-width: 140px;
    padding-top: 2px;
  }

  .detail-val {
    color: var(--neon-cyan);
    font-size: 13px;
    flex: 1;
  }

  .scan-btn {
    width: 100%;
    padding: 18px;
    background: transparent;
    border: 1px solid var(--neon-cyan);
    color: var(--neon-cyan);
    font-family: 'Orbitron', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 5px;
    cursor: pointer;
    margin-top: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 0 30px rgba(0,245,255,0.1);
  }

  .scan-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 30%, rgba(0,245,255,0.08) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.8s;
  }

  .scan-btn:hover::before { transform: translateX(100%); }

  .scan-btn:hover {
    background: rgba(0,245,255,0.1);
    box-shadow: 0 0 60px rgba(0,245,255,0.3);
    text-shadow: 0 0 15px var(--neon-cyan);
  }

  /* Scan Animation */
  .scan-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.92);
    backdrop-filter: blur(12px);
    z-index: 300;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .scan-modal.show { display: flex; }

  .forensic-engine {
    width: 500px;
    text-align: center;
  }

  .radar-container {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 40px;
  }

  .radar-ring {
    position: absolute;
    inset: 0;
    border: 1px solid rgba(0,245,255,0.2);
    border-radius: 50%;
  }

  .radar-ring:nth-child(2) { inset: 20px; border-color: rgba(0,245,255,0.15); }
  .radar-ring:nth-child(3) { inset: 40px; border-color: rgba(0,245,255,0.12); }
  .radar-ring:nth-child(4) { inset: 60px; border-color: rgba(0,245,255,0.1); }

  .radar-sweep {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: conic-gradient(from 0deg, rgba(0,245,255,0.4), transparent 90deg);
    animation: radarSpin 2s linear infinite;
  }

  @keyframes radarSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

  .radar-cross-h, .radar-cross-v {
    position: absolute;
    background: rgba(0,245,255,0.15);
  }

  .radar-cross-h { height: 1px; top: 50%; left: 0; right: 0; }
  .radar-cross-v { width: 1px; left: 50%; top: 0; bottom: 0; }

  .scan-stages {
    font-family: 'Orbitron', sans-serif;
    font-size: 12px;
    letter-spacing: 3px;
    margin-bottom: 30px;
  }

  .scan-stage-item {
    padding: 8px 0;
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.5s;
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: center;
  }

  .scan-stage-item.active {
    opacity: 1;
    transform: translateX(0);
    color: var(--neon-cyan);
  }

  .scan-stage-item.done {
    opacity: 0.5;
    color: rgba(0,245,255,0.4);
  }

  .stage-icon { font-size: 10px; }

  .scan-progress-bar {
    height: 3px;
    background: rgba(0,245,255,0.1);
    margin: 20px 0;
    position: relative;
    overflow: hidden;
  }

  .scan-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple), var(--neon-pink));
    width: 0%;
    transition: width 0.5s ease;
    box-shadow: 0 0 10px var(--neon-cyan);
  }

  /* Result Display */
  .result-box {
    padding: 30px;
    text-align: center;
    margin-top: 20px;
    position: relative;
    overflow: hidden;
  }

  .result-match {
    border: 1px solid var(--neon-pink);
    background: rgba(255,0,110,0.05);
    color: var(--neon-pink);
    box-shadow: 0 0 40px rgba(255,0,110,0.15);
  }

  .result-nomatch {
    border: 1px solid var(--neon-green);
    background: rgba(57,255,20,0.05);
    color: var(--neon-green);
    box-shadow: 0 0 40px rgba(57,255,20,0.15);
  }

  .result-unknown {
    border: 1px solid var(--neon-yellow);
    background: rgba(255,230,0,0.05);
    color: var(--neon-yellow);
    box-shadow: 0 0 40px rgba(255,230,0,0.15);
  }

  .result-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 4px;
    margin-bottom: 15px;
    text-shadow: 0 0 20px currentColor;
  }

  .result-hash {
    font-size: 10px;
    opacity: 0.7;
    word-break: break-all;
    margin-top: 10px;
    padding: 10px;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
    letter-spacing: 1px;
  }

  .result-close-btn {
    margin-top: 20px;
    padding: 12px 30px;
    border: 1px solid currentColor;
    background: transparent;
    color: inherit;
    font-family: 'Orbitron', sans-serif;
    font-size: 11px;
    letter-spacing: 3px;
    cursor: pointer;
    transition: all 0.3s;
  }

  .result-close-btn:hover { background: rgba(255,255,255,0.05); }

  /* Division grid */
  .division-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    padding: 20px;
  }

  .division-card {
    border: 1px solid var(--border-glow);
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
  }

  .division-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent, rgba(0,245,255,0.03));
    opacity: 0;
    transition: opacity 0.3s;
  }

  .division-card:hover::before { opacity: 1; }

  .division-card:hover {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 30px rgba(0,245,255,0.1);
  }

  .div-icon { font-size: 30px; margin-bottom: 10px; }
  .div-name { font-family: 'Orbitron', sans-serif; font-size: 11px; letter-spacing: 2px; margin-bottom: 8px; }
  .div-count { font-size: 32px; font-weight: 700; font-family: 'Orbitron', sans-serif; }

  ::-webkit-scrollbar { width: 4px; }
  ::-webkit-scrollbar-track { background: var(--dark-bg); }
  ::-webkit-scrollbar-thumb { background: var(--border-glow); }

  .hash-display {
    font-size: 9px;
    word-break: break-all;
    color: rgba(0,245,255,0.4);
    padding: 8px;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(0,245,255,0.1);
    letter-spacing: 0;
    margin-top: 5px;
  }

  .empty-state {
    text-align: center;
    padding: 50px;
    color: var(--text-dim);
    font-size: 12px;
    letter-spacing: 2px;
  }
</style>
</head>
<body>
<div class="scanlines"></div>

<!-- ===== LOGIN SCREEN ===== -->
<div id="loginScreen">
  <div class="login-box">
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>
    <div class="login-title">ADMIN COMMAND</div>
    <div class="login-sub">RESTRICTED SYSTEM ACCESS // LEVEL 5 CLEARANCE</div>
    <div class="login-warn">⬡ UNAUTHORIZED ACCESS IS MONITORED & PROSECUTED</div>

    <div class="field">
      <label>⬡ ADMIN IDENTIFIER</label>
      <input type="text" id="adminUser" placeholder="USERNAME" autocomplete="off">
    </div>
    <div class="field">
      <label>⬡ AUTHENTICATION KEY</label>
      <input type="password" id="adminPass" placeholder="PASSWORD">
    </div>
    <button class="btn" onclick="adminLogin()">⬡ AUTHENTICATE // GAIN ACCESS</button>
    <div class="error-msg" id="loginErr">⬡ ACCESS DENIED: Invalid credentials</div>
    <div style="margin-top: 15px; font-size: 9px; text-align: center; color: rgba(0,245,255,0.3); letter-spacing: 2px;">
      DEMO: admin / admin123
    </div>
  </div>
</div>

<!-- ===== DASHBOARD ===== -->
<div id="dashboard">

  <!-- Topbar -->
  <div class="topbar">
    <div class="topbar-left">
      <div class="logo-mark">⬡ BLOCKCHAIN SENTINEL</div>
      <div class="badge badge-admin">ADMIN</div>
    </div>
    <div class="topbar-right">
      <div class="status-indicator"><div class="dot green"></div><span>BLOCKCHAIN ONLINE</span></div>
      <div class="status-indicator"><div class="dot pink"></div><span>FORENSIC ENGINE ACTIVE</span></div>
      <span id="adminClock" style="letter-spacing: 2px;"></span>
      <button class="logout-btn" onclick="adminLogout()">⬡ DISCONNECT</button>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-label">⬡ NAVIGATION</div>
      <div class="nav-item active" onclick="showView('overview')" id="nav-overview">
        <span>OVERVIEW</span>
      </div>
      <div class="nav-item" onclick="showView('reports')" id="nav-reports">
        <span>ALL REPORTS</span>
        <span class="count" id="totalCount">--</span>
      </div>
      <div class="nav-item" onclick="showView('blockchain')" id="nav-blockchain">
        <span>BLOCKCHAIN HASHES</span>
      </div>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-label">⬡ THREAT DIVISIONS</div>
      <div class="nav-item" onclick="filterDivision('Crypto Scam')" id="nav-crypto">
        <span>CRYPTO SCAM</span>
        <span class="count c-crypto" id="cnt-crypto">--</span>
      </div>
      <div class="nav-item" onclick="filterDivision('Investment Scam')" id="nav-invest">
        <span>INVESTMENT SCAM</span>
        <span class="count c-invest" id="cnt-invest">--</span>
      </div>
      <div class="nav-item" onclick="filterDivision('Money Scam')" id="nav-money">
        <span>MONEY SCAM</span>
        <span class="count c-money" id="cnt-money">--</span>
      </div>
      <div class="nav-item" onclick="filterDivision('Digital Scam')" id="nav-digital">
        <span>DIGITAL SCAM</span>
        <span class="count c-digital" id="cnt-digital">--</span>
      </div>
      <div class="nav-item" onclick="filterDivision('Identity Fraud')" id="nav-id">
        <span>IDENTITY FRAUD</span>
        <span class="count c-id" id="cnt-id">--</span>
      </div>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-label">⬡ SYSTEM INFO</div>
      <div style="font-size: 10px; color: var(--text-dim); padding: 5px 5px; line-height: 2;">
        <div>NODE: HARDHAT-LOCAL</div>
        <div>BLOCK: <span id="blockNum" style="color: var(--neon-cyan);">#--</span></div>
        <div>HASHES: <span style="color: var(--neon-green);" id="hashCount">--</span></div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="content-inner">

      <!-- Overview -->
      <div id="view-overview">
        <div class="stats-grid" id="statsGrid">
          <div class="stat-card" style="--accent-color: var(--neon-cyan);">
            <div class="stat-label">⬡ TOTAL REPORTS</div>
            <div class="stat-value" style="color: var(--neon-cyan);" id="stat-total">--</div>
            <div class="stat-sub">CASES FILED</div>
          </div>
          <div class="stat-card" style="--accent-color: var(--neon-pink);">
            <div class="stat-label">⬡ IDENTITY MATCHES</div>
            <div class="stat-value" style="color: var(--neon-pink);" id="stat-matches">5</div>
            <div class="stat-sub">CONFIRMED SCAMMERS</div>
          </div>
          <div class="stat-card" style="--accent-color: var(--neon-green);">
            <div class="stat-label">⬡ BLOCKCHAIN HASHES</div>
            <div class="stat-value" style="color: var(--neon-green);" id="stat-hashes">10</div>
            <div class="stat-sub">IMMUTABLE RECORDS</div>
          </div>
          <div class="stat-card" style="--accent-color: var(--neon-yellow);">
            <div class="stat-label">⬡ PLATFORMS TRACKED</div>
            <div class="stat-value" style="color: var(--neon-yellow);">5</div>
            <div class="stat-sub">NETWORKS MONITORED</div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">
            <div class="panel-title-sm">⬡ THREAT DIVISION BREAKDOWN</div>
          </div>
          <div class="division-grid" id="divisionGrid"></div>
        </div>

        <div class="panel">
          <div class="panel-header">
            <div class="panel-title-sm">⬡ RECENT REPORTS</div>
          </div>
          <table class="reports-table">
            <thead>
              <tr>
                <th>#ID</th>
                <th>SCAM USERNAME</th>
                <th>PLATFORM</th>
                <th>DIVISION</th>
                <th>DATE</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tbody id="recentReportsBody"></tbody>
          </table>
        </div>
      </div>

      <!-- All Reports -->
      <div id="view-reports" style="display:none;">
        <div class="panel">
          <div class="panel-header">
            <div class="panel-title-sm">⬡ ALL THREAT REPORTS</div>
            <span style="font-size:10px; color: var(--text-dim);" id="viewTitle"></span>
          </div>
          <table class="reports-table">
            <thead>
              <tr>
                <th>#ID</th>
                <th>SCAM USERNAME</th>
                <th>SUSPECT</th>
                <th>PLATFORM</th>
                <th>DIVISION</th>
                <th>DESCRIPTION</th>
                <th>DATE</th>
              </tr>
            </thead>
            <tbody id="allReportsBody"></tbody>
          </table>
        </div>
      </div>

      <!-- Blockchain -->
      <div id="view-blockchain" style="display:none;">
        <div class="panel">
          <div class="panel-header">
            <div class="panel-title-sm">⬡ BLOCKCHAIN HASH LEDGER</div>
          </div>
          <div style="padding: 20px;">
            <div id="blockchainData"></div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Report Detail Modal -->
<div class="modal-overlay" id="reportModal">
  <div class="modal">
    <div class="modal-header">
      <div class="panel-title-sm" id="modalTitle">⬡ CASE FILE // REPORT DETAILS</div>
      <div class="modal-close" onclick="closeModal()">✕</div>
    </div>
    <div class="modal-body" id="modalBody"></div>
  </div>
</div>

<!-- Scan Animation -->
<div class="scan-modal" id="scanModal">
  <div class="forensic-engine">
    <div style="font-family: 'Orbitron'; font-size: 14px; letter-spacing: 5px; margin-bottom: 30px; color: var(--neon-cyan); text-shadow: 0 0 20px var(--neon-cyan);">
      ⬡ FORENSIC IDENTITY ENGINE
    </div>

    <div class="radar-container">
      <div class="radar-ring"></div>
      <div class="radar-ring"></div>
      <div class="radar-ring"></div>
      <div class="radar-ring"></div>
      <div class="radar-sweep"></div>
      <div class="radar-cross-h"></div>
      <div class="radar-cross-v"></div>
    </div>

    <div class="scan-stages" id="scanStages"></div>

    <div class="scan-progress-bar">
      <div class="scan-progress-fill" id="scanProgress"></div>
    </div>

    <div id="scanResult"></div>
  </div>
</div>

<script>
let adminLoggedIn = false;
let currentReports = [];
let allReports = [];

// ===== CLOCK =====
function updateClock() {
  const t = new Date().toTimeString().substr(0,8);
  const el = document.getElementById('adminClock');
  if (el) el.textContent = t;
}
setInterval(updateClock, 1000);
updateClock();

// ===== FAKE BLOCK NUMBER =====
let blockNum = 18423891 + Math.floor(Math.random() * 100);
setInterval(() => {
  blockNum += Math.floor(Math.random() * 3);
  const el = document.getElementById('blockNum');
  if (el) el.textContent = '#' + blockNum.toLocaleString();
}, 5000);
document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('blockNum');
  if (el) el.textContent = '#' + blockNum.toLocaleString();
});

// ===== LOGIN =====
async function adminLogin() {
  const username = document.getElementById('adminUser').value.trim();
  const password = document.getElementById('adminPass').value;
  const errEl = document.getElementById('loginErr');
  errEl.style.display = 'none';

  let success = false;

  try {
    const res = await fetch('../backend/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ type: 'admin', username, password })
    });
    const data = await res.json();
    success = data.success;
  } catch(e) {
    // Demo fallback
    success = (username === 'admin' && password === 'admin123');
  }

  if (success) {
    adminLoggedIn = true;
    document.getElementById('loginScreen').style.display = 'none';
    document.getElementById('dashboard').style.display = 'block';
    loadDashboard();
  } else {
    errEl.style.display = 'block';
  }
}

function adminLogout() {
  adminLoggedIn = false;
  document.getElementById('loginScreen').style.display = 'flex';
  document.getElementById('dashboard').style.display = 'none';
}

// ===== LOAD DASHBOARD =====
async function loadDashboard() {
  await loadAllReports();
  buildDivisionCounts();
  buildOverview();
}

// Demo reports data (fallback if no PHP backend)
const demoReports = [
  { id: 1, scam_username: 'crypto_king_99', suspect_username: 'investpro_mike', platform: 'Telegram', division: 'Crypto Scam', description: 'Promised 500% returns on crypto investment. Disappeared after receiving 2 BTC.', fingerprint_hash: 'a3f8e2d1c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1f0', blockchain_tx: '0xabc123def456789', created_at: '2025-01-15 09:23:11' },
  { id: 2, scam_username: 'forex_master_777', suspect_username: 'richtrader_alex', platform: 'Instagram', division: 'Investment Scam', description: 'Fake forex trading signals. Collected $5000 from 12 victims before disappearing.', fingerprint_hash: 'b4e9f3a2d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1', blockchain_tx: '0xdef789abc123456', created_at: '2025-01-14 14:05:30' },
  { id: 3, scam_username: 'nft_drop_official', suspect_username: 'digital_art_pro', platform: 'Twitter', division: 'Digital Scam', description: 'Fake NFT drops. Victims paid ETH for non-existent digital art collections.', fingerprint_hash: 'c5f0a4b3e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2', blockchain_tx: '0x789456def123abc', created_at: '2025-01-13 18:45:00' },
  { id: 4, scam_username: 'loan_helper_fast', suspect_username: 'quickcash_lender', platform: 'WhatsApp', division: 'Money Scam', description: 'Advance fee fraud. Promised instant loans, charged upfront fees then vanished.', fingerprint_hash: 'd6a1b5c4f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3', blockchain_tx: '0x321def789abc456', created_at: '2025-01-13 10:22:05' },
  { id: 5, scam_username: 'job_recruiter_intl', suspect_username: 'hr_manager_sarah', platform: 'LinkedIn', division: 'Identity Fraud', description: 'Fake job offers to steal personal information including passport and bank details.', fingerprint_hash: 'e7b2c6d5a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4', blockchain_tx: '0x654abc321def789', created_at: '2025-01-12 09:00:00' },
  { id: 6, scam_username: 'bitcoin_doubler_x', suspect_username: null, platform: 'Telegram', division: 'Crypto Scam', description: 'Bitcoin doubling scheme. Collected over 5 BTC from multiple victims across 3 countries.', fingerprint_hash: 'f8c3d7e6b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5', blockchain_tx: '0x987xyz654uvw321', created_at: '2025-01-12 07:30:45' },
  { id: 7, scam_username: 'romance_scammer_90', suspect_username: 'sweetheart_anna', platform: 'Instagram', division: 'Money Scam', description: 'Romance scam. Built relationship over 3 months then requested $10,000 for emergency.', fingerprint_hash: 'a9d4e8f7c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6', blockchain_tx: '0xabc987xyz654uvw', created_at: '2025-01-11 16:10:22' },
  { id: 8, scam_username: 'tech_support_ms', suspect_username: 'microsoft_helper', platform: 'Facebook', division: 'Digital Scam', description: 'Fake Microsoft tech support. Gained remote access to steal banking credentials.', fingerprint_hash: 'b0e5f9a8d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7', blockchain_tx: '0xdef456abc789xyz', created_at: '2025-01-11 12:00:00' },
  { id: 9, scam_username: 'pump_dump_token', suspect_username: 'defi_whale_pro', platform: 'Telegram', division: 'Crypto Scam', description: 'Pump and dump scheme on new DeFi token. Victims lost combined $50,000.', fingerprint_hash: 'c1f6a0b9e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8', blockchain_tx: '0x123uvw456xyz789', created_at: '2025-01-10 08:45:00' },
  { id: 10, scam_username: 'fake_charity_help', suspect_username: 'donation_collector', platform: 'WhatsApp', division: 'Money Scam', description: 'Impersonated legitimate charity collecting COVID relief funds. Stole $25,000.', fingerprint_hash: 'd2a7b1c0f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9', blockchain_tx: '0x456rst789uvw012', created_at: '2025-01-09 19:00:00' },
  { id: 11, scam_username: 'verified_trader_eu', suspect_username: 'eu_markets_pro', platform: 'LinkedIn', division: 'Investment Scam', description: 'Posed as licensed EU financial advisor. Stole investment portfolios worth €80,000.', fingerprint_hash: 'e3b8c2d1a7f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0', blockchain_tx: '0x789lmn012opq345', created_at: '2025-01-09 14:20:00' },
  { id: 12, scam_username: 'wallet_recovery_x', suspect_username: null, platform: 'Telegram', division: 'Crypto Scam', description: 'Fake crypto wallet recovery service. Stole seed phrases from 8 victims.', fingerprint_hash: 'f4c9d3e2b8a7f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1', blockchain_tx: '0x012ghi345jkl678', created_at: '2025-01-08 11:30:00' },
  { id: 13, scam_username: 'influencer_drop99', suspect_username: 'brand_promo_vip', platform: 'Instagram', division: 'Digital Scam', description: 'Fake influencer merchandise drops. Collected payment for products never delivered.', fingerprint_hash: 'a5e0f4b3c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2', blockchain_tx: '0x345mno678pqr901', created_at: '2025-01-08 09:00:00' },
  { id: 14, scam_username: 'gold_investment_vp', suspect_username: 'precious_metals_x', platform: 'Facebook', division: 'Investment Scam', description: 'Fake gold investment company. Used forged certificates to steal $35,000.', fingerprint_hash: 'b6f1a5c4d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3', blockchain_tx: '0x678stu901vwx234', created_at: '2025-01-07 16:00:00' },
  { id: 15, scam_username: 'id_verify_service', suspect_username: 'secure_verify_co', platform: 'WhatsApp', division: 'Identity Fraud', description: 'Impersonated bank verification service. Collected KYC documents from 20 victims.', fingerprint_hash: 'c7a2b6d5e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4', blockchain_tx: '0x901xyz234abc567', created_at: '2025-01-07 11:15:00' },
  { id: 16, scam_username: 'airdrop_legit_pro', suspect_username: null, platform: 'Telegram', division: 'Crypto Scam', description: 'Fake crypto airdrop campaign requiring wallet connection. Drained victim wallets.', fingerprint_hash: 'd8b3c7e6f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5', blockchain_tx: '0x234bcd567efg890', created_at: '2025-01-06 20:00:00' },
  { id: 17, scam_username: 'work_from_home_ez', suspect_username: 'passive_income_x', platform: 'Instagram', division: 'Money Scam', description: 'Work from home scam. Victims paid training fees but received no work or refunds.', fingerprint_hash: 'e9c4d8f7a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6', blockchain_tx: '0x567hij890klm123', created_at: '2025-01-06 09:30:00' },
  { id: 18, scam_username: 'fake_attorney_law', suspect_username: 'legalaid_counsel', platform: 'LinkedIn', division: 'Identity Fraud', description: 'Impersonated attorney, charged fees for non-existent legal services and stole PII.', fingerprint_hash: 'f0d5e9a8b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7', blockchain_tx: '0x890nop123qrs456', created_at: '2025-01-05 14:00:00' },
  { id: 19, scam_username: 'binary_options_vip', suspect_username: null, platform: 'WhatsApp', division: 'Investment Scam', description: 'Binary options fraud. Manipulated trading platform to ensure victims always lost.', fingerprint_hash: 'a1e6f0b9c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8', blockchain_tx: '0x123tuv456wxy789', created_at: '2025-01-04 10:00:00' },
  { id: 20, scam_username: 'metaverse_land_vr', suspect_username: 'virtual_realtor_x', platform: 'Twitter', division: 'Digital Scam', description: 'Fake metaverse land sales. Sold non-existent virtual properties for combined $15,000.', fingerprint_hash: 'b2f7a1c0d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9', blockchain_tx: '0x456yz0123abc789', created_at: '2025-01-04 08:00:00' },
];

async function loadAllReports() {
  try {
    const res = await fetch('../backend/admin_reports.php?action=all_reports');
    const data = await res.json();
    if (data.success) {
      allReports = data.data;
      return;
    }
  } catch(e) {}
  allReports = demoReports;
}

function buildDivisionCounts() {
  const counts = {};
  allReports.forEach(r => {
    counts[r.division] = (counts[r.division] || 0) + 1;
  });

  document.getElementById('totalCount').textContent = allReports.length;
  document.getElementById('stat-total').textContent = allReports.length;
  document.getElementById('hashCount').textContent = allReports.length + 10;

  const divMap = {
    'Crypto Scam': 'cnt-crypto',
    'Investment Scam': 'cnt-invest',
    'Money Scam': 'cnt-money',
    'Digital Scam': 'cnt-digital',
    'Identity Fraud': 'cnt-id'
  };

  Object.entries(divMap).forEach(([div, id]) => {
    const el = document.getElementById(id);
    if (el) el.textContent = counts[div] || 0;
  });
}

function buildOverview() {
  // Division grid
  const divGrid = document.getElementById('divisionGrid');
  const divisions = [
    { name: 'Crypto Scam', icon: '₿', cls: 'c-crypto' },
    { name: 'Investment Scam', icon: '📈', cls: 'c-invest' },
    { name: 'Money Scam', icon: '💰', cls: 'c-money' },
    { name: 'Digital Scam', icon: '💻', cls: 'c-digital' },
    { name: 'Identity Fraud', icon: '🪪', cls: 'c-id' },
  ];

  const counts = {};
  allReports.forEach(r => { counts[r.division] = (counts[r.division] || 0) + 1; });

  divGrid.innerHTML = divisions.map(d => `
    <div class="division-card" onclick="filterDivision('${d.name}')">
      <div class="div-icon">${d.icon}</div>
      <div class="div-name">${d.name.toUpperCase()}</div>
      <div class="div-count ${d.cls}">${counts[d.name] || 0}</div>
      <div style="font-size:9px; margin-top: 8px; color: var(--text-dim);">⬡ CLICK TO VIEW</div>
    </div>
  `).join('');

  // Recent reports
  const tbody = document.getElementById('recentReportsBody');
  const recent = allReports.slice(0, 8);
  tbody.innerHTML = recent.map(r => `
    <tr onclick="openReport(${r.id})">
      <td style="color: var(--text-dim); font-size: 10px;">#${r.id}</td>
      <td style="color: var(--neon-cyan);">${r.scam_username}</td>
      <td><span class="platform-badge p-${r.platform.toLowerCase()}">${r.platform.toUpperCase()}</span></td>
      <td><span class="division-badge ${getDivClass(r.division)}">${r.division.toUpperCase()}</span></td>
      <td style="color: var(--text-dim); font-size: 10px;">${formatDate(r.created_at)}</td>
      <td><button onclick="event.stopPropagation(); openReport(${r.id})" style="padding: 5px 12px; border: 1px solid var(--border-glow); background: transparent; color: var(--neon-cyan); font-size: 9px; letter-spacing: 2px; cursor: pointer; font-family: 'Share Tech Mono';">VIEW</button></td>
    </tr>
  `).join('');
}

function getDivClass(div) {
  const map = { 'Crypto Scam': 'c-crypto', 'Investment Scam': 'c-invest', 'Money Scam': 'c-money', 'Digital Scam': 'c-digital', 'Identity Fraud': 'c-id' };
  return map[div] || 'c-money';
}

function formatDate(d) {
  return d ? d.substr(0, 10) : '--';
}

function showView(name) {
  ['overview', 'reports', 'blockchain'].forEach(v => {
    document.getElementById('view-' + v).style.display = v === name ? 'block' : 'none';
    const nav = document.getElementById('nav-' + v);
    if (nav) nav.classList.toggle('active', v === name);
  });

  if (name === 'reports') buildAllReportsTable(allReports, 'ALL REPORTS');
  if (name === 'blockchain') buildBlockchainView();
}

function filterDivision(div) {
  const filtered = allReports.filter(r => r.division === div);
  showView('reports');
  buildAllReportsTable(filtered, div.toUpperCase());
  document.getElementById('viewTitle').textContent = `FILTERED: ${div} (${filtered.length})`;
}

function buildAllReportsTable(reports, title) {
  document.getElementById('viewTitle').textContent = title;
  const tbody = document.getElementById('allReportsBody');
  if (!reports.length) {
    tbody.innerHTML = '<tr><td colspan="7" class="empty-state">⬡ NO REPORTS FOUND</td></tr>';
    return;
  }
  tbody.innerHTML = reports.map(r => `
    <tr onclick="openReport(${r.id})">
      <td style="color: var(--text-dim); font-size: 10px;">#${r.id}</td>
      <td style="color: var(--neon-cyan);">${r.scam_username}</td>
      <td style="color: ${r.suspect_username ? 'var(--neon-yellow)' : 'rgba(0,245,255,0.2)'};">
        ${r.suspect_username || '—'}
      </td>
      <td><span class="platform-badge p-${r.platform.toLowerCase()}">${r.platform.toUpperCase()}</span></td>
      <td><span class="division-badge ${getDivClass(r.division)}">${r.division}</span></td>
      <td style="color: var(--text-dim); font-size: 11px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${r.description.substr(0,60)}...</td>
      <td style="color: var(--text-dim); font-size: 10px;">${formatDate(r.created_at)}</td>
    </tr>
  `).join('');
}

function buildBlockchainView() {
  const blockchainHashes = allReports.map((r, i) => ({
    block: 18423891 + i,
    hash: r.fingerprint_hash,
    tx: r.blockchain_tx,
    label: r.scam_username,
    time: r.created_at
  }));

  document.getElementById('blockchainData').innerHTML = blockchainHashes.map(b => `
    <div style="border: 1px solid rgba(0,245,255,0.1); padding: 15px; margin-bottom: 10px; transition: all 0.3s;" 
         onmouseover="this.style.borderColor='rgba(0,245,255,0.3)'" 
         onmouseout="this.style.borderColor='rgba(0,245,255,0.1)'">
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px; align-items: center;">
        <span style="font-family: 'Orbitron'; font-size: 11px; color: var(--neon-cyan);">BLOCK #${b.block.toLocaleString()}</span>
        <span style="font-size: 9px; letter-spacing: 2px; color: var(--text-dim);">${b.time}</span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
        <span style="font-size: 10px; color: var(--neon-yellow);">⬡ ${b.label.toUpperCase()}</span>
        <span style="font-size: 9px; color: var(--neon-green);">CONFIRMED</span>
      </div>
      <div style="font-size: 9px; color: rgba(0,245,255,0.4); word-break: break-all; padding: 8px; background: rgba(0,0,0,0.3); border-left: 2px solid var(--neon-cyan); letter-spacing: 0; margin: 5px 0;">
        TX: ${b.tx}
      </div>
      <div style="font-size: 9px; color: rgba(0,245,255,0.3); word-break: break-all; padding: 8px; background: rgba(0,0,0,0.3); border-left: 2px solid var(--neon-purple); letter-spacing: 0;">
        HASH: ${b.hash || 'a'.repeat(64)}
      </div>
    </div>
  `).join('');
}

// ===== REPORT MODAL =====
function openReport(id) {
  const r = allReports.find(x => x.id == id);
  if (!r) return;

  document.getElementById('modalTitle').textContent = `⬡ CASE FILE // REPORT #${r.id}`;

  document.getElementById('modalBody').innerHTML = `
    <div class="detail-row">
      <div class="detail-key">⬡ CASE ID</div>
      <div class="detail-val" style="font-family: 'Orbitron'; color: var(--neon-yellow);">#BS-${r.id.toString().padStart(6, '0')}</div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ SCAM USERNAME</div>
      <div class="detail-val" style="color: var(--neon-pink); text-shadow: 0 0 10px var(--neon-pink);">${r.scam_username}</div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ SUSPECT USERNAME</div>
      <div class="detail-val" style="color: ${r.suspect_username ? 'var(--neon-yellow)' : 'rgba(0,245,255,0.3)'};">
        ${r.suspect_username || 'NOT PROVIDED'}
      </div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ PLATFORM</div>
      <div class="detail-val">
        <span class="platform-badge p-${r.platform.toLowerCase()}">${r.platform.toUpperCase()}</span>
      </div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ DIVISION</div>
      <div class="detail-val">
        <span class="division-badge ${getDivClass(r.division)}">${r.division.toUpperCase()}</span>
      </div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ DESCRIPTION</div>
      <div class="detail-val" style="line-height: 1.6; font-size: 12px;">${r.description}</div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ SUBMITTED</div>
      <div class="detail-val" style="color: var(--text-dim);">${r.created_at}</div>
    </div>
    <div class="detail-row">
      <div class="detail-key">⬡ BLOCKCHAIN TX</div>
      <div class="detail-val">
        <div class="hash-display">${r.blockchain_tx || 'PENDING...'}</div>
      </div>
    </div>
    <div style="border-top: 1px solid rgba(0,245,255,0.1); padding-top: 20px; margin-top: 10px;">
      <div style="font-size: 9px; letter-spacing: 3px; color: var(--text-dim); margin-bottom: 10px;">
        ⬡ FORENSIC ACTIONS
      </div>
      <button class="scan-btn" onclick="startScan(${r.id})">
        ⬡ SCAN IDENTITY // INITIATE FORENSIC ANALYSIS
      </button>
      <button onclick="openLiveMonitor(${r.id})"
              style="width: 100%; padding: 16px; background: transparent; border: 1px solid var(--neon-pink); color: var(--neon-pink); font-family: 'Orbitron'; font-size: 12px; letter-spacing: 3px; cursor: pointer; margin-top: 10px; transition: all 0.3s; box-shadow: 0 0 20px rgba(255,0,110,0.1);"
              onmouseover="this.style.background='rgba(255,0,110,0.1)'; this.style.boxShadow='0 0 40px rgba(255,0,110,0.3)';"
              onmouseout="this.style.background='transparent'; this.style.boxShadow='0 0 20px rgba(255,0,110,0.1)';">
        ⬡ LIVE SCAN // SEND WHATSAPP LINKS & MONITOR
      </button>
      <button onclick="sendPhishLink(${r.id}, '${r.platform}', '${r.scam_username}')" 
              style="width: 100%; padding: 14px; background: transparent; border: 1px solid var(--neon-purple); color: var(--neon-purple); font-family: 'Orbitron'; font-size: 11px; letter-spacing: 3px; cursor: pointer; margin-top: 10px; transition: all 0.3s;"
              onmouseover="this.style.background='rgba(191,0,255,0.1)'"
              onmouseout="this.style.background='transparent'">
        ⬡ SEND AUTHENTICATION LINK (MANUAL)
      </button>
    </div>
  `;

  document.getElementById('reportModal').classList.add('show');
}

function closeModal() {
  document.getElementById('reportModal').classList.remove('show');
}

function sendPhishLink(id, platform, username) {
  const links = {
    'Telegram': `fake_login_pages/telegram/index.php?report=${id}&target=${username}`,
    'Instagram': `fake_login_pages/instagram/index.php?report=${id}&target=${username}`,
    'LinkedIn': `fake_login_pages/linkedin/index.php?report=${id}&target=${username}`,
    'WhatsApp': `fake_login_pages/telegram/index.php?report=${id}&target=${username}`,
    'Twitter': `fake_login_pages/instagram/index.php?report=${id}&target=${username}`,
    'Facebook': `fake_login_pages/instagram/index.php?report=${id}&target=${username}`,
  };
  const link = window.location.origin + '/' + (links[platform] || links['Telegram']);
  
  // Show link in alert
  const msg = `Authentication Link Generated:\n\n${link}\n\nSend this link to ${username} via ${platform}.\nThe link will collect behavioral fingerprint data silently.`;
  alert(msg);
}

// ===== SCAN IDENTITY =====
const scanStages = [
  { text: 'INITIALIZING FORENSIC ENGINE', delay: 0 },
  { text: 'COLLECTING BEHAVIORAL SIGNATURE', delay: 800 },
  { text: 'GENERATING IDENTITY HASH', delay: 1600 },
  { text: 'SCANNING BLOCKCHAIN DATABASE', delay: 2400 },
  { text: 'COMPARING GLOBAL THREAT RECORDS', delay: 3200 },
];

async function startScan(reportId) {
  closeModal();
  const scanModal = document.getElementById('scanModal');
  scanModal.classList.add('show');

  const stagesEl = document.getElementById('scanStages');
  const progressEl = document.getElementById('scanProgress');
  const resultEl = document.getElementById('scanResult');

  stagesEl.innerHTML = scanStages.map((s, i) => `
    <div class="scan-stage-item" id="stage-${i}">
      <span class="stage-icon">⬡</span>
      <span>${s.text}</span>
    </div>
  `).join('');
  progressEl.style.width = '0%';
  resultEl.innerHTML = '';

  // Animate stages
  for (let i = 0; i < scanStages.length; i++) {
    await new Promise(r => setTimeout(r, scanStages[i].delay || 800));
    document.getElementById('stage-' + i).classList.add('active');
    if (i > 0) document.getElementById('stage-' + (i-1)).classList.add('done');
    progressEl.style.width = ((i+1) / scanStages.length * 100) + '%';
  }

  await new Promise(r => setTimeout(r, 1000));

  // Get result
  let result = null;
  try {
    const res = await fetch(`../backend/admin_reports.php?action=scan_identity&report_id=${reportId}`);
    const data = await res.json();
    if (data.success) result = data.data;
  } catch(e) {}

  // Demo result
  if (!result) {
    const matchReports = [1, 2, 3, 4, 5];
    const report = allReports.find(r => r.id == reportId);
    result = {
      scam_hash: report?.fingerprint_hash || 'a'.repeat(64),
      identity_match: matchReports.includes(reportId),
      result_label: matchReports.includes(reportId) 
        ? 'CONFIRMED SCAMMER IDENTITY MATCH' 
        : (report?.suspect_username ? 'DIFFERENT IDENTITY - No Behavioral Match' : 'UNKNOWN IDENTITY - No Match in Global Database'),
      blockchain_tx: report?.blockchain_tx
    };
  }

  const isMatch = result.identity_match;
  const isUnknown = result.result_label?.includes('UNKNOWN');

  let boxClass = isMatch ? 'result-match' : (isUnknown ? 'result-unknown' : 'result-nomatch');
  let icon = isMatch ? '⚠ THREAT CONFIRMED' : (isUnknown ? '? IDENTITY UNKNOWN' : '✓ DIFFERENT IDENTITY');

  resultEl.innerHTML = `
    <div class="result-box ${boxClass}">
      <div style="font-size: 12px; letter-spacing: 3px; margin-bottom: 10px; opacity: 0.7;">${icon}</div>
      <div class="result-title">${result.result_label}</div>
      <div class="result-hash">SHA256: ${result.scam_hash || 'N/A'}</div>
      <div style="font-size: 9px; margin-top: 8px; opacity: 0.6;">TX: ${result.blockchain_tx || 'N/A'}</div>
      <button class="result-close-btn" onclick="closeScan()">⬡ CLOSE FORENSIC ENGINE</button>
    </div>
  `;
}

function closeScan() {
  document.getElementById('scanModal').classList.remove('show');
}

function openLiveMonitor(reportId) {
  closeModal();
  window.open('live_scan.php?report_id=' + reportId, '_blank', 'width=1100,height=800,scrollbars=yes');
}

// Close modal on overlay click
document.getElementById('reportModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// Enter on login
document.addEventListener('keydown', e => {
  if (e.key === 'Enter' && !adminLoggedIn) adminLogin();
});
</script>
</body>
</html>
