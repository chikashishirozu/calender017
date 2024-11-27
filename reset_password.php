<?php
// reset_password.php

session_start();

// SQLite3データベースファイルのパスを指定
$dsn = 'sqlite:calendar.db';

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, null, null, $opt);

$email = $_POST['email'];
$newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

// メールアドレスに対応するユーザーのパスワードを更新
$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->bindValue(1, $newPassword, PDO::PARAM_STR);
$stmt->bindValue(2, $email, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo "パスワードがリセットされました。";
    header("Location: login.html");
    exit();
} else {
    echo "パスワードのリセットに失敗しました。";
}

// データベース接続を閉じる
$pdo = null;
?>

