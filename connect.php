<?php
$servername = "std-mysql";
$username = "std_2083_kursach";
$password = "l3eVeG1ss5";
$dbname = "std_2083_kursach";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  echo die("Connection failed: " . $conn->connect_error);
}
?>