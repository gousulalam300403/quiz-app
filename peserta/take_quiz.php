<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('peserta');

$page_title = 'Ikuti Kuis';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Get available quizzes (not taken by user)
$quizzes = mysqli_query($conn, 
    "SELECT q.* FROM quizzes q 
     WHERE q.id NOT IN (SELECT quiz_id FROM results WHERE user_id = $user_id)
     ORDER BY q.created_at DESC");

// Get ongoing quiz if any
$ongoing_quiz = null;
if (isset($_SESSION['quiz_start_time']) && isset($_SESSION['current_quiz'])) {
    $quiz_id = $_SESSION['current_quiz'];
    $ongoing_quiz = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT * FROM quizzes WHERE id = $quiz_id"));
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ikuti Kuis</h1>
        <?php if ($ongoing_quiz): ?>
        <div class="timer" id="timer">
            <i class="fas fa-clock me-2"></i>
            <span id="timeRemaining">Loading...</span>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if ($ongoing_quiz): ?>
    <!-- Ongoing Quiz Card -->
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">
                <i class="fas fa-exclamation-circle me-2"></i>Kuis Sedang Berlangsung
            </h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4><?php echo $ongoing_quiz['judul']; ?></h4>
                    <p><?php echo $ongoing_quiz['deskripsi']; ?></p>
                    <p><strong>Durasi:</strong> <?php echo $ongoing_quiz['durasi']; ?> menit</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="quiz.php?id=<?php echo $ongoing_quiz['id']; ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-play-circle me-2"></i>Lanjutkan Kuis
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Available Quizzes -->
    <div class="row">
        <?php if (mysqli_num_rows($quizzes) > 0): ?>
            <?php while ($quiz = mysqli_fetch_assoc($quizzes)): ?>
            <div class="col-md-4 mb-4">
                <div class="card quiz-card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $quiz['judul']; ?></h5>
                        <p class="card-text"><?php echo substr($quiz['deskripsi'], 0, 100) . '...'; ?></p>
                        <div class="mb-3">
                            <span class="badge bg-info me-2">
                                <i class="fas fa-clock me-1"></i><?php echo $quiz['durasi']; ?> menit
                            </span>
                            <?php
                            $question_count = mysqli_fetch_assoc(mysqli_query($conn, 
                                "SELECT COUNT(*) as count FROM questions WHERE quiz_id = {$quiz['id']}"))['count'];
                            ?>
                            <span class="badge bg-secondary">
                                <i class="fas fa-question-circle me-1"></i><?php echo $question_count; ?> soal
                            </span>
                        </div>
                        <a href="start_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Mulai Kuis
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h4>Semua Kuis Telah Diselesaikan</h4>
                        <p class="text-muted">Anda telah menyelesaikan semua kuis yang tersedia.</p>
                        <a href="history.php" class="btn btn-primary">
                            <i class="fas fa-history me-2"></i>Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($ongoing_quiz): ?>
<script>
    // Timer functionality for ongoing quiz
    function updateTimer() {
        const startTime = <?php echo $_SESSION['quiz_start_time']; ?>;
        const duration = <?php echo $ongoing_quiz['durasi']; ?> * 60 * 1000; // Convert to milliseconds
        const now = new Date().getTime();
        const elapsed = now - startTime;
        const remaining = duration - elapsed;
        
        if (remaining <= 0) {
            document.getElementById('timeRemaining').textContent = 'Waktu Habis!';
            // Auto-submit form when time is up
            window.location.href = 'submit_quiz.php?timeout=1';
            return;
        }
        
        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        
        document.getElementById('timeRemaining').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    // Update timer every second
    updateTimer();
    setInterval(updateTimer, 1000);
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>