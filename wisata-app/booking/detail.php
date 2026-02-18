<?php

require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireLogin();

$pageTitle = 'Detail Pesanan';
$rawId = $_GET['id'] ?? '';
$id = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id === false) {
    setFlash('danger', 'ID pesanan tidak valid');
    header('Location: ' . base_url('booking/'));
    exit;
}

$userId = getCurrentUser()['id'];
$isAdmin = isAdmin();

$booking = null;
$passengers = [];

try {
    if ($isAdmin) {
        $stmt = $pdo->prepare("
            SELECT b.*, u.nama as user_nama, u.email as user_email, u.telepon as user_telepon,
                   p.nama as paket_nama, p.deskripsi as paket_deskripsi, p.harga as paket_harga,
                   p.durasi as paket_durasi, p.lokasi as paket_lokasi, p.foto as paket_foto
            FROM booking b 
            JOIN users u ON b.user_id = u.id 
            JOIN paket p ON b.paket_id = p.id 
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
    } else {
        $stmt = $pdo->prepare("
            SELECT b.*, p.nama as paket_nama, p.deskripsi as paket_deskripsi, p.harga as paket_harga,
                   p.durasi as paket_durasi, p.lokasi as paket_lokasi, p.foto as paket_foto
            FROM booking b 
            JOIN paket p ON b.paket_id = p.id 
            WHERE b.id = ? AND b.user_id = ?
        ");
        $stmt->execute([$id, $userId]);
    }
    $booking = $stmt->fetch();

    if ($booking) {
        $stmt = $pdo->prepare("SELECT * FROM booking_detail WHERE booking_id = ?");
        $stmt->execute([$id]);
        $passengers = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    // Error handling
}

if (!$booking) {
    setFlash('danger', 'Pesanan tidak ditemukan');
    header('Location: ' . base_url('booking/'));
    exit;
}

$statusClass = [
    'pending' => 'badge-warning',
    'confirmed' => 'badge-success',
    'cancelled' => 'badge-danger'
][$booking['status']] ?? 'badge-info';

$statusText = [
    'pending' => 'Menunggu Konfirmasi',
    'confirmed' => 'Dikonfirmasi',
    'cancelled' => 'Dibatalkan'
][$booking['status']] ?? $booking['status'];

$paymentStatusClass = [
    'unpaid' => 'badge-danger',
    'pending_verification' => 'badge-warning',
    'paid' => 'badge-success'
][$booking['payment_status'] ?? 'unpaid'] ?? 'badge-info';

$paymentStatusText = [
    'unpaid' => 'Belum Dibayar',
    'pending_verification' => 'Menunggu Verifikasi',
    'paid' => 'Lunas'
][$booking['payment_status'] ?? 'unpaid'] ?? 'Belum Dibayar';

$paymentMethodNames = [
    'bca' => 'Transfer Bank BCA',
    'bni' => 'Transfer Bank BNI',
    'mandiri' => 'Transfer Bank Mandiri',
    'dana' => 'DANA',
    'gopay' => 'GoPay',
    'ovo' => 'OVO'
];
$paymentMethodName = $paymentMethodNames[$booking['metode_pembayaran'] ?? ''] ?? '-';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <script>
        (function () {
            var theme = localStorage.getItem('wisata-theme');
            if (theme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            } else if (theme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
            } else {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            }
        })();
    </script>
    <style>
        /* Perbaikan Jarak Utama */
        .content-wrapper {
            padding-top: 100px;
            padding-bottom: 80px;
            /* Jarak agar tidak mepet footer */
            min-height: calc(100vh - 80px);
            /* Menyesuaikan agar footer tetap di bawah */
            background: var(--bg-body);
        }

        .invoice-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 2.5rem;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            color: white;
        }

        .invoice-body {
            padding: 2.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .info-item label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .passenger-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .total-section {
            background: rgba(13, 148, 136, 0.1);
            border-top: 2px solid var(--primary);
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-section {
            background: var(--bg-card-hover);
            border: 1px solid var(--primary);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-top: 2.5rem;
        }

        .payment-proof-img {
            max-width: 100%;
            max-height: 350px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: transform 0.3s ease;
            border: 2px solid var(--primary);
        }

        /* Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.9);
            padding: 2rem;
            align-items: center;
            justify-content: center;
        }

        .image-modal.show {
            display: flex;
        }

        @media print {
            @page {
                margin: 1cm;
                size: A4 portrait;
            }

            /* Hide navigation elements completely */
            .no-print,
            header,
            footer,
            .btn,
            .page-header,
            .image-modal,
            .navbar,
            .nav-menu,
            .nav-actions,
            .theme-toggle,
            .menu-toggle,
            .logo span {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
                font-size: 12pt !important;
                line-height: 1.5 !important;
                font-family: 'Times New Roman', serif !important;
            }

            .content-wrapper {
                padding: 0 !important;
                min-height: auto !important;
            }

            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            .card {
                border: 2px solid #000 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                overflow: visible !important;
            }

            .invoice-header {
                display: block !important;
                padding: 25px 20px !important;
                background: #fff !important;
                color: #000 !important;
                border-bottom: 4px double #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                overflow: visible !important;
            }

            .invoice-header .d-flex {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }

            .invoice-header h2 {
                font-size: 28pt !important;
                font-weight: 900 !important;
                text-transform: uppercase;
                margin-bottom: 8px !important;
                color: #000 !important;
                letter-spacing: 3px;
                text-shadow: none !important;
                border-bottom: 2px solid #000 !important;
                padding-bottom: 5px !important;
                display: inline-block !important;
            }

            .invoice-header p {
                font-size: 12pt !important;
                color: #000 !important;
                opacity: 1 !important;
                font-weight: 700 !important;
                margin-top: 8px !important;
            }

            .invoice-header i {
                display: none !important;
            }

            .invoice-body {
                padding: 1.5rem !important;
                overflow: visible !important;
            }

            /* Fix badge status agar tidak terpotong */
            .invoice-body>div:first-child {
                margin-bottom: 1.5rem !important;
                overflow: visible !important;
            }

            .invoice-body .badge {
                display: inline-block !important;
                border: 2px solid #000 !important;
                color: #000 !important;
                background: #fff !important;
                font-weight: 900 !important;
                font-size: 12pt !important;
                padding: 8px 16px !important;
                border-radius: 0 !important;
                text-transform: uppercase !important;
                letter-spacing: 1px !important;
                white-space: nowrap !important;
                overflow: visible !important;
            }

            .info-grid {
                gap: 1rem !important;
                margin-bottom: 1rem !important;
            }

            .info-item label {
                font-size: 10pt !important;
                color: #444 !important;
                text-transform: uppercase;
                font-weight: bold;
            }

            .info-item strong {
                font-size: 12pt !important;
                color: #000 !important;
            }

            .passenger-list {
                margin-top: 1.5rem !important;
            }

            .passenger-list h3 {
                font-size: 13pt !important;
                margin-bottom: 0.75rem !important;
                color: #000 !important;
            }

            .passenger-item {
                padding: 0.5rem 0.75rem !important;
                margin-bottom: 0.5rem !important;
                font-size: 10pt !important;
                background: #f9f9f9 !important;
                border: 1px solid #ccc !important;
            }

            .payment-section {
                padding: 1rem !important;
                margin-top: 1.5rem !important;
                background: #f9f9f9 !important;
                border: 1px solid #ddd !important;
            }

            .payment-section h3 {
                font-size: 13pt !important;
                margin-bottom: 0.75rem !important;
                color: #000 !important;
            }

            /* Show payment proof image */
            .payment-proof-container {
                display: block !important;
            }

            .payment-proof-img {
                max-height: 180px !important;
                max-width: 250px !important;
                border: 1px solid #ccc !important;
            }

            .payment-proof-container .d-flex {
                display: none !important;
            }

            .total-section {
                padding: 1rem 1.5rem !important;
                background: #f5f5f5 !important;
                border-top: 2px solid #000 !important;
            }

            .total-section p {
                font-size: 10pt !important;
                color: #000 !important;
            }

            .total-section h2 {
                font-size: 18pt !important;
                color: #000 !important;
                font-weight: 900 !important;
            }

            /* Prevent page breaks inside elements */
            .passenger-item,
            .payment-section,
            .card {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>

<body>
    <?php include '../views/header.php'; ?>

    <main class="content-wrapper">
        <div class="container" style="max-width: 950px;">

            <div class="page-header no-print">
                <div>
                    <h1 class="page-title">Detail Pesanan #<?= $booking['id'] ?></h1>
                    <p class="text-muted">Invoice resmi pemesanan tiket wisata</p>
                </div>
                <div class="d-flex gap-1">
                    <button onclick="window.print()" class="btn btn-ghost">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="<?= base_url('booking/') ?>" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card" style="overflow: hidden; box-shadow: var(--shadow-lg);">
                <div class="invoice-header">
                    <div class="d-flex justify-between align-center" style="flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h2 style="margin-bottom: 0.5rem; font-size: 2rem;">TravelDNE</h2>
                            <p style="opacity: 0.9; margin-bottom: 0;"><i class="fas fa-map-marker-alt"></i> Jasa
                                Pariwisata Terpercaya</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin-bottom: 0.25rem; font-weight: bold;">INVOICE #<?= $booking['id'] ?></p>
                            <p style="opacity: 0.9; margin-bottom: 0;"><?= formatTanggal($booking['created_at']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="invoice-body">
                    <div style="margin-bottom: 2.5rem;">
                        <span class="badge <?= $statusClass ?>" style="font-size: 1.1rem; padding: 0.6rem 1.2rem;">
                            <?= $statusText ?>
                        </span>
                    </div>

                    <div class="info-grid">
                        <?php if ($isAdmin): ?>
                            <div class="info-item">
                                <label>Nama Pemesan</label>
                                <strong><?= htmlspecialchars($booking['user_nama']) ?></strong>
                            </div>
                            <div class="info-item">
                                <label>Telepon</label>
                                <strong><?= htmlspecialchars($booking['user_telepon'] ?? '-') ?></strong>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Paket Wisata</label>
                            <strong><?= htmlspecialchars($booking['paket_nama']) ?></strong>
                        </div>
                        <div class="info-item">
                            <label>Lokasi</label>
                            <strong><?= htmlspecialchars($booking['paket_lokasi']) ?></strong>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Berangkat</label>
                            <strong><?= formatTanggal($booking['tanggal_berangkat']) ?></strong>
                        </div>
                    </div>

                    <div class="passenger-list" style="margin-top: 3rem;">
                        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-users text-primary"></i> Daftar Penumpang
                        </h3>
                        <?php foreach ($passengers as $index => $p): ?>
                            <div class="passenger-item">
                                <span
                                    style="background:var(--primary); color:white; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold;"><?= $index + 1 ?></span>
                                <div style="flex: 1;">
                                    <strong><?= htmlspecialchars($p['nama_penumpang']) ?></strong>
                                    <small class="text-muted" style="display:block;">No. Identitas:
                                        <?= htmlspecialchars($p['no_identitas'] ?? '-') ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="payment-section">
                        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-wallet text-secondary"></i> Status
                            Pembayaran</h3>
                        <div class="info-grid" style="margin-bottom: 2rem;">
                            <div class="info-item">
                                <label>Status</label>
                                <span class="badge <?= $paymentStatusClass ?>"><?= $paymentStatusText ?></span>
                            </div>
                            <div class="info-item">
                                <label>Metode</label>
                                <strong><?= $paymentMethodName ?></strong>
                            </div>
                        </div>

                        <?php if ($booking['bukti_pembayaran']): ?>
                            <div class="payment-proof-container">
                                <label class="text-muted" style="display:block; margin-bottom: 10px;">Bukti Pembayaran
                                    Terlampir:</label>
                                <img src="<?= base_url('uploads/payments/' . $booking['bukti_pembayaran']) ?>"
                                    class="payment-proof-img" alt="Bukti Pembayaran" onclick="openImageModal(this.src)">

                                <div class="d-flex gap-1" style="margin-top: 1.5rem;">
                                    <a href="<?= base_url('uploads/payments/' . $booking['bukti_pembayaran']) ?>"
                                        class="btn btn-ghost btn-sm" target="_blank"><i class="fas fa-expand"></i>
                                        Perbesar</a>
                                    <?php if ($isAdmin && $booking['payment_status'] === 'pending_verification'): ?>
                                        <a href="<?= base_url('booking/process.php?action=verify_payment&id=' . $booking['id']) ?>"
                                            class="btn btn-success btn-sm"><i class="fas fa-check-double"></i> Verifikasi
                                            Sekarang</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div
                                style="background: rgba(0,0,0,0.05); padding: 2rem; text-align: center; border-radius: var(--radius-md);">
                                <i class="fas fa-file-invoice-dollar"
                                    style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                                <p class="text-muted">Belum ada bukti pembayaran yang diunggah.</p>
                                <?php if (!$isAdmin): ?>
                                    <a href="<?= base_url('booking/payment.php?id=' . $booking['id']) ?>"
                                        class="btn btn-primary" style="margin-top: 1rem;">Upload Bukti Sekarang</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="total-section">
                    <p class="text-muted" style="margin:0;">Total Tagihan (<?= count($passengers) ?> Orang)</p>
                    <h2 style="margin:0; color: var(--secondary); font-size: 2.2rem;">
                        <?= formatRupiah($booking['total_harga']) ?>
                    </h2>
                </div>
            </div>

            <?php if ($isAdmin && $booking['status'] === 'pending'): ?>
                <div class="d-flex gap-1 justify-center no-print" style="margin-top: 2.5rem;">
                    <a href="<?= base_url('booking/process.php?action=confirm&id=' . $booking['id']) ?>"
                        class="btn btn-success btn-lg"><i class="fas fa-check"></i> Terima Pesanan</a>
                    <a href="<?= base_url('booking/process.php?action=cancel&id=' . $booking['id']) ?>"
                        class="btn btn-danger btn-lg"><i class="fas fa-times"></i> Tolak Pesanan</a>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <div class="image-modal" id="imageModal" onclick="closeImageModal()">
        <button
            style="position:absolute; top:20px; right:30px; background:none; border:none; color:white; font-size:3rem; cursor:pointer;">&times;</button>
        <img src="" id="modalImage"
            style="max-width:90%; max-height:90%; border-radius:10px; box-shadow: 0 0 20px rgba(0,0,0,0.5);">
    </div>

    <?php include '../views/footer.php'; ?>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('show');
            document.body.style.overflow = '';
        }
    </script>
</body>

</html>