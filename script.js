// ═══════════════════════════════════════════════════════
// BACKGROUND ANIMATION — 3D orbit rings
// ═══════════════════════════════════════════════════════
(function(){
  const cv = document.getElementById('bgCanvas');
  const ctx = cv.getContext('2d');
  let W,H,CX,CY;
  function resize(){W=cv.width=window.innerWidth;H=cv.height=window.innerHeight;CX=W/2;CY=H/2;}
  resize(); window.addEventListener('resize',resize);

  const rings = [
    {a:0.4,b:0.12,tilt:0.3,speed:0.0004,color:'rgba(0,245,255,',phase:0,chars:'01'},
    {a:0.35,b:0.1,tilt:-0.4,speed:-0.0006,color:'rgba(191,0,255,',phase:Math.PI/3,chars:'01▣⬡'},
    {a:0.28,b:0.08,tilt:0.6,speed:0.0007,color:'rgba(57,255,20,',phase:Math.PI},
    {a:0.22,b:0.06,tilt:-0.2,speed:-0.0005,color:'rgba(255,230,0,',phase:Math.PI*1.5,chars:'⬡01'},
  ];
  const NPTS = 28;
  let t = 0;

  function draw(){
    ctx.clearRect(0,0,W,H);
    const sc = Math.min(W,H);
    t += 1;
    rings.forEach(ring=>{
      ring.phase += ring.speed;
      const pts = [];
      for(let i=0;i<NPTS;i++){
        const angle = (i/NPTS)*Math.PI*2 + ring.phase;
        const x0 = Math.cos(angle)*ring.a*sc;
        const y0 = Math.sin(angle)*ring.b*sc;
        const y1 = y0*Math.cos(ring.tilt) - 0;
        const z  = y0*Math.sin(ring.tilt);
        const depth = (z/(ring.b*sc)+1)/2;
        pts.push({x:CX+x0,y:CY+y1,depth,angle});
      }
      pts.sort((a,b)=>a.depth-b.depth);
      pts.forEach(p=>{
        const alpha = 0.08 + p.depth*0.35;
        const sz = 7 + p.depth*5;
        ctx.font = `${sz}px Share Tech Mono`;
        ctx.fillStyle = ring.color+alpha+')';
        ctx.fillText('0', p.x, p.y);
      });
    });
    requestAnimationFrame(draw);
  }
  draw();
})();

