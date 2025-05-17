<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($password)) {
        die("Lütfen tüm alanları doldurun.");
    }

    // Kullanıcıyı bul
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Giriş sonrası yönlendirilecek sayfa
            exit;
        } else {
            die("Şifre yanlış.");
        }
    } else {
        die("Kullanıcı bulunamadı.");
    }
} else {
    header("Location: index.html");
    exit;
}
?>
