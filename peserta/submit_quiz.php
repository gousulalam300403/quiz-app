<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('peserta');

// Check if there's an active quiz
if (!isset($_SESSION['current_quiz']) || !isset($_SESSION['quiz_questions'])) {
    header('Location: take_quiz.php');
    exit();
}

$quiz_id = $_SESSION['current_quiz'];
$questions = $_SESSION['quiz_questions'];
$answers = $_SESSION['quiz_answers'] ?? array();
$user_id = $_SESSION['user_id'];

// Calculate score
$score = 0;
$correct_answers = 0;
$total_questions = count($questions);

foreach ($questions as $index => $question) {
    if (isset($answers[$index]) && $answers[$index] == $question['jawaban_benar']) {
        $score += $question['poin'];
        $correct_answers++;
    }
}

// Calculate percentage
$percentage = ($score / ($total_questions * 10)) * 100; // Assuming 10 points per question

// Calculate time taken
$start_time = $_SESSION['quiz_start_time'] / 1000; // Convert to seconds
$end_time = time();
$time_taken = $end_time - $start_time;

// Save result to database
$query = "INSERT INTO results (user_id, quiz_id, score, total_questions, correct_answers, waktu_pengerjaan) 
          VALUES ($user_id, $quiz_id, $score, $total_questions, $correct_answers, $time_taken)";
mysqli_query($conn, $query);

// Get quiz info
$quiz = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM quizzes WHERE id = $quiz_id"));

// Clear quiz session
unset($_SESSION['current_quiz']);
unset($_SESSION['quiz_start_time']);
unset($_SESSION['quiz_questions']);
unset($_SESSION['quiz_answers']);

$page_title = 'Hasil Kuis';
include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Kuis Selesai!
                    </h5>
                </div>
                <div class="card-body text-center">
                    <!-- Result Icon -->
                    <div class="mb-4">
                        <?php if ($percentage >= 70): ?>
                        <i class="fas fa-trophy fa-4x text-warning mb-3"></i>
                        <h4 class="text-success">Sangat Baik!</h4>
                        <?php elseif ($percentage >= 50): ?>
                        <i class="fas fa-star fa-4x text-info mb-3"></i>
                        <h4 class="text-info">Baik!</h4>
                        <?php else: ?>
                        <i class="fas fa-lightbulb fa-4x text-warning mb-3"></i>
                        <h4 class="text-warning">Perlu Belajar Lagi</h4>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Quiz Info -->
                    <div class="mb-4">
                        <h3><?php echo $quiz['judul']; ?></h3>
                        <p class="text-muted"><?php echo $quiz['deskripsi']; ?></p>
                    </div>
                    
                    <!-- Score Display -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-chart-line"></i>
                                    <h3><?php echo $score; ?></h3>
                                    <p>Total Poin</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-percentage"></i>
                                    <h3><?php echo number_format($percentage, 1); ?>%</h3>
                                    <p>Persentase</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Results -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Detail Hasil</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <h5 class="text-primary"><?php echo $total_questions; ?></h5>
                                    <small class="text-muted">Total Soal</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h5 class="text-success"><?php echo $correct_answers; ?></h5>
                                    <small class="text-muted">Jawaban Benar</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h5 class="text-danger"><?php echo $total_questions - $correct_answers; ?></h5>
                                    <small class="text-muted">Jawaban Salah</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h5 class="text-info"><?php echo floor($time_taken / 60); ?>:<?php echo str_pad($time_taken % 60, 2, '0', STR_PAD_LEFT); ?></h5>
                                    <small class="text-muted">Waktu Pengerjaan</small>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?php echo ($correct_answers / $total_questions) * 100; ?>%">
                                    <?php echo $correct_answers; ?> Benar
                                </div>
                                <div class="progress-bar bg-danger" 
                                     style="width: <?php echo (($total_questions - $correct_answers) / $total_questions) * 100; ?>%">
                                    <?php echo $total_questions - $correct_answers; ?> Salah
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feedback -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6>Umpan Balik:</h6>
                            <p>
                                <?php
                                if ($percentage >= 80) {
                                    echo "Selamat! Anda memiliki pemahaman yang sangat baik tentang materi ini.";
                                } elseif ($percentage >= 60) {
                                    echo "Bagus! Anda memahami materi dengan cukup baik.";
                                } elseif ($percentage >= 40) {
                                    echo "Cukup baik. Ada beberapa area yang perlu diperbaiki.";
                                } else {
                                    echo "Anda perlu mempelajari kembali materi ini.";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="take_quiz.php" class="btn btn-primary">
                            <i class="fas fa-play-circle me-2"></i>Ambil Kuis Lain
                        </a>
                        <a href="results.php" class="btn btn-success">
                            <i class="fas fa-chart-line me-2"></i>Lihat Hasil Saya
                        </a>
                        <a href="review.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>Review Jawaban
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>