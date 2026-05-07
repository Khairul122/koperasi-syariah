<?php

namespace App\Models;

use PDO;
use PDOException;

class AuthModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error finding user by email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find user by ID
     */
    public function findById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Register new user with SHA-256 HMAC
     * Note: Hash and salt are stored together in password column (format: hash:salt)
     */
    public function register($data)
    {
        try {
            // Generate random salt (32 bytes = 64 hex characters)
            $salt = bin2hex(random_bytes(32));

            // Hash password using SHA-256 HMAC
            $hashedPassword = hash_hmac('sha256', $data['password'], $salt);

            // Store hash and salt together in password column (format: hash:salt)
            $passwordWithSalt = $hashedPassword . ':' . $salt;

            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, phone_number, created_at)
                VALUES (:name, :email, :password, :role, :phone_number, NOW())
            ");

            $result = $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $passwordWithSalt,
                'role' => $data['role'] ?? 'client',
                'phone_number' => $data['phone_number'] ?? null
            ]);

            if ($result) {
                return $this->db->lastInsertId();
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error registering user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Login user with SHA-256 HMAC verification
     * Note: Hash and salt are retrieved from password column (format: hash:salt)
     */
    public function login($email, $password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifikasi password dengan SHA-256 HMAC
            if ($user && isset($user['password'])) {
                // Parse hash and salt from password column (format: hash:salt)
                $parts = explode(':', $user['password']);

                if (count($parts) === 2) {
                    list($storedHash, $salt) = $parts;

                    // Compute hash dengan salt yang tersimpan
                    $hashedInput = hash_hmac('sha256', $password, $salt);

                    // Use timing-safe comparison to prevent timing attacks
                    if (hash_equals($storedHash, $hashedInput)) {
                        // Remove password from returned data for security
                        unset($user['password']);
                        return $user;
                    }
                }
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error during login: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update last login time
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
     * Check if email exists
     */
    public function emailExists($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error checking email existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user count by role
     */
    public function getUserCountByRole($role = null)
    {
        try {
            if ($role) {
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE role = :role");
                $stmt->execute(['role' => $role]);
            } else {
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users");
                $stmt->execute();
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error getting user count: " . $e->getMessage());
            return 0;
        }
    }
}
