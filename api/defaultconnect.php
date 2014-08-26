<?php

//N.B.**** CHANGE THESE AND SAVE AS "connect.php"

$mysqli = mysqli_connect("127.0.0.1", "connor", "sharkb8", "dodge");

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

?>