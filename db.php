<?php
function db_connect() {
    $host = 'localhost';
    $user = 'root';
    $pass = ''; // default XAMPP password is empty
    $dbname = 'sims - brosas';

    $mysqli = new mysqli($host, $user, $pass, $dbname);

    if ($mysqli->connect_error) {
        die('Database connection failed: ' . $mysqli->connect_error);
    }

    return $mysqli;
}
?>
