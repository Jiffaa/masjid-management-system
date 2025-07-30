<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Subscription</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #eef2f3;
      color: #333;
    }

    header {
      background-color: #007BFF;
      padding: 20px;
      color: white;
      text-align: center;
    }

    .nav-links {
      display: flex;
      justify-content: space-between;
      background-color: #f8f9fa;
      padding: 10px 20px;
      border-bottom: 1px solid #ccc;
    }

    .nav-links a {
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
    }

    .container {
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #007BFF;
      border-bottom: 2px solid #007BFF;
      padding-bottom: 5px;
    }

    .section {
      margin-bottom: 30px;
    }

    input, select, textarea, button {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    button {
      background-color: #007BFF;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #007BFF;
      color: white;
    }

    .footer-nav {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #ccc;
    }

    .footer-nav a {
      background: #007BFF;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }

    .footer-nav a:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

<header>
  <h1>🕌 Masjid Member Subscription</h1>
</header>

<div class="nav-links">
  <a href="index.php">← Dashboard</a>
  <a href="report.php">Report →</a>
</div>

<div class="container">

  <!-- Search Subscription -->
  <div class="section">
    <h2>🔍 Search Member Subscriptions</h2>
    <form method="GET">
      <label>Enter Member ID:</label>
      <input type="number" name="search_member_id" required />
      <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['search_member_id'])) {
        $member_id = intval($_GET['search_member_id']);
        $member = $conn->query("SELECT * FROM members WHERE member_id = $member_id")->fetch_assoc();

        if ($member) {
            echo "<h3>Member: {$member['name']} (ID: $member_id)</h3>";
            $result = $conn->query("SELECT * FROM subscriptions WHERE member_id = $member_id ORDER BY paid_date DESC");

            echo "<table><tr><th>Date</th><th>Amount</th><th>Remarks</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['paid_date']}</td><td>Rs. {$row['amount']}</td><td>{$row['remarks']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:red;'>No member found.</p>";
        }
    }
    ?>
  </div>

  <!-- Add Subscription -->
  <div class="section">
    <h2>💰 Enter New Subscription</h2>
    <form method="POST">
      <label>Member ID:</label>
      <input type="number" name="member_id" required>
      <label>Paid Date:</label>
      <input type="date" name="paid_date" required>
      <label>Amount (Rs.):</label>
      <input type="number" name="amount" step="0.01" required>
      <label>Remarks:</label>
      <textarea name="remarks"></textarea>
      <button type="submit" name="add_subscription">Add Payment</button>
    </form>

    <?php
    if (isset($_POST['add_subscription'])) {
        $member_id = intval($_POST['member_id']);
        $paid_date = $_POST['paid_date'];
        $amount = floatval($_POST['amount']);
        $remarks = $conn->real_escape_string($_POST['remarks']);

        $sql = "INSERT INTO subscriptions (member_id, paid_date, amount, remarks)
                VALUES ($member_id, '$paid_date', $amount, '$remarks')";

        if ($conn->query($sql)) {
            echo "<p style='color:green;'>Subscription added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }
    ?>
  </div>

  <!-- Add Info -->
  <div class="section">
    <h2>📝 Additional Member Info</h2>
    <form method="POST">
      <label>Member ID:</label>
      <input type="number" name="info_member_id" required>
      <label>Info Key:</label>
      <input type="text" name="info_key" required>
      <label>Info Value:</label>
      <input type="text" name="info_value" required>
      <button type="submit" name="add_info">Add Info</button>
    </form>

    <?php
    if (isset($_POST['add_info'])) {
        $mid = intval($_POST['info_member_id']);
        $key = $conn->real_escape_string($_POST['info_key']);
        $val = $conn->real_escape_string($_POST['info_value']);

        $sql = "INSERT INTO member_info (member_id, info_key, info_value)
                VALUES ($mid, '$key', '$val')";

        if ($conn->query($sql)) {
            echo "<p style='color:green;'>Info added!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }
    ?>
  </div>

  <!-- Navigation Footer -->
  <div class="footer-nav">
    <a href="index.php">← Back to Dashboard</a>
    <a href="reports.php">Go to Report →</a>
  </div>

</div>

</body>
</html>
