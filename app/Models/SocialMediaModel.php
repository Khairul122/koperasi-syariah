<?php

namespace App\Models;

use PDO;
use PDOException;

class SocialMediaModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Get all social media accounts
     */
    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform_name, account_name, profile_url, icon, is_active
                FROM social_medias
                ORDER BY id DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all social media: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get social media by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform_name, account_name, profile_url, icon, is_active
                FROM social_medias
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting social media by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new social media account
     */
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO social_medias (platform_name, account_name, profile_url, icon, is_active)
                VALUES (:platform_name, :account_name, :profile_url, :icon, :is_active)
            ");

            $params = [
                'platform_name' => $data['platform_name'],
                'account_name' => $data['account_name'] ?? null,
                'profile_url' => $data['profile_url'],
                'icon' => $data['icon'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error creating social media: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update social media account
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE social_medias
                SET platform_name = :platform_name,
                    account_name = :account_name,
                    profile_url = :profile_url,
                    is_active = :is_active
            ";

            $params = [
                'id' => $id,
                'platform_name' => $data['platform_name'],
                'account_name' => $data['account_name'] ?? null,
                'profile_url' => $data['profile_url'],
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
            error_log("Error updating social media: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete social media account
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM social_medias
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting social media: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle social media active status
     */
    public function toggleActive($id)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE social_medias
                SET is_active = NOT is_active
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error toggling social media status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active social media accounts only
     */
    public function getActiveSocialMedia()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform_name, account_name, profile_url, icon
                FROM social_medias
                WHERE is_active = 1
                ORDER BY platform_name ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting active social media: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if platform name already exists
     */
    public function platformExists($platformName, $excludeId = null)
    {
        try {
            $sql = "
                SELECT COUNT(*) as count
                FROM social_medias
                WHERE platform_name = :platform_name
            ";

            $params = ['platform_name' => $platformName];

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

    /**
     * Get social media by platform name
     */
    public function getByPlatform($platformName)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, platform_name, account_name, profile_url, icon, is_active
                FROM social_medias
                WHERE platform_name = :platform_name
                LIMIT 1
            ");
            $stmt->execute(['platform_name' => $platformName]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting social media by platform: " . $e->getMessage());
            return false;
        }
    }
}
