<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🔹 Paso 1: Iniciando ejecución<br>";

// Crear CSV
echo "🔹 Paso 2: Creando CSV<br>";
$dir = __DIR__ . '/archivos';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
$csv_file = $dir . '/fuec_debug_step.csv';
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
echo "✅ CSV creado en: $csv_file<br>";

// Verificar existencia
if (!file_exists($csv_file)) {
    die("❌ No se encontró el archivo CSV para adjuntar.<br>");
}
echo "🔹 Paso 3: CSV verificado<br>";

// Simular adjunto
echo "🔹 Paso 4: Preparando envío por correo (simulado)<br>";
echo "✅ Proceso completado hasta el final<br>";
?>
