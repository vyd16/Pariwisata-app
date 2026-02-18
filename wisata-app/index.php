<?php
/**
 * Landing Page - TravelDNE
 */
$pageTitle = 'TravelDNE - Jasa Pariwisata Terpercaya';
require_once 'views/header.php';
?>

<!-- HERO SECTION -->
<section class="hero">
    <!-- Background -->
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80" alt="Beach">
    </div>

    <div class="container">
        <div class="hero-content animate-fade-in-up">
            <span class="hero-badge">Jelajahi Indonesia bersama kami</span>

            <h1>
                Temukan <span class="text-gradient">Petualangan</span><br>
                Impian Anda
            </h1>

            <p>
                Nikmati pengalaman liburan tak terlupakan dengan berbagai pilihan
                paket wisata eksotis. Kami siap membawa Anda ke destinasi terbaik Indonesia.
            </p>

            <div class="hero-actions">
                <a href="paket.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-compass"></i> Lihat Paket
                </a>
                <a href="kontak.php" class="btn btn-ghost btn-lg">
                    Hubungi Kami
                </a>
            </div>

            <!-- STATS -->
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Destinasi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Pelanggan Puas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Paket Wisata</div>
                </div>
            </div>
        </div>

        <!-- FLOATING CARDS -->
        <div class="floating-cards">
            <div class="float-card">
                <div class="d-flex align-center gap-1">
                    <i class="fas fa-star text-secondary"></i>
                    <span>4.9 Rating</span>
                </div>
            </div>

            <div class="float-card">
                <div class="d-flex align-center gap-1">
                    <i class="fas fa-shield-alt text-primary"></i>
                    <span>100% Aman</span>
                </div>
            </div>

            <div class="float-card">
                <div class="d-flex align-center gap-1">
                    <i class="fas fa-headset text-success"></i>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'views/footer.php'; ?>