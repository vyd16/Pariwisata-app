<?php
/**
 * Booking Process - Status Update Handler
 * 
 * SECURITY: Menggunakan filter_var() untuk validasi input dan
 * prepared statements untuk mencegah SQL Injection.
 */
require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireAdmin();

$action = $_GET['action'] ?? '';

// SECURITY: Validasi parameter 'id' untuk mencegah SQL Injection
$rawId = $_GET['id'] ?? '';
$id = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id !== false && in_array($action, ['confirm', 'cancel', 'verify_payment'])) {
    try {
        if ($action === 'verify_payment') {
            // Verify payment - mark as paid
            $stmt = $pdo->prepare("UPDATE booking SET payment_status = 'paid' WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                setFlash('success', 'Pembayaran berhasil diverifikasi');
            } else {
                setFlash('danger', 'Pesanan tidak ditemukan');
            }
        } else {
            // Confirm or cancel booking
            $status = $action === 'confirm' ? 'confirmed' : 'cancelled';

            $stmt = $pdo->prepare("UPDATE booking SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            if ($stmt->rowCount() > 0) {
                $message = $action === 'confirm' ? 'Pesanan berhasil dikonfirmasi' : 'Pesanan berhasil dibatalkan';
                setFlash('success', $message);
            } else {
                setFlash('danger', 'Pesanan tidak ditemukan');
            }
        }
    } catch (PDOException $e) {
        setFlash('danger', 'Gagal memproses pesanan: ' . $e->getMessage());
    }
}

// Redirect back to the referring page or booking index
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if (strpos($referer, 'detail.php') !== false) {
    header('Location: ' . base_url('booking/detail.php?id=' . $id));
} else {
    header('Location: ' . base_url('booking/'));
}
exit;

