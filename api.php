<?php
// Connect to your database here
$db = new PDO('sqlite:calendar.db');

// Fetch data for a specific date
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $stmt = $db->prepare("SELECT id, memo, reminder FROM memos WHERE date = :date");
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data);
    exit();
}

// Save memo and reminder
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $date = $data['date'];
    $memo = $data['memo'];
    $reminder = $data['reminder'];

    $stmt = $db->prepare("REPLACE INTO memos (id, date, memo, reminder) VALUES (:id, :date, :memo, :reminder)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':memo', $memo);
    $stmt->bindParam(':reminder', $reminder);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit();
}

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $date = $data['date'];
        $memo = $data['memo'];
        $reminder = $data['reminder'];

        // データベース内の該当データを更新する処理
        $stmt = $db->prepare("UPDATE memos SET date = :date, memo = :memo, reminder = :reminder WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':memo', $memo, PDO::PARAM_STR);
        $stmt->bindParam(':reminder', $reminder, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'データ更新失敗: ' . $e->getMessage()]);
    }
    exit();
}


// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // バリデーション
        if (!isset($data['date']) || !isset($data['id'])) {
            throw new Exception("必要なデータが不足しています。");
        }

        $date = $data['date'];
        $id = $data['id'];

        // データベースから該当する日付とIDのデータを削除する処理
        $stmt = $db->prepare("DELETE FROM memos WHERE date = :date AND id = :id");
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'データ削除失敗: ' . $e->getMessage()]);
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

