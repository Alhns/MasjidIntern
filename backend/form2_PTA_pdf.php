<?php
require('fpdf.php'); // Ensure fpdf.php is in the same directory

class PDF extends FPDF {
    // Table Header
    function Header() {
        // Set font
        $this->SetFont('Arial', 'B', 14);
        // Title
        $this->Cell(0, 10, 'Form 2 Report', 0, 1, 'C');
        $this->Ln(5);

        // Column headers
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200); // Light gray background for headers
        $this->Cell(10, 10, 'No', 1, 0, 'C', true);
        $this->Cell(50, 10, 'Name', 1, 0, 'C', true);
        $this->Cell(40, 10, 'IC', 1, 0, 'C', true);
        $this->Cell(35, 10, 'Phone', 1, 0, 'C', true);
        $this->Cell(50, 10, 'Address', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Job', 1, 0, 'C', true);
        $this->Cell(20, 10, 'Vote', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Role', 1, 1, 'C', true);
    }

    // Table Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Create PDF instance with Landscape mode
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Connect to database
include('connection.php');

// Fetch data from session
session_start();
if (!isset($_SESSION['search_results']) || empty($_SESSION['search_results'])) {
    $pdf->Cell(0, 10, 'No data available.', 1, 1, 'C');
} else {
    // Display table data
    $counter = 1; // Initialize counter
    foreach ($_SESSION['search_results'] as $row) {
    $pdf->Cell(10, 10, $counter++, 1, 0, 'C'); // Add row number
    $pdf->Cell(50, 10, htmlspecialchars($row['name']), 1, 0, 'L');
    $pdf->Cell(40, 10, htmlspecialchars($row['ic']), 1, 0, 'L');
    $pdf->Cell(35, 10, htmlspecialchars($row['phone']), 1, 0, 'L');
    $pdf->Cell(50, 10, htmlspecialchars($row['address']), 1, 0, 'L');
    $pdf->Cell(40, 10, htmlspecialchars($row['job']), 1, 0, 'L');
    $pdf->Cell(20, 10, isset($row['total_vote']) ? $row['total_vote'] : 0, 1, 0, 'C');
    $pdf->Cell(40, 10, isset($row['role']) ? $row['role'] : '-', 1, 1, 'C');
}
}

// Output PDF for download
$pdf->Output('D', 'Form2_PTA_Report.pdf'); // 'D' forces download
?>
