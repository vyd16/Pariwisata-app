<?php
/**
 * Header View - Navbar & CSS Links
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/functions.php';

$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Jasa Pariwisata Terpercaya - Nikmati pengalaman liburan tak terlupakan bersama kami">
    <title>
        <?= $pageTitle ?? 'TravelDNE - Jasa Pariwisata' ?>
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>?v=<?= time() ?>">

    <!-- Theme Initializer (prevents flash of wrong theme) -->
    <script>
        (function () {
            var theme = localStorage.getItem('wisata-theme');
            if (theme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            } else if (!theme && window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="<?= base_url() ?>" class="logo">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="TravelDNE Logo" class="logo-img">
                <span>TravelDNE</span>
            </a>

            <ul class="nav-menu">
                <li>
                    <a href="<?= base_url('index.php') ?>" class="<?= $currentPage === 'index' ? 'active' : '' ?>">
                        Beranda
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('paket.php') ?>" class="<?= $currentPage === 'paket' ? 'active' : '' ?>">
                        Paket Wisata
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('tentang.php') ?>" class="<?= $currentPage === 'tentang' ? 'active' : '' ?>">
                        Tentang
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('kontak.php') ?>" class="<?= $currentPage === 'kontak' ? 'active' : '' ?>">
                        Kontak
                    </a>
                </li>

                <?php if (isLoggedIn()): ?>
                    <li>
                        <a href="<?= base_url('booking/') ?>"
                            class="<?= strpos($_SERVER['REQUEST_URI'], 'booking') !== false ? 'active' : '' ?>">
                            Pesanan Saya
                        </a>
                    </li>
                <?php endif; ?>
            </ul>


            <div class="nav-actions">
                <!-- Theme Toggle Button -->
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme" title="Toggle Dark/Light Mode">
                    <i class="fas fa-sun sun-icon"></i>
                    <i class="fas fa-moon moon-icon"></i>
                </button>

                <?php if (isLoggedIn()): ?>
                    <div class="d-flex align-center gap-1">
                        <?php if (isAdmin()): ?>
                            <a href="<?= base_url('admin/') ?>" class="btn btn-ghost btn-sm">
                                <i class="fas fa-cog"></i> <span class="btn-text">Dashboard</span>
                            </a>
                        <?php endif; ?>
                        <a href="<?= base_url('profile.php') ?>" class="btn btn-ghost btn-sm">
                            <i class="fas fa-user"></i> <span class="btn-text">Profil</span>
                        </a>
                        <a href="<?= base_url('logout.php') ?>" class="btn btn-outline btn-sm">
                            <i class="fas fa-sign-out-alt"></i> <span class="btn-text">Logout</span>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('login.php') ?>" class="btn btn-ghost btn-sm">
                        <i class="fas fa-sign-in-alt"></i> <span class="btn-text">Masuk</span>
                    </a>
                    <a href="<?= base_url('register.php') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus"></i> <span class="btn-text">Daftar</span>
                    </a>
                <?php endif; ?>
            </div>

            <button class="menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>