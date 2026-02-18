<?php
/**
 * Halaman Paket Tour - TravelDNE
 */
$pageTitle = 'Paket Tour - TravelDNE';
require_once '../views/header.php';
?>

<!-- SERVICE HERO -->
<section class="service-hero">
    <div class="container">
        <div class="service-hero-content animate-fade-in-up">
            <div class="service-icon">
                <i class="fas fa-route"></i>
            </div>
            <h1>Paket <span class="text-gradient">Tour</span></h1>
            <p class="lead">
                Nikmati perjalanan wisata yang terorganisir dengan sempurna.
                Kami menyediakan berbagai pilihan paket tour untuk memenuhi kebutuhan liburan Anda.
            </p>
        </div>
    </div>
</section>

<!-- SERVICE CONTENT -->
<section class="section">
    <div class="container">

        <!-- JENIS PAKET -->
        <div class="section-header">
            <h2>Pilihan <span class="text-primary">Paket Tour</span></h2>
            <p>Berbagai jenis paket tour yang bisa Anda pilih sesuai preferensi</p>
        </div>

        <div class="service-grid">
            <!-- Open Trip -->
            <div class="service-card">
                <div class="service-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Open Trip</h3>
                <p>
                    Bergabung dengan wisatawan lain dalam perjalanan seru.
                    Harga lebih terjangkau dan kesempatan bertemu teman baru.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Jadwal tetap setiap minggu</li>
                    <li><i class="fas fa-check"></i> Harga ekonomis</li>
                    <li><i class="fas fa-check"></i> Seru bareng wisatawan lain</li>
                </ul>
            </div>

            <!-- Private Trip -->
            <div class="service-card">
                <div class="service-card-icon secondary">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Private Trip</h3>
                <p>
                    Perjalanan eksklusif untuk Anda dan rombongan.
                    Fleksibel dalam jadwal dan itinerary sesuai keinginan.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Jadwal fleksibel</li>
                    <li><i class="fas fa-check"></i> Itinerary custom</li>
                    <li><i class="fas fa-check"></i> Privasi terjaga</li>
                </ul>
            </div>

            <!-- Honeymoon -->
            <div class="service-card">
                <div class="service-card-icon success">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Honeymoon Package</h3>
                <p>
                    Paket spesial untuk pasangan pengantin baru.
                    Momen romantis di destinasi terbaik Indonesia.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Akomodasi romantis</li>
                    <li><i class="fas fa-check"></i> Private dinner</li>
                    <li><i class="fas fa-check"></i> Couple spa & treatment</li>
                </ul>
            </div>

            <!-- Family Trip -->
            <div class="service-card">
                <div class="service-card-icon info">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Family Trip</h3>
                <p>
                    Paket wisata keluarga dengan aktivitas yang cocok untuk semua usia.
                    Ciptakan kenangan indah bersama keluarga tercinta.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Kid-friendly activities</li>
                    <li><i class="fas fa-check"></i> Akomodasi family room</li>
                    <li><i class="fas fa-check"></i> Dokumentasi lengkap</li>
                </ul>
            </div>
        </div>

        <!-- KEUNGGULAN -->
        <div class="service-features">
            <div class="section-header">
                <h2>Mengapa Memilih <span class="text-primary">Kami?</span></h2>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-user-tie"></i>
                    <h4>Guide Profesional</h4>
                    <p>Pemandu berpengalaman & ramah</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-wallet"></i>
                    <h4>Harga Terjangkau</h4>
                    <p>Best price guarantee</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Asuransi Perjalanan</h4>
                    <p>Perjalanan aman & nyaman</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-camera"></i>
                    <h4>Dokumentasi</h4>
                    <p>Foto & video profesional</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="service-cta">
            <h3>Tertarik dengan Paket Tour Kami?</h3>
            <p>Hubungi kami sekarang untuk konsultasi dan pemesanan</p>
            <a href="https://wa.me/6283896459423?text=Halo,%20saya%20tertarik%20dengan%20paket%20tour" target="_blank"
                class="btn btn-primary btn-lg">
                <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
            </a>
        </div>

    </div>
</section>

<?php require_once '../views/footer.php'; ?>