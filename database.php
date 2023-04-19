<?php
$ip = "localhost";
$user = "userName";
$password = "password";
$db = "databaseName";
try {
    $database = new PDO("mysql:host=$ip;dbname=$db", $user, $password);
    $database->exec("SET CHARSET UTF8");
} catch (PDOException $e) {
    die("Hata var");
}
