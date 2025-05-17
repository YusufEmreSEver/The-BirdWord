<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: index.html");
  exit;
}
$conn = new mysqli("localhost", "root", "", "birdword");

$user_id = $_SESSION["user_id"];
$result = $conn->prepare("SELECT id, content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$posts = $result->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Profilim - Birdword</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header><h1>Profilim (@<?= $_SESSION["username"] ?>)</h1></header>
  <nav>
    <div>
      <a href="home.php">Anasayfa</a>
      <a href="profile.php">Profilim</a>
      <a href="search.php">Kullanıcı Ara</a>
    </div>
    <a href="logout.php" class="logout">Çıkış</a>
  </nav>

  <div class="container">
    <h2>Paylaşımlarım</h2>

    <?php while ($row = $posts->fetch_assoc()): ?>
      <div class="post">
        <p><?= htmlspecialchars($row["content"]) ?></p>
        <div class="meta"><?= $row["created_at"] ?></div>
        <form method="POST" action="edit_post.php" style="display:inline;">
          <input type="hidden" name="post_id" value="<?= $row["id"] ?>">
          <input type="submit" value="Düzenle">
        </form>
        <form method="POST" action="delete_post.php" style="display:inline;" onsubmit="return confirm('Emin misin?');">
          <input type="hidden" name="post_id" value="<?= $row["id"] ?>">
          <input type="submit" value="Sil">
        </form>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
