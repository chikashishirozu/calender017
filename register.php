<?php
// db.php をインポート
require 'mysql_db.php';

session_start();

$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// ユーザー名またはメールアドレスが既に存在するか確認
$stmt = $db->pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
$stmt->bindValue(1, $username, PDO::PARAM_STR);
$stmt->bindValue(2, $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    // ユーザー名またはメールアドレスが既に存在する場合
    echo "このユーザー名またはメールアドレスは既に使用されています。別のユーザー名またはメールアドレスを選んでください。";
} else {
    // ユーザー名またはメールアドレスが存在しない場合のみ登録を実行
    $stmt = $db->pdo->prepare('INSERT INTO users (email, username, password) VALUES (?, ?, ?)');
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->bindValue(2, $username, PDO::PARAM_STR);
    $stmt->bindValue(3, $password, PDO::PARAM_STR);

    $result = $stmt->execute();

    if ($result) {
        // 登録が成功した場合、ログインページにリダイレクト
        header("Location: login.html");
        exit();
    } else {
        echo "ユーザー登録に失敗しました: " . $stmt->errorInfo()[2];
    }
}
?>


