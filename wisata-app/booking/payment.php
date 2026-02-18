<?php
/**
 * Payment Page - Select payment method and upload proof
 */
require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireLogin();

$pageTitle = 'Pembayaran';
$error = '';
$success = '';
$userId = getCurrentUser()['id'];

// Get booking ID from URL
$bookingId = intval($_GET['id'] ?? 0);

if (!$bookingId) {
    setFlash('danger', 'ID pesanan tidak valid');
    header('Location: ' . base_url('booking/'));
    exit;
}

// Fetch booking details
$booking = null;
try {
    $stmt = $pdo->prepare("
        SELECT b.*, p.nama as paket_nama, p.harga as paket_harga, p.foto as paket_foto,
               p.durasi as paket_durasi, p.lokasi as paket_lokasi
        FROM booking b 
        JOIN paket p ON b.paket_id = p.id 
        WHERE b.id = ? AND b.user_id = ?
    ");
    $stmt->execute([$bookingId, $userId]);
    $booking = $stmt->fetch();
} catch (PDOException $e) {
    // Error fetching
}

if (!$booking) {
    setFlash('danger', 'Pesanan tidak ditemukan');
    header('Location: ' . base_url('booking/'));
    exit;
}

// Payment methods available
$paymentMethods = [
    'bca' => ['name' => 'Transfer Bank BCA', 'icon' => 'fa-building-columns', 'account' => '1234567890', 'holder' => 'PT TravelDNE Indonesia'],
    'bni' => ['name' => 'Transfer Bank BNI', 'icon' => 'fa-building-columns', 'account' => '0987654321', 'holder' => 'PT TravelDNE Indonesia'],
    'mandiri' => ['name' => 'Transfer Bank Mandiri', 'icon' => 'fa-building-columns', 'account' => '1122334455', 'holder' => 'PT TravelDNE Indonesia'],
    'dana' => ['name' => 'DANA', 'icon' => 'fa-wallet', 'account' => '081234567890', 'holder' => 'TravelDNE'],
    'gopay' => ['name' => 'GoPay', 'icon' => 'fa-wallet', 'account' => '081234567890', 'holder' => 'TravelDNE'],
    'ovo' => ['name' => 'OVO', 'icon' => 'fa-wallet', 'account' => '081234567890', 'holder' => 'TravelDNE'],
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodePembayaran = $_POST['metode_pembayaran'] ?? '';

    // Validate payment method
    if (!array_key_exists($metodePembayaran, $paymentMethods)) {
        $error = 'Pilih metode pembayaran yang valid';
    } elseif (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Upload bukti pembayaran wajib diisi';
    } else {
        // Upload payment proof
        $uploadResult = uploadFile($_FILES['bukti_pembayaran'], 'uploads/payments');

        if (!$uploadResult['success']) {
            $error = $uploadResult['error'];
        } else {
            try {
                // Update booking with payment info
                $stmt = $pdo->prepare("
                    UPDATE booking 
                    SET metode_pembayaran = ?, bukti_pembayaran = ?, payment_status = 'pending_verification'
                    WHERE id = ? AND user_id = ?
                ");
                $stmt->execute([$metodePembayaran, $uploadResult['filename'], $bookingId, $userId]);

                setFlash('success', 'Bukti pembayaran berhasil diupload! Silakan tunggu verifikasi dari admin.');
                header('Location: ' . base_url('booking/detail.php?id=' . $bookingId));
                exit;

            } catch (PDOException $e) {
                $error = 'Gagal menyimpan data pembayaran: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $pageTitle ?>
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Theme Initializer -->
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
        .payment-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 900px) {
            .payment-container {
                grid-template-columns: 1fr;
            }
        }

        .payment-methods {
            display: grid;
            gap: 0.75rem;
        }

        .payment-method {
            background: var(--bg-card);
            border: 2px solid transparent;
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .payment-method:hover {
            border-color: var(--primary);
            transform: translateX(5px);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background: rgba(13, 148, 136, 0.1);
        }

        .payment-method input[type="radio"] {
            display: none;
        }

        .payment-method .method-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .payment-method .method-info {
            flex: 1;
        }

        .payment-method .method-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .payment-method .method-account {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .payment-method .check-icon {
            width: 24px;
            height: 24px;
            border: 2px solid var(--text-muted);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .payment-method.selected .check-icon {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .order-summary {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .order-summary h3 {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .order-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .order-item img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: var(--radius-md);
        }

        .order-total {
            border-top: 2px solid var(--primary);
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .upload-area {
            border: 2px dashed var(--text-muted);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(13, 148, 136, 0.05);
        }

        .upload-area.has-file {
            border-color: var(--secondary);
            background: rgba(245, 158, 11, 0.1);
        }

        .upload-area i {
            font-size: 2.5rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .upload-area.has-file i {
            color: var(--secondary);
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: var(--radius-md);
            margin-top: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-title .number {
            background: var(--primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .payment-info-box {
            background: rgba(13, 148, 136, 0.1);
            border: 1px solid var(--primary);
            border-radius: var(--radius-lg);
            padding: 1rem;
            margin-top: 1rem;
            display: none;
        }

        .payment-info-box.show {
            display: block;
        }

        .copy-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .copy-btn:hover {
            background: var(--primary-dark);
        }

        .upload-section {
            display: none;
        }

        .upload-section.show {
            display: block;
        }
    </style>
</head>

<body>
    <?php include '../views/header.php'; ?>

    <main style="padding-top: 100px; padding-bottom: 3rem; min-height: 100vh;">
        <div class="container">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Pembayaran</h1>
                    <p class="text-muted">Selesaikan pembayaran untuk pesanan #
                        <?= $booking['id'] ?>
                    </p>
                </div>
                <a href="<?= base_url('booking/detail.php?id=' . $bookingId) ?>" class="btn btn-ghost">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($booking['payment_status'] === 'pending_verification'): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-clock"></i>
                    Pembayaran Anda sedang dalam proses verifikasi. Silakan tunggu konfirmasi dari admin.
                </div>
            <?php elseif ($booking['payment_status'] === 'paid'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Pembayaran Anda sudah dikonfirmasi!
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" id="paymentForm">
                <div class="payment-container">
                    <div class="payment-main">
                        <!-- Step 1: Select Payment Method -->
                        <div class="card" style="margin-bottom: 1.5rem;">
                            <div class="card-body">
                                <div class="section-title">
                                    <span class="number">1</span>
                                    <h3 style="margin: 0;">Pilih Metode Pembayaran</h3>
                                </div>

                                <div class="payment-methods">
                                    <?php foreach ($paymentMethods as $key => $method): ?>
                                        <label class="payment-method" data-method="<?= $key ?>">
                                            <input type="radio" name="metode_pembayaran" value="<?= $key ?>"
                                                <?= ($booking['metode_pembayaran'] === $key) ? 'checked' : '' ?>>
                                            <div class="method-icon">
                                                <i class="fas <?= $method['icon'] ?>"></i>
                                            </div>
                                            <div class="method-info">
                                                <div class="method-name">
                                                    <?= $method['name'] ?>
                                                </div>
                                                <div class="method-account">
                                                    <?= $method['account'] ?> -
                                                    <?= $method['holder'] ?>
                                                </div>
                                            </div>
                                            <div class="check-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                                <div class="payment-info-box" id="paymentInfoBox">
                                    <h4 style="margin-bottom: 0.5rem;"><i class="fas fa-info-circle text-primary"></i>
                                        Informasi Transfer</h4>
                                    <p class="text-muted" style="margin-bottom: 0.5rem;">Transfer ke rekening berikut:
                                    </p>
                                    <div
                                        style="background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: var(--radius-md);">
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <strong id="selectedAccountNumber">-</strong>
                                                <br>
                                                <small class="text-muted" id="selectedAccountHolder">-</small>
                                            </div>
                                            <button type="button" class="copy-btn" onclick="copyAccountNumber()">
                                                <i class="fas fa-copy"></i> Salin
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-muted"
                                        style="margin-top: 0.75rem; margin-bottom: 0; font-size: 0.85rem;">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        Pastikan nominal transfer sesuai dengan total pembayaran
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Upload Payment Proof (hidden by default) -->
                        <div class="card upload-section" id="uploadSection">
                            <div class="card-body">
                                <div class="section-title">
                                    <span class="number">2</span>
                                    <h3 style="margin: 0;">Upload Bukti Pembayaran</h3>
                                </div>

                                <p class="text-muted">
                                    Upload screenshot atau foto bukti transfer pembayaran Anda.
                                </p>

                                <div class="upload-area" id="uploadArea"
                                    onclick="document.getElementById('buktiPembayaran').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p style="margin-bottom: 0.5rem;"><strong>Klik untuk upload</strong></p>
                                    <p class="text-muted" style="margin-bottom: 0; font-size: 0.85rem;">
                                        Format: JPG, PNG, WEBP (Maks. 2MB)
                                    </p>
                                    <img id="previewImage" class="preview-image" style="display: none;">
                                </div>

                                <input type="file" name="bukti_pembayaran" id="buktiPembayaran"
                                    accept="image/jpeg,image/png,image/webp" style="display: none;">

                                <?php if ($booking['bukti_pembayaran']): ?>
                                    <div style="margin-top: 1rem;">
                                        <p class="text-muted" style="margin-bottom: 0.5rem;">Bukti pembayaran yang sudah
                                            diupload:</p>
                                        <img src="<?= base_url('uploads/payments/' . $booking['bukti_pembayaran']) ?>"
                                            style="max-width: 100%; max-height: 200px; border-radius: var(--radius-md);">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="submit-section" id="submitSection" style="margin-top: 1.5rem; display: none;">
                            <button type="submit" class="btn btn-primary btn-lg"
                                <?= ($booking['payment_status'] === 'paid') ? 'disabled' : '' ?>>
                                <i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="order-summary">
                        <h3><i class="fas fa-receipt text-primary"></i> Ringkasan Pesanan</h3>

                        <div class="order-item">
                            <img src="<?= $booking['paket_foto'] ? base_url('uploads/packages/' . $booking['paket_foto']) : 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=200' ?>"
                                alt="">
                            <div>
                                <strong>
                                    <?= htmlspecialchars($booking['paket_nama']) ?>
                                </strong>
                                <p class="text-muted" style="margin-bottom: 0; font-size: 0.85rem;">
                                    <i class="fas fa-calendar"></i>
                                    <?= formatTanggal($booking['tanggal_berangkat']) ?>
                                </p>
                            </div>
                        </div>

                        <div style="font-size: 0.9rem;">
                            <div class="d-flex justify-between" style="margin-bottom: 0.5rem;">
                                <span class="text-muted">ID Pesanan</span>
                                <strong>#
                                    <?= $booking['id'] ?>
                                </strong>
                            </div>
                            <div class="d-flex justify-between" style="margin-bottom: 0.5rem;">
                                <span class="text-muted">Status</span>
                                <?php
                                $statusClass = [
                                    'pending' => 'badge-warning',
                                    'confirmed' => 'badge-success',
                                    'cancelled' => 'badge-danger'
                                ][$booking['status']] ?? 'badge-info';
                                $statusText = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'cancelled' => 'Dibatalkan'
                                ][$booking['status']] ?? $booking['status'];
                                ?>
                                <span class="badge <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-total">
                            <div class="d-flex justify-between align-center">
                                <span class="text-muted">Total Pembayaran</span>
                                <h2 style="margin: 0; color: var(--secondary);">
                                    <?= formatRupiah($booking['total_harga']) ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
        </div>
    </main>

    <?php include '../views/footer.php'; ?>

    <script>
        // Payment Methods Data
        const paymentMethods = <?= json_encode($paymentMethods) ?>;

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function () {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;

                // Show payment info
                const methodKey = this.dataset.method;
                const info = paymentMethods[methodKey];
                document.getElementById('selectedAccountNumber').textContent = info.account;
                document.getElementById('selectedAccountHolder').textContent = info.name + ' - ' + info.holder;
                document.getElementById('paymentInfoBox').classList.add('show');

                // Show upload section and submit button
                document.getElementById('uploadSection').classList.add('show');
                document.getElementById('submitSection').style.display = 'block';
            });

            // Check if already selected
            if (method.querySelector('input[type="radio"]').checked) {
                method.classList.add('selected');
                const methodKey = method.dataset.method;
                const info = paymentMethods[methodKey];
                document.getElementById('selectedAccountNumber').textContent = info.account;
                document.getElementById('selectedAccountHolder').textContent = info.name + ' - ' + info.holder;
                document.getElementById('paymentInfoBox').classList.add('show');

                // Show upload section and submit button if already selected
                document.getElementById('uploadSection').classList.add('show');
                document.getElementById('submitSection').style.display = 'block';
            }
        });

        // Copy account number
        function copyAccountNumber() {
            const accountNumber = document.getElementById('selectedAccountNumber').textContent;
            navigator.clipboard.writeText(accountNumber).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.innerHTML = '<i class="fas fa-check"></i> Tersalin';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-copy"></i> Salin';
                }, 2000);
            });
        }

        // File upload preview
        document.getElementById('buktiPembayaran').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('previewImage');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    document.getElementById('uploadArea').classList.add('has-file');
                    document.querySelector('#uploadArea > i').className = 'fas fa-check-circle';
                    document.querySelector('#uploadArea > p:first-of-type').innerHTML = '<strong>' + file.name + '</strong>';
                }
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function (e) {
            const methodSelected = document.querySelector('input[name="metode_pembayaran"]:checked');
            const fileInput = document.getElementById('buktiPembayaran');

            if (!methodSelected) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran');
                return;
            }

            if (!fileInput.files.length && !document.querySelector('.preview-image[src]')) {
                e.preventDefault();
                alert('Silakan upload bukti pembayaran');
                return;
            }
        });
    </script>
</body>

</html>