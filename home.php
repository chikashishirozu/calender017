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
        /* ===== テーマ変数 =====
           data-theme="light"（通常） / "soft"（中間・目に優しい） / "dark"（ダーク）
           の3段階を body の data-theme 属性で切り替える */
        :root {
            --bg: #e6e6fa;
            --text: #222;
            --header-bg: #eef;
            --header-text: #333;
            --cell-bg: #f0f0f0;
            --cell-border: #ddd;
            --cell-hover: #e0e0e0;
            --selected-bg: #2196f3;
            --selected-text: #fff;
            --today-bg: #ffeb3b;
            --modal-overlay: rgba(0,0,0,0.4);
            --modal-bg: #fefefe;
            --modal-border: #888;
            --button-bg: #b0c4de;
            --button-text: #222;
            --sunday: #e53935;
            --saturday: #1e88e5;
            --link: #333;
        }
        body[data-theme="soft"] {
            /* 眩しすぎない、明暗の中間くらいのトーン */
            --bg: #cfcfd6;
            --text: #2b2b2f;
            --header-bg: #b8b8c2;
            --header-text: #2b2b2f;
            --cell-bg: #bdbdc6;
            --cell-border: #9a9aa4;
            --cell-hover: #a9a9b3;
            --selected-bg: #5c7ea3;
            --selected-text: #f2f2f2;
            --today-bg: #c9b458;
            --modal-overlay: rgba(0,0,0,0.5);
            --modal-bg: #d6d6dd;
            --modal-border: #8c8c96;
            --button-bg: #8fa6bd;
            --button-text: #1e1e22;
            --sunday: #c0625e;
            --saturday: #5a7fa6;
            --link: #2b2b2f;
        }
        body[data-theme="dark"] {
            --bg: #1e1e24;
            --text: #e6e6e6;
            --header-bg: #2a2a33;
            --header-text: #e6e6e6;
            --cell-bg: #2c2c35;
            --cell-border: #444;
            --cell-hover: #3a3a45;
            --selected-bg: #3f6ea5;
            --selected-text: #fff;
            --today-bg: #7a6a1e;
            --modal-overlay: rgba(0,0,0,0.7);
            --modal-bg: #26262e;
            --modal-border: #555;
            --button-bg: #45566b;
            --button-text: #f0f0f0;
            --sunday: #ef5350;
            --saturday: #64b5f6;
            --link: #e6e6e6;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        h1 {
            font-weight: 400;
        }
        h2 {
            font-weight: 400;
        }
        li a {
            text-decoration: none;
            color: var(--link);
        }
        button {
            margin: 0 10px 10px 0;
        }
        #themeToggle {
            background-color: var(--button-bg);
            color: var(--button-text);
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
        }
        #currentMonthYear {
            margin: 0 10px 10px 0;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            background-color: var(--bg);
        }
        .day {
            border: 1px solid var(--cell-border);
            padding: 10px;
            text-align: center;
            background-color: var(--cell-bg);
            color: var(--text);
        }
        .day.selected {
            background-color: var(--selected-bg);
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
            background-color: var(--modal-overlay);
            padding-top: 60px;
        }
        .modal-content {
            background-color: var(--modal-bg);
            color: var(--text);
            margin: 5% auto;
            padding: 20px;
            border: 1px solid var(--modal-border);
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
	    color: var(--sunday);
	}

	.saturday {
	    color: var(--saturday);
	}
	#current-time {
	    font-size: 20px;
	    font-weight: 400;
	    margin: 0 0 20px 0;
	    color: var(--header-text);
	    background-color: var(--header-bg);
	    padding: 5px 10px;
	    border-radius: 5px;
	    display: inline-block;
	    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
	}	
 /* 今日の日付のハイライト */
 .day.today {
     background-color: var(--today-bg);
     font-weight: bold;
     box-shadow: 0 2px 4px rgba(0,0,0,0.2);
 }

 /* 選択された日付のスタイル改善 */
 .day.selected {
     background-color: var(--selected-bg);
     color: var(--selected-text);
     font-weight: bold;
 }

 /* 日付セルのホバー効果 */
 .day:not(.header):hover {
     background-color: var(--cell-hover);
     cursor: pointer;
     transform: scale(1.05);
     transition: all 0.2s ease;
 }

 /* 空のセルには効果を適用しない */
 .day:empty:hover {
     background-color: var(--cell-bg);
     cursor: default;
     transform: none;
 }

 /* モーダルの改善 */
 .modal-content {
     border-radius: 10px;
     box-shadow: 0 4px 20px rgba(0,0,0,0.3);
 }

 /* ボタンのホバー効果 */
 button:hover {
     opacity: 0.8;
     cursor: pointer;
 }	
    </style>
    <script>
        // 表示直後にちらつかないよう、保存済みテーマを先に適用しておく
        (function () {
            var saved = localStorage.getItem('calendarTheme');
            document.documentElement.dataset.themePreload = saved || 'light';
        })();
    </script>
</head>
<body data-theme="light">
    <header>
        <div>
            <button id="themeToggle" type="button">🌓 テーマ切替（通常）</button>
        </div>
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




