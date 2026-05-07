<?php

namespace App\Models;

use PDO;
use PDOException;

class BankModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Get all bank accounts
     */
    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, bank_name, account_number, account_holder, bank_logo, is_active, created_at
                FROM bank_accounts
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all banks: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get bank account by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, bank_name, account_number, account_holder, bank_logo, is_active, created_at
                FROM bank_accounts
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting bank by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new bank account
     */
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO bank_accounts (bank_name, account_number, account_holder, bank_logo, is_active)
                VALUES (:bank_name, :account_number, :account_holder, :bank_logo, :is_active)
            ");

            $params = [
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder' => $data['account_holder'],
                'bank_logo' => $data['bank_logo'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error creating bank: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update bank account
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE bank_accounts
                SET bank_name = :bank_name,
                    account_number = :account_number,
                    account_holder = :account_holder,
                    is_active = :is_active
            ";

            $params = [
                'id' => $id,
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder' => $data['account_holder'],
                'is_active' => $data['is_active'] ?? 1
            ];

            // Include bank_logo only if it's provided
            if (isset($data['bank_logo'])) {
                $sql .= ", bank_logo = :bank_logo";
                $params['bank_logo'] = $data['bank_logo'];
            }

            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating bank: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete bank account
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM bank_accounts
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting bank: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle bank active status
     */
    public function toggleActive($id)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE bank_accounts
                SET is_active = NOT is_active
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error toggling bank status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active banks only
     */
    public function getActiveBanks()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, bank_name, account_number, account_holder, bank_logo
                FROM bank_accounts
                WHERE is_active = 1
                ORDER BY bank_name ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting active banks: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if account number exists
     */
    public function accountNumberExists($accountNumber, $excludeId = null)
    {
        try {
            $sql = "
                SELECT COUNT(*) as count
                FROM bank_accounts
                WHERE account_number = :account_number
            ";

            $params = ['account_number' => $accountNumber];

            if ($excludeId) {
                $sql .= " AND id != :id";
                $params['id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error checking account number: " . $e->getMessage());
            return false;
        }
    }
}
