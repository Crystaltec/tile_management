<?php
//include_once "html2pdf/html2fpdf.php"; 
require("html2fpdf.php"); 
 
			$htmlFile = "http://www.facebook.com"; 
			$buffer = file_get_contents($htmlFile); 
 
			$pdf = new HTML2FPDF('P', 'mm', 'Letter'); 
			$pdf->AddPage(); 
			$pdf->WriteHTML($buffer); 
			$pdf->Output('test.pdf', 'F');
			
?>