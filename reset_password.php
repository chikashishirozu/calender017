<?php
require 'mysql_db.php';

// $mysql_db インスタンスの確認 
if (!isset($mysql_db->pdo)) { 
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
    $newPassword = $_POST['password'] ?? '';

    if (!$email || !$newPassword) {
        echo json_encode(['status' => 'error', 'message' => 'メールアドレスと新しいパスワードを入力してください。']);
        exit();
    }

    try {    
        $stmt = $mysql_db->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $mysql_db->pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            if ($stmt->execute([$hashedPassword, $email])) {
                echo json_encode(['status' => 'success', 'message' => 'パスワードがリセットされました。']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'パスワードリセットに失敗しました。']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => '該当するメールアドレスが見つかりません。']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'システムエラー: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>



