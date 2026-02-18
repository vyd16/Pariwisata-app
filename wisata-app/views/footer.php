<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?= base_url() ?>" class="logo footer-logo">
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="TravelDNE Logo" class="footer-logo-img">
                    <span>TravelDNE</span>
                </a>
                <p>
                    Jasa pariwisata terpercaya dengan berbagai pilihan paket wisata menarik
                    untuk liburan tak terlupakan bersama keluarga dan orang tersayang.
                </p>

                <!-- Social Media -->
                <div class="footer-social">
                    <a href="https://www.facebook.com/" target="_blank" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/TravelDNE.id" target="_blank" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com/" target="_blank" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/6283896459423" target="_blank" class="social-link">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <div>
                <h4>Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="<?= base_url() ?>">Beranda</a></li>
                    <li><a href="<?= base_url('paket.php') ?>">Paket Wisata</a></li>
                    <li><a href="<?= base_url('tentang.php') ?>">Tentang Kami</a></li>
                    <li><a href="<?= base_url('kontak.php') ?>">Kontak</a></li>
                </ul>
            </div>

            <!-- HIDDEN: Layanan section (untuk demonstrasi paket wisata saja)
            <div>
                <h4>Layanan</h4>
                <ul class="footer-links">
                    <li><a href="<?= base_url('layanan/paket-tour.php') ?>">Paket Tour</a></li>
                    <li><a href="<?= base_url('layanan/rental-mobil.php') ?>">Rental Mobil</a></li>
                    <li><a href="<?= base_url('layanan/tiket-pesawat.php') ?>">Tiket Pesawat</a></li>
                    <li><a href="<?= base_url('layanan/hotel.php') ?>">Hotel & Penginapan</a></li>
                </ul>
            </div>
            -->

            <div>
                <h4>Kontak</h4>
                <ul class="footer-links">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        Jl. Panembahan Girilaya, Palimanan
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <a href="tel:+6283896459423">+62 838 9645 9423</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:traveldne@gmail.com">traveldne@gmail.com</a>
                    </li>
                    <li>
                        <i class="fab fa-instagram"></i>
                        <a href="https://www.instagram.com/TravelDNE.id" target="_blank">
                            @TravelDNE.id
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> TravelDNE. All rights reserved.</p>

        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>

</html>