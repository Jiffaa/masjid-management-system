<?php include 'db.php'; ?>
<?php
$where = "WHERE 1=1";
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where .= " AND s.paid_date BETWEEN '$from' AND '$to'";
}
if (!empty($_GET['region'])) {
    $region = $_GET['region'];
    $where .= " AND m.region_id IN (SELECT region_id FROM regions WHERE region_name = '$region')";
}
if (!empty($_GET['family_min'])) {
    $min = $_GET['family_min'];
    $where .= " AND m.family_count >= $min";
}
if (!empty($_GET['family_max'])) {
    $max = $_GET['family_max'];
    $where .= " AND m.family_count <= $max";
}
if (!empty($_GET['amount_min'])) {
    $amin = $_GET['amount_min'];
    $where .= " AND s.amount >= $amin";
}
if (!empty($_GET['amount_max'])) {
    $amax = $_GET['amount_max'];
    $where .= " AND s.amount <= $amax";
}
if (!empty($_GET['payment_status'])) {
    $status = $_GET['payment_status'];
    if ($status === "Not Paid") {
        $where .= " AND s.amount = 0";
    } elseif ($status === "Partial") {
        $where .= " AND s.amount > 0 AND s.amount < m.defined_amount";
    } elseif ($status === "Paid") {
        $where .= " AND s.amount >= m.defined_amount";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <style>
        body { font-family: Arial; background-color: #f4f4f4; padding: 20px; }
        h2 { background-color: #6c757d; color: white; padding: 10px; }
        .section { background-color: white; padding: 20px; margin-bottom: 30px; border-radius: 8px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #ddd; }
        input, select { padding: 5px; width: 200px; margin-right: 10px; }
        button, a.export-btn { margin-top: 10px; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block; }
        button:hover, a.export-btn:hover { background-color: #0056b3; }
        .nav-buttons { margin-bottom: 20px; }
    </style>
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<div class="nav-buttons">
    <a href="index.php">🏠 Dashboard</a>
</div>
<h2>📊 Masjid Reports</h2>
<div class="section" id="subscriptions-section">
    <h3>📄 All Subscription Data</h3>
    <form method="GET" style="margin-bottom: 15px;">
        <label>From:
            <input type="date" name="from_date" value="<?php echo $_GET['from_date'] ?? ''; ?>">
        </label>
        <label>To:
            <input type="date" name="to_date" value="<?php echo $_GET['to_date'] ?? ''; ?>">
        </label>
        <label>Region:
            <select name="region">
    <option value="">All</option>
    <?php
    $region_sql = "SELECT region_name FROM regions ORDER BY region_name";
    $region_res = $conn->query($region_sql);
    while ($reg = $region_res->fetch_assoc()) {
        $selected = ($_GET['region'] ?? '') === $reg['region_name'] ? 'selected' : '';
        echo "<option value=\"{$reg['region_name']}\" $selected>{$reg['region_name']}</option>";
    }
    ?>
</select>
        </label>
		
        <label>Family Count:
            <input type="number" name="family_min" placeholder="Min" value="<?= $_GET['family_min'] ?? '' ?>">
            <input type="number" name="family_max" placeholder="Max" value="<?= $_GET['family_max'] ?? '' ?>">
        </label>
        <label>Amount:
            <input type="number" name="amount_min" placeholder="Min" value="<?= $_GET['amount_min'] ?? '' ?>">
            <input type="number" name="amount_max" placeholder="Max" value="<?= $_GET['amount_max'] ?? '' ?>">
        </label>
        <label>Status:
            <select name="payment_status">
                <option value="">All</option>
                <option value="Paid" <?= ($_GET['payment_status'] ?? '') === 'Paid' ? 'selected' : '' ?>>Paid</option>
                <option value="Not Paid" <?= ($_GET['payment_status'] ?? '') === 'Not Paid' ? 'selected' : '' ?>>Not Paid</option>
                <option value="Partial" <?= ($_GET['payment_status'] ?? '') === 'Partial' ? 'selected' : '' ?>>Partial</option>
            </select>
        </label>
        <button type="submit">Filter</button>
        <a href="report.php" class="export-btn" style="background-color:#6c757d;">Reset</a>
    </form>
    <button class="export-btn" onclick="exportToExcel('subs-table', 'All_Subscriptions')">Export to Excel</button>
    <button class="export-btn" onclick="exportToPDF('subscriptions-section', 'All_Subscriptions')">Export to PDF</button>
   <table id="subs-table">
    <tr>
        <th>Member ID</th>
        <th>Name</th>
        <th>Region</th>
        <th>Paid Date</th>
        <th>Amount</th>
        <th>Remarks</th>
    </tr>

        <?php
        $sql = "SELECT s.*, m.name, m.defined_amount, r.region_name 
        FROM subscriptions s
        JOIN members m ON s.member_id = m.member_id
        LEFT JOIN regions r ON m.region_id = r.region_id
        $where
        ORDER BY s.paid_date DESC";

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
        <td>{$row['member_id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['region_name']}</td>
        <td>{$row['paid_date']}</td>
        <td>{$row['amount']}</td>
        <td>{$row['remarks']}</td>
      </tr>";

        }
        ?>
    </table>
</div>



<!-- 2. Single Member Subscription Report -->
<div class="section" id="single-member-section">
    <h3>🔍 Search Subscription by Member ID</h3>
    <form method="GET">
        <input type="number" name="search_member" placeholder="Enter Member ID" required>
        <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['search_member'])) {
        $mid = intval($_GET['search_member']);
        $member = $conn->query("SELECT * FROM members WHERE member_id = $mid")->fetch_assoc();

        if ($member) {
            echo "<h4>Member: {$member['name']} (ID: $mid)</h4>";
            echo "<button class='export-btn' onclick=\"exportToExcel('single-subs-table', 'Subscriptions_Member_$mid')\">Export to Excel</button> ";
            echo "<button class='export-btn' onclick=\"exportToPDF('single-member-section', 'Subscriptions_Member_$mid')\">Export to PDF</button>";

            $subs = $conn->query("SELECT * FROM subscriptions WHERE member_id = $mid");
            echo "<table id='single-subs-table'>
                    <tr><th>Date</th><th>Amount</th><th>Remarks</th></tr>";
            while ($s = $subs->fetch_assoc()) {
                echo "<tr>
                        <td>{$s['paid_date']}</td>
                        <td>{$s['amount']}</td>
                        <td>{$s['remarks']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:red;'>Member not found.</p>";
        }
    }
    ?>
</div>

<!-- 3. Masjid Resider Report -->
<div class="section" id="resider-section">
    <h3>🏠 Masjid Resider Report</h3>
    <button class="export-btn" onclick="exportToExcel('resider-table', 'Masjid_Residers')">Export to Excel</button>
    <button class="export-btn" onclick="exportToPDF('resider-section', 'Masjid_Residers')">Export to PDF</button>

    <table id="resider-table">
        <tr>
            <th>Member ID</th>
            <th>Name</th>
            <th>Region</th>
            <th>Defined Amount</th>
            <th>Family Count</th>
        </tr>
        <?php
        $sql = "SELECT m.member_id, m.name, r.region_name, m.defined_amount, m.family_count
                FROM members m
                LEFT JOIN regions r ON m.region_id = r.region_id
                ORDER BY m.member_id ASC";
        $res = $conn->query($sql);
        while ($row = $res->fetch_assoc()) {
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

<!-- Export Scripts -->
<script>
function exportToExcel(tableId, filename = 'export') {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
    XLSX.writeFile(wb, `${filename}.xlsx`);
}

function exportToPDF(sectionId, filename = 'export') {
    const element = document.getElementById(sectionId);
    html2pdf().from(element).save(`${filename}.pdf`);
}
</script>

</body>
</html>
