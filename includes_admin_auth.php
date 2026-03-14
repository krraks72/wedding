<?php

declare(strict_types=1);

function adminSessionStart(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function adminCredentials(): array
{
    $username = getenv('ADMIN_USER') ?: 'admin';
    $password = getenv('ADMIN_PASS') ?: 'admin123';

    return [$username, $password];
}

function isAdminAuthenticated(): bool
{
    adminSessionStart();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminAuth(): void
{
    if (!isAdminAuthenticated()) {
        header('Location: admin-content.php?login=1');
        exit;
    }
}
