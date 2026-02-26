<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$port = 3306;

$mysqli = new mysqli($host, $user, $pass, null, $port);
if ($mysqli->connect_errno) {
    fwrite(STDERR, 'MySQL connect error: ' . $mysqli->connect_error . PHP_EOL);
    exit(1);
}

if (!$mysqli->query('CREATE DATABASE IF NOT EXISTS adluma_backend')) {
    fwrite(STDERR, 'Create DB error: ' . $mysqli->error . PHP_EOL);
    exit(1);
}

echo "Database created or exists." . PHP_EOL;
