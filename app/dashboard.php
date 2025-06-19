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
    <title>Dashboard - FindIt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">FindIt</a>
            <div>
                <span class="navbar-text me-3">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="items/lost/create.php" class="btn btn-light me-2">+ Barang Hilang</a>
                <a href="items/found/create.php" class="btn btn-light me-2">+ Barang Ditemukan</a>
                <a href="auth/logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4">Barang Hilang Anda</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?php foreach($lost_items as $item): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?= htmlspecialchars('../' . $item['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
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
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach($found_items as $item): ?>
            <div class="col">
                <div class="card h-100">
                   <img src="<?= htmlspecialchars('../' . $item['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
