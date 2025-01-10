<?php
// データベース接続
try {
    $db = new PDO('sqlite:calendar.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続失敗: ' . $e->getMessage()]);
    exit();
}

// データ取得
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $stmt = $db->prepare("SELECT id, memo, reminder FROM memos WHERE date = :date");
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data);
    exit();
}

// データ保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
        exit();
    }

    if (!isset($data['id']) || !isset($data['date']) || !isset($data['memo']) || !isset($data['reminder'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit();
    }

    $id = $data['id'];
    $date = $data['date'];
    $memo = $data['memo'];
    $reminder = $data['reminder'];

    $stmt = $db->prepare("REPLACE INTO memos (id, date, memo, reminder) VALUES (:id, :date, :memo, :reminder)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
        exit();
    }

    try {
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':memo', $memo);
        $stmt->bindParam(':reminder', $reminder);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'クエリ実行失敗: ' . $e->getMessage()]);
    }
    exit();
}

// データ更新
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
        exit();
    }

    if (!isset($data['id']) || !isset($data['date']) || !isset($data['memo']) || !isset($data['reminder'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit();
    }

    $id = $data['id'];
    $date = $data['date'];
    $memo = $data['memo'];
    $reminder = $data['reminder'];

    $stmt = $db->prepare("UPDATE memos SET date = :date, memo = :memo, reminder = :reminder WHERE id = :id");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
        exit();
    }

    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':memo', $memo, PDO::PARAM_STR);
        $stmt->bindParam(':reminder', $reminder, PDO::PARAM_STR);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'クエリ実行失敗: ' . $e->getMessage()]);
    }
    exit();
}

// データ削除
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
        exit();
    }

    if (!isset($data['date']) || !isset($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit();
    }

    $date = $data['date'];
    $id = $data['id'];

    $stmt = $db->prepare("DELETE FROM memos WHERE date = :date AND id = :id");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
        exit();
    }

    try {
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'クエリ実行失敗: ' . $e->getMessage()]);
    }
    exit();
}

// 全メモとリマインダーを取得
if (isset($_GET['getAll'])) {
    try {
        $stmt = $db->query("SELECT id, date, memo, reminder FROM memos");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'memos' => $data]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'データ取得失敗: ' . $e->getMessage()]);
    }
    exit();
}
?>

