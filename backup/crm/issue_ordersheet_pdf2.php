<?
require('include/html2fpdf.php');
$pdf=new HTML2FPDF();
$pdf->AddPage();
$fp = fopen("issue_ordersheet.php","r");
$strContent = fread($fp, filesize("issue_ordersheet.php"));
fclose($fp);
$pdf->WriteHTML($strContent);
$pdf->Output("sample.pdf");
echo "PDF file is generated successfully!";
?>
