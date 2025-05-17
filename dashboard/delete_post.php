<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: index.html");
  exit;
}
$conn = new mysqli("localhost", "root", "", "birdword");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post_id"])) {
  $post_id = $_POST["post_id"];
  $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $post_id, $_SESSION["user_id"]);
  $stmt->execute();
}
header("Location: profile.php");
exit;
?>
