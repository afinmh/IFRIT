<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password))  {
        $_SESSION['message'] = 'Semua field wajib diisi.';
        $_SESSION['message_type'] = 'error';
        header('Location: login.php');
        exit;
    }

    try {
        // Simpan data ke database tanpa enkripsi password
        $stmt = $conn->prepare("INSERT INTO userakun (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            // Registrasi berhasil
            $_SESSION['message'] = 'Registrasi berhasil! Silakan login.';
            $_SESSION['message_type'] = 'success';
            header('Location: login.php');
        } else {
            throw new Exception("Terjadi kesalahan saat menyimpan data.");
        }
    } catch (mysqli_sql_exception $e) {
        // Tangani error duplicate entry
        if ($e->getCode() === 1062) { // Kode 1062 untuk duplicate entry
            $_SESSION['message'] = 'Username sudah digunakan. Silakan pilih username lain.';
        } else {
            $_SESSION['message'] = 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
        }
        $_SESSION['message_type'] = 'error';
        header('Location: login.php');
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    $_SESSION['message'] = 'Metode tidak valid.';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php');
}
?>
