<?php
// SQLite3データベースへの接続
try {
    $pdo = new PDO('sqlite:./calendar.db'); // データベースパスを正しく設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '接続失敗: ' . $e->getMessage();
    exit();
}

// Memosテーブル作成（存在しない場合のみ）
$pdo->exec("CREATE TABLE IF NOT EXISTS Memos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL,
    memo TEXT,
    reminder TEXT,
    created_at TEXT DEFAULT (datetime('now', 'localtime'))
);");

// usersテーブル作成（存在しない場合のみ）
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    created_at TEXT DEFAULT (datetime('now', 'localtime'))
);");

echo "テーブルの作成が完了しました。";
?>



