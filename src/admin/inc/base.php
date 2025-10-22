<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
if (!defined('BASE_URL')) {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // e.g. /CSDL-School-Management/src/admin/index.php
    $parts = explode('/src/', $script, 2);
    $base = isset($parts[0]) ? rtrim($parts[0], '/') : '';
    if ($base === '/') $base = '';
    define('BASE_URL', $base);
}
?>