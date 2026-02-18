<?php
$pageTitle = 'Kontak - TravelDNE';
require_once 'views/header.php';
?>

<section class="section section-lg kontak-section">
    <div class="container">

        <!-- HEADER -->
        <div class="section-header center">
            <h2>
                Hubungi <span class="text-primary">TravelDNE</span>
            </h2>
            <p class="subtitle">
                Punya pertanyaan atau butuh bantuan?
                Tim kami siap menemani perjalanan Anda
            </p>
        </div>

        <!-- KONTAK GRID -->
        <div class="kontak-grid">

            <!-- TELEPON -->
            <a href="tel:+6283896459423" class="kontak-link">
                <div class="card kontak-card">
                    <div class="kontak-icon phone">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>Telepon</h4>
                    <p>+62 838 9645 9423</p>
                </div>
            </a>

            <!-- WHATSAPP -->
            <a href="https://wa.me/6283896459423" target="_blank" class="kontak-link">
                <div class="card kontak-card">
                    <div class="kontak-icon whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h4>WhatsApp</h4>
                    <p>Chat cepat & responsif</p>
                </div>
            </a>

            <!-- EMAIL -->
            <a href="mailto:traveldne@gmail.com" class="kontak-link">
                <div class="card kontak-card">
                    <div class="kontak-icon email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email</h4>
                    <p>traveldne@gmail.com</p>
                </div>
            </a>

            <!-- INSTAGRAM -->
            <a href="https://instagram.com/TravelDne.id" target="_blank" class="kontak-link">
                <div class="card kontak-card">
                    <div class="kontak-icon instagram">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <h4>Instagram</h4>
                    <p>@TravelDne.id</p>
                </div>
            </a>


        </div>
    </div>
</section>

<?php require_once 'views/footer.php'; ?>