<form method="post">
  <label for="channel">Канал отправки:</label>
  <input type="text" id="channel" name="channel">
  <label for="message">Сообщение:</label>
  <textarea id="message" name="message"></textarea>
  <input type="submit" value="Отправить">
</form>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ?menu=signin');
  exit;
}
if (isset($_POST['channel']) && isset($_POST['message'])) {

$channel = $_POST['channel'];
$message = $_POST['message'];

include 'connect.php';

$channel_id = 0;
$result = $conn->query("SELECT id FROM channel WHERE name='$channel'");
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $channel_id = $row['id'];
} else {
  $stmt = $conn->prepare("INSERT INTO channel (name) VALUES (?)");
  $stmt->bind_param("s", $channel);
  $stmt->execute();
  $channel_id = $conn->insert_id;
  $stmt->close();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO sms (user_id, channel_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $channel_id, $message);
$stmt->execute();
$sms_id = $conn->insert_id;
$stmt->close();

preg_match_all("/#([\p{Cyrillic}\w]+)/u", $message, $matches);
if (empty($matches[1])) {
  setcookie('sms_id',$sms_id);
  header('Location: ./?menu=addhashtags');
} else {
foreach ($matches[1] as $tag) {
  $stmt = $conn->prepare("INSERT INTO hashtag (name, sms_id) VALUES (?, ?)");
  $stmt->bind_param("si", $tag, $sms_id);
  $stmt->execute();
  $stmt->close();
}
}
$conn->close();
}
?>