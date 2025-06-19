<?php
require_once '../config.php';

// Hapus session
session_unset();
session_destroy();

// Redirect ke halaman utama
header('Location: ../../index.php');
exit;
?>
