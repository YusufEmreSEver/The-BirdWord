<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: index.html");
  exit;
}
$conn = new mysqli("localhost", "root", "", "birdword");

$results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["query"])) {
  $query = '%' . $_POST["query"] . '%';
  $stmt = $conn->prepare("SELECT username FROM users WHERE username LIKE ? LIMIT 10");
  $stmt->bind_param("s", $query);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $results[] = $row["username"];
  }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kullanıcı Ara</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header><h1>Kullanıcı Ara</h1></header>
  <nav>
    <div>
      <a href="home.php">Anasayfa</a>
      <a href="profile.php">Profilim</a>
      <a href="search.php">Kullanıcı Ara</a>
    </div>
    <a href="logout.php" class="logout">Çıkış</a>
  </nav>

  <div class="container">
    <form method="POST">
      <input type="text" name="query" placeholder="Kullanıcı adı ara" required>
      <input type="submit" value="Ara">
    </form>

    <?php if (!empty($results)): ?>
      <ul>
        <?php foreach ($results as $username): ?>
          <li>@<?= htmlspecialchars($username) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
      <p>Hiç kullanıcı bulunamadı.</p>
    <?php endif; ?>
  </div>
</body>
</html>
