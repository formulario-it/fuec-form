<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

echo "🔹 Paso 1: Inicializando PHPMailer<br>";

$mail = new PHPMailer(true);

try {
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'extractofuec@gmail.com';
    $mail->Password = 'xnpjdayahlvgaflq'; // Reemplazar por tu clave real
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    echo "🔹 Paso 2: Configuración SMTP lista<br>";

    // Remitente y destinatario
    $mail->setFrom('extractofuec@gmail.com', 'Formulario FUEC');
    $mail->addAddress('extractofuec@gmail.com');

    echo "🔹 Paso 3: Dirección configurada<br>";

    // Asunto
    $mail->Subject = 'Nuevo formulario FUEC recibido';

    // Cuerpo del mensaje
    $mail->isHTML(true);
    $mail->Body = 'Adjunto encontrarás el PDF y el CSV del formulario FUEC.';

    // Adjuntar PDF
    $pdf_file = __DIR__ . '/fuec_debug.pdf'; // simulado
    file_put_contents($pdf_file, 'Contenido PDF simulado');
    if (!file_exists($pdf_file)) {
        die("❌ No se encontró el PDF.<br>");
    }
    $mail->addAttachment($pdf_file, 'fuec.pdf');
    echo "✅ PDF adjuntado<br>";

    // Crear CSV
    echo "🔹 Creando CSV<br>";
    $dir = __DIR__ . '/archivos';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $csv_file = $dir . '/fuec_debug_final.csv';
    $data = [
        'contrato' => '123',
        'contratante' => 'Empresa XYZ',
        'ccnit' => '900123456',
        'objeto' => 'Transporte especial',
        'fecha' => date('Y-m-d')
    ];
    $fp = fopen($csv_file, 'w');
    if (!$fp) {
        die('❌ No se pudo abrir el archivo CSV.<br>');
    }
    fputcsv($fp, array_keys($data));
    fputcsv($fp, array_values($data));
    fclose($fp);

    if (!file_exists($csv_file)) {
        die("❌ No se encontró el archivo CSV.<br>");
    }
    $mail->addAttachment($csv_file, 'fuec.csv');
    echo "✅ CSV adjuntado<br>";

    // Enviar
    if ($mail->send()) {
        echo "✅ Correo enviado correctamente.<br>";
    } else {
        echo "❌ Error al enviar correo: " . $mail->ErrorInfo;
    }

} catch (Exception $e) {
    echo "❌ Excepción al enviar correo: {$mail->ErrorInfo}";
}
?>
