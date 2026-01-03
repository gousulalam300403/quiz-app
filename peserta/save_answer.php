<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('peserta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get posted data
    $question_index = intval($_POST['question_index']);
    $answer = $_POST['answer'];
    
    // Save to session
    if (isset($_SESSION['quiz_answers'])) {
        $_SESSION['quiz_answers'][$question_index] = $answer;
    }
    
    echo 'OK';
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>