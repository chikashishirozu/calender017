<?php 
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
} 
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.html"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./favicon.png">
    <title>メモ付きカレンダー</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6e6fa;
        }
        h1 {
            font-weight: 400;
        }
        h2 {
            font-weight: 400;
        }
        li a {
            text-decoration: none;
        }
        button {
            margin: 0 10px 10px 0;
        }
        #currentMonthYear {
            margin: 0 10px 10px 0;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            background-color: #e6e6fa;
        }
        .day {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background-color: #f0f0f0;
        }
        .day.selected {
            background-color: #f0f8ff;
        }
        .id, .memo, .reminder {
            margin-top: 20px;
        }
        .memo textarea, .reminder textarea {
            width: 100%;
            height: 100px;
        }
        .error {
            color: red;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
	.sunday {
	    color: red;
	}

	.saturday {
	    color: blue;
	}
	#current-time {
	    font-size: 20px;
	    font-weight: 500;
	    margin: 0 0 20px 0;
	    color: #333;
	    background-color: #eef;
	    padding: 10px;
	    border-radius: 5px;
	    display: inline-block;
	    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
	}	
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">トップページ</a></li>
                <li><a href="logout.php">ログアウト</a></li>
                <!-- li><a href="register.html">登録</a></li>
                <li><a href="calendar.html">カレンダー</a></li>
                <li><a href="memo_form.html">メモフォーム</a></li>
                <li><a href="reminder_form.html">リマインダーフォーム</a></li>
                <li><a href="search.html">検索</a></li>
                <li><a href="tag_management.html">タグマネージメント</a></li -->
            </ul>
        </nav>
    </header>
    <h1>メモ付きカレンダー</h1><div id="current-time"></div>
    <div>
        <button id="prevMonth">前の月</button>
        <span id="currentMonthYear"></span>
        <button id="nextMonth">次の月</button>
    </div>
    <div class="calendar" id="calendar"></div>
    <div class="id" id="id">
        <h2>ID</h2>    
        <ul id="idList"></ul> <!-- Added this line -->
    </div>    
    <div class="memo" id="memo">
        <h2>メモ</h2>
        <ul id="memoList"></ul> <!-- Added this line -->
    </div>
    <div class="reminder" id="reminder">
        <h2>リマインダー</h2>    
        <ul id="reminderList"></ul> <!-- Added this line -->
    </div>
    <div id="reminderModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalDate"></h2>
            <textarea id="idText" placeholder="idを入力してください" style="width:10%; height: 120px;"></textarea>
            <textarea id="memoText" placeholder="メモを入力してください" style="width:35%; height: 120px;"></textarea>
            <textarea id="modalReminderText" placeholder="リマインダーを入力してください" style="width:35%; height: 120px;"></textarea>
            <button id="saveModalReminder" style="margin: 10px; padding: 5px; border: none; border-radius: 3px; background-color: #b0c4de;">保存</button>
            <button id="editModalReminder" style="margin: 10px; padding: 5px; border: none; border-radius: 3px; background-color: #b0c4de;">編集</button>
            <button id="deleteModalReminder" style="margin: 10px; padding: 5px; border: none; border-radius: 3px; background-color: #b0c4de;">削除</button>
        </div>
    </div>
    <div id="result"></div>
    <div id="errorMessage" class="error"></div>
    <!-- script src="delete_record.js"></script -->
    <script src="current-time018.js"></script>
    <script src="script.js"></script>
</body>
</html>




