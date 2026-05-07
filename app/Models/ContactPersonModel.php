<?php

namespace App\Models;

use PDO;
use PDOException;

class ContactPersonModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Get all contact persons
     */
    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform, contact_info, link_url, icon, is_active
                FROM contact_persons
                ORDER BY id DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all contact persons: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get contact person by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform, contact_info, link_url, icon, is_active
                FROM contact_persons
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting contact person by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new contact person
     */
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO contact_persons (platform, contact_info, link_url, icon, is_active)
                VALUES (:platform, :contact_info, :link_url, :icon, :is_active)
            ");

            $params = [
                'platform' => $data['platform'],
                'contact_info' => $data['contact_info'],
                'link_url' => $data['link_url'],
                'icon' => $data['icon'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error creating contact person: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update contact person
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE contact_persons
                SET platform = :platform,
                    contact_info = :contact_info,
                    link_url = :link_url,
                    is_active = :is_active
            ";

            $params = [
                'id' => $id,
                'platform' => $data['platform'],
                'contact_info' => $data['contact_info'],
                'link_url' => $data['link_url'],
                'is_active' => $data['is_active'] ?? 1
            ];

            // Include icon only if it's provided
            if (isset($data['icon'])) {
                $sql .= ", icon = :icon";
                $params['icon'] = $data['icon'];
            }

            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating contact person: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete contact person
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM contact_persons
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting contact person: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle contact person active status
     */
    public function toggleActive($id)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE contact_persons
                SET is_active = NOT is_active
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error toggling contact person status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active contact persons only
     */
    public function getActiveContactPersons()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform, contact_info, link_url, icon
                FROM contact_persons
                WHERE is_active = 1
                ORDER BY platform ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting active contact persons: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if platform already exists
     */
    public function platformExists($platform, $excludeId = null)
    {
        try {
            $sql = "
                SELECT COUNT(*) as count
                FROM contact_persons
                WHERE platform = :platform
            ";

            $params = ['platform' => $platform];

            if ($excludeId) {
                $sql .= " AND id != :id";
                $params['id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error checking platform: " . $e->getMessage());
            return false;
        }
    }
}
