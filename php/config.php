<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/db_settings.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
$db_connection->set_charset('utf8mb4');
