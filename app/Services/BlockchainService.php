<?php

namespace App\Services;

use App\Core\Database;
use Exception;

class BlockchainService {
    private $db;
    private $difficulty = 2; // Simulated proof of work (number of leading zeros in block hash)

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureGenesisBlock();
    }

    /**
     * Mines a new block representing verified records
     * @param string $dataType e.g. "internship_cert", "course_cert", "business_record"
     * @param array $payload Key-value metadata containing student/owner and reference details
     * @return array The mined block structure
     */
    public function mineRecord($dataType, $payload) {
        $lastBlock = $this->getLastBlock();
        $index = $lastBlock ? intval($lastBlock['block_index']) + 1 : 1;
        $prevHash = $lastBlock ? $lastBlock['hash'] : '0000000000000000000000000000000000000000000000000000000000000000';
        $timestamp = time();

        // Calculate Merkle root of data payload
        $dataJson = json_encode(array_merge(['type' => $dataType], $payload));
        $merkleRoot = hash('sha256', $dataJson);

        // Mine block (Proof of work loop)
        $nonce = 0;
        $hash = '';
        $targetPrefix = str_repeat('0', $this->difficulty);

        while (true) {
            $hash = hash('sha256', $index . $timestamp . $prevHash . $merkleRoot . $nonce);
            if (substr($hash, 0, $this->difficulty) === $targetPrefix) {
                break;
            }
            $nonce++;
        }

        // Insert into database
        $sql = "INSERT INTO blockchain_blocks (block_index, timestamp, previous_hash, hash, merkle_root, data) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $index,
            $timestamp,
            $prevHash,
            $hash,
            $merkleRoot,
            $dataJson
        ]);

        return [
            'index' => $index,
            'timestamp' => $timestamp,
            'previous_hash' => $prevHash,
            'hash' => $hash,
            'merkle_root' => $merkleRoot,
            'nonce' => $nonce,
            'data' => $payload
        ];
    }

    /**
     * Verifies the cryptographic chain sequence integrity
     * @return bool True if chain hashes are intact, false if tampered with
     */
    public function validateChain() {
        $blocks = $this->db->query("SELECT * FROM blockchain_blocks ORDER BY block_index ASC");
        
        if (count($blocks) <= 1) {
            return true;
        }

        for ($i = 1; $i < count($blocks); $i++) {
            $current = $blocks[$i];
            $previous = $blocks[$i - 1];

            // 1. Verify previous hash pointer matches
            if ($current['previous_hash'] !== $previous['hash']) {
                return false;
            }

            // 2. Verify Merkle root matches payload contents
            $calculatedMerkle = hash('sha256', $current['data']);
            if ($current['merkle_root'] !== $calculatedMerkle) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all verified blocks for display in the admin ledger explorer
     */
    public function getLedger() {
        return $this->db->query("SELECT * FROM blockchain_blocks ORDER BY block_index DESC");
    }

    /**
     * Helper to retrieve last appended block
     */
    private function getLastBlock() {
        return $this->db->queryRow("SELECT * FROM blockchain_blocks ORDER BY block_index DESC LIMIT 1");
    }

    /**
     * Initialize Genesis block if blockchain is empty
     */
    private function ensureGenesisBlock() {
        $count = $this->db->queryRow("SELECT COUNT(*) as count FROM blockchain_blocks")['count'] ?? 0;
        
        if ($count == 0) {
            $timestamp = time();
            $data = json_encode(['info' => 'Bihar Vihaan Enterprise Cryptographic Ledger Genesis Block']);
            $merkleRoot = hash('sha256', $data);
            $hash = hash('sha256', '0' . $timestamp . '0' . $merkleRoot . '0');

            $sql = "INSERT INTO blockchain_blocks (block_index, timestamp, previous_hash, hash, merkle_root, data) 
                    VALUES (0, ?, '0', ?, ?, ?)";
            
            $this->db->execute($sql, [$timestamp, $hash, $merkleRoot, $data]);
        }
    }
}
