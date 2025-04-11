<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dir = __DIR__ . '/archivos';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$filename = $dir . '/fuec_test.csv';
$data = [
    ['Nombre', 'Correo', 'Mensaje'],
    ['Juan Pérez', 'juan@example.com', 'Hola mundo']
];

$fp = fopen($filename, 'w');
if ($fp === false) {
    die('❌ No se pudo abrir el archivo para escritura.');
}

foreach ($data as $row) {
    fputcsv($fp, $row);
}
fclose($fp);

echo "✅ CSV creado en: $filename";
?>
