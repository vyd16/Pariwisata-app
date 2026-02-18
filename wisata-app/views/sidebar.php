<?php
/**
 * Admin Sidebar View
 */
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar Toggle Button (Mobile) -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= base_url() ?>" class="logo">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="TravelDNE Logo" class="logo-img">
            <span>TravelDNE</span>
        </a>
        <!-- Close button for mobile -->
        <button class="sidebar-close" id="sidebarClose" aria-label="Close sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('admin/') ?>"
                    class="<?= $currentDir === 'admin' && $currentPage === 'index' ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-home"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('paket/') ?>" class="<?= $currentDir === 'paket' ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-box"></i></span>
                    <span>Paket Wisata</span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('booking/') ?>" class="<?= $currentDir === 'booking' ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-calendar-check"></i></span>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('users/') ?>" class="<?= $currentDir === 'users' ? 'active' : '' ?>">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span>Pengguna</span>
                </a>
            </li>
        </ul>

        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">

        <ul class="sidebar-menu">
            <li>
                <!-- Theme Toggle Button -->
                <button class="theme-toggle sidebar-theme-toggle" id="themeToggle" aria-label="Toggle theme"
                    title="Toggle Dark/Light Mode"
                    style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: transparent; border: none; color: inherit; cursor: pointer; border-radius: var(--radius-md); transition: background 0.2s;">
                    <span class="icon">
                        <i class="fas fa-sun sun-icon"></i>
                        <i class="fas fa-moon moon-icon"></i>
                    </span>
                    <span>Mode Tema</span>
                </button>
            </li>
            <li>
                <a href="<?= base_url() ?>" target="_blank">
                    <span class="icon"><i class="fas fa-external-link-alt"></i></span>
                    <span>Lihat Website</span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('logout.php') ?>">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Info -->
    <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="d-flex align-center gap-1">
            <?php
            $currentAdminUser = getCurrentUser();
            $adminPhoto = $currentAdminUser && $currentAdminUser['foto'] ? base_url('uploads/users/' . $currentAdminUser['foto']) : 'https://ui-avatars.com/api/?name=' . urlencode($currentAdminUser['nama'] ?? 'Admin') . '&background=0d9488&color=fff';
            ?>
            <img src="<?= $adminPhoto ?>" alt="User" class="avatar">
            <div>
                <strong style="font-size: 0.9rem;">
                    <?= htmlspecialchars($currentAdminUser['nama'] ?? 'Admin') ?>
                </strong>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">
                    <?= $currentAdminUser['role'] ?? 'admin' ?>
                </p>
            </div>
        </div>
    </div>
</aside>