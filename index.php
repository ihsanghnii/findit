<?php
require_once 'app/config.php';

// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil 6 data terbaru untuk ditampilkan
$recent_lost = $mysqli->query("SELECT * FROM lost_items ORDER BY created_at DESC LIMIT 6")->fetch_all(MYSQLI_ASSOC);
$recent_found = $mysqli->query("SELECT * FROM found_items ORDER BY created_at DESC LIMIT 6")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindIt - Temukan Barang Hilang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="app/items/assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">FindIt</a>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-white me-3">Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></span>
                    <a href="app/auth/logout.php" class="btn btn-outline-light">Logout</a>
                <?php else: ?>
                    <a href="app/auth/login.php" class="btn btn-light me-2">Login</a>
                    <a href="app/auth/register.php" class="btn btn-outline-light">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Barang Hilang di Kampus? Tenang, Ada FindIt</h1><br>
            <p class="lead">Temukan atau laporkan barang hilangmu dengan mudah di lingkungan STT Nurul Fikri.
                Dari kelas, mushola, sampai kantin — semua bisa ditemukan kembali di sini.</p>
        </div>
    </section>

    <!-- Tombol Aksi -->
    <section class="container mb-5">
        <?php
        $form_link = isset($_SESSION['user_id']) ? 'app/dashboard.php' : 'app/auth/login.php';
        ?>
        <div class="card text-center shadow">
            <div class="card-body">
                <h4 class="card-title mb-3">Kehilangan atau Menemukan Barang?</h4>
                <p class="card-text">Klik tombol di bawah ini untuk melaporkan barang hilang atau ditemukan.</p>
                <a href="<?= $form_link ?>" class="btn btn-lg btn-primary">Daftarkan Barang</a>
            </div>
        </div>
    </section>

    <!-- service -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Kenapa Pakai Layanan Kami?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Komunitas Kampus</h3>
                    <p>Platform ini dibangun untuk membantu mahasiswa dan civitas akademika NF saling bantu menemukan barang yang hilang di sekitar kampus.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Proses Cepat</h3>
                    <p>Laporkan atau temukan barang hanya dalam beberapa klik. Sistem kami langsung menampilkan laporan terbaru dari sesama pengguna.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Terverifikasi & Aman</h3>
                    <p>Setiap laporan terhubung dengan akun pengguna, memastikan proses pelaporan dan pengembalian barang berlangsung lebih aman dan terpercaya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Lost Items -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Barang Hilang Terbaru</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($recent_lost as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-light">
                        <img src="<?= htmlspecialchars($item['image_path'] ?? 'assets/img/no-image.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text" style="max-height: 60px; overflow: hidden;">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Lokasi: <?= htmlspecialchars($item['location_lost']) ?></small><br>
                            <small class="text-muted">Tanggal: <?= htmlspecialchars($item['date_lost']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Recent Found Items -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Barang Ditemukan Terbaru</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($recent_found as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-light">
                        <img src="<?= htmlspecialchars($item['image_path'] ?? 'assets/img/no-image.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text" style="max-height: 60px; overflow: hidden;">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Lokasi: <?= htmlspecialchars($item['location_found']) ?></small><br>
                            <small class="text-muted">Tanggal: <?= htmlspecialchars($item['date_found']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> FindIt - Platform Penemuan Barang Hilang</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>