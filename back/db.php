<?php
$host = 'localhost';
$db = 'reseaux_sociaux';
$user = 'userMysql';
$pass = 'AZertyuiop123@@@!';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
