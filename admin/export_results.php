<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=hasil_kuis_' . date('Y-m-d') . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8
fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

// CSV headers
fputcsv($output, [
    'ID',
    'Tanggal',
    'Nama Peserta',
    'Username',
    'Kuis',
    'Nilai',
    'Persentase',
    'Jawaban Benar',
    'Total Soal',
    'Waktu Pengerjaan (detik)',
    'Durasi Kuis (menit)'
]);

// Get all results
$query = "SELECT r.*, u.nama_lengkap, u.username, q.judul as quiz_title, q.durasi 
          FROM results r 
          JOIN users u ON r.user_id = u.id 
          JOIN quizzes q ON r.quiz_id = q.id 
          ORDER BY r.completed_at DESC";

$results = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($results)) {
    $percentage = ($row['score'] / ($row['total_questions'] * 10)) * 100;
    
    fputcsv($output, [
        $row['id'],
        $row['completed_at'],
        $row['nama_lengkap'],
        $row['username'],
        $row['quiz_title'],
        $row['score'],
        number_format($percentage, 2) . '%',
        $row['correct_answers'],
        $row['total_questions'],
        $row['waktu_pengerjaan'],
        $row['durasi']
    ]);
}

fclose($output);
exit();
?>