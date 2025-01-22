<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri</title>
    <link rel="stylesheet" href="css/histori.css"> <!-- Link to external CSS -->
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .gallery-item {
            border: 1px solid #ccc;
            padding: 5px;
            width: 200px;
            text-align: center;
        }
        .gallery-item img {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script>
        async function loadGallery() {
            try {
                const response = await fetch('apigambar.php');
                const result = await response.json();

                if (result.status === 'success') {
                    const gallery = document.querySelector('.gallery');
                    gallery.innerHTML = ''; // Clear existing content

                    result.data.forEach(item => {
                        const galleryItem = document.createElement('div');
                        galleryItem.classList.add('gallery-item');

                        galleryItem.innerHTML = `
                            <img src="data:image/jpeg;base64,${item.image}" alt="Image ${item.id}">
                            <p>${new Date(item.timestamp).toLocaleString()}</p>
                        `;
                        gallery.appendChild(galleryItem);
                    });
                } else {
                    alert('Gagal memuat galeri: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        document.addEventListener('DOMContentLoaded', loadGallery);
    </script>
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
            <li><a href="histori.php" style="text-decoration: none; color: inherit;">Histori</a></li>
            <li class="active"><a href="galeri.php" style="text-decoration: none; color: inherit;">Galeri</a></li>
            <li><a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a></li>
        </ul>
    </div>
    

    <!-- Main Content -->
    <div class="main-content">
        <h1>Deteksi Api</h1>
        <div class="gallery">
            <!-- Gambar akan ditambahkan secara dinamis oleh JavaScript -->
        </div>
    </div>
</body>
</html>
