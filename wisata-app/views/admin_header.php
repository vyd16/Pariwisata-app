<?php
/**
 * Admin Header View
 * Include this at the start of all admin pages for consistent styling and theme support
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/functions.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $pageTitle ?? 'Admin - TravelDNE' ?>
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Theme Initializer (prevents flash of wrong theme) -->
    <script>
        (function () {
            var theme = localStorage.getItem('wisata-theme');
            if (theme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            } else if (theme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
            } else {
                // Auto-detect from device preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            }
        })();
    </script>
</head>

<body>
    <div class="admin-layout">
        <?php include __DIR__ . '/sidebar.php'; ?>

        <main class="main-content">