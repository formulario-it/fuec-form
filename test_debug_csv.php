<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simular datos recibidos por formulario
$_POST = [
    'contrato' => '123',
    'contratante' => 'Empresa XYZ',
    'ccnit' => '900123456',
    'objeto' => 'Transporte especial',
    'fecha' => date('Y-m-d')
];

// Crear carpeta si no existe
$dir = __DIR__ . '/archivos';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Definir nombre del archivo CSV
$csv_file = $dir . '/fuec_debug.csv';

// Crear archivo CSV
$fp = fopen($csv_file, 'w');
if (!$fp) {
    die('❌ No se pudo abrir el archivo para escribir.');
}

fputcsv($fp, array_keys($_POST));
fputcsv($fp, array_values($_POST));
fclose($fp);

// Verificar si el archivo se creó
if (!file_exists($csv_file)) {
    die('❌ El archivo CSV no se creó.');
}

echo "✅ El archivo CSV fue creado correctamente en:<br>$csv_file<br>";
echo "📎 Puedes descargarlo aquí: <a href='archivos/fuec_debug.csv' target='_blank'>Ver CSV</a>";
?>
