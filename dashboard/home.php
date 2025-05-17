<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: index.html");
  exit;
}
$conn = new mysqli("localhost", "root", "", "birdword");

// Postları çek
$posts = $conn->query("
  SELECT posts.content, posts.created_at, users.username 
  FROM posts 
  JOIN users ON posts.user_id = users.id 
  ORDER BY posts.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Birdword - Anasayfa</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header><h1>Birdword</h1></header>
  <nav>
    <div>
      <a href="home.php">Anasayfa</a>
      <a href="profile.php">Profilim</a>
      <a href="search.php">Kullanıcı Ara</a>
    </div>
    <a href="logout.php" class="logout">Çıkış</a>
  </nav>

  <div class="container">
    <form method="POST" action="create_post.php">
      <textarea name="content" placeholder="Ne düşünüyorsun?" required></textarea>
      <input type="submit" value="Paylaş">
    </form>

    <?php while ($row = $posts->fetch_assoc()): ?>
      <div class="post">
        <p><?= htmlspecialchars($row["content"]) ?></p>
        <div class="meta">@<?= htmlspecialchars($row["username"]) ?> - <?= $row["created_at"] ?></div>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
