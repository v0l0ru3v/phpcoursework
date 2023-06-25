<form action="" class="form-add" method="post">
    <div class="add">
        <label for="tag">Хэштег</label>
        <input type="text" name="tag" id="tag">
        <label for="field">Область знаний</label>
        <input type="text" name="field" id="field">
        <button type="submit" class="submit">Добавить связь</button>
    </div>
</form>

<?php
include 'connect.php';

if (!empty($_POST)) {
    // Получаем введенные данные из формы
    $tag = $_POST["tag"];
    $field = $_POST["field"];

    $tagResult = $conn->query("SELECT * FROM hashtag WHERE name = '$tag'");
    $fieldResult = $conn->query("SELECT * FROM field WHERE name = '$field'");

    if ($tagResult->num_rows == 0 || $fieldResult->num_rows == 0) {
        echo "Такой хэштег или область знаний не существует.";
    } else {
        $tagRow = $tagResult->fetch_assoc();
        $tagId = $tagRow["id"];

        $fieldRow = $fieldResult->fetch_assoc();
        $fieldId = $fieldRow["id"];

        $linkResult = $conn->query("SELECT * FROM tagtofield WHERE id_tag = '$tagId' AND id_field = '$fieldId'");

        if ($linkResult->num_rows > 0) {
            echo "Данная связь уже существует.";
        } else {
            $sql = "INSERT INTO tagtofield (id_tag, id_field) VALUES ('$tagId', '$fieldId')";
            if ($conn->query($sql) === TRUE) {
                echo "Связь добавлена успешно!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
}
?>