// ═══════════════════════════════════════════════════════
// DEMO DATA
// ═══════════════════════════════════════════════════════
const demoReports = [
  {id:1,scam_username:'crypto_king_99',suspect_username:'investpro_mike',platform:'Telegram',division:'Crypto Scam',description:'Promised 500% returns on crypto investment. Disappeared after receiving 2 BTC.',fingerprint_hash:'a3f8e2d1c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1f0',blockchain_tx:'0xabc123def456789',created_at:'2025-01-15 09:23:11'},
  {id:2,scam_username:'forex_master_777',suspect_username:'richtrader_alex',platform:'Instagram',division:'Investment Scam',description:'Fake forex trading signals. Collected $5000 from 12 victims before disappearing.',fingerprint_hash:'b4e9f3a2d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1',blockchain_tx:'0xdef789abc123456',created_at:'2025-01-14 14:05:30'},
  {id:3,scam_username:'nft_drop_official',suspect_username:'digital_art_pro',platform:'Twitter',division:'Digital Scam',description:'Fake NFT drops. Victims paid ETH for non-existent digital art collections.',fingerprint_hash:'c5f0a4b3e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2',blockchain_tx:'0x789456def123abc',created_at:'2025-01-13 18:45:00'},
  {id:4,scam_username:'loan_helper_fast',suspect_username:'quickcash_lender',platform:'WhatsApp',division:'Money Scam',description:'Advance fee fraud. Promised instant loans, charged upfront fees then vanished.',fingerprint_hash:'d6a1b5c4f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3',blockchain_tx:'0x321def789abc456',created_at:'2025-01-13 10:22:05'},
  {id:5,scam_username:'job_recruiter_intl',suspect_username:'hr_manager_sarah',platform:'LinkedIn',division:'Identity Fraud',description:'Fake job offers to steal personal information including passport and bank details.',fingerprint_hash:'e7b2c6d5a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4',blockchain_tx:'0x654abc321def789',created_at:'2025-01-12 09:00:00'},
  {id:6,scam_username:'bitcoin_doubler_x',suspect_username:null,platform:'Telegram',division:'Crypto Scam',description:'Bitcoin doubling scheme. Collected over 5 BTC from multiple victims.',fingerprint_hash:'f8c3d7e6b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5',blockchain_tx:'0x987xyz654uvw321',created_at:'2025-01-12 07:30:45'},
  {id:7,scam_username:'romance_scammer_90',suspect_username:'sweetheart_anna',platform:'Instagram',division:'Money Scam',description:'Romance scam. Built relationship over 3 months then requested $10,000 for emergency.',fingerprint_hash:'a9d4e8f7c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6',blockchain_tx:'0xabc987xyz654uvw',created_at:'2025-01-11 16:10:22'},
  {id:8,scam_username:'tech_support_ms',suspect_username:'microsoft_helper',platform:'Facebook',division:'Digital Scam',description:'Fake Microsoft tech support. Gained remote access to steal banking credentials.',fingerprint_hash:'b0e5f9a8d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7',blockchain_tx:'0xdef456abc789xyz',created_at:'2025-01-11 12:00:00'},
];

const platColors = {WhatsApp:'#25D366',Telegram:'#0088cc',Instagram:'#dc2743',LinkedIn:'#0a66c2',Twitter:'#1da1f2',Facebook:'#1877f2',TikTok:'#ff0050',YouTube:'#ff0000',Snapchat:'#f5c500'};
const divColors  = {'Crypto Scam':'#ff006e','Investment Scam':'#ff6b00','Money Scam':'#ffe600','Digital Scam':'#00f5ff','Identity Fraud':'#bf00ff'};

// ═══════════════════════════════════════════════════════
// CLOCK + BLOCK
// ═══════════════════════════════════════════════════════
function tick(){
  const n=new Date();
  document.getElementById('clock').textContent=n.toTimeString().substr(0,8);
}
setInterval(tick,1000); tick();

let block=18424012;
setInterval(()=>{block+=Math.floor(Math.random()*3);document.getElementById('blockNum').textContent='#'+block.toLocaleString();},4000);

// ═══════════════════════════════════════════════════════
// NAV TABS
// ═══════════════════════════════════════════════════════
const TABS = ['overview','submit','monitor','result'];
function goTab(name){
  TABS.forEach(t=>{
    const btn = document.getElementById('tab-'+t);
    const cnt = document.getElementById('tab-'+t+'-content');
    const isActive = t===name;
    if(btn){ btn.style.color=isActive?'var(--cyan)':'var(--dim)'; btn.style.borderBottom=isActive?'2px solid var(--cyan)':'2px solid transparent'; }
    if(cnt) cnt.style.display=isActive?'block':'none';
  });
  if(name==='overview') buildOverview();
}

