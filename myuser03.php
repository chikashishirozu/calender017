<?php
// db.php をインポート
require 'mysql_db.php';

$email = 'alice367@gmail.com';
$username = 'alice367';
$password = password_hash('em3nohwQlg.r`H', PASSWORD_DEFAULT);

$stmt = $mysql_db->pdo->prepare('INSERT INTO users (email, username, password) VALUES (?, ?, ?)');
$stmt->bindValue(1, $email, PDO::PARAM_STR);
$stmt->bindValue(2, $username, PDO::PARAM_STR);
$stmt->bindValue(3, $password, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo "ユーザーが正常に追加されました。";
} else {
    echo "ユーザー追加に失敗しました: " . $stmt->errorInfo()[2];
}
?>

