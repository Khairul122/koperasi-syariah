<?php

namespace App\Models;

use PDO;
use PDOException;

class BannerModel
{
    private $db;

    public function __construct($database)
    {
        // Debug: Log what we received
        error_log("BannerModel initialized with: " . get_class($database));
        $this->db = $database;
    }

    /**
     * Get all banners
     */
    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, title, description, image_path, link_url, is_active, created_at
                FROM banners
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all banners: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get banner by ID
     */
    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, title, description, image_path, link_url, is_active, created_at
                FROM banners
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting banner by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new banner
     */
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO banners (title, description, image_path, link_url, is_active)
                VALUES (:title, :description, :image_path, :link_url, :is_active)
            ");

            $params = [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'image_path' => $data['image_path'],
                'link_url' => $data['link_url'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ];

            $result = $stmt->execute($params);

            if ($result) {
                $insertId = $this->db->lastInsertId();
                error_log("Banner created successfully with ID: " . $insertId);
            } else {
                error_log("Banner create execute() returned false. Error info: " . print_r($stmt->errorInfo(), true));
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error creating banner: " . $e->getMessage());
            error_log("PDO Error Code: " . $e->getCode());
            return false;
        }
    }

    /**
     * Update banner
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE banners
                SET title = :title,
                    description = :description,
                    link_url = :link_url,
                    is_active = :is_active
            ";

            $params = [
                'id' => $id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'link_url' => $data['link_url'] ?? null,
                'is_active' => $data['is_active'] ?? 1
            ];

            // Include image_path only if it's provided
            if (isset($data['image_path'])) {
                $sql .= ", image_path = :image_path";
                $params['image_path'] = $data['image_path'];
            }

            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating banner: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete banner
     */
    public function delete($id)
    {
        try {
            // Get banner data first to delete image file
            $stmt = $this->db->prepare("
                SELECT image_path
                FROM banners
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            $banner = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete image file if exists
            if ($banner && !empty($banner['image_path']) && file_exists($banner['image_path'])) {
                if (unlink($banner['image_path'])) {
                    error_log("Deleted banner image: " . $banner['image_path']);
                } else {
                    error_log("Failed to delete banner image: " . $banner['image_path']);
                }
            }

            // Delete from database
            $stmt = $this->db->prepare("
                DELETE FROM banners
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting banner: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle banner active status
     */
    public function toggleActive($id)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE banners
                SET is_active = NOT is_active
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error toggling banner status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active banners only
     */
    public function getActiveBanners()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, title, description, image_path, link_url
                FROM banners
                WHERE is_active = 1
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting active banners: " . $e->getMessage());
            return false;
        }
    }
}
