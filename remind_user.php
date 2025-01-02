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

    if (!$email) {
        echo json_encode(['status' => 'error', 'message' => 'メールアドレスを入力してください。']);
        exit();
    }

    try {
        $stmt = $mysql_db->pdo->prepare('SELECT username FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 仮にメール送信処理を記述（ここは実装環境に依存）
            echo json_encode(['status' => 'success', 'message' => 'ユーザー名をメールで送信しました。']);
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


