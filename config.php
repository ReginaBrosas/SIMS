<?php
// Database configuration and connection setup

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sims - brosas');

// Connect to database using mysqli
function db_connect() {
    static $mysqli = null;
    if ($mysqli === null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }
        $mysqli->set_charset("utf8mb4");
    }
    return $mysqli;
}