// ═══════════════════════════════════════════════════════
// OVERVIEW
// ═══════════════════════════════════════════════════════
function buildOverview(){
  // division bars
  const divCounts={};
  demoReports.forEach(r=>{divCounts[r.division]=(divCounts[r.division]||0)+1;});
  const divMax = Math.max(...Object.values(divCounts));
  document.getElementById('divBars').innerHTML = Object.entries(divCounts).map(([d,c])=>`
    <div style="margin-bottom:12px;">
      <div style="display:flex;justify-content:space-between;font-size:10px;margin-bottom:5px;">
        <span style="color:var(--dim);">${d}</span>
        <span style="color:${divColors[d]||'var(--cyan)'};font-family:Orbitron;">${c}</span>
      </div>
      <div style="height:5px;background:rgba(255,255,255,.05);border-radius:2px;overflow:hidden;">
        <div style="height:100%;width:${(c/divMax*100)}%;background:${divColors[d]||'var(--cyan)'};border-radius:2px;transition:width 1s;"></div>
      </div>
    </div>`).join('');

  // platform bars
  const platCounts={};
  demoReports.forEach(r=>{platCounts[r.platform]=(platCounts[r.platform]||0)+1;});
  const platMax = Math.max(...Object.values(platCounts));
  document.getElementById('platBars').innerHTML = Object.entries(platCounts).map(([p,c])=>`
    <div style="margin-bottom:12px;">
      <div style="display:flex;justify-content:space-between;font-size:10px;margin-bottom:5px;">
        <span style="color:var(--dim);">${p}</span>
        <span style="color:${platColors[p]||'var(--cyan)'};font-family:Orbitron;">${c}</span>
      </div>
      <div style="height:5px;background:rgba(255,255,255,.05);border-radius:2px;overflow:hidden;">
        <div style="height:100%;width:${(c/platMax*100)}%;background:${platColors[p]||'var(--cyan)'};border-radius:2px;"></div>
      </div>
    </div>`).join('');

  // recent table
  document.getElementById('recentBody').innerHTML = demoReports.slice(0,8).map(r=>`
    <tr onclick="openModal(${r.id})">
      <td style="color:var(--dim);font-size:10px;">#${r.id}</td>
      <td style="color:var(--pink);">${r.scam_username}</td>
      <td style="color:${r.suspect_username?'var(--yellow)':'rgba(0,245,255,.2)'};">${r.suspect_username||'—'}</td>
      <td><span class="badge badge-${r.platform.toLowerCase()}">${r.platform}</span></td>
      <td style="font-size:10px;color:${divColors[r.division]||'var(--cyan)'};">${r.division}</td>
      <td style="color:var(--dim);font-size:10px;">${r.created_at.substr(0,10)}</td>
      <td><button onclick="event.stopPropagation();openModal(${r.id})" style="padding:5px 12px;border:1px solid var(--border);background:transparent;color:var(--cyan);font-size:9px;cursor:pointer;font-family:'Share Tech Mono';">VIEW</button></td>
    </tr>`).join('');
}

// ═══════════════════════════════════════════════════════
// REPORT MODAL
// ═══════════════════════════════════════════════════════
function openModal(id){
  const r = demoReports.find(x=>x.id==id);
  if(!r) return;
  document.getElementById('modalTitle').textContent = `⬡ CASE FILE // BS-${String(r.id).padStart(6,'0')}`;
  document.getElementById('modalBody').innerHTML = `
    <div class="detail-row"><div class="detail-key">CASE ID</div><div class="detail-val" style="font-family:'Orbitron';color:var(--yellow);">BS-${String(r.id).padStart(6,'0')}</div></div>
    <div class="detail-row"><div class="detail-key">SCAM USERNAME</div><div class="detail-val" style="color:var(--pink);">${r.scam_username}</div></div>
    <div class="detail-row"><div class="detail-key">SUSPECT</div><div class="detail-val" style="color:${r.suspect_username?'var(--yellow)':'rgba(0,245,255,.3)'};">${r.suspect_username||'NOT PROVIDED'}</div></div>
    <div class="detail-row"><div class="detail-key">PLATFORM</div><div class="detail-val"><span class="badge badge-${r.platform.toLowerCase()}">${r.platform}</span></div></div>
    <div class="detail-row"><div class="detail-key">DIVISION</div><div class="detail-val" style="color:${divColors[r.division]||'var(--cyan)'};">${r.division}</div></div>
    <div class="detail-row"><div class="detail-key">DESCRIPTION</div><div class="detail-val" style="font-size:11px;line-height:1.7;color:rgba(0,245,255,.7);">${r.description}</div></div>
    <div class="detail-row"><div class="detail-key">DATE</div><div class="detail-val" style="color:var(--dim);">${r.created_at}</div></div>
    <div class="detail-row" style="border:none;"><div class="detail-key">BLOCKCHAIN TX</div><div class="detail-val" style="font-size:9px;color:rgba(0,245,255,.35);word-break:break-all;">${r.blockchain_tx}</div></div>
    <div style="border-top:1px solid var(--border);margin-top:16px;padding-top:16px;display:flex;gap:8px;flex-wrap:wrap;">
      <button onclick="loadCaseIntoMonitor(${r.id})" class="btn btn-pink" style="flex:1;justify-content:center;padding:11px;">◎ OPEN IN LIVE SCAN</button>
      <button onclick="closeModal()" class="btn" style="padding:11px 18px;">CLOSE</button>
    </div>`;
  document.getElementById('reportModal').classList.add('open');
}
function closeModal(){ document.getElementById('reportModal').classList.remove('open'); }
document.getElementById('reportModal').addEventListener('click',e=>{ if(e.target===document.getElementById('reportModal')) closeModal(); });

