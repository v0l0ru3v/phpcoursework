<?php
include 'connect.php';
session_start();
if (isset($_POST['saveflag'])) {
    $user_id = $_SESSION['user_id'];
    foreach ($_POST['saveflag'] as $value) {
        $save_query = "INSERT INTO likedsms (sms, user_id) VALUES ('$value', '$user_id')";
        $conn->query($save_query);
        echo "Record inserted successfully";

    }
}
if (isset($_POST['hashtag'])) {
    $hashtag = $_POST['hashtag'];
    $hashtag_query = "SELECT name FROM hashtag WHERE name = '$hashtag'";
    $hashtag_result = $conn->query($hashtag_query);

    if ($hashtag_result->num_rows > 0) {
        $hashtag_row = $hashtag_result->fetch_assoc();
        $hashtag_name = $hashtag_row['name'];

        $message_query = "SELECT sms.message, channel.name , users.login, sms.id FROM sms JOIN hashtag ON sms.id=hashtag.sms_id JOIN users ON sms.user_id=users.id JOIN channel ON channel.id = sms.channel_id WHERE hashtag.name = '$hashtag_name'";
        $message_result = $conn->query($message_query);

        if ($message_result->num_rows > 0) {
            echo "<h2>Сообщения с хештегом #$hashtag:</h2>";
            echo '<form action="" method="post">';
            echo "<ul>";
            while ($message_row = $message_result->fetch_assoc()) {
                echo "<li>" . $message_row['name'] . ' ' . $message_row['login'] . ": " . $message_row['message'] . "<input type='checkbox' name='saveflag[]' value='" . $message_row['message'] . "'></li>";
            }
            echo "</ul>";
            echo '<input type="submit" value="добавить в избранное">';
            echo '</form>';
        } else {
            echo "Нет сообщений для выбранного хештега";
        }
    } else {
        echo "Хештег не найден";
    }
}

$conn->close();
?>

<h2>Введите хештег:</h2>
<form action="" method="post">
    <label for="hashtag">Хештег:</label>
    <input type="text" name="hashtag" id="hashtag">
    <button type="submit">Найти</button>
</form>