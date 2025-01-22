<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        $_SESSION['message'] = 'Username atau password tidak boleh kosong.';
        $_SESSION['message_type'] = 'error';
        header('Location: login.php');
        exit;
    }

    // Query untuk mencocokkan data user
    $stmt = $conn->prepare("SELECT * FROM userakun WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['message'] = 'Login berhasil!';
        $_SESSION['message_type'] = 'success';
        header('Location: dashboard.php');
    } else {
        // Login gagal
        $_SESSION['message'] = 'Username atau password salah.';
        $_SESSION['message_type'] = 'error';
        header('Location: login.php');
    }

    $stmt->close();
} else {
    $_SESSION['message'] = 'Metode tidak valid.';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php');
}

$conn->close();
