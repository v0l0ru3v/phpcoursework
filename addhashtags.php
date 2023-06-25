<h2>Добавьте хештеги к сообщению</h2>
<form method='post'>
    <textarea name="hashtags" id="" cols="30" rows="10"></textarea>
    <input type="submit" value="Добавить">
</form>
<?php
include 'connect.php';
if (isset($_POST['hashtags'])) {
    $hashtags = $_POST['hashtags'];
    $sms_id = $_COOKIE['sms_id'];
    preg_match_all("/#([\p{Cyrillic}\w]+)/u", $hashtags, $matches);
    foreach ($matches[1] as $tag) {
        $hashtag = "INSERT INTO hashtag (name, sms_id) VALUES ('$tag', '$sms_id')";
        if ($conn->query($hashtag) === TRUE) {
            echo "Хештег добавлен успешно!";
        } else {
            echo "Error: " . $hashtag . "<br>" . $conn->error;
        }
    }
}
?>