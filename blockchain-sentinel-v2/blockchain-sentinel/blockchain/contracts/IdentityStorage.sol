// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title IdentityStorage
 * @dev Blockchain Sentinel - Immutable identity fingerprint hash storage
 * @author Blockchain Sentinel Security Team
 */
contract IdentityStorage {
    
    // ===== DATA STRUCTURES =====
    
    struct HashRecord {
        string hash;
        uint256 timestamp;
        uint256 reportId;
        address submittedBy;
        bool exists;
    }

    // ===== STATE VARIABLES =====
    
    address public owner;
    uint256 public hashCount;
    
    // Mapping from index to HashRecord
    mapping(uint256 => HashRecord) private hashRecords;
    
    // Mapping from hash string to index (for lookup)
    mapping(string => uint256) private hashToIndex;
    
    // Mapping from reportId to hash index
    mapping(uint256 => uint256) private reportToHash;

    // ===== EVENTS =====
    
    event HashStored(
        uint256 indexed index,
        string hash,
        uint256 indexed reportId,
        uint256 timestamp,
        address submittedBy
    );
    
    event HashVerified(
        string hash,
        bool found,
        uint256 reportId,
        uint256 timestamp
    );

    // ===== MODIFIERS =====
    
    modifier onlyOwner() {
        require(msg.sender == owner, "IdentityStorage: Only owner can call this");
        _;
    }
    
    modifier validHash(string memory hash) {
        require(bytes(hash).length > 0, "IdentityStorage: Hash cannot be empty");
        require(bytes(hash).length <= 256, "IdentityStorage: Hash too long");
        _;
    }

    // ===== CONSTRUCTOR =====
    
    constructor() {
        owner = msg.sender;
        hashCount = 0;
    }

    // ===== MAIN FUNCTIONS =====

    /**
     * @dev Store a new identity fingerprint hash
     * @param hash SHA256 hash of behavioral fingerprint data
     * @param reportId Associated report ID from MySQL database
     */
    function storeHash(string memory hash, uint256 reportId) 
        public 
        validHash(hash)
        returns (uint256)
    {
        // Check if hash already stored
        if (hashToIndex[hash] != 0) {
            // Hash exists - return existing index
            return hashToIndex[hash];
        }
        
        hashCount++;
        uint256 newIndex = hashCount;
        
        hashRecords[newIndex] = HashRecord({
            hash: hash,
            timestamp: block.timestamp,
            reportId: reportId,
            submittedBy: msg.sender,
            exists: true
        });
        
        hashToIndex[hash] = newIndex;
        reportToHash[reportId] = newIndex;
        
        emit HashStored(newIndex, hash, reportId, block.timestamp, msg.sender);
        
        return newIndex;
    }

    /**
     * @dev Verify if a hash exists in the blockchain
     * @param hash SHA256 hash to verify
     */
    function verifyHash(string memory hash) 
        public 
        returns (bool found, uint256 reportId, uint256 timestamp)
    {
        uint256 index = hashToIndex[hash];
        
        if (index != 0 && hashRecords[index].exists) {
            HashRecord memory record = hashRecords[index];
            emit HashVerified(hash, true, record.reportId, record.timestamp);
            return (true, record.reportId, record.timestamp);
        }
        
        emit HashVerified(hash, false, 0, 0);
        return (false, 0, 0);
    }

    /**
     * @dev Get hash record by index
     * @param index Index of the hash record
     */
    function getHashByIndex(uint256 index) 
        public 
        view 
        returns (
            string memory hash,
            uint256 timestamp,
            uint256 reportId,
            address submittedBy
        )
    {
        require(index > 0 && index <= hashCount, "IdentityStorage: Index out of bounds");
        require(hashRecords[index].exists, "IdentityStorage: Record does not exist");
        
        HashRecord memory record = hashRecords[index];
        return (record.hash, record.timestamp, record.reportId, record.submittedBy);
    }

    /**
     * @dev Get hash by report ID
     * @param reportId Report ID from MySQL
     */
    function getHashByReportId(uint256 reportId)
        public
        view
        returns (string memory hash, uint256 timestamp)
    {
        uint256 index = reportToHash[reportId];
        require(index != 0, "IdentityStorage: No hash for this report");
        
        HashRecord memory record = hashRecords[index];
        return (record.hash, record.timestamp);
    }

    /**
     * @dev Get total number of stored hashes
     */
    function getHashCount() public view returns (uint256) {
        return hashCount;
    }

    /**
     * @dev Check if two hashes match (for identity comparison)
     * @param hash1 First SHA256 hash
     * @param hash2 Second SHA256 hash
     */
    function compareHashes(string memory hash1, string memory hash2)
        public
        pure
        returns (bool)
    {
        return keccak256(bytes(hash1)) == keccak256(bytes(hash2));
    }

    /**
     * @dev Batch store multiple hashes (for initial data seeding)
     * @param hashes Array of hashes
     * @param reportIds Array of corresponding report IDs
     */
    function batchStoreHashes(string[] memory hashes, uint256[] memory reportIds)
        public
        onlyOwner
    {
        require(hashes.length == reportIds.length, "Arrays must have same length");
        require(hashes.length <= 50, "Max 50 hashes per batch");
        
        for (uint256 i = 0; i < hashes.length; i++) {
            if (bytes(hashes[i]).length > 0) {
                storeHash(hashes[i], reportIds[i]);
            }
        }
    }

    /**
     * @dev Transfer contract ownership
     */
    function transferOwnership(address newOwner) public onlyOwner {
        require(newOwner != address(0), "Invalid address");
        owner = newOwner;
    }
}