// ═══════════════════════════════════════════════════════
// SUBMIT REPORT
// ═══════════════════════════════════════════════════════
let currentReport = null;
let scamHash = null;
let susHash  = null;
let scamScanned = false;
let susScanned  = false;

function sha256sim(str){
  // deterministic-looking fake hash from string
  let h = 0;
  for(let i=0;i<str.length;i++) h = (Math.imul(31,h)+str.charCodeAt(i))|0;
  const seed = Math.abs(h);
  let out = '';
  const chars='0123456789abcdef';
  let s = seed;
  for(let i=0;i<64;i++){s=(s*1664525+1013904223)>>>0; out+=chars[s%16];}
  return out;
}

function submitReport(){
  const scam   = document.getElementById('f_scam').value.trim();
  const plat   = document.getElementById('f_platform').value;
  const div    = document.getElementById('f_division').value;
  const desc   = document.getElementById('f_desc').value.trim();
  const err    = document.getElementById('formErr');
  if(!scam||!plat||!div||!desc){ err.style.display='block'; return; }
  err.style.display='none';

  const sus      = document.getElementById('f_suspect').value.trim();
  const scamPh   = document.getElementById('f_scam_phone').value.trim();
  const susPh    = document.getElementById('f_sus_phone').value.trim();
  const id       = demoReports.length+1;
  const hash     = sha256sim(scam+plat+div+Date.now());
  const tx       = '0x'+sha256sim(hash).substr(0,16);
  const caseId   = 'BS-'+String(id).padStart(6,'0');

  currentReport = {id,scam_username:scam,suspect_username:sus||null,platform:plat,division:div,description:desc,fingerprint_hash:hash,blockchain_tx:tx,scam_phone:scamPh,suspect_phone:susPh,created_at:new Date().toISOString().replace('T',' ').substr(0,19)};
  demoReports.unshift(currentReport);

  // show scan anim
  runScanAnim(()=>{
    document.getElementById('formStep').style.display='none';
    document.getElementById('linksStep').style.display='block';
    document.getElementById('newCaseId').textContent=caseId;
    document.getElementById('newHash').textContent=hash;
    updateLinks();
    setStep(2);
  });
}

