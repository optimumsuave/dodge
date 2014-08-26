<?php


$mysqli = mysqli_connect("127.0.0.1", "root", "alwaysm8", "dodgeball");

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

?>