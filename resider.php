<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Masjid Resider Details</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h2 { background-color: #28a745; color: white; padding: 10px; }
        .section { background-color: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        input, select, textarea { padding: 8px; margin: 8px 0; width: 100%; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #218838; }
        .nav-buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .nav-buttons a {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }
        .nav-buttons a:hover {
            background: #218838;
            transform: scale(1.05);
        }
        .message { font-size: 16px; padding-top: 10px; }
    </style>
</head>
<body>

<h2>Masjid Resider Details</h2>

<!-- Navigation Links -->
<div class="nav-buttons">
    <a href="index.php">← Back to Dashboard</a>
    <a href="reports.php">📄 Go to Report</a>
</div>

<?php
// Fetch all regions for dropdown
$regions_result = $conn->query("SELECT region_id, region_name FROM regions ORDER BY region_name ASC");
?>

<!-- Section: Add New Resider -->
<div class="section">
    <h3>➕ Add / Update Resider Details</h3>
    <form method="POST">
        <label>Member ID (must exist):</label>
        <input type="text" name="member_id" id="member_id" required pattern="\d{6,}" minlength="6" maxlength="10" 
               title="Member ID must be at least 6 digits and numeric only.">
        <span id="error_msg" style="color: red; font-size: 14px;"></span>

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Region:</label>
        <select name="region_id" required>
            <option value="">-- Select Region --</option>
            <?php while ($region = $regions_result->fetch_assoc()): ?>
                <option value="<?= $region['region_id'] ?>"><?= htmlspecialchars($region['region_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Defined Monthly Amount:</label>
        <input type="number" name="defined_amount" step="0.01" required>

        <label>Family Member Count:</label>
        <input type="number" name="family_count" required>

        <button type="submit" name="add_resider">Save Resider</button>
    </form>

    <?php
    if (isset($_POST['add_resider'])) {
        $member_id = intval($_POST['member_id']);
        $name = $conn->real_escape_string($_POST['name']);
        $region_id = intval($_POST['region_id']);
        $defined_amount = floatval($_POST['defined_amount']);
        $family_count = intval($_POST['family_count']);

        // Check if member already exists
        $check = $conn->query("SELECT * FROM members WHERE member_id = $member_id");
        if ($check->num_rows > 0) {
            $sql = "UPDATE members 
                    SET name='$name', region_id=$region_id, defined_amount=$defined_amount, family_count=$family_count 
                    WHERE member_id = $member_id";
        } else {
            $sql = "INSERT INTO members (member_id, name, region_id, defined_amount, family_count)
                    VALUES ($member_id, '$name', $region_id, $defined_amount, $family_count)";
        }

        echo '<div class="message">';
        if ($conn->query($sql)) {
            echo "<span style='color:green;'>✅ Resider details saved successfully!</span>";
        } else {
            echo "<span style='color:red;'>❌ Error: " . $conn->error . "</span>";
        }
        echo '</div>';
    }
    ?>
</div>

<!-- Section: View All Residers -->
<div class="section">
    <h3>👥 All Masjid Residers</h3>

    <table>
        <tr>
            <th>Member ID</th>
            <th>Name</th>
            <th>Region</th>
            <th>Defined Amount</th>
            <th>Family Count</th>
        </tr>
        <?php
        $residers = $conn->query("SELECT m.*, r.region_name AS region_name 
                                  FROM members m 
                                  LEFT JOIN regions r ON m.region_id = r.region_id  
                                  ORDER BY m.member_id ASC");
        while ($row = $residers->fetch_assoc()) {
            echo "<tr>
                <td>{$row['member_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['region_name']}</td>
                <td>{$row['defined_amount']}</td>
                <td>{$row['family_count']}</td>
            </tr>";
        }
        ?>
    </table>
</div>

<script>
document.querySelector("form").addEventListener("submit", function (e) {
    const memberId = document.getElementById("member_id").value.trim();
    const errorMsg = document.getElementById("error_msg");

    if (!/^\d{6,}$/.test(memberId)) {
        errorMsg.textContent = "❌ Member ID must be at least 6 digits and contain only numbers.";
        e.preventDefault();
    } else {
        errorMsg.textContent = "";
    }
});
</script>

</body>
</html>