function updateLinks(){
  if(!currentReport) return;
  const base = (document.getElementById('ngrokUrl').value||'http://localhost').replace(/\/+$/,'');
  const page = currentReport.platform.toLowerCase();
  const scamUrl = `${base}/blockchain-sentinel/fake_login_pages/${page}/index.php?report=${currentReport.id}&role=scammer`;
  const susUrl  = `${base}/blockchain-sentinel/fake_login_pages/${page}/index.php?report=${currentReport.id}&role=suspect`;

  document.getElementById('lk_scam_name').textContent = currentReport.scam_username;
  document.getElementById('lk_sus_name').textContent  = currentReport.suspect_username||'(not set)';
  document.getElementById('lk_scam_url').textContent  = scamUrl;
  document.getElementById('lk_sus_url').textContent   = susUrl;
  document.getElementById('lk_plat_badge').textContent= currentReport.platform.toUpperCase();
  document.getElementById('lk_plat_badge').className  = `badge badge-${currentReport.platform.toLowerCase()}`;

  const msg = (role)=>`⚠ *Security Alert — Verification Required*\n\nYour ${currentReport.platform} account has been flagged. Verify identity immediately:\n\n${role==='scammer'?scamUrl:susUrl}\n\nExpires in 24 hours.`;
  const scamPh = currentReport.scam_phone;
  const susPh  = currentReport.suspect_phone;
  document.getElementById('lk_scam_wa').href = scamPh ? `https://wa.me/${scamPh.replace(/\D/g,'')}?text=${encodeURIComponent(msg('scammer'))}` : '#';
  document.getElementById('lk_sus_wa').href  = susPh  ? `https://wa.me/${susPh.replace(/\D/g,'')}?text=${encodeURIComponent(msg('suspect'))}` : '#';
}

function openFakePage(role){
  if(!currentReport) return;
  const plat = currentReport.platform.toLowerCase();
  const platUI = {
    whatsapp:  {bg:'#f0f2f5',accent:'#25D366',logo:'💬',name:'WhatsApp',fields:['Your WhatsApp Number','WhatsApp PIN']},
    telegram:  {bg:'#f1f1f1',accent:'#0088cc',logo:'✈️',name:'Telegram',fields:['Phone Number or Username','Password']},
    instagram: {bg:'#fafafa',accent:'#dc2743',logo:'📷',name:'Instagram',fields:['Username, email, or phone','Password']},
    facebook:  {bg:'#f0f2f5',accent:'#1877f2',logo:'👤',name:'Facebook',fields:['Email or phone number','Password']},
    linkedin:  {bg:'#f3f2ef',accent:'#0a66c2',logo:'💼',name:'LinkedIn',fields:['Email or Phone','Password']},
    twitter:   {bg:'#000',accent:'#1da1f2',logo:'𝕏',name:'Twitter / X',fields:['Phone, email, or username','Password']},
    tiktok:    {bg:'#fff',accent:'#ff0050',logo:'🎵',name:'TikTok',fields:['Phone / email / username','Password']},
    youtube:   {bg:'#fff',accent:'#ff0000',logo:'▶️',name:'YouTube',fields:['Email or phone','Password']},
    snapchat:  {bg:'#fffc00',accent:'#000',logo:'👻',name:'Snapchat',fields:['Username','Password']},
  };
  const ui = platUI[plat]||platUI.whatsapp;
  const isDark = plat==='twitter';
  const tc = isDark?'#fff':'#222';

  document.getElementById('fpModalTitle').textContent = `⬡ FAKE ${ui.name.toUpperCase()} PAGE // ${role.toUpperCase()}`;
  document.getElementById('fpModalBody').innerHTML = `
    <div style="background:${ui.bg};padding:32px 28px;font-family:-apple-system,sans-serif;">
      <div style="text-align:center;margin-bottom:20px;">
        <div style="font-size:44px;margin-bottom:8px;">${ui.logo}</div>
        <div style="font-size:18px;font-weight:700;color:${tc};">${ui.name}</div>
      </div>
      <div style="background:${isDark?'#1a1a1a':'#fff3cd'};border:1px solid ${isDark?'#f4212e':'#ffc107'};border-radius:6px;padding:11px;margin-bottom:18px;font-size:12px;color:${isDark?'#f4212e':'#856404'};">
        ⚠ <strong>Security Alert:</strong> Unusual activity detected on your account. Please verify your identity.
      </div>
      ${ui.fields.map(f=>`
        <div style="margin-bottom:12px;">
          <input placeholder="${f}" style="width:100%;padding:12px 14px;border:1.5px solid ${isDark?'#333':'#ddd'};border-radius:6px;font-size:14px;outline:none;background:${isDark?'#000':ui.bg};color:${tc};">
        </div>`).join('')}
      <button style="width:100%;padding:13px;background:${ui.accent};color:${plat==='snapchat'?'#000':'white'};border:none;border-radius:6px;font-size:15px;font-weight:700;cursor:pointer;margin-top:4px;">
        Verify Account
      </button>
      <div style="text-align:center;margin-top:12px;font-size:11px;color:#888;">🔒 Secured by ${ui.name}</div>
      <div style="text-align:center;margin-top:8px;padding:8px;background:rgba(0,0,0,.06);border-radius:4px;font-size:10px;color:#aaa;">
        [ Fingerprint collection runs silently on submit ]
      </div>
    </div>`;
  document.getElementById('fakePageModal').classList.add('open');
}
document.getElementById('fakePageModal').addEventListener('click',e=>{ if(e.target===document.getElementById('fakePageModal')) document.getElementById('fakePageModal').classList.remove('open'); });

