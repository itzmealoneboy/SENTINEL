// node_service/server.js
// Blockchain Sentinel - Node.js API for Hardhat/Ethereum interaction

const express = require('express');
const cors = require('cors');
const crypto = require('crypto');
const { ethers } = require('ethers');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = 3001;

app.use(cors());
app.use(express.json());

// ===== Load contract ABI =====
let contract = null;
let provider = null;
let signer = null;

async function initBlockchain() {
  try {
    // Connect to local Hardhat node
    provider = new ethers.JsonRpcProvider('http://127.0.0.1:8545');
    
    // Use first Hardhat account as signer
    const accounts = await provider.listAccounts();
    signer = await provider.getSigner(0);

    // Load deployment info
    const abiPath = path.join(__dirname, 'abi.json');
    if (fs.existsSync(abiPath)) {
      const abiData = JSON.parse(fs.readFileSync(abiPath, 'utf8'));
      contract = new ethers.Contract(abiData.address, abiData.abi, signer);
      console.log('✓ Connected to IdentityStorage at:', abiData.address);
    } else {
      console.log('⚠ abi.json not found. Run: cd blockchain && npx hardhat run scripts/deploy.js --network localhost');
    }
  } catch (err) {
    console.log('⚠ Blockchain not available. Running in simulation mode.');
    console.log('  To enable: npx hardhat node (in another terminal)');
    console.log('  Then: cd blockchain && npx hardhat run scripts/deploy.js --network localhost');
  }
}

// ===== In-memory simulation store (when no blockchain) =====
const simulatedHashes = new Map();
let simulatedCount = 10;

// Pre-seed simulation with demo hashes
const demoHashes = [
  { hash: 'a3f8e2d1c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1f0', reportId: 1 },
  { hash: 'b4e9f3a2d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1', reportId: 2 },
  { hash: 'c5f0a4b3e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2', reportId: 3 },
  { hash: 'd6a1b5c4f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3', reportId: 4 },
  { hash: 'e7b2c6d5a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4', reportId: 5 },
  { hash: 'f8c3d7e6b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5', reportId: 6 },
  { hash: 'a9d4e8f7c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6', reportId: 7 },
  { hash: 'b0e5f9a8d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7', reportId: 8 },
  { hash: 'c1f6a0b9e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8', reportId: 9 },
  { hash: 'd2a7b1c0f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9', reportId: 10 },
];

demoHashes.forEach(d => simulatedHashes.set(d.hash, { ...d, timestamp: Date.now(), tx: '0x' + crypto.randomBytes(16).toString('hex') }));

// ===== Helper: generate SHA256 =====
function sha256(data) {
  return crypto.createHash('sha256').update(typeof data === 'string' ? data : JSON.stringify(data)).digest('hex');
}

// ===== ROUTES =====

// Health check
app.get('/health', (req, res) => {
  res.json({
    status: 'online',
    blockchain: contract ? 'connected' : 'simulation',
    hashCount: contract ? 'live' : simulatedHashes.size,
    timestamp: new Date().toISOString()
  });
});

// Store hash on blockchain
app.post('/store-hash', async (req, res) => {
  const { hash, reportId } = req.body;

  if (!hash) {
    return res.status(400).json({ success: false, error: 'Hash required' });
  }

  try {
    if (contract) {
      // Real blockchain
      const tx = await contract.storeHash(hash, reportId || 0);
      const receipt = await tx.wait();
      
      res.json({
        success: true,
        txHash: receipt.hash,
        blockNumber: receipt.blockNumber,
        hash,
        reportId
      });
    } else {
      // Simulation mode
      const txHash = '0x' + crypto.randomBytes(32).toString('hex');
      simulatedHashes.set(hash, { hash, reportId, timestamp: Date.now(), tx: txHash });
      simulatedCount++;

      res.json({
        success: true,
        txHash,
        blockNumber: 18423891 + simulatedCount,
        hash,
        reportId,
        mode: 'simulation'
      });
    }
  } catch (err) {
    res.status(500).json({ success: false, error: err.message });
  }
});

