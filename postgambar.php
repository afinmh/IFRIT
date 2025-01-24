<?php
header("Content-Type: application/json");
require_once 'config.php'; // File konfigurasi database

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']); // Membaca file gambar
        $timestamp = date('Y-m-d H:i:s');

        try {
            $sql = "INSERT INTO fire_image (image, timestamp) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $image, $timestamp);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'Gambar berhasil diunggah',
                'id' => $stmt->insert_id
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Gagal menyimpan gambar: ' . $e->getMessage()
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Gagal mengunggah gambar. Pastikan file gambar valid.'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid. Gunakan POST.'
    ];
}

echo json_encode($response);
?>
