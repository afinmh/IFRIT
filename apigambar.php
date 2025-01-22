<?php
header("Content-Type: application/json");
require_once 'config.php';

$response = [];

try {
    $sql = "SELECT id, image, timestamp FROM fire_image ORDER BY timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $image, $timestamp);

    while ($stmt->fetch()) {
        $response[] = [
            'id' => $id,
            'image' => base64_encode($image), // Encode gambar ke Base64
            'timestamp' => $timestamp,
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $response]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
