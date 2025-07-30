<?php
require 'vendor/autoload.php';
require 'db.php';

use Fpdf\Fpdf;

$type = $_GET['type'] ?? '';
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

if ($type === 'subscriptions') {
    $pdf->Cell(190,10,'All Subscriptions',0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,10,'Member ID',1);
    $pdf->Cell(40,10,'Name',1);
    $pdf->Cell(40,10,'Date',1);
    $pdf->Cell(30,10,'Amount',1);
    $pdf->Cell(50,10,'Remarks',1);
    $pdf->Ln();

    $result = $conn->query("SELECT s.*, m.name FROM subscriptions s 
                            JOIN members m ON s.member_id = m.member_id ORDER BY s.paid_date DESC");

    $pdf->SetFont('Arial','',10);
    while ($r = $result->fetch_assoc()) {
        $pdf->Cell(30,10,$r['member_id'],1);
        $pdf->Cell(40,10,$r['name'],1);
        $pdf->Cell(40,10,$r['paid_date'],1);
        $pdf->Cell(30,10,number_format($r['amount'],2),1);
        $pdf->Cell(50,10,substr($r['remarks'],0,30),1);
        $pdf->Ln();
    }

} elseif ($type === 'residers') {
    $pdf->Cell(190,10,'Masjid Residers',0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,10,'Member ID',1);
    $pdf->Cell(40,10,'Name',1);
    $pdf->Cell(40,10,'Region',1);
    $pdf->Cell(40,10,'Amount',1);
    $pdf->Cell(40,10,'Family Count',1);
    $pdf->Ln();

    $res = $conn->query("SELECT * FROM members ORDER BY member_id ASC");
    $pdf->SetFont('Arial','',10);
    while ($r = $res->fetch_assoc()) {
        $pdf->Cell(30,10,$r['member_id'],1);
        $pdf->Cell(40,10,$r['name'],1);
        $pdf->Cell(40,10,$r['region'],1);
        $pdf->Cell(40,10,number_format($r['defined_amount'],2),1);
        $pdf->Cell(40,10,$r['family_count'],1);
        $pdf->Ln();
    }

} elseif ($type === 'single' && isset($_GET['member_id'])) {
    $member_id = intval($_GET['member_id']);
    $member = $conn->query("SELECT name FROM members WHERE member_id = $member_id")->fetch_assoc();

    $pdf->Cell(190,10,"Subscriptions for Member: {$member['name']}",0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,10,'Date',1);
    $pdf->Cell(40,10,'Amount',1);
    $pdf->Cell(110,10,'Remarks',1);
    $pdf->Ln();

    $subs = $conn->query("SELECT * FROM subscriptions WHERE member_id = $member_id");
    $pdf->SetFont('Arial','',10);
    while ($s = $subs->fetch_assoc()) {
        $pdf->Cell(40,10,$s['paid_date'],1);
        $pdf->Cell(40,10,number_format($s['amount'],2),1);
        $pdf->Cell(110,10,substr($s['remarks'],0,50),1);
        $pdf->Ln();
    }
}

$pdf->Output();
exit;
?>
