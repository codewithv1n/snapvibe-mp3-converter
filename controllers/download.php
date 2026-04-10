<?php
$base_dir = dirname(__DIR__);
$download_folder = $base_dir . '/downloads/';

$file = $_GET['file'] ?? '';
$file = basename($file);

$filepath = $download_folder . $file;

if (empty($file) || !file_exists($filepath) || pathinfo($file, PATHINFO_EXTENSION) !== 'mp3') {
    http_response_code(404);
    echo "File not found.";
    exit;
}

$display_name = str_replace('_', ' ', $file);

header('Content-Type: audio/mpeg');
header('Content-Disposition: attachment; filename="' . $display_name . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: no-cache, must-revalidate');

readfile($filepath);
exit;
?>