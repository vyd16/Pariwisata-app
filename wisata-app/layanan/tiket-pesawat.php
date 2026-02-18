<?php
/**
 * Halaman Tiket Pesawat - TravelDNE
 */
$pageTitle = 'Tiket Pesawat - TravelDNE';
require_once '../views/header.php';
?>

<!-- SERVICE HERO -->
<section class="service-hero">
    <div class="container">
        <div class="service-hero-content animate-fade-in-up">
            <div class="service-icon info">
                <i class="fas fa-plane"></i>
            </div>
            <h1>Tiket <span class="text-gradient">Pesawat</span></h1>
            <p class="lead">
                Pesan tiket pesawat dengan harga terbaik dan berbagai pilihan maskapai.
                Perjalanan nyaman dimulai dari pemesanan yang mudah.
            </p>
        </div>
    </div>
</section>

<!-- SERVICE CONTENT -->
<section class="section">
    <div class="container">

        <!-- MASKAPAI -->
        <div class="section-header">
            <h2>Maskapai <span class="text-primary">Partner</span></h2>
            <p>Tersedia tiket dari berbagai maskapai penerbangan terpercaya</p>
        </div>

        <div class="airline-grid">
            <div class="airline-card">
                <i class="fas fa-plane-departure"></i>
                <span>Garuda Indonesia</span>
            </div>
            <div class="airline-card">
                <i class="fas fa-plane-departure"></i>
                <span>Lion Air</span>
            </div>
            <div class="airline-card">
                <i class="fas fa-plane-departure"></i>
                <span>Citilink</span>
            </div>
            <div class="airline-card">
                <i class="fas fa-plane-departure"></i>
                <span>Batik Air</span>
            </div>
            <div class="airline-card">
                <i class="fas fa-plane-departure"></i>
                <span>Air Asia</span>
            </div>
            <div class="airline-card">
                <i class="fas fa-plane-departure"></i>
                <span>Sriwijaya Air</span>
            </div>
        </div>

        <!-- DESTINASI POPULER -->
        <div class="section-header mt-6">
            <h2>Destinasi <span class="text-primary">Populer</span></h2>
            <p>Rute penerbangan favorit dengan harga kompetitif</p>
        </div>

        <div class="service-grid">
            <div class="service-card destination-card">
                <div class="destination-icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <h3>Jakarta → Bali</h3>
                <p>Pulau dewata dengan pantai eksotis dan budaya unik</p>
                <div class="price-tag">Mulai Rp 850.000</div>
            </div>

            <div class="service-card destination-card">
                <div class="destination-icon">
                    <i class="fas fa-mountain"></i>
                </div>
                <h3>Jakarta → Yogyakarta</h3>
                <p>Kota budaya dengan Borobudur dan kuliner legendaris</p>
                <div class="price-tag">Mulai Rp 550.000</div>
            </div>

            <div class="service-card destination-card">
                <div class="destination-icon">
                    <i class="fas fa-water"></i>
                </div>
                <h3>Jakarta → Lombok</h3>
                <p>Surga diving dengan Gili Trawangan yang memukau</p>
                <div class="price-tag">Mulai Rp 950.000</div>
            </div>

            <div class="service-card destination-card">
                <div class="destination-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Jakarta → Labuan Bajo</h3>
                <p>Rumah Komodo dan keajaiban alam Flores</p>
                <div class="price-tag">Mulai Rp 1.200.000</div>
            </div>
        </div>

        <!-- KEUNGGULAN -->
        <div class="service-features">
            <div class="section-header">
                <h2>Mengapa Pesan <span class="text-primary">di Kami?</span></h2>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-tags"></i>
                    <h4>Harga Terbaik</h4>
                    <p>Jaminan harga kompetitif</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <h4>Booking Mudah</h4>
                    <p>Proses cepat & simple</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-clock"></i>
                    <h4>E-Ticket Instan</h4>
                    <p>Tiket langsung dikirim email</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-redo"></i>
                    <h4>Reschedule Mudah</h4>
                    <p>Bantuan ganti jadwal</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="service-cta">
            <h3>Mau Pesan Tiket Pesawat?</h3>
            <p>Konsultasikan rencana perjalanan Anda, kami bantu carikan tiket terbaik</p>
            <a href="https://wa.me/6283896459423?text=Halo,%20saya%20ingin%20pesan%20tiket%20pesawat" target="_blank"
                class="btn btn-primary btn-lg">
                <i class="fab fa-whatsapp"></i> Konsultasi Gratis
            </a>
        </div>

    </div>
</section>

<?php require_once '../views/footer.php'; ?>