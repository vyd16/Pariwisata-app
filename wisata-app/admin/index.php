<?php
/**
 * Admin Dashboard - Enhanced with Reports
 */
require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireAdmin();

$pageTitle = 'Dashboard - Admin';

// Basic Stats
$stats = [
    'users' => 0,
    'packages' => 0,
    'bookings' => 0,
    'revenue' => 0,
    'pending_bookings' => 0,
    'today_bookings' => 0
];

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $stats['users'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM paket");
    $stats['packages'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM booking");
    $stats['bookings'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COALESCE(SUM(total_harga), 0) FROM booking WHERE status = 'confirmed'");
    $stats['revenue'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM booking WHERE status = 'pending'");
    $stats['pending_bookings'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM booking WHERE DATE(created_at) = CURDATE()");
    $stats['today_bookings'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Tables might not exist yet
}

// Get recent bookings
$recentBookings = [];
try {
    $stmt = $pdo->query("
        SELECT b.*, u.nama as user_nama, p.nama as paket_nama 
        FROM booking b 
        JOIN users u ON b.user_id = u.id 
        JOIN paket p ON b.paket_id = p.id 
        ORDER BY b.created_at DESC 
        LIMIT 5
    ");
    $recentBookings = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tables might not exist
}

// Get booking status breakdown
$bookingStatus = ['pending' => 0, 'confirmed' => 0, 'cancelled' => 0];
try {
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM booking GROUP BY status");
    while ($row = $stmt->fetch()) {
        $bookingStatus[$row['status']] = $row['count'];
    }
} catch (PDOException $e) {
}

// Get daily revenue for the last 1 month
$dailyRevenue = [];
try {
    $stmt = $pdo->query("
        SELECT 
            DATE(created_at) as day,
            SUM(CASE WHEN status = 'confirmed' THEN total_harga ELSE 0 END) as revenue
        FROM booking 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        GROUP BY DATE(created_at)
        ORDER BY day ASC
    ");
    $dailyRevenue = $stmt->fetchAll();
} catch (PDOException $e) {
}

// Get popular packages
$popularPackages = [];
try {
    $stmt = $pdo->query("
        SELECT p.*, COUNT(b.id) as booking_count, SUM(b.total_harga) as total_revenue
        FROM paket p
        LEFT JOIN booking b ON p.id = b.paket_id AND b.status != 'cancelled'
        GROUP BY p.id
        ORDER BY booking_count DESC
        LIMIT 5
    ");
    $popularPackages = $stmt->fetchAll();
} catch (PDOException $e) {
}

// Get recent users
$recentUsers = [];
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
    $recentUsers = $stmt->fetchAll();
} catch (PDOException $e) {
}

// Prepare chart data for daily revenue
$chartLabels = [];
$chartData = [];

foreach ($dailyRevenue as $dr) {
    $date = new DateTime($dr['day']);
    $chartLabels[] = $date->format('d M');
    $chartData[] = (float) $dr['revenue'];
}
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
    <script src="<?= base_url('assets/js/chart.min.js') ?>"></script>

    <!-- Theme Initializer (prevents flash of wrong theme) -->
    <script>
        (function () {
            var theme = localStorage.getItem('wisata-theme');
            if (theme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            } else if (!theme && window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
</head>

<body>
    <div class="admin-layout">
        <?php include '../views/sidebar.php'; ?>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="text-muted">Selamat datang,
                        <?= htmlspecialchars(getCurrentUser()['nama'] ?? 'Admin') ?>!
                    </p>
                </div>
            </div>

            <?php if ($flash = getFlash()): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['users']) ?></h3>
                        <p>Total Pengguna</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon secondary">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['packages']) ?></h3>
                        <p>Paket Wisata</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['bookings']) ?></h3>
                        <p>Total Pesanan</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= formatRupiah($stats['revenue']) ?></h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card" style="border-left: 4px solid var(--warning);">
                    <div class="stat-icon" style="background: rgba(234, 179, 8, 0.2); color: var(--warning);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['pending_bookings']) ?></h3>
                        <p>Menunggu Konfirmasi</p>
                    </div>
                </div>

                <div class="stat-card" style="border-left: 4px solid var(--primary);">
                    <div class="stat-icon primary">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['today_bookings']) ?></h3>
                        <p>Pesanan Hari Ini</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-grid"
                style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Revenue Chart -->
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-bottom: 1rem;"><i class="fas fa-chart-line text-primary"></i> Pendapatan 1
                            Bulan Terakhir</h4>
                        <div style="position: relative; height: 250px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Booking Status Chart -->
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-bottom: 1rem;"><i class="fas fa-chart-pie text-secondary"></i> Status Pesanan
                        </h4>
                        <div style="position: relative; height: 200px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div
                            style="margin-top: 1rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                            <span><i class="fas fa-circle" style="color: #eab308;"></i> Pending
                                (<?= $bookingStatus['pending'] ?>)</span>
                            <span><i class="fas fa-circle" style="color: #22c55e;"></i> Confirmed
                                (<?= $bookingStatus['confirmed'] ?>)</span>
                            <span><i class="fas fa-circle" style="color: #ef4444;"></i> Cancelled
                                (<?= $bookingStatus['cancelled'] ?>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Packages & Recent Users -->
            <div class="charts-grid"
                style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Popular Packages -->
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-bottom: 1rem;"><i class="fas fa-fire text-secondary"></i> Paket Terpopuler
                        </h4>
                        <?php if (empty($popularPackages)): ?>
                            <p class="text-muted">Belum ada data</p>
                        <?php else: ?>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <?php foreach ($popularPackages as $index => $pkg): ?>
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                                        <span
                                            style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; font-weight: bold; font-size: 0.85rem;">
                                            <?= $index + 1 ?>
                                        </span>
                                        <div style="flex: 1;">
                                            <strong style="font-size: 0.9rem;"><?= htmlspecialchars($pkg['nama']) ?></strong>
                                            <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted);">
                                                <?= $pkg['booking_count'] ?> booking •
                                                <?= formatRupiah($pkg['total_revenue'] ?? 0) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-bottom: 1rem;"><i class="fas fa-user-plus text-primary"></i> Pengguna Terbaru
                        </h4>
                        <?php if (empty($recentUsers)): ?>
                            <p class="text-muted">Belum ada data</p>
                        <?php else: ?>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <?php foreach ($recentUsers as $user): ?>
                                    <?php
                                    $userPhoto = $user['foto']
                                        ? base_url('uploads/users/' . $user['foto'])
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama']) . '&background=0d9488&color=fff&size=40';
                                    ?>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="<?= $userPhoto ?>" alt="" class="avatar" style="width: 40px; height: 40px;">
                                        <div style="flex: 1;">
                                            <strong style="font-size: 0.9rem;"><?= htmlspecialchars($user['nama']) ?></strong>
                                            <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted);">
                                                <?= htmlspecialchars($user['email']) ?>
                                            </p>
                                        </div>
                                        <span class="badge <?= $user['role'] === 'admin' ? 'badge-primary' : 'badge-info' ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div>
                <h3 style="margin-bottom: 1rem;">Pesanan Terbaru</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentBookings)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted" style="padding: 2rem;">
                                        Belum ada pesanan
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td><strong>#<?= $booking['id'] ?></strong></td>
                                        <td><?= htmlspecialchars($booking['user_nama']) ?></td>
                                        <td><?= htmlspecialchars($booking['paket_nama']) ?></td>
                                        <td><?= formatTanggal($booking['tanggal_berangkat']) ?></td>
                                        <td><?= formatRupiah($booking['total_harga']) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'badge-warning',
                                                'confirmed' => 'badge-success',
                                                'cancelled' => 'badge-danger'
                                            ][$booking['status']] ?? 'badge-info';
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('booking/detail.php?id=' . $booking['id']) ?>"
                                                class="btn btn-ghost btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Chart.js is available
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                document.querySelectorAll('canvas').forEach(function (canvas) {
                    canvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #ef4444;"><i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-right: 0.5rem;"></i> Chart.js gagal dimuat</div>';
                });
                return;
            }

            console.log('Chart.js loaded successfully, version:', Chart.version);

            // Revenue Chart
            try {
                const revenueCanvas = document.getElementById('revenueChart');
                if (revenueCanvas) {
                    const revenueCtx = revenueCanvas.getContext('2d');
                    const chartLabels = <?= json_encode($chartLabels) ?: '[]' ?>;
                    const chartData = <?= json_encode($chartData) ?: '[]' ?>;

                    // If no data, show placeholder
                    if (chartLabels.length === 0) {
                        revenueCanvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted);"><i class="fas fa-chart-line" style="font-size: 2rem; margin-right: 0.5rem;"></i> Belum ada data pendapatan</div>';
                    } else {
                        new Chart(revenueCtx, {
                            type: 'line',
                            data: {
                                labels: chartLabels,
                                datasets: [{
                                    label: 'Pendapatan',
                                    data: chartData,
                                    borderColor: '#0d9488',
                                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#0d9488',
                                    pointRadius: 5,
                                    pointHoverRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function (value) {
                                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                            },
                                            color: '#94a3b8'
                                        },
                                        grid: {
                                            color: 'rgba(255,255,255,0.05)'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#94a3b8'
                                        },
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Revenue chart error:', e);
            }

            // Status Chart
            try {
                const statusCanvas = document.getElementById('statusChart');
                if (statusCanvas) {
                    const statusCtx = statusCanvas.getContext('2d');
                    const statusData = [<?= $bookingStatus['pending'] ?>, <?= $bookingStatus['confirmed'] ?>, <?= $bookingStatus['cancelled'] ?>];
                    const hasData = statusData.some(v => v > 0);

                    if (!hasData) {
                        statusCanvas.parentElement.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted);"><i class="fas fa-chart-pie" style="font-size: 2rem; margin-right: 0.5rem;"></i> Belum ada pesanan</div>';
                    } else {
                        new Chart(statusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Pending', 'Confirmed', 'Cancelled'],
                                datasets: [{
                                    data: statusData,
                                    backgroundColor: ['#eab308', '#22c55e', '#ef4444'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                cutout: '60%'
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Status chart error:', e);
            }
        });
    </script>
</body>

</html>