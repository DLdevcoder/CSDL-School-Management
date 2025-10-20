<?php
$db['db_host'] = 'localhost';
$db['db_user'] = 'root';
$db['db_password'] = '';
$db['db_name'] = 'school';

foreach($db as $key => $value) {
    define(strtoupper($key), $value);
}

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$con) {
    error_log('MySQL connect error: ' . mysqli_connect_error());
    die('Database connection error.'); // hoặc throw exception trong dev
}
mysqli_set_charset($con, 'utf8mb4');
// ...existing code...
?>