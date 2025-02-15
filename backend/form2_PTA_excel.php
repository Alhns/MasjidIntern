<?php
session_start();
require '../vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION['search_results']) || empty($_SESSION['search_results'])) {
    die('No data available to export.');
}

// Create new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Table Header
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'IC');
$sheet->setCellValue('C1', 'Phone');
$sheet->setCellValue('D1', 'Address');
$sheet->setCellValue('E1', 'Total Vote');

// Fill Data
$rowNum = 2;
foreach ($_SESSION['search_results'] as $row) {
    $sheet->setCellValue('A' . $rowNum, $row['name']);
    $sheet->setCellValue('B' . $rowNum, $row['ic']);
    $sheet->setCellValue('C' . $rowNum, $row['phone']);
    $sheet->setCellValue('D' . $rowNum, $row['address']);
    $sheet->setCellValue('E' . $rowNum, $row['total_vote']);
    $rowNum++;
}

// Generate File
$writer = new Xlsx($spreadsheet);
$filename = 'Form2_PTA_Report.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$writer->save('php://output');
?>
