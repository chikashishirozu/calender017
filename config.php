<?php
// 開発・本番切り替え
$env = getenv('APP_ENV') ?: 'production';

if ($env === 'development') {
    session_save_path(__DIR__ . '/sessions');
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

