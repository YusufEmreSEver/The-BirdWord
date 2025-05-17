<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php'; // Veritabanı bağlantısını dahil et

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    // Boş alan kontrolü
    if (empty($username) || empty($email) || empty($password)) {
        die("Lütfen tüm alanları doldurun.");
    }

    // Kullanıcı adı veya e-posta zaten var mı kontrol et
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        die("Hazırlama hatası: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("Kullanıcı adı veya e-posta zaten kayıtlı.");
    }
    $stmt->close();

    // Şifreyi güvenli şekilde hashle
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Yeni kullanıcı ekle
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Hazırlama hatası: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        die("Kayıt yapılamadı, bir hata oluştu.");
    }
} else {
    header("Location: index.html");
    exit;
}
