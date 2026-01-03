<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('peserta');

$quiz_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($quiz_id == 0) {
    header('Location: take_quiz.php');
    exit();
}

// Check if quiz exists
$quiz = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM quizzes WHERE id = $quiz_id"));

if (!$quiz) {
    header('Location: take_quiz.php');
    exit();
}

// Check if user has already taken this quiz
$user_id = $_SESSION['user_id'];
$already_taken = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as count FROM results WHERE user_id = $user_id AND quiz_id = $quiz_id"))['count'];

if ($already_taken > 0) {
    header('Location: take_quiz.php');
    exit();
}

// Check number of questions
$question_count = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as count FROM questions WHERE quiz_id = $quiz_id"))['count'];

if ($question_count == 0) {
    $_SESSION['error'] = 'Kuis ini belum memiliki soal';
    header('Location: take_quiz.php');
    exit();
}

// Start the quiz - store in session
$_SESSION['current_quiz'] = $quiz_id;
$_SESSION['quiz_start_time'] = time() * 1000; // in milliseconds
$_SESSION['quiz_questions'] = array();
$_SESSION['quiz_answers'] = array();

// Get all questions for this quiz
$questions_result = mysqli_query($conn,
    "SELECT * FROM questions WHERE quiz_id = $quiz_id ORDER BY RAND()");

while ($question = mysqli_fetch_assoc($questions_result)) {
    $_SESSION['quiz_questions'][] = $question;
}

// Redirect to quiz page
header('Location: quiz.php');
exit();
?>