// blockchain/scripts/deploy.js
// Deploy IdentityStorage contract and seed with demo data

const { ethers } = require("hardhat");
const fs = require("fs");
const path = require("path");

async function main() {
  console.log("⬡ BLOCKCHAIN SENTINEL - Deploying IdentityStorage...\n");

  // Get deployer
  const [deployer] = await ethers.getSigners();
  console.log("Deploying with account:", deployer.address);
  console.log("Account balance:", ethers.formatEther(await ethers.provider.getBalance(deployer.address)), "ETH\n");

  // Deploy contract
  const IdentityStorage = await ethers.getContractFactory("IdentityStorage");
  const contract = await IdentityStorage.deploy();
  await contract.waitForDeployment();

  const contractAddress = await contract.getAddress();
  console.log("✓ IdentityStorage deployed to:", contractAddress);

  // Seed with demo fingerprint hashes
  const demoHashes = [
    "a3f8e2d1c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1f0",
    "b4e9f3a2d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1",
    "c5f0a4b3e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2",
    "d6a1b5c4f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3",
    "e7b2c6d5a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4",
    "f8c3d7e6b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5",
    "a9d4e8f7c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6",
    "b0e5f9a8d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7",
    "c1f6a0b9e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8",
    "d2a7b1c0f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9",
  ];

  const reportIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

  console.log("\n⬡ Seeding blockchain with demo identity hashes...");

  const tx = await contract.batchStoreHashes(demoHashes, reportIds);
  await tx.wait();

  const count = await contract.getHashCount();
  console.log(`✓ ${count} hashes stored on blockchain`);

  // Save deployment info
  const deployInfo = {
    contractAddress,
    deployer: deployer.address,
    network: "hardhat-localhost",
    chainId: 31337,
    deployedAt: new Date().toISOString(),
    hashCount: count.toString()
  };

  const outputPath = path.join(__dirname, "../deployment.json");
  fs.writeFileSync(outputPath, JSON.stringify(deployInfo, null, 2));
  console.log("\n✓ Deployment info saved to blockchain/deployment.json");

  // Also save ABI for Node API
  const artifact = require("../artifacts/contracts/IdentityStorage.sol/IdentityStorage.json");
  const abiPath = path.join(__dirname, "../../node_service/abi.json");
  fs.writeFileSync(abiPath, JSON.stringify({ abi: artifact.abi, address: contractAddress }, null, 2));
  console.log("✓ ABI saved to node_service/abi.json");

  console.log("\n⬡ DEPLOYMENT COMPLETE");
  console.log("Contract Address:", contractAddress);
  console.log("Total Hashes:", count.toString());
  console.log("\nNext steps:");
  console.log("  1. cd ../node_service && npm install && node server.js");
  console.log("  2. Open xampp/htdocs/blockchain-sentinel/index.php");
}

main()
  .then(() => process.exit(0))
  .catch((err) => {
    console.error("Deployment failed:", err);
    process.exit(1);
  });
