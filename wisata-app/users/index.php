<?php
/**
 * User Management - List View
 */
require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireAdmin();

$pageTitle = 'Pengguna - Admin';

// Fetch all users
$users = [];
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    setFlash('danger', 'Gagal memuat data pengguna');
}

include '../views/admin_header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Pengguna</h1>
        <p class="text-muted">Kelola semua pengguna terdaftar</p>
    </div>
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
                <th>Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted" style="padding: 3rem;">
                        <i class="fas fa-users" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                        Belum ada pengguna
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php
                            $userPhoto = $user['foto']
                                ? base_url('uploads/users/' . $user['foto'])
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama']) . '&background=0d9488&color=fff&size=50';
                            ?>
                            <img src="<?= $userPhoto ?>" alt="<?= htmlspecialchars($user['nama']) ?>" class="avatar">
                        </td>
                        <td><strong>
                                <?= htmlspecialchars($user['nama']) ?>
                            </strong></td>
                        <td>
                            <?= htmlspecialchars($user['email']) ?>
                        </td>
                        <td>
                            <span class="badge <?= $user['role'] === 'admin' ? 'badge-primary' : 'badge-info' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?= formatTanggal($user['created_at']) ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= base_url('users/edit.php?id=' . $user['id']) ?>" class="btn btn-ghost btn-sm"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../views/admin_footer.php'; ?>