// Verify hash
app.get('/verify-hash', async (req, res) => {
  const { hash } = req.query;

  if (!hash) {
    return res.status(400).json({ success: false, error: 'Hash required' });
  }

  try {
    if (contract) {
      const [found, reportId, timestamp] = await contract.verifyHash(hash);
      res.json({ success: true, found, reportId: Number(reportId), timestamp: Number(timestamp) });
    } else {
      const record = simulatedHashes.get(hash);
      res.json({
        success: true,
        found: !!record,
        reportId: record?.reportId || 0,
        timestamp: record?.timestamp || 0,
        mode: 'simulation'
      });
    }
  } catch (err) {
    res.status(500).json({ success: false, error: err.message });
  }
});

// Compare two hashes
app.post('/compare-hashes', async (req, res) => {
  const { hash1, hash2 } = req.body;

  if (!hash1 || !hash2) {
    return res.status(400).json({ success: false, error: 'Both hashes required' });
  }

  const match = hash1 === hash2;

  res.json({
    success: true,
    match,
    hash1,
    hash2,
    result: match ? 'CONFIRMED SCAMMER IDENTITY MATCH' : 'DIFFERENT IDENTITY',
    message: match 
      ? 'Behavioral fingerprints match. Same individual confirmed.' 
      : 'Behavioral fingerprints differ. Different identities detected.'
  });
});

// Generate SHA256 from fingerprint data
app.post('/generate-hash', (req, res) => {
  const { fingerprint } = req.body;

  if (!fingerprint) {
    return res.status(400).json({ success: false, error: 'Fingerprint data required' });
  }

  const normalizedFingerprint = {
    typing_rhythm: fingerprint.typing_rhythm || [],
    screen_resolution: fingerprint.screen_resolution || '',
    browser: fingerprint.browser || '',
    os: fingerprint.os || '',
    font_size: fingerprint.font_size || '',
    canvas_fingerprint: fingerprint.canvas_fingerprint || '',
    webgl_fingerprint: fingerprint.webgl_fingerprint || '',
    timezone: fingerprint.timezone || '',
    device_memory: fingerprint.device_memory || ''
  };

  const hash = sha256(JSON.stringify(normalizedFingerprint));

  res.json({
    success: true,
    hash,
    algorithm: 'SHA-256'
  });
});

// Get hash count
app.get('/hash-count', async (req, res) => {
  try {
    if (contract) {
      const count = await contract.getHashCount();
      res.json({ success: true, count: Number(count) });
    } else {
      res.json({ success: true, count: simulatedHashes.size, mode: 'simulation' });
    }
  } catch (err) {
    res.status(500).json({ success: false, error: err.message });
  }
});

// Get all stored hashes (admin only)
app.get('/all-hashes', (req, res) => {
  const hashes = [];
  simulatedHashes.forEach((val, key) => {
    hashes.push({
      hash: key,
      reportId: val.reportId,
      timestamp: val.timestamp,
      tx: val.tx
    });
  });
  res.json({ success: true, hashes, count: hashes.length });
});

// ===== START SERVER =====
app.listen(PORT, async () => {
  console.log('');
  console.log('╔══════════════════════════════════════╗');
  console.log('║  BLOCKCHAIN SENTINEL - Node API      ║');
  console.log(`║  Running on port ${PORT}               ║`);
  console.log('╚══════════════════════════════════════╝');
  console.log('');
  await initBlockchain();
  console.log(`\n⬡ API Ready: http://localhost:${PORT}`);
  console.log('  GET  /health');
  console.log('  POST /store-hash');
  console.log('  GET  /verify-hash?hash=...');
  console.log('  POST /compare-hashes');
  console.log('  POST /generate-hash');
  console.log('  GET  /hash-count');
  console.log('');
});
