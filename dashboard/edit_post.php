<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: index.html");
  exit;
}
$conn = new mysqli("localhost", "root", "", "birdword");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post_id"])) {
  $post_id = $_POST["post_id"];

  // Güncelleme işlemi
  if (isset($_POST["updated_content"])) {
    $updated = $_POST["updated_content"];
    $stmt = $conn->prepare("UPDATE posts SET content = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $updated, $post_id, $_SESSION["user_id"]);
    $stmt->execute();
    header("Location: profile.php");
    exit;
  }

  // Eski postu getir
  $stmt = $conn->prepare("SELECT content FROM posts WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $post_id, $_SESSION["user_id"]);
  $stmt->execute();
  $stmt->bind_result($content);
  $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Post Düzenle</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header><h1>Post Düzenle</h1></header>
  <div class="container">
    <form method="POST">
      <textarea name="updated_content" required><?= htmlspecialchars($content) ?></textarea>
      <input type="hidden" name="post_id" value="<?= $post_id ?>">
      <input type="submit" value="Güncelle">
    </form>
  </div>
</body>
</html>
