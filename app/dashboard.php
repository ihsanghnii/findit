<?php
require_once 'config.php';
require_login();

// Ambil data barang user
$user_id = $_SESSION['user_id'];
$lost_items = $mysqli->query("SELECT * FROM lost_items WHERE user_id = $user_id ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
$found_items = $mysqli->query("SELECT * FROM found_items WHERE user_id = $user_id ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FindIt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 180px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .card:hover {
            transform: translateY(-3px);
            transition: 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            width: 250px;
            background-color: #0d6efd;
            color: white;
            min-height: 100vh;
            padding: 1.5rem 1rem;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }
        }

        .main-content {
            flex: 1;
            padding: 2rem 1rem;
        }

        @media (min-width: 768px) {
            .wrapper {
                display: flex;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Mobile -->
<nav class="navbar navbar-dark bg-primary d-md-none">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand">FindIt</span>
    </div>
</nav>

<!-- Sidebar Mobile (Offcanvas) -->
<div class="offcanvas offcanvas-start text-bg-primary" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <h4><a href="../index.php" class="text-white text-decoration-none">FindIt</a></h4>
        <p class="mt-3">Halo, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
        <a href="items/lost/create.php" class="btn btn-light w-100 mb-2">+ Barang Hilang</a>
        <a href="items/found/create.php" class="btn btn-light w-100 mb-2">+ Barang Ditemukan</a>
        <a href="auth/logout.php" class="btn btn-outline-light w-100">Logout</a>
    </div>
</div>

<!-- Wrapper untuk Desktop -->
<div class="wrapper">
    <!-- Sidebar Desktop -->
    <div class="sidebar d-none d-md-block">
        <h4><a href="../index.php" class="text-white text-decoration-none">FindIt</a></h4>
        <p class="mt-3">Halo, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
        <a href="items/lost/create.php" class="btn btn-light w-100 mb-2">+ Barang Hilang</a>
        <a href="items/found/create.php" class="btn btn-light w-100 mb-2">+ Barang Ditemukan</a>
        <a href="auth/logout.php" class="btn btn-outline-light w-100">Logout</a>
    </div>

    <!-- Konten Utama -->
    <main class="main-content">
        <h2 class="mb-4">Barang Hilang Anda</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-5">
            <?php foreach ($lost_items as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-light">
                        <img src="<?= htmlspecialchars('../' . $item['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text" style="max-height: 60px; overflow: hidden;">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Lokasi: <?= htmlspecialchars($item['location_lost']) ?></small><br>
                            <small class="text-muted">Tanggal: <?= htmlspecialchars($item['date_lost']) ?></small>
                            <div class="mt-2">
                                <a href="items/lost/edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="items/lost/delete.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="mb-4">Barang Ditemukan Anda</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($found_items as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-light">
                        <img src="<?= htmlspecialchars('../' . $item['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text" style="max-height: 60px; overflow: hidden;">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Lokasi: <?= htmlspecialchars($item['location_found']) ?></small><br>
                            <small class="text-muted">Tanggal: <?= htmlspecialchars($item['date_found']) ?></small>
                            <div class="mt-2">
                                <a href="items/found/edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="items/found/delete.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
