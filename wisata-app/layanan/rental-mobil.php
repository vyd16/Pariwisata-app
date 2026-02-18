<?php
/**
 * Halaman Rental Mobil - TravelDNE
 */
$pageTitle = 'Rental Mobil - TravelDNE';
require_once '../views/header.php';
?>

<!-- SERVICE HERO -->
<section class="service-hero">
    <div class="container">
        <div class="service-hero-content animate-fade-in-up">
            <div class="service-icon secondary">
                <i class="fas fa-car"></i>
            </div>
            <h1>Rental <span class="text-gradient">Mobil</span></h1>
            <p class="lead">
                Nikmati perjalanan dengan kendaraan nyaman dan driver berpengalaman.
                Tersedia berbagai pilihan mobil untuk kebutuhan wisata Anda.
            </p>
        </div>
    </div>
</section>

<!-- SERVICE CONTENT -->
<section class="section">
    <div class="container">

        <!-- JENIS MOBIL -->
        <div class="section-header">
            <h2>Pilihan <span class="text-primary">Kendaraan</span></h2>
            <p>Armada lengkap untuk berbagai kebutuhan perjalanan</p>
        </div>

        <div class="service-grid">
            <!-- City Car -->
            <div class="service-card">
                <div class="service-card-icon">
                    <i class="fas fa-car-side"></i>
                </div>
                <h3>City Car</h3>
                <p>
                    Mobil compact untuk perjalanan singkat atau couple.
                    Hemat BBM dan mudah parkir.
                </p>
                <div class="car-specs">
                    <span><i class="fas fa-user"></i> 4 Orang</span>
                    <span><i class="fas fa-suitcase"></i> 2 Koper</span>
                </div>
                <div class="price-tag">Mulai Rp 350.000/hari</div>
            </div>

            <!-- MPV -->
            <div class="service-card">
                <div class="service-card-icon secondary">
                    <i class="fas fa-shuttle-van"></i>
                </div>
                <h3>MPV (Avanza/Xenia)</h3>
                <p>
                    Pilihan populer untuk keluarga kecil atau rombongan.
                    Nyaman dan ekonomis.
                </p>
                <div class="car-specs">
                    <span><i class="fas fa-user"></i> 6 Orang</span>
                    <span><i class="fas fa-suitcase"></i> 4 Koper</span>
                </div>
                <div class="price-tag">Mulai Rp 450.000/hari</div>
            </div>

            <!-- Innova -->
            <div class="service-card">
                <div class="service-card-icon success">
                    <i class="fas fa-car-alt"></i>
                </div>
                <h3>Innova Reborn</h3>
                <p>
                    Mobil premium dengan kenyamanan maksimal.
                    Cocok untuk perjalanan jarak jauh.
                </p>
                <div class="car-specs">
                    <span><i class="fas fa-user"></i> 6 Orang</span>
                    <span><i class="fas fa-suitcase"></i> 5 Koper</span>
                </div>
                <div class="price-tag">Mulai Rp 650.000/hari</div>
            </div>

            <!-- Hiace -->
            <div class="service-card">
                <div class="service-card-icon info">
                    <i class="fas fa-bus"></i>
                </div>
                <h3>Hiace Commuter</h3>
                <p>
                    Ideal untuk rombongan besar atau study tour.
                    Luas dan nyaman untuk perjalanan panjang.
                </p>
                <div class="car-specs">
                    <span><i class="fas fa-user"></i> 14 Orang</span>
                    <span><i class="fas fa-suitcase"></i> Bagasi luas</span>
                </div>
                <div class="price-tag">Mulai Rp 1.200.000/hari</div>
            </div>

            <!-- Bus Medium -->
            <div class="service-card">
                <div class="service-card-icon warning">
                    <i class="fas fa-bus-alt"></i>
                </div>
                <h3>Bus Medium</h3>
                <p>
                    Untuk rombongan sedang seperti gathering atau tour kantor.
                    AC dan audio system lengkap.
                </p>
                <div class="car-specs">
                    <span><i class="fas fa-user"></i> 25-35 Orang</span>
                    <span><i class="fas fa-suitcase"></i> Cargo luas</span>
                </div>
                <div class="price-tag">Mulai Rp 2.500.000/hari</div>
            </div>

            <!-- Bus Besar -->
            <div class="service-card">
                <div class="service-card-icon danger">
                    <i class="fas fa-truck-moving"></i>
                </div>
                <h3>Bus Besar</h3>
                <p>
                    Bus pariwisata untuk rombongan besar.
                    Full AC, toilet, dan fasilitas lengkap.
                </p>
                <div class="car-specs">
                    <span><i class="fas fa-user"></i> 45-59 Orang</span>
                    <span><i class="fas fa-suitcase"></i> Cargo sangat luas</span>
                </div>
                <div class="price-tag">Mulai Rp 4.500.000/hari</div>
            </div>
        </div>

        <!-- KEUNGGULAN -->
        <div class="service-features">
            <div class="section-header">
                <h2>Keunggulan <span class="text-primary">Layanan Kami</span></h2>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-id-card"></i>
                    <h4>Driver Berpengalaman</h4>
                    <p>Sopir profesional & hafal rute</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-tools"></i>
                    <h4>Mobil Terawat</h4>
                    <p>Armada selalu dalam kondisi prima</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <h4>Tarif Transparan</h4>
                    <p>Tidak ada biaya tersembunyi</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <h4>Support 24/7</h4>
                    <p>Siap membantu kapan saja</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="service-cta">
            <h3>Butuh Rental Mobil?</h3>
            <p>Hubungi kami untuk reservasi dan informasi lebih lanjut</p>
            <a href="https://wa.me/6283896459423?text=Halo,%20saya%20ingin%20rental%20mobil" target="_blank"
                class="btn btn-primary btn-lg">
                <i class="fab fa-whatsapp"></i> Reservasi Sekarang
            </a>
        </div>

    </div>
</section>

<?php require_once '../views/footer.php'; ?>