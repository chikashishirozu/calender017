<?php
require 'mysql_db.php';

// $mysql_db インスタンスの確認 
if (!isset($mysql_db) || !isset($mysql_db->pdo)) { 
    echo json_encode(['status' => 'error', 'message' => 'データベース接続インスタンスが未定義です。']); 
    exit(); 
}

header('Content-Type: application/json; charset=utf-8');
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$username || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'すべてのフィールドを入力してください。']);
        exit();
    }

    try {
        $stmt = $mysql_db->pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(['status' => 'error', 'message' => 'このユーザー名またはメールアドレスは既に使用されています。']);
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysql_db->pdo->prepare('INSERT INTO users (email, username, password) VALUES (?, ?, ?)');
        if ($stmt->execute([$email, $username, $hashedPassword])) {
            $_SESSION['user_id'] = $mysql_db->pdo->lastInsertId();
            $_SESSION['username'] = $username;
            echo json_encode(['status' => 'success', 'message' => '登録が完了しました。ログインページへ移動してください。']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ユーザー登録に失敗しました。']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'システムエラー: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'システムエラー: ' . $e->getMessage()]);
    }
}
?>




