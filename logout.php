<?php
session_start();

// Simpan pesan logout di variabel
$message = 'Berhasil Logout';
$type = 'success';

// Hapus session
session_unset();
session_destroy();

// Redirect dengan pesan
header("Location: login.php?message=" . urlencode($message) . "&type=" . urlencode($type));
exit;
?>