// ═══════════════════════════════════════════════════════
// LIVE SCAN MONITOR
// ═══════════════════════════════════════════════════════
function startMonitor(){
  scamScanned=false; susScanned=false; scamHash=null; susHash=null;
  resetMonitorUI();
  setStep(3);
}

function loadCaseIntoMonitor(id){
  closeModal();
  const r = demoReports.find(x=>x.id==id);
  if(r) currentReport=r;
  goTab('monitor');
  startMonitor();
}

function resetMonitorUI(){
  ['Scam','Sus'].forEach(k=>{
    document.getElementById('mon'+k+'Idle').style.display='block';
    document.getElementById('mon'+k+'Data').style.display='none';
    document.getElementById('mon'+k+'Status').className='status-badge waiting';
    document.getElementById('mon'+k+'Status').textContent='WAITING';
    const panel=document.getElementById('mon'+k+'Panel');
    panel.style.borderColor='var(--border)';
  });
  document.getElementById('compareIdle').style.display='block';
  document.getElementById('comparingAnim').style.display='none';
  document.getElementById('monProgressBar').style.width='0%';
  document.getElementById('monProgressPct').textContent='0 / 2 scanned';
}

function simulateScan(role){
  const isScam = role==='scammer';
  const key    = isScam?'Scam':'Sus';
  const hash   = sha256sim((currentReport?(isScam?currentReport.scam_username:currentReport.suspect_username):role)+Date.now()+Math.random());

  document.getElementById('mon'+key+'Status').className='status-badge scanning';
  document.getElementById('mon'+key+'Status').textContent='SCANNING...';

  const stages=['READING DEVICE...','CANVAS FINGERPRINT...','WEBGL HASH...','BROWSER SIGNATURE...','GENERATING SHA-256...'];
  let si=0;
  const iv=setInterval(()=>{
    si++;
    if(si>=stages.length){
      clearInterval(iv);
      if(isScam){ scamScanned=true; scamHash=hash; }
      else { susScanned=true; susHash=hash; }

      document.getElementById('mon'+key+'Idle').style.display='none';
      document.getElementById('mon'+key+'Data').style.display='block';
      document.getElementById('mon'+key+'Status').className='status-badge complete';
      document.getElementById('mon'+key+'Status').textContent='COMPLETE';
      document.getElementById('mon'+key+'Panel').style.borderColor=isScam?'rgba(255,0,110,.4)':'rgba(255,230,0,.4)';

      const name = currentReport?(isScam?currentReport.scam_username:currentReport.suspect_username):role;
      document.getElementById('mon'+key+'Hash').textContent=hash;
      document.getElementById('mon'+key+'Meta').innerHTML=
        `Device: ${navigator.userAgent.substr(0,50)}...<br>Timezone: ${Intl.DateTimeFormat().resolvedOptions().timeZone}<br>Screen: ${screen.width}×${screen.height}<br>Language: ${navigator.language}`;

      const scanned = (scamScanned?1:0)+(susScanned?1:0);
      document.getElementById('monProgressBar').style.width=(scanned/2*100)+'%';
      document.getElementById('monProgressPct').textContent=scanned+' / 2 scanned';

      if(scamScanned && susScanned) setTimeout(runComparison, 500);
    }
  },380);
}

