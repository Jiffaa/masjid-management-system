<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $residing_from = $_POST['residing_from'];
    $contact_info = $_POST['contact_info'];

    $stmt = $conn->prepare("INSERT INTO residers (member_id, residing_from, contact_info) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $member_id, $residing_from, $contact_info);
    $stmt->execute();
    $stmt->close();
    $success = true;
}

// Fetch members for dropdown
$members = [];
$result = $conn->query("SELECT member_id, name FROM members");
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Masjid Resider</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('images/masjid_foto.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      text-align: center;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.7);
      min-height: 100vh;
      padding: 40px 20px;
    }

    h1 {
      font-size: 2.8em;
      margin-bottom: 30px;
    }

    form {
      max-width: 500px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }

    select, input[type="text"], input[type="date"] {
      width: 90%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      font-size: 1em;
    }

    input[type="submit"] {
      background-color: #28a745;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 1.1em;
      cursor: pointer;
      margin-top: 15px;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #218838;
    }

    .nav-buttons {
      margin-top: 40px;
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
    }

    .nav-buttons a {
      background: rgba(255, 255, 255, 0.2);
      padding: 12px 20px;
      border-radius: 10px;
      text-decoration: none;
      color: #fff;
      font-weight: bold;
      transition: 0.3s ease;
    }

    .nav-buttons a:hover {
      background: rgba(255, 255, 255, 0.4);
      transform: scale(1.05);
    }

    .success-msg {
      background: rgba(40, 167, 69, 0.8);
      padding: 10px 20px;
      border-radius: 10px;
      margin-top: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="overlay">
    <h1>Register Masjid Resider</h1>

    <?php if (!empty($success)): ?>
      <div class="success-msg">Resider record added successfully!</div>
    <?php endif; ?>

    <form method="POST">
      <select name="member_id" required>
        <option value="">Select Member</option>
        <?php foreach ($members as $member): ?>
          <option value="<?= $member['member_id'] ?>"><?= htmlspecialchars($member['name']) ?></option>
        <?php endforeach; ?>
      </select><br>

      <input type="date" name="residing_from" placeholder="Residing From Date" required><br>
      <input type="text" name="contact_info" placeholder="Contact Info" required><br>

      <input type="submit" value="Add Resider">
    </form>

    <div class="nav-buttons">
      <a href="index.php">&larr; Dashboard</a>
      <a href="report.php">Report &rarr;</a>
    </div>
  </div>
</body>
</html>
