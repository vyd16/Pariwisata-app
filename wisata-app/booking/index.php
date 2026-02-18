<?php

require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireLogin();

$pageTitle = 'Riwayat Pesanan';
$isAdmin = isAdmin();
$userId = getCurrentUser()['id'];

// Fetch bookings
$bookings = [];
try {
    if ($isAdmin) {
        // Admin sees all bookings
        $stmt = $pdo->query("
            SELECT b.*, u.nama as user_nama, p.nama as paket_nama, p.foto as paket_foto,
                   (SELECT COUNT(*) FROM booking_detail WHERE booking_id = b.id) as jumlah_penumpang
            FROM booking b 
            JOIN users u ON b.user_id = u.id 
            JOIN paket p ON b.paket_id = p.id 
            ORDER BY b.created_at DESC
        ");
    } else {
        // User sees only their bookings
        $stmt = $pdo->prepare("
            SELECT b.*, p.nama as paket_nama, p.foto as paket_foto,
                   (SELECT COUNT(*) FROM booking_detail WHERE booking_id = b.id) as jumlah_penumpang
            FROM booking b 
            JOIN paket p ON b.paket_id = p.id 
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
    }
    $bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tables might not exist
}

// Use different layout based on role
if ($isAdmin) {
    include '../views/admin_header.php';
} else {
    include '../views/header.php';
}
?>

<?php if (!$isAdmin): ?>
    <main style="padding-top: 100px; min-height: 100vh;">
        <div class="container">
        <?php endif; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <?= $isAdmin ? 'Semua Pesanan' : 'Pesanan Saya' ?>
                </h1>
                <p class="text-muted">
                    <?= $isAdmin ? 'Kelola semua pesanan wisata' : 'Riwayat pemesanan paket wisata Anda' ?>
                </p>
            </div>
            <?php if (!$isAdmin): ?>
                <a href="<?= base_url() ?>#paket" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Booking Baru
                </a>
            <?php endif; ?>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <?php if ($isAdmin): ?>
                            <th>Pelanggan</th>
                        <?php endif; ?>
                        <th>Paket</th>
                        <th>Tgl Berangkat</th>
                        <th>Penumpang</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 8 : 7 ?>" class="text-center text-muted" style="padding: 3rem;">
                                <i class="fas fa-calendar-times"
                                    style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                                Belum ada pesanan
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><strong>#<?= $booking['id'] ?></strong></td>
                                <?php if ($isAdmin): ?>
                                    <td><?= htmlspecialchars($booking['user_nama']) ?></td>
                                <?php endif; ?>
                                <td>
                                    <div class="d-flex align-center gap-1">
                                        <img src="<?= $booking['paket_foto'] ? base_url('uploads/packages/' . $booking['paket_foto']) : 'https://via.placeholder.com/40' ?>"
                                            class="img-thumbnail" alt="">
                                        <?= htmlspecialchars($booking['paket_nama']) ?>
                                    </div>
                                </td>
                                <td><?= formatTanggal($booking['tanggal_berangkat']) ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= $booking['jumlah_penumpang'] ?> orang
                                    </span>
                                </td>
                                <td><strong class="text-primary">
                                        <?= formatRupiah($booking['total_harga']) ?>
                                    </strong></td>
                                <td>
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
                                </td>
                                <td>
                                    <?php
                                    $paymentStatusClass = [
                                        'unpaid' => 'badge-danger',
                                        'pending_verification' => 'badge-warning',
                                        'paid' => 'badge-success'
                                    ][$booking['payment_status'] ?? 'unpaid'] ?? 'badge-info';
                                    $paymentStatusText = [
                                        'unpaid' => 'Belum Bayar',
                                        'pending_verification' => 'Verifikasi',
                                        'paid' => 'Lunas'
                                    ][$booking['payment_status'] ?? 'unpaid'] ?? 'Belum Bayar';
                                    ?>
                                    <span class="badge <?= $paymentStatusClass ?>">
                                        <?= $paymentStatusText ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <?php if ($isAdmin && !empty($booking['bukti_pembayaran'])): ?>
                                            <button type="button" class="btn btn-ghost btn-sm" title="Lihat Bukti Pembayaran"
                                                onclick="openPaymentProof('<?= base_url('uploads/payments/' . $booking['bukti_pembayaran']) ?>', '#<?= $booking['id'] ?>')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        <?php elseif ($isAdmin): ?>
                                            <a href="<?= base_url('booking/detail.php?id=' . $booking['id']) ?>"
                                                class="btn btn-ghost btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('booking/detail.php?id=' . $booking['id']) ?>"
                                                class="btn btn-ghost btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($isAdmin && $booking['status'] === 'pending'): ?>
                                            <a href="<?= base_url('booking/process.php?action=confirm&id=' . $booking['id']) ?>"
                                                class="btn btn-success btn-sm" title="Konfirmasi">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= base_url('booking/process.php?action=cancel&id=' . $booking['id']) ?>"
                                                class="btn btn-danger btn-sm" data-confirm="Batalkan pesanan ini?" title="Batalkan">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($isAdmin && ($booking['payment_status'] ?? '') === 'pending_verification'): ?>
                                            <a href="<?= base_url('booking/process.php?action=verify_payment&id=' . $booking['id']) ?>"
                                                class="btn btn-primary btn-sm" title="Verifikasi Pembayaran"
                                                data-confirm="Verifikasi pembayaran ini?">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($isAdmin): ?>
            <?php include '../views/admin_footer.php'; ?>
        <?php else: ?>
        </div>
    </main>
    <?php include '../views/footer.php'; ?>
<?php endif; ?>

<?php if ($isAdmin): ?>
    <!-- Payment Proof Modal -->
    <div id="paymentProofModal"
        style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9); padding: 2rem; box-sizing: border-box;">
        <button onclick="closePaymentProof()"
            style="position: absolute; top: 1rem; right: 1.5rem; color: white; font-size: 2rem; cursor: pointer; background: none; border: none;">&times;</button>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
            <h3 id="paymentProofTitle" style="color: white; margin-bottom: 1rem;">Bukti Pembayaran</h3>
            <img id="paymentProofImage" src="" alt="Bukti Pembayaran"
                style="max-width: 90%; max-height: 80%; border-radius: 8px;">
            <div style="margin-top: 1rem;">
                <a id="paymentProofDownload" href="" download class="btn btn-primary btn-sm" style="margin-right: 0.5rem;">
                    <i class="fas fa-download"></i> Download
                </a>
                <a id="paymentProofNewTab" href="" target="_blank" class="btn btn-ghost btn-sm">
                    <i class="fas fa-external-link-alt"></i> Buka Tab Baru
                </a>
            </div>
        </div>
    </div>

    <script>
        function openPaymentProof(imageUrl, bookingId) {
            document.getElementById('paymentProofImage').src = imageUrl;
            document.getElementById('paymentProofDownload').href = imageUrl;
            document.getElementById('paymentProofNewTab').href = imageUrl;
            document.getElementById('paymentProofTitle').textContent = 'Bukti Pembayaran Pesanan ' + bookingId;
            document.getElementById('paymentProofModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closePaymentProof() {
            document.getElementById('paymentProofModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePaymentProof();
        });

        // Close modal on backdrop click
        document.getElementById('paymentProofModal').addEventListener('click', function (e) {
            if (e.target === this) closePaymentProof();
        });
    </script>
<?php endif; ?>