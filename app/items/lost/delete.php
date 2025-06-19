<?php
require_once '../../config.php';
require_login();

// Ambil data item
$item_id = $_GET['id'] ?? 0;
$stmt = $mysqli->prepare("SELECT * FROM lost_items WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $item_id, $_SESSION['user_id']);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

// Jika tidak ada atau bukan milik user
if (!$item) {
    header('Location: ../../dashboard.php');
    exit;
}

// Proses penghapusan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus gambar jika ada
    if ($item['image_path'] && file_exists('../../'.$item['image_path'])) {
        unlink('../../'.$item['image_path']);
    }

    // Hapus dari database
    $delete = $mysqli->prepare("DELETE FROM lost_items WHERE id = ?");
    $delete->bind_param("i", $item_id);
    $delete->execute();

    header('Location: ../../dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Barang Hilang - FindIt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../../../index.php">FindIt</a>
            <div>
                <span class="navbar-text me-3">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="../../dashboard.php" class="btn btn-light me-2">Kembali</a>
                <a href="../../auth/logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4">Hapus Barang Hilang</h2>
        
        <div class="alert alert-danger">
            <h4>Apakah Anda yakin ingin menghapus barang ini?</h4>
            <p><strong><?= htmlspecialchars($item['title']) ?></strong></p>
            <p><?= htmlspecialchars($item['description']) ?></p>
            
            <form method="POST" class="mt-3">
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                <a href="../../dashboard.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
