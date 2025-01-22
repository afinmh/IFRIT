<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="js/mqttws31.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript">
        var connected_flag = 0;
        var mqtt;
        var reconnectTimeout = 2000;
        var host = "broker.hivemq.com";
        var port = 8884;

        function onConnectionLost() {
            console.log("Connection lost");
            document.getElementById("status").innerHTML = "Connection Lost";
            connected_flag = 0;
        }

        function onFailure(message) {
            console.log("Failed");
            setTimeout(MQTTconnect, reconnectTimeout);
        }

        function onMessageArrived(r_message) {
            let payload = JSON.parse(r_message.payloadString);
            if (r_message.destinationName === "motorffitenass/sensor") {
                // Update the elements with new data
                document.getElementById("pompa").innerHTML =  payload.pompa;
                document.getElementById("strobo").innerHTML = payload.strobo;
                document.getElementById("speaker").innerHTML = payload.speaker;
                document.getElementById("fire").innerHTML = payload.fire;
                document.getElementById("distance").innerHTML = payload.distance + " cm";
                
                // Update chart data
                updateCharts(payload);
            }

        }

        function onConnect() {
            connected_flag = 1;
            document.getElementById("status").innerHTML = "Connected";
            console.log("Connected");

            mqtt.subscribe("motorffitenass/sensor");
        }

        function MQTTconnect() {
            console.log("Connecting to " + host + ":" + port);

            let clientId = "clientId-" + Math.floor(Math.random() * 10000);
            mqtt = new Paho.MQTT.Client(host, port, clientId);

            let options = {
                timeout: 3,
                onSuccess: onConnect,
                onFailure: onFailure,
                useSSL: true,
            };

            mqtt.onConnectionLost = onConnectionLost;
            mqtt.onMessageArrived = onMessageArrived;

            mqtt.connect(options);
        }

        window.onload = function () {
            MQTTconnect();
        }
        
        function updateCharts(payload) {
            if (distanceChart.data.datasets[0].data.length >= 8) {
            distanceChart.data.datasets[0].data.shift();
            distanceChart.data.labels.shift();
            }

            distanceChart.data.datasets[0].data.push(payload.distance);
            distanceChart.data.labels.push(new Date().toLocaleTimeString());
            distanceChart.update();

            batteryChart.data.datasets[0].data = [payload.batre, 100 - payload.batre];
            updateBatteryColor(payload.batre); 
            batteryChart.update();
        }

        function updateBatteryColor(batteryPercentage) {
            let color = 'green';
            if (batteryPercentage < 50) {
                color = 'yellow';
            }
            if (batteryPercentage < 20) {
                color = 'red';
            }

            batteryChart.data.datasets[0].backgroundColor = [color, '#e0e0e0']; 
            document.getElementById("battery-percentage").innerHTML = batteryPercentage + "%";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="./img/logo1.png" alt="Logo">
            <h1>IFRIT</h1>
        </div>
        <ul>
            <li class="active">Dashboard</li>
            <li><a href="histori.php" style="text-decoration: none; color: inherit;">Histori</a></li>
            <li><a href="galeri.php" style="text-decoration: none; color: inherit;">Galeri</a></li>
            <li><a href="logout.php" style="text-decoration: none; color: inherit;">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Dashboard</h1>

        <div class="cards">
            <div class="card">
                <i class="fas fa-check-circle card-icon"></i>
                <div class="card-content">
                    <h2>Status: </h2>
                    <p id="status">Disconnect</p>
                </div>
        </div>
        <div class="card">
            <i class="fas fa-pump-medical card-icon"></i>
            <div class="card-content">
                <h2>Pompa: </h2>
                <p id="pompa">OFF</p>
            </div>
        </div>
        <div class="card">
            <i class="fas fa-volume-up card-icon"></i>
            <div class="card-content">
                <h2>Speaker: </h2>
                <p id="speaker">OFF</p>
            </div>
        </div>
        <div class="card">
            <i class="fas fa-lightbulb card-icon"></i>
            <div class="card-content">
                <h2>Strobo: </h2>
                <p id="strobo">OFF</p>
            </div>
        </div>
        <div class="card">
            <i class="fas fa-fire-alt card-icon"></i>
            <div class="card-content">
                <h2>Fire: </h2>
                <p id="fire">Aman</p>   
            </div>
        </div>
        <div class="card">
            <i class="fas fa-random card-icon"></i>
            <div class="card-content">
                <h2>Distance</h2>
                <p id="distance">0 cm</p>
            </div>
        </div>
</div>


        <!-- Charts Section -->
        <div class="charts-container">
            <div class="chart-section">
                <h3>Grafik Jarak</h3>
                <div class="chart-container">
                    <canvas id="distanceChart"></canvas>
                </div>
            </div>
            <div class="chart-section">
                <h3>Presentase Batre</h3>
                <div class="battery-chart">
                    <canvas id="batteryChart"></canvas>
                    <div class="battery-percentage" id="battery-percentage">100%</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Distance Chart
        const distanceChartCtx = document.getElementById('distanceChart').getContext('2d');
        const distanceChart = new Chart(distanceChartCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Distance',
                    data: [],
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
                    y: { title: { display: true, text: 'Distance (cm)' } }
                }
            }
        });

        // Battery Chart
        const batteryChartCtx = document.getElementById('batteryChart').getContext('2d');
        const batteryChart = new Chart(batteryChartCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [100, 0],  // Initial battery percentage (100% full)
                    backgroundColor: ['green', '#e0e0e0']
                }]
            },
            options: {
                responsive: true,
                cutout: '80%',  // Creates a donut chart
                plugins: {
                    tooltip: { enabled: false },
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>
