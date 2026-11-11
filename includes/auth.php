<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

// Жабық беттерді қорғау
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Кірген адамды login/register беттеріне жібермеу
function redirect_if_logged_in(): void
{
    if (is_logged_in()) {
        header('Location: profile.php');
        exit;
    }
}
