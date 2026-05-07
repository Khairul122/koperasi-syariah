<?php

namespace App\Models;

use PDO;
use PDOException;
use DateTime;
use Exception;

class DashboardModel
{
    public $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        try {
            $stats = [];

            // Total users
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_users'] = $result['total'];

            // Users by role
            $stmt = $this->db->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            $stmt->execute();
            $roleCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($roleCounts as $row) {
                $stats[$row['role'] . '_count'] = $row['count'];
            }

            // New users this month
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count
                FROM users
                WHERE MONTH(created_at) = MONTH(CURDATE())
                AND YEAR(created_at) = YEAR(CURDATE())
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['new_this_month'] = $result['count'];

            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting user stats: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recent users
     */
    public function getRecentUsers($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, role, phone_number, created_at
                FROM users
                ORDER BY created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting recent users: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user by ID
     */
    public function getUserById($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update last login
     */
    public function updateLastLogin($userId)
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET updated_at = NOW() WHERE id = :id");
            return $stmt->execute(['id' => $userId]);
        } catch (PDOException $e) {
            error_log("Error updating last login: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get dashboard summary for admin
     */
    public function getAdminSummary()
    {
        try {
            $summary = [];

            // Get user stats
            $summary['users'] = $this->getUserStats();

            // Get recent users
            $summary['recent_users'] = $this->getRecentUsers(10);

            // System info
            $summary['system'] = [
                'php_version' => phpversion(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'server_time' => date('Y-m-d H:i:s')
            ];

            return $summary;
        } catch (Exception $e) {
            error_log("Error getting admin summary: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get dashboard summary for client
     */
    public function getClientSummary($userId)
    {
        try {
            $summary = [];

            // Get user info
            $summary['user'] = $this->getUserById($userId);

            // Get account age
            if ($summary['user']) {
                $createdDate = new DateTime($summary['user']['created_at']);
                $now = new DateTime();
                $interval = $createdDate->diff($now);
                $summary['account_age'] = $this->formatInterval($interval);
            }

            // System info
            $summary['system'] = [
                'server_time' => date('Y-m-d H:i:s'),
                'timezone' => date_default_timezone_get()
            ];

            return $summary;
        } catch (Exception $e) {
            error_log("Error getting client summary: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format date interval for human reading
     */
    private function formatInterval($interval)
    {
        if ($interval->y > 0) {
            return $interval->y . ' tahun ' . $interval->m . ' bulan';
        } elseif ($interval->m > 0) {
            return $interval->m . ' bulan ' . $interval->d . ' hari';
        } elseif ($interval->d > 0) {
            return $interval->d . ' hari';
        } elseif ($interval->h > 0) {
            return $interval->h . ' jam';
        } else {
            return 'Baru saja';
        }
    }
}
