<?php
/**
 * Delete Package
 * 
 * SECURITY: Menggunakan filter_var() untuk validasi input dan
 * prepared statements untuk mencegah SQL Injection.
 */
require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireAdmin();

// SECURITY: Validasi parameter 'id' untuk mencegah SQL Injection
$rawId = $_GET['id'] ?? '';
$id = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id !== false) {
    try {
        // Get package to delete photo
        $stmt = $pdo->prepare("SELECT foto FROM paket WHERE id = ?");
        $stmt->execute([$id]);
        $package = $stmt->fetch();

        if ($package) {
            // Delete photo file
            if ($package['foto']) {
                deleteFile($package['foto'], 'uploads/packages');
            }

            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM paket WHERE id = ?");
            $stmt->execute([$id]);

            setFlash('success', 'Paket wisata berhasil dihapus');
        } else {
            setFlash('danger', 'Paket tidak ditemukan');
        }
    } catch (PDOException $e) {
        setFlash('danger', 'Gagal menghapus paket: ' . $e->getMessage());
    }
}

header('Location: ' . base_url('paket/'));
exit;
