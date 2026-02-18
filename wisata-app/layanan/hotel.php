<?php
/**
 * Halaman Hotel & Penginapan - TravelDNE
 */
$pageTitle = 'Hotel & Penginapan - TravelDNE';
require_once '../views/header.php';
?>

<!-- SERVICE HERO -->
<section class="service-hero">
    <div class="container">
        <div class="service-hero-content animate-fade-in-up">
            <div class="service-icon success">
                <i class="fas fa-hotel"></i>
            </div>
            <h1>Hotel & <span class="text-gradient">Penginapan</span></h1>
            <p class="lead">
                Temukan akomodasi terbaik untuk perjalanan Anda.
                Dari hotel bintang 5 hingga homestay nyaman, semua tersedia di sini.
            </p>
        </div>
    </div>
</section>

<!-- SERVICE CONTENT -->
<section class="section">
    <div class="container">

        <!-- JENIS AKOMODASI -->
        <div class="section-header">
            <h2>Pilihan <span class="text-primary">Akomodasi</span></h2>
            <p>Berbagai tipe penginapan sesuai budget dan kebutuhan Anda</p>
        </div>

        <div class="service-grid">
            <!-- Hotel -->
            <div class="service-card">
                <div class="service-card-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Hotel</h3>
                <p>
                    Hotel berbintang dengan fasilitas lengkap.
                    Cocok untuk business trip atau liburan keluarga.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Bintang 1-5 tersedia</li>
                    <li><i class="fas fa-check"></i> Breakfast included</li>
                    <li><i class="fas fa-check"></i> Fasilitas lengkap</li>
                </ul>
                <div class="price-tag">Mulai Rp 250.000/malam</div>
            </div>

            <!-- Villa -->
            <div class="service-card">
                <div class="service-card-icon secondary">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Villa</h3>
                <p>
                    Privasi dan kenyamanan layaknya rumah sendiri.
                    Perfect untuk liburan keluarga atau gathering.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Private pool</li>
                    <li><i class="fas fa-check"></i> Full facilities</li>
                    <li><i class="fas fa-check"></i> Dapur lengkap</li>
                </ul>
                <div class="price-tag">Mulai Rp 1.500.000/malam</div>
            </div>

            <!-- Homestay -->
            <div class="service-card">
                <div class="service-card-icon success">
                    <i class="fas fa-house-user"></i>
                </div>
                <h3>Homestay</h3>
                <p>
                    Pengalaman menginap ala lokal dengan harga terjangkau.
                    Rasakan keramahan penduduk setempat.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Budget friendly</li>
                    <li><i class="fas fa-check"></i> Local experience</li>
                    <li><i class="fas fa-check"></i> Breakfast homemade</li>
                </ul>
                <div class="price-tag">Mulai Rp 150.000/malam</div>
            </div>

            <!-- Resort -->
            <div class="service-card">
                <div class="service-card-icon info">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <h3>Resort</h3>
                <p>
                    Liburan mewah dengan pemandangan spektakuler.
                    All-inclusive package untuk pengalaman tak terlupakan.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Beachfront/Hillside</li>
                    <li><i class="fas fa-check"></i> Spa & wellness</li>
                    <li><i class="fas fa-check"></i> Activities included</li>
                </ul>
                <div class="price-tag">Mulai Rp 2.500.000/malam</div>
            </div>

            <!-- Glamping -->
            <div class="service-card">
                <div class="service-card-icon warning">
                    <i class="fas fa-campground"></i>
                </div>
                <h3>Glamping</h3>
                <p>
                    Camping mewah dengan kenyamanan hotel.
                    Dekat dengan alam tapi tetap nyaman.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Nature experience</li>
                    <li><i class="fas fa-check"></i> Comfortable bed</li>
                    <li><i class="fas fa-check"></i> BBQ facilities</li>
                </ul>
                <div class="price-tag">Mulai Rp 800.000/malam</div>
            </div>

            <!-- Guest House -->
            <div class="service-card">
                <div class="service-card-icon danger">
                    <i class="fas fa-door-open"></i>
                </div>
                <h3>Guest House</h3>
                <p>
                    Penginapan nyaman untuk backpacker atau solo traveler.
                    Lokasi strategis dengan harga bersahabat.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Strategic location</li>
                    <li><i class="fas fa-check"></i> Free WiFi</li>
                    <li><i class="fas fa-check"></i> Common area</li>
                </ul>
                <div class="price-tag">Mulai Rp 100.000/malam</div>
            </div>
        </div>

        <!-- KEUNGGULAN -->
        <div class="service-features">
            <div class="section-header">
                <h2>Keuntungan <span class="text-primary">Booking di Kami</span></h2>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-percent"></i>
                    <h4>Best Rate Guarantee</h4>
                    <p>Harga terbaik dijamin</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <h4>Lokasi Strategis</h4>
                    <p>Dekat area wisata</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-star"></i>
                    <h4>Review Terpercaya</h4>
                    <p>Rekomendasi berdasarkan rating</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-calendar-check"></i>
                    <h4>Free Cancellation</h4>
                    <p>Flexible booking policy</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="service-cta">
            <h3>Cari Penginapan Untuk Liburan?</h3>
            <p>Konsultasikan kebutuhan akomodasi Anda, kami bantu carikan yang terbaik</p>
            <a href="https://wa.me/6283896459423?text=Halo,%20saya%20ingin%20booking%20hotel" target="_blank"
                class="btn btn-primary btn-lg">
                <i class="fab fa-whatsapp"></i> Booking Sekarang
            </a>
        </div>

    </div>
</section>

<?php require_once '../views/footer.php'; ?>