<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_joki"; 
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Cek koneksi dulu tanpa database
            $testConn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $testConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Cek apakah database ada (gunakan backtick untuk nama dengan tanda hubung)
            $testConn->exec("USE `" . $this->database . "`");
            $testConn = null;

            // Jika database ada, buat koneksi dengan database
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->database . ";charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Set charset ke utf8mb4 untuk support emoji
            $this->conn->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

            error_log("Database connection successful: " . $this->database);

        } catch(PDOException $exception) {
            // Log error detail
            error_log("Database connection error: " . $exception->getMessage());
            error_log("Database: " . $this->database);
            error_log("Host: " . $this->host);

            // Return null jika gagal
            $this->conn = null;
        }

        return $this->conn;
    }

    /**
     * Cek apakah koneksi berhasil
     */
    public function isConnected() {
        return $this->conn !== null;
    }

    /**
     * Get database name
     */
    public function getDatabaseName() {
        return $this->database;
    }
}
?>