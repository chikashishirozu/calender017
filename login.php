<?php
// login.php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

// セッションを開始（すでに開始されている場合はスキップ）
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "Starting session...<br>";

// セッションが開始してからの経過時間をチェック 
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 7200)) {
    // セッションタイムアウトの場合 
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}

// セッションの最終アクセス時間を更新 
$_SESSION['login_time'] = time();

// db.php をインポート
require 'mysql_db.php';

$email = $_POST['email'];
$password = $_POST['password'];

echo "Provided email: " . $email . "<br>"; 
echo "Provided password: " . $password . "<br>";

$stmt = $mysql_db->pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->bindValue(1, $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User found in database.<br>";
    if (password_verify($password, $user['password'])) {
        echo "Password verified.<br>";
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_time'] = time();
        header("Location: home.php");
        exit();
    } else {
        echo "Password verification failed.<br>";
    }
} else {
    echo "User not found in database.<br>";
}

echo "メールアドレスまたはパスワードが正しくありません。";
?>






