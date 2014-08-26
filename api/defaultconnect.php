<?php


$mysqli = mysqli_connect("127.0.0.1", "username", "password", "dodgeball");

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

?>