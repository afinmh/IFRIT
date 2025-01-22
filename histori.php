<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Arahkan ke login jika belum login
    exit;
}

$username = $_SESSION['username'];
// URL API yang akan diakses
$url = 'http://localhost/backend/apisensor.php'; // Ganti dengan URL endpoint yang sesuai

// Inisialisasi cURL
$ch = curl_init($url);

// Atur opsi cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Agar hasilnya dikembalikan sebagai string
curl_setopt($ch, CURLOPT_HTTPGET, true);       // Tentukan metode GET

// Eksekusi cURL
$response = curl_exec($ch);

// Periksa apakah ada error
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Tutup cURL
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);
$datachart = array_slice($data, -10); // Ambil 10 elemen terakhir
// Pagination logic
$itemsPerPage = 10; // Jumlah data per halaman
$totalItems = count($data); // Total data
$totalPages = ceil($totalItems / $itemsPerPage); // Jumlah halaman

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
$startIndex = ($page - 1) * $itemsPerPage; // Index data yang akan ditampilkan
$currentPageData = array_slice($data, $startIndex, $itemsPerPage); // Data untuk halaman ini
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/histori.css"> <!-- Link to external CSS -->
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="./img/logo1.png" alt="Logo">
            <h1>IFRIT</h1>
        </div>
        <ul>
            <li><a href="dashboard.php" style="text-decoration: none; color: inherit;">Dashboard</a></li>
            <li class="active"><a href="histori.php" style="text-decoration: none; color: inherit;">Histori</a></li>
            <li><a href="galeri.php" style="text-decoration: none; color: inherit;">Galeri</a></li>
            <li><a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a></li>
        </ul>
    </div>
    

    <!-- Main Content -->
    <div class="main-content">
        <h1>Histori</h1>
        <?php if (isset($currentPageData) && is_array($currentPageData)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pompa</th>
                        <th>Strobo</th>
                        <th>Speaker</th>
                        <th>Fire</th>
                        <th>Batre</th>
                        <th>Distance</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentPageData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= $row['pompa'] ?></td>
                            <td><?= $row['strobo'] ?></td>
                            <td><?= $row['speaker'] ?></td>
                            <td><?= $row['fire'] ?></td>
                            <td><?= $row['batre'] ?></td>
                            <td><?= htmlspecialchars($row['distance']) ?></td>
                            <td><?= htmlspecialchars($row['timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data yang tersedia.</p>
        <?php endif; ?>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i == $page ? 'style="background-color: #3f1a23;"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <button id="openChartModal" class="chart-btn">Chart</button>
        </div>

        <div class="modal" id="chartModal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Grafik Jarak</h2>
                <canvas id="distanceChart"></canvas>
            </div>
        </div>
    </div>

<script>
    // Modal Logic
const modal = document.getElementById('chartModal');
const chartBtn = document.getElementById('openChartModal');

// Open Modal when chart button is clicked
chartBtn.onclick = function() {
    modal.style.display = "block";
}

// Close Modal if the user clicks outside of the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Chart Data (use your own dataset here)
// Ambil data terbaru (10 data terakhir)
const labels = <?= json_encode(array_column(array_slice($data, -5), 'timestamp')) ?>;
const distances = <?= json_encode(array_column(array_slice($data, -5), 'distance')) ?>;

// Distance Chart
const ctxDistance = document.getElementById('distanceChart').getContext('2d');
new Chart(ctxDistance, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Distance',
            data: distances,
            backgroundColor: 'rgba(83, 34, 42, 0.2)',
            borderColor: '#53222A',
            borderWidth: 2,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { title: { display: true, text: 'Timestamp' } },
            y: { title: { display: true, text: 'Distance' } }
        }
    }
});

</script>

</body>
</html>
