<?php
require 'vendor/autoload.php';
require 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$type = $_GET['type'] ?? '';
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

if ($type === 'subscriptions') {
    $sheet->setTitle('Subscriptions');
    $sheet->fromArray(['Member ID', 'Name', 'Date', 'Amount', 'Remarks'], NULL, 'A1');

    $sql = "SELECT s.*, m.name FROM subscriptions s 
            JOIN members m ON s.member_id = m.member_id ORDER BY s.paid_date DESC";
    $result = $conn->query($sql);
    $row = 2;
    while ($r = $result->fetch_assoc()) {
        $sheet->fromArray([$r['member_id'], $r['name'], $r['paid_date'], $r['amount'], $r['remarks']], NULL, "A$row");
        $row++;
    }

} elseif ($type === 'residers') {
    $sheet->setTitle('Residers');
    $sheet->fromArray(['Member ID', 'Name', 'Region', 'Defined Amount', 'Family Count'], NULL, 'A1');

    $res = $conn->query("SELECT * FROM members ORDER BY member_id ASC");
    $row = 2;
    while ($r = $res->fetch_assoc()) {
        $sheet->fromArray([$r['member_id'], $r['name'], $r['region'], $r['defined_amount'], $r['family_count']], NULL, "A$row");
        $row++;
    }

} elseif ($type === 'single' && isset($_GET['member_id'])) {
    $member_id = intval($_GET['member_id']);
    $member = $conn->query("SELECT name FROM members WHERE member_id = $member_id")->fetch_assoc();
    $sheet->setTitle("Member $member_id");
    $sheet->fromArray(['Date', 'Amount', 'Remarks'], NULL, 'A1');

    $subs = $conn->query("SELECT * FROM subscriptions WHERE member_id = $member_id");
    $row = 2;
    while ($s = $subs->fetch_assoc()) {
        $sheet->fromArray([$s['paid_date'], $s['amount'], $s['remarks']], NULL, "A$row");
        $row++;
    }
}

$filename = "masjid_report_" . $type . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
?>
