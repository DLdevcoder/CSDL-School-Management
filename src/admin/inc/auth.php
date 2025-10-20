<?php
function requireAdmin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user_name'])) {
        header('Location: ' . BASE_URL . '/src/admin/login.php');
        exit;
    }
}