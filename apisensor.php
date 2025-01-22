<?php
header('Content-Type: application/json');
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mendapatkan semua data dari database
    $sql = "SELECT * FROM data";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari body request
    $input = json_decode(file_get_contents('php://input'), true);

    $pompa = $input['pompa'] ?? null;
    $strobo = $input['strobo'] ?? null;
    $speaker = $input['speaker'] ?? null;
    $fire = $input['fire'] ?? null;
    $batre = $input['batre'] ?? null;
    $distance = $input['distance'] ?? null;

    if ($pompa && $strobo && $speaker && $fire && $batre && $distance) {
        $stmt = $conn->prepare("INSERT INTO data (pompa, strobo, speaker, fire, batre, distance, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssii", $pompa, $strobo, $speaker, $fire, $batre, $distance);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Data berhasil disimpan"]);
        } else {
            echo json_encode(["message" => "Gagal menyimpan data"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["message" => "Data tidak lengkap"]);
    }
} else {
    echo json_encode(["message" => "Metode tidak didukung"]);
}

$conn->close();
?>
