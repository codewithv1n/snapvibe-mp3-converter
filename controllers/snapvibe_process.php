<?php
set_time_limit(0);

$base_dir = dirname(__DIR__);
$download_folder = $base_dir . '/downloads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $files = glob($download_folder . "*.mp3"); 
    foreach($files as $file) {
        if (time() - filemtime($file) > 100) {
            unlink($file); 
        }
    }

    $youtube_url = $_POST['youtube_url'] ?? '';
    $youtube_url = filter_var($youtube_url, FILTER_SANITIZE_URL);

    if (!filter_var($youtube_url, FILTER_VALIDATE_URL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid URL',
        ]);
        exit;
    }

    $parsed = parse_url($youtube_url);
    $allowed_hosts = ['www.youtube.com','youtube.com','music.youtube.com'];
    if (!$parsed || !in_array($parsed['host'] ?? '', $allowed_hosts)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Only YouTube URLs are allowed.',
        ]);
        exit;
    }

    $safe_url = escapeshellarg($youtube_url);
    
    $yt_dlp = $base_dir . '/yt-dlp.exe';
    $ffmpeg = $base_dir . '/ffmpeg.exe';
    
    $win_yt_dlp = str_replace('/', '\\', $yt_dlp);
    $win_ffmpeg = str_replace('/', '\\', $ffmpeg);
    $win_download = str_replace('/', '\\', $download_folder);

    $output_template = "\"" . $win_download . "%(title)s.%(ext)s\"";

    $command = "\"$win_yt_dlp\" --ffmpeg-location \"$win_ffmpeg\" -x --audio-format mp3 --restrict-filenames -o $output_template $safe_url 2>&1";
    exec($command, $output, $return_var);

    $mp3_files = glob($download_folder . '*.mp3');
    if (!empty($mp3_files)) {
        usort($mp3_files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $latest_file = basename($mp3_files[0]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Video converted successfully',
            'filename' => $latest_file
        ]);
    } else {
        error_log('Snapvibe conversion failed: ' . implode("\n", $output));
        echo json_encode([
            'status' => 'error',
            'message' => 'Conversion failed. Please try again or check the URL.',
        ]);
    }

    exit;
}