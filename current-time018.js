// script.js
function updateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // 月は0から始まるので+1
    const date = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // 年から秒までのフォーマット
    const formattedTime = `${year}年${month}月${date}日 ${hours}時${minutes}分${seconds}秒`;

    // HTMLに反映
    document.getElementById('current-time').textContent = formattedTime;
}

// 1秒ごとに時間を更新
setInterval(updateTime, 1000);

// 初回実行
updateTime();

