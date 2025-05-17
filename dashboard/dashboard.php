<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}

// Kullanıcı bilgisi
$username = $_SESSION['username'];

// Kullanıcı id'sini al
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Paylaşımları çek
$sql = "SELECT posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - BirdWord</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff8f0;
      margin: 0; padding: 0;
      color: #333;
    }
    header {
      background-color: #ff6600;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h2 {
      margin: 0;
      font-weight: normal;
    }
    a.logout {
      background: white;
      color: #ff6600;
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
      transition: background 0.3s;
    }
    a.logout:hover {
      background: #ffe6cc;
    }
    main {
      max-width: 700px;
      margin: 30px auto;
      padding: 0 15px;
    }
    form textarea {
      width: 100%;
      font-size: 16px;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      resize: vertical;
    }
    form button {
      margin-top: 10px;
      background-color: #ff6600;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }
    form button:hover {
      background-color: #e65c00;
    }
    .post {
      background: white;
      border-radius: 6px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      padding: 15px;
      margin-bottom: 20px;
    }
    .post-header {
      font-weight: bold;
      color: #ff6600;
      margin-bottom: 5px;
    }
    .post-date {
      color: #999;
      font-size: 12px;
      margin-left: 8px;
      font-weight: normal;
    }
  </style>
</head>
<body>

<header>
  <h2>Hoşgeldin, <?php echo htmlspecialchars($username); ?></h2>
  <a href="logout.php" class="logout">Çıkış Yap</a>
</header>

<main>
  <section class="new-post">
    <h3>Yeni Paylaşım</h3>
    <form method="POST" action="post_create.php">
      <textarea name="post_content" rows="4" placeholder="Ne düşünüyorsun?"></textarea><br>
      <button type="submit">Paylaş</button>
    </form>
  </section>

  <section class="posts-list">
    <h3>Paylaşımlar</h3>
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<div class='post-header'>" . htmlspecialchars($row['username']) . 
                 "<span class='post-date'>" . $row['created_at'] . "</span></div>";
            echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Henüz paylaşım yok.</p>";
    }
    ?>
  </section>
</main>
<p>©2025 Yusuf Emre Sever</p>
</body>
</html>
