# ⬡ BLOCKCHAIN SENTINEL
## Advanced Cyber Fraud Identity Attribution Platform
### Complete Setup Guide

---

## SYSTEM REQUIREMENTS

- XAMPP (Apache + MySQL + PHP 8.0+)
- Node.js v18+
- Git

---

## STEP 1: PROJECT SETUP

Place the entire `blockchain-sentinel` folder inside:

```
C:\xampp\htdocs\blockchain-sentinel\
```

Your structure should look like:
```
C:\xampp\htdocs\blockchain-sentinel\
│
├── index.php                    ← Public portal (login + report)
├── admin\
│   └── index.php                ← Admin dashboard
├── backend\
│   ├── config.php               ← DB connection
│   ├── login.php                ← Auth handler
│   ├── submit_report.php        ← Report submission
│   ├── admin_reports.php        ← Admin data API
│   └── collect_fingerprint.php  ← Fingerprint collector
├── database\
│   └── schema.sql               ← Database schema + demo data
├── blockchain\
│   ├── contracts\
│   │   └── IdentityStorage.sol  ← Solidity smart contract
│   ├── scripts\
│   │   └── deploy.js            ← Deployment script
│   └── hardhat.config.js
├── node_service\
│   ├── server.js                ← Node.js blockchain API
│   └── package.json
└── fake_login_pages\
    ├── telegram\index.php
    ├── instagram\index.php
    └── linkedin\index.php
```

---

## STEP 2: DATABASE SETUP

1. Start XAMPP - enable **Apache** and **MySQL**
2. Open **phpMyAdmin**: `http://localhost/phpmyadmin`
3. Click **"New"** to create a database
4. Name it: `blockchain_sentinel`
5. Click **SQL** tab
6. Copy and paste contents of `database/schema.sql`
7. Click **Go** / Execute

This creates:
- `reports` table with 20 demo reports
- `fingerprint_hashes` table with 10 pre-loaded blockchain hashes

---

## STEP 3: NODE.JS BLOCKCHAIN API SETUP

```bash
# Terminal 1: Install Node API dependencies
cd C:\xampp\htdocs\blockchain-sentinel\node_service
npm install

# Start the blockchain API
node server.js
```

The API runs at: `http://localhost:3001`

You should see:
```
╔══════════════════════════════════════╗
║  BLOCKCHAIN SENTINEL - Node API      ║
║  Running on port 3001                ║
╚══════════════════════════════════════╝
⬡ API Ready: http://localhost:3001
```

---

## STEP 4: HARDHAT BLOCKCHAIN SETUP (Optional - Full Blockchain)

```bash
# Terminal 2: Install Hardhat
cd C:\xampp\htdocs\blockchain-sentinel\blockchain
npm install

# Start local blockchain node
npx hardhat node

# Terminal 3: Deploy smart contract
cd C:\xampp\htdocs\blockchain-sentinel\blockchain
npx hardhat run scripts/deploy.js --network localhost
```

This deploys the `IdentityStorage` contract and pre-seeds 10 fingerprint hashes.

---

## STEP 5: LAUNCH THE PLATFORM

Open browser: `http://localhost/blockchain-sentinel/`

---

## LOGIN CREDENTIALS

### Public Portal
- **Email:** `public123`
- **Password:** `pass123`

### Admin Dashboard
- **Username:** `admin`
- **Password:** `admin123`

---

## FEATURES WALKTHROUGH

### Public User Flow
1. Go to `http://localhost/blockchain-sentinel/`
2. Login with public credentials
3. Fill in report form (scam username, platform, description)
4. Submit — watch the cyberpunk animation:
   - "INITIALIZING THREAT ANALYSIS"
   - "SCANNING NETWORK SIGNATURE"
   - "BLOCKCHAIN RECORD INITIATED"
   - "THREAT REPORT SUCCESSFULLY STORED"
5. Report is stored in MySQL with SHA256 hash

### Admin Flow
1. Go to `http://localhost/blockchain-sentinel/admin/`
2. Login with admin credentials
3. View dashboard with threat division breakdown
4. Click any division to filter reports
5. Click a report to view case details
6. Click **"SCAN IDENTITY"** to run forensic analysis:
   - Radar animation with 5 scan stages
   - Hash comparison against blockchain database
   - Result: MATCH / NO MATCH / UNKNOWN
7. Click **"SEND AUTHENTICATION LINK"** to get fake login URL
   - Send link to scammer on their platform
   - Link opens fake login page (Telegram/Instagram/LinkedIn clone)
   - Behavioral fingerprint data collected silently

### Scan Results (Demo)
- Reports **#1-5** will show **CONFIRMED IDENTITY MATCH** ✓
- Reports **#6-20** will show **UNKNOWN IDENTITY** or **DIFFERENT IDENTITY**

---

## BLOCKCHAIN DEMO DATA

Pre-loaded on blockchain:
- 10 fingerprint hashes from known scammers
- 5 reports will match during scan (IDs 1-5)
- All hashes visible in Admin → Blockchain Hashes tab

---

## API ENDPOINTS (Node.js)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /health | System status |
| POST | /store-hash | Store hash on blockchain |
| GET | /verify-hash?hash=... | Verify hash exists |
| POST | /compare-hashes | Compare two hashes |
| POST | /generate-hash | SHA256 from fingerprint |
| GET | /hash-count | Total stored hashes |
| GET | /all-hashes | List all hashes (admin) |

---

## SYSTEM ARCHITECTURE

```
Browser (Public User)
        ↓
   index.php (PHP/XAMPP)
        ↓
  submit_report.php
        ↓
   MySQL Database ←──────────────────┐
        ↓                            │
  SHA256 Hash Generated              │
        ↓                            │
  Node.js API (Port 3001)            │
        ↓                            │
  Hardhat Blockchain (Port 8545)     │
                                     │
Browser (Admin)                      │
        ↓                            │
  admin/index.php                    │
        ↓                            │
  admin_reports.php ─────────────────┘
        ↓
  Scan Identity
        ↓
  Compare SHA256 Hashes
        ↓
  Result: MATCH / NO MATCH
```

---

## TROUBLESHOOTING

**Apache won't start:** Port 80 conflict → Change port to 8080 in XAMPP
**MySQL error:** Ensure database `blockchain_sentinel` exists
**Node API not connecting:** Run `node server.js` first
**Blockchain errors:** Run `npx hardhat node` in separate terminal
**PHP errors:** Ensure PHP version ≥ 8.0 in XAMPP

---

## TECHNOLOGY STACK

| Layer | Technology |
|-------|------------|
| Frontend | HTML5, CSS3, JavaScript, Canvas API |
| Backend | PHP 8.0+ |
| Database | MySQL (XAMPP) |
| Blockchain | Solidity 0.8.19, Hardhat, ethers.js |
| API | Node.js + Express |
| Cryptography | SHA-256 |
| Animations | CSS3 animations, Canvas particles |
| UI Theme | Cyberpunk 2077 inspired |

---

*⬡ BLOCKCHAIN SENTINEL // All data encrypted & immutable*