function runComparison(){
  document.getElementById('compareIdle').style.display='none';
  document.getElementById('comparingAnim').style.display='block';

  const stages2=['COMPARING CANVAS FINGERPRINTS...','CHECKING WEBGL SIGNATURES...','ANALYZING BROWSER ENTROPY...','CROSS-REFERENCING BLOCKCHAIN HASHES...','COMPUTING SIMILARITY SCORE...','FINALIZING VERDICT...'];
  let i=0;
  const lbl=document.getElementById('compareStage');
  const bar=document.getElementById('compareBar');
  const iv=setInterval(()=>{
    lbl.textContent=stages2[i]||stages2[stages2.length-1];
    bar.style.width=((i+1)/stages2.length*100)+'%';
    i++;
    if(i>=stages2.length){
      clearInterval(iv);
      setTimeout(()=>showResult(), 600);
    }
  },480);
}

function showResult(){
  // In demo: if report has both scammer+suspect → MATCH (simulated)
  const isMatch = currentReport && currentReport.suspect_username && (Math.random()>0.4);
  const caseId  = currentReport?'BS-'+String(currentReport.id).padStart(6,'0'):'BS-000001';

  document.getElementById('resultPending').style.display='none';
  document.getElementById('resultContent').style.display='block';
  document.getElementById('resultMatchBox').style.display   = isMatch?'block':'none';
  document.getElementById('resultNoMatchBox').style.display = isMatch?'none':'block';

  document.getElementById('res_scam_hash').textContent = scamHash||'a3f8e2d1c7b6a5f4...';
  document.getElementById('res_sus_hash').textContent  = isMatch?(scamHash||'a3f8e2d1c7b6a5f4...'):(susHash||'b4e9f3a2d8c7b6a5...');
  document.getElementById('res_icon').textContent      = isMatch?'⚠':'✓';
  document.getElementById('res_match_lbl').textContent = isMatch?'IDENTICAL HASH':'DIFFERENT HASH';
  document.getElementById('res_icon').style.color      = isMatch?'var(--pink)':'var(--green)';

  if(currentReport){
    document.getElementById('ev_case').textContent = caseId;
    document.getElementById('ev_scam').textContent = currentReport.scam_username;
    document.getElementById('ev_sus').textContent  = currentReport.suspect_username||'Not provided';
    document.getElementById('ev_plat').innerHTML   = `<span class="badge badge-${currentReport.platform.toLowerCase()}">${currentReport.platform}</span>`;
    document.getElementById('ev_tx').textContent   = currentReport.blockchain_tx;
  }
  document.getElementById('ev_result').innerHTML = isMatch
    ? `<span style="color:var(--pink);font-family:Orbitron;letter-spacing:1px;">⚠ CONFIRMED MATCH</span>`
    : `<span style="color:var(--green);font-family:Orbitron;letter-spacing:1px;">✓ NO MATCH — DIFFERENT DEVICES</span>`;

  // highlight result tab
  const btn=document.getElementById('tab-result');
  btn.style.color='var(--yellow)'; btn.style.borderBottom='2px solid var(--yellow)';
  setTimeout(()=>goTab('result'), 900);
}

