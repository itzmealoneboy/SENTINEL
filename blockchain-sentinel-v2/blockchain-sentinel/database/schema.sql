-- Blockchain Sentinel Database Schema
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS blockchain_sentinel;
USE blockchain_sentinel;

-- Reports table
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scam_username VARCHAR(100) NOT NULL,
    suspect_username VARCHAR(100) DEFAULT NULL,
    platform VARCHAR(100) NOT NULL,
    division VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    fingerprint_hash VARCHAR(255) DEFAULT NULL,
    blockchain_tx VARCHAR(255) DEFAULT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Fingerprint hashes table (for comparison)
CREATE TABLE IF NOT EXISTS fingerprint_hashes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash VARCHAR(255) NOT NULL UNIQUE,
    linked_report_id INT DEFAULT NULL,
    label VARCHAR(100) DEFAULT 'unknown',
    blockchain_tx VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (linked_report_id) REFERENCES reports(id) ON DELETE SET NULL
);

-- Admin sessions
CREATE TABLE IF NOT EXISTS admin_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 1 HOUR)
);

-- Demo reports data
INSERT INTO reports (scam_username, suspect_username, platform, division, description, fingerprint_hash, blockchain_tx) VALUES
('crypto_king_99', 'investpro_mike', 'Telegram', 'Crypto Scam', 'Promised 500% returns on crypto investment. Disappeared after receiving 2 BTC.', 'a3f8e2d1c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1f0', '0xabc123def456789'),
('forex_master_777', 'richtrader_alex', 'Instagram', 'Investment Scam', 'Fake forex trading signals. Collected $5000 from 12 victims before disappearing.', 'b4e9f3a2d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1', '0xdef789abc123456'),
('nft_drop_official', 'digital_art_pro', 'Twitter', 'Digital Scam', 'Fake NFT drops. Victims paid ETH for non-existent digital art collections.', 'c5f0a4b3e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2', '0x789456def123abc'),
('loan_helper_fast', 'quickcash_lender', 'WhatsApp', 'Money Scam', 'Advance fee fraud. Promised instant loans, charged upfront fees then vanished.', 'd6a1b5c4f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3', '0x321def789abc456'),
('job_recruiter_intl', 'hr_manager_sarah', 'LinkedIn', 'Identity Fraud', 'Fake job offers to steal personal information including passport and bank details.', 'e7b2c6d5a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4', '0x654abc321def789'),
('bitcoin_doubler_x', NULL, 'Telegram', 'Crypto Scam', 'Bitcoin doubling scheme. Collected over 5 BTC from multiple victims across 3 countries.', 'f8c3d7e6b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5', '0x987xyz654uvw321'),
('romance_scammer_90', 'sweetheart_anna', 'Instagram', 'Money Scam', 'Romance scam. Built relationship over 3 months then requested $10,000 for emergency.', 'a9d4e8f7c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6', '0xabc987xyz654uvw'),
('tech_support_ms', 'microsoft_helper', 'Facebook', 'Digital Scam', 'Fake Microsoft tech support. Gained remote access to steal banking credentials.', 'b0e5f9a8d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7', '0xdef456abc789xyz'),
('pump_dump_token', 'defi_whale_pro', 'Telegram', 'Crypto Scam', 'Pump and dump scheme on new DeFi token. Victims lost combined $50,000.', 'c1f6a0b9e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8', '0x123uvw456xyz789'),
('fake_charity_help', 'donation_collector', 'WhatsApp', 'Money Scam', 'Impersonated legitimate charity collecting COVID relief funds. Stole $25,000.', 'd2a7b1c0f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9', '0x456rst789uvw012'),
('verified_trader_eu', 'eu_markets_pro', 'LinkedIn', 'Investment Scam', 'Posed as licensed EU financial advisor. Stole investment portfolios worth €80,000.', 'e3b8c2d1a7f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0', '0x789lmn012opq345'),
('wallet_recovery_x', NULL, 'Telegram', 'Crypto Scam', 'Fake crypto wallet recovery service. Stole seed phrases from 8 victims.', 'f4c9d3e2b8a7f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1', '0x012ghi345jkl678'),
('influencer_drop99', 'brand_promo_vip', 'Instagram', 'Digital Scam', 'Fake influencer merchandise drops. Collected payment for products never delivered.', 'a5e0f4b3c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2', '0x345mno678pqr901'),
('gold_investment_vp', 'precious_metals_x', 'Facebook', 'Investment Scam', 'Fake gold investment company. Used forged certificates to steal $35,000.', 'b6f1a5c4d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3', '0x678stu901vwx234'),
('id_verify_service', 'secure_verify_co', 'WhatsApp', 'Identity Fraud', 'Impersonated bank verification service. Collected KYC documents from 20 victims.', 'c7a2b6d5e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4', '0x901xyz234abc567'),
('airdrop_legit_pro', NULL, 'Telegram', 'Crypto Scam', 'Fake crypto airdrop campaign requiring wallet connection. Drained victim wallets.', 'd8b3c7e6f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5', '0x234bcd567efg890'),
('work_from_home_ez', 'passive_income_x', 'Instagram', 'Money Scam', 'Work from home scam. Victims paid training fees but received no work or refunds.', 'e9c4d8f7a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6', '0x567hij890klm123'),
('fake_attorney_law', 'legalaid_counsel', 'LinkedIn', 'Identity Fraud', 'Impersonated attorney, charged fees for non-existent legal services and stole PII.', 'f0d5e9a8b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7', '0x890nop123qrs456'),
('binary_options_vip', NULL, 'WhatsApp', 'Investment Scam', 'Binary options fraud. Manipulated trading platform to ensure victims always lost.', 'a1e6f0b9c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c1d0e9f8', '0x123tuv456wxy789'),
('metaverse_land_vr', 'virtual_realtor_x', 'Twitter', 'Digital Scam', 'Fake metaverse land sales. Sold non-existent virtual properties for combined $15,000.', 'b2f7a1c0d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9d8e7f6a5b4c3d2e1f0a9', '0x456yz0123abc789');

-- Pre-load fingerprint hashes (for blockchain demo matching)
INSERT INTO fingerprint_hashes (hash, label, blockchain_tx) VALUES
('a3f8e2d1c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1f0', 'Known Scammer - crypto_king_99', '0xabc123def456789'),
('b4e9f3a2d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5d4c3b2a1', 'Known Scammer - forex_master_777', '0xdef789abc123456'),
('f8c3d7e6b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8a7f6e5', 'Known Scammer - bitcoin_doubler_x', '0x987xyz654uvw321'),
('c1f6a0b9e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1d0c9b8', 'Known Scammer - pump_dump_token', '0x123uvw456xyz789'),
('f4c9d3e2b8a7f6e5d4c3b2a1f0e9d8c7b6a5f4e3d2c1b0a9f8e7d6c5b4a3f2e1', 'Known Scammer - wallet_recovery_x', '0x012ghi345jkl678'),
('sample_hash_alpha_001_fingerprint_test_data_xyz', 'Sample Identity A', '0xsample001alpha'),
('sample_hash_beta_002_fingerprint_test_data_xyz', 'Sample Identity B', '0xsample002beta'),
('sample_hash_gamma_003_fingerprint_test_data_xyz', 'Sample Identity C', '0xsample003gamma'),
('sample_hash_delta_004_fingerprint_test_data_xyz', 'Sample Identity D', '0xsample004delta'),
('sample_hash_epsilon_005_fingerprint_test_data_xyz', 'Sample Identity E', '0xsample005epsilon');
