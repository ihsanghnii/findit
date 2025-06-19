<?php
require_once '../../config.php';
require_login();

// Ambil data item
$item_id = $_GET['id'] ?? 0;
$stmt = $mysqli->prepare("SELECT * FROM found_items WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $item_id, $_SESSION['user_id']);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

// Jika tidak ada atau bukan milik user
if (!$item) {
    header('Location: ../dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $location = sanitize_input($_POST['location']);
    $date = sanitize_input($_POST['date']);
    $image_path = $item['image_path'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Hapus gambar lama jika ada
        if ($image_path && file_exists('../../'.$image_path)) {
            unlink('../../'.$image_path);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'found_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = 'uploads/' . $filename;
        }
    }

    // Update database
    $update = $mysqli->prepare("UPDATE found_items SET title = ?, description = ?, location_found = ?, date_found = ?, image_path = ? WHERE id = ?");
    $update->bind_param("sssssi", $title, $description, $location, $date, $image_path, $item_id);
    
    if ($update->execute()) {
        header('Location: ../dashboard.php');
        exit;
    } else {
        $error = 'Gagal memperbarui data';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang Ditemukan - FindIt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">FindIt</a>
            <div>
                <span class="navbar-text me-3">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="../dashboard.php" class="btn btn-light me-2">Kembali</a>
                <a href="../auth/logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4">Edit Barang Ditemukan</h2>
        
        <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($item['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi Ditemukan</label>
                <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($item['location_found']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Tanggal Ditemukan</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($item['date_found']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar (Opsional)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <?php if ($item['image_path']): ?>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
