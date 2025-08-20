<?php
// ... your existing functions ...

// Safe output
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

// Role guard helper
function requireLoginRole(string $role) {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== $role) {
        header("Location: /restaurant-ms/auth/login.php");
        exit;
    }
}
