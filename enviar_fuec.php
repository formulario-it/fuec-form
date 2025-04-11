<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'fpdf/fpdf.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Generar PDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'FORMATO UNICO DE EXTRACTO DEL CONTRATO - FUEC',0,1,'C');
        $this->Ln(5);
    }
    function Section($title) {
        $this->SetFont('Arial','B',12);
        $this->SetFillColor(230,230,230);
        $this->Cell(0,10,$title,0,1,'L', true);
    }
    function Field($label, $value) {
        $this->SetFont('Arial','B',10);
        $this->Cell(60,8,iconv('UTF-8','ISO-8859-1',$label),1);
        $this->SetFont('Arial','',10);
        $this->Cell(130,8,iconv('UTF-8','ISO-8859-1',$value),1);
        $this->Ln();
    }
}

$pdf = new PDF();
$pdf->AddPage();

$secciones = [
    "Datos del Contrato" => ['contrato','contratante','ccnit','objeto','origen','convenio'],
    "Vigencia del Contrato" => ['fecha_inicial','fecha_vencimiento'],
    "Características del Vehículo" => ['placa','modelo','marca','clase','num_interno','tarjeta_operacion'],
    "Datos del Conductor 1" => ['conductor1_nombre','conductor1_cedula','conductor1_licencia','conductor1_conduccion','conductor1_vigencia'],
    "Datos del Conductor 2" => ['conductor2_nombre','conductor2_cedula','conductor2_licencia','conductor2_conduccion','conductor2_vigencia'],
    "Responsable del Contratante" => ['responsable_nombre','responsable_cedula','responsable_telefono','responsable_direccion']
];

foreach ($secciones as $titulo => $campos) {
    $pdf->Section($titulo);
    foreach ($campos as $campo) {
        $pdf->Field(ucwords(str_replace("_", " ", $campo)), $_POST[$campo] ?? '');
    }
    $pdf->Ln(2);
}

$pdf_path = __DIR__ . '/fuec_formulario.pdf';
$pdf->Output($pdf_path, 'F');

// Crear CSV
$csv_path = __DIR__ . '/archivos/fuec_2025-04-10.csv';
$fp = fopen($csv_path, 'w');
fputcsv($fp, array_keys($_POST));
fputcsv($fp, array_values($_POST));
fclose($fp);

// Enviar correo
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'extractofuec@gmail.com';
    $mail->Password = 'xnpjdayahlvgaflq'; // REEMPLAZA
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('extractofuec@gmail.com', 'Formulario FUEC');
    $mail->addAddress('extractofuec@gmail.com');

    $mail->Subject = 'Formulario FUEC recibido';
    $mail->Body = 'Adjunto el PDF y CSV del formulario FUEC.';

    $mail->addAttachment($pdf_path, 'fuec_formulario.pdf');
    $mail->addAttachment($csv_path, 'fuec_2025-04-10.csv');

    $mail->send();

    unlink($pdf_path); // Eliminar después del envío
    // unlink($csv_path); // Si deseas borrar el CSV del servidor

    header("Location: index.html");
    exit();

} catch (Exception $e) {
    echo "❌ Error al enviar el correo: {\$mail->ErrorInfo}";
}
?>