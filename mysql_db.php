<?php
// db.php

class MySQLDatabase {
    private $host = 'localhost';
    private $db = 'calendar017';
    private $user = 'calendar_user';
    private $pass = 's~eyNgOpsQQ0(+';
    private $charset = 'utf8mb4';
    public $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            echo '接続失敗: ' . $e->getMessage();
            exit();
        }
    }

    private function createTables() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(255) NOT NULL UNIQUE,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ");
        
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS Memos (
                id INT PRIMARY KEY AUTO_INCREMENT,
                date TEXT NOT NULL,
                memo TEXT NOT NULL,
                reminder TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ");
    }
}

$mysql_db = new MySQLDatabase();
?>