// ═══════════════════════════════════════════════════════
// SCAN ANIMATION
// ═══════════════════════════════════════════════════════
function runScanAnim(cb){
  const ov=document.getElementById('scanOverlay');
  const lbl=document.getElementById('scanLabel');
  const bar=document.getElementById('scanBar');
  ov.classList.add('open');
  const stages=['INITIALIZING FORENSIC ENGINE...','GENERATING BEHAVIORAL HASH...','SIGNING TO BLOCKCHAIN...','STORING IDENTITY RECORD...','COMPLETE ✓'];
  let i=0;
  const iv=setInterval(()=>{
    lbl.textContent=stages[i]||stages[stages.length-1];
    bar.style.width=((i+1)/stages.length*100)+'%';
    i++;
    if(i>=stages.length){ clearInterval(iv); setTimeout(()=>{ ov.classList.remove('open'); bar.style.width='0%'; cb(); },400); }
  },520);
}

// ═══════════════════════════════════════════════════════
// STEP INDICATOR
// ═══════════════════════════════════════════════════════
function setStep(n){
  for(let i=1;i<=3;i++){
    const el=document.getElementById('step'+i);
    if(!el) continue;
    el.className='step'+(i<n?' done':i===n?' active':'');
  }
}

// ═══════════════════════════════════════════════════════
// UTILITIES
// ═══════════════════════════════════════════════════════
function copyEl(id){
  const txt=document.getElementById(id).textContent;
  navigator.clipboard.writeText(txt).then(()=>{
    const el=document.getElementById(id);
    const orig=el.style.color;
    el.style.color='var(--green)';
    setTimeout(()=>el.style.color=orig,1400);
  }).catch(()=>alert('Copied:\n'+txt));
}

function resetForm(){
  ['f_scam','f_suspect','f_scam_phone','f_sus_phone','f_desc'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
  ['f_platform','f_division'].forEach(id=>{const el=document.getElementById(id);if(el)el.selectedIndex=0;});
  document.getElementById('formStep').style.display='block';
  document.getElementById('linksStep').style.display='none';
  setStep(1);
}

function printResult(){
  const r=currentReport;
  const w=window.open('','_blank','width=760,height=600');
  w.document.write(`<!DOCTYPE html><html><head><title>Evidence Report</title>
  <style>body{font-family:monospace;padding:30px;color:#111;}h1{font-size:18px;border-bottom:2px solid #00a0b0;padding-bottom:10px;margin-bottom:20px;}table{width:100%;border-collapse:collapse;}td{padding:9px 12px;border-bottom:1px solid #eee;font-size:13px;}td:first-child{color:#666;width:38%;font-weight:600;}.footer{margin-top:30px;border-top:1px solid #ddd;padding-top:12px;font-size:11px;color:#999;display:flex;justify-content:space-between;}</style>
  </head><body>
  <h1>⬡ BLOCKCHAIN SENTINEL — FORENSIC EVIDENCE REPORT</h1>
  <table>
  <tr><td>Case ID</td><td>${r?'BS-'+String(r.id).padStart(6,'0'):'—'}</td></tr>
  <tr><td>Scammer</td><td>${r?.scam_username||'—'}</td></tr>
  <tr><td>Suspect</td><td>${r?.suspect_username||'Not Identified'}</td></tr>
  <tr><td>Platform</td><td>${r?.platform||'—'}</td></tr>
  <tr><td>Division</td><td>${r?.division||'—'}</td></tr>
  <tr><td>Scammer Hash</td><td style="font-size:11px;word-break:break-all;">${scamHash||'—'}</td></tr>
  <tr><td>Suspect Hash</td><td style="font-size:11px;word-break:break-all;">${susHash||'—'}</td></tr>
  <tr><td>Blockchain TX</td><td style="font-size:11px;">${r?.blockchain_tx||'—'}</td></tr>
  <tr><td>Generated</td><td>${new Date().toLocaleString()}</td></tr>
  </table>
  <div class="footer"><span>BLOCKCHAIN SENTINEL // FORENSIC DIVISION</span><span>CONFIDENTIAL</span></div>
  </body></html>`);
  w.document.close(); setTimeout(()=>w.print(),500);
}

// ═══════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════
buildOverview();
