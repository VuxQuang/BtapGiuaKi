<?php
session_start();
$_SESSION["IsLogin"] = false;

$uri = "mysql://{$aivenUsername}:{$aivenPassword}@quang-1a-webadvanced1.l.aivencloud.com:22234/defaultdb?ssl-mode=REQUIRED";
$fields = parse_url($uri);

// build the DSN including SSL settings
$conn = "mysql:";
$conn .= "host=" . $fields["host"];
$conn .= ";port=" . $fields["port"];
$conn .= ";dbname=" . ltrim($fields["path"], '/');
$conn .= ";sslmode=verify-ca;sslrootcert=ca.pem";

try {
    $db = new PDO($conn, $fields["user"], $fields["pass"]);

    $user = $_POST['usernameForm'];
    $pass = $_POST['passwordForm']; // Sử dụng hàm sha1 để mã hóa mật khẩu

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$user, $pass]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION["IsLogin"] = true;
        header("Location: Logout.php");
        exit; 
    } else {
        // Nếu tài khoản không chính xác, chuyển hướng người dùng đến trang đăng nhập lại
        header("Location: login.htm");
        exit;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>