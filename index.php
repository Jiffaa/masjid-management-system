<?php
include 'db.php';

$result = $conn->query("SELECT * FROM masjid_info LIMIT 1");
$masjid = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Masjid Dashboard</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Amiri&display=swap');

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('images/masjid_foto.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      text-align: center;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 50px 20px;
      min-height: 100vh;
    }

    .masjid-info {
      font-size: 1.8em;
      margin-bottom: 40px;
      text-shadow: 2px 2px 4px #000;
    }

    .masjid-info h1 {
      font-size: 3.5em;
      margin-bottom: 0.2em;
    }

    .links {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      max-width: 900px;
      margin: 0 auto;
    }

    .link-card {
      background: rgba(255, 255, 255, 0.15);
      padding: 30px 20px;
      border-radius: 20px;
      text-decoration: none;
      color: #fff;
      font-size: 1.5em;
      transition: all 0.3s ease;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }

    .link-card:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.07);
    }

    footer {
      margin-top: 60px;
      font-size: 0.9em;
      color: #ccc;
    }

    .tagline {
      font-size: 1.2em;
      margin-bottom: 25px;
      font-style: italic;
    }

    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      direction: rtl;
      background-color: rgba(0, 0, 0, 0.7);
      padding: 10px 0;
      font-family: 'Amiri', serif;
      font-size: 1.6em;
      color: #00ff00; /* bright green */
      margin-bottom: 10px;
    }

    .marquee-text {
      display: inline-block;
      padding-left: 100%;
      animation: scroll-left 15s linear infinite;
    }

    @keyframes scroll-left {
      0% {
        transform: translateX(0%);
      }
      100% {
        transform: translateX(-100%);
      }
    }
  </style>
</head>
<body>
  <div class="marquee-container">
    <div class="marquee-text">السَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللَّهِ وَبَرَكَاتُهُ — بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>
  </div>

  <div class="overlay">
    <div class="masjid-info">
      <h1><?= $masjid['name'] ?? 'Masjid Name Not Set' ?></h1>
      <p><strong></strong> <?= $masjid['address'] ?? 'Address Not Set' ?></p>
      <p><strong>Contact:</strong> <?= $masjid['contact_info'] ?? 'Contact Info Not Set' ?></p>
    </div>

    <div class="tagline">Welcome to your Masjid's centralized management system</div>

    <div class="links">
      <a class="link-card" href="subscription.php">🧾 Member Subscriptions</a>
      <a class="link-card" href="resider.php">🏠 Masjid Resider Details</a>
      <a class="link-card" href="reports.php">📊 Reports</a>
    </div>

    <footer>
      &copy; <?= date('Y') ?> Masjid Management System. All rights reserved.
    </footer>
  </div>
</body>
</html>
