<?php
$currentPage = 'tentang';
$pageTitle = 'Tentang - TravelDNE';
require_once 'views/header.php';
?>

<!-- ABOUT HERO -->
<section class="section section-sm about-hero">
    <div class="container text-center">
        <span class="hero-badge">Tentang Kami</span>
        <h1>Mengapa Memilih <span class="text-primary">TravelDNE?</span></h1>
        <p class="lead">
            Kami bukan sekadar penyedia jasa wisata, tapi partner perjalanan
            yang siap menemani setiap petualangan Anda.
        </p>
    </div>
</section>

<!-- ABOUT CONTENT -->
<section class="section" style="background: var(--bg-card);">
    <div class="container">

        <div class="about-grid">
            <div class="about-text">
                <h3>Pengalaman Liburan Tanpa Ribet</h3>
                <p>
                    TravelDNE hadir untuk membantu Anda menikmati liburan yang nyaman,
                    aman, dan berkesan. Mulai dari perencanaan perjalanan, pemilihan destinasi,
                    hingga pendampingan selama wisata, semua kami siapkan secara profesional.
                </p>

                <p>
                    Dengan jaringan mitra terpercaya di berbagai daerah Indonesia,
                    kami memastikan setiap perjalanan Anda berjalan lancar dan sesuai harapan.
                </p>
            </div>

            <div class="about-highlight">
                <div class="highlight-card">
                    <h4>500+</h4>
                    <p>Destinasi Wisata</p>
                </div>
                <div class="highlight-card">
                    <h4>10K+</h4>
                    <p>Pelanggan Puas</p>
                </div>
                <div class="highlight-card">
                    <h4>100+</h4>
                    <p>Paket Wisata</p>
                </div>
            </div>
        </div>


        <!-- STATS -->
        <div class="stats-grid mt-6">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>100%</h3>
                    <p>Terpercaya & Aman</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-info">
                    <h3>Best Price</h3>
                    <p>Harga Terjangkau</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="stat-info">
                    <h3>24/7</h3>
                    <p>Customer Support</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Pro Guide</h3>
                    <p>Pemandu Berpengalaman</p>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once 'views/footer.php'; ?>