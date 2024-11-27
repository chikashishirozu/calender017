<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="shortcut icon" href="./favicon.png">
    <title>メモ付きカレンダーアプリ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00bfff;
            margin-top: 20px;
        }
        h2 {
            margin: 0 0 15px 20px;
        }
        li a {
            margin: 0 ;
            font-weight: 600;
        }
        .header-menus {
            list-style-type: none;
        }
        .header-menus li {
            display: inline;
            margin-right: 10px;
        }
        .header-menus a {
            text-decoration: none;
        }
        #topimage01 {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <ul class="header-menus">
        <?php
        if (isset($_SESSION['user_id'])) {
            if (time() - $_SESSION['login_time'] <= 7200) { // 7200秒 = 2時間
                echo '<li><a href="./home.php">ホームページ</a></li>';
            }
            echo '<li><a href="./logout.php">ログアウト</a></li>';
        } else {
            echo '<li><a href="./login.html">ログイン</a></li>';
            echo '<li><a href="./register.html">新規登録</a></li>';
        }
        ?>
    </ul>
    <div class="top">
        <div class="wrapper">
            <div class="content">
                <h2>メモ付きカレンダーアプリ</h2>
            </div>
        </div>
        <img class="image" src="./Designer (5).jpeg" id="topimage01" alt="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>


