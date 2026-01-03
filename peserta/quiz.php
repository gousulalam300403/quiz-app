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
$current_question = isset($_GET['q']) ? intval($_GET['q']) : 1;
$total_questions = count($questions);

if ($current_question < 1 || $current_question > $total_questions) {
    $current_question = 1;
}

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer'])) {
    $question_index = intval($_POST['question_index']);
    $answer = $_POST['answer'];
    
    // Store answer
    $_SESSION['quiz_answers'][$question_index] = $answer;
    
    // Move to next question or finish
    if ($current_question < $total_questions) {
        header('Location: quiz.php?q=' . ($current_question + 1));
        exit();
    } else {
        header('Location: submit_quiz.php');
        exit();
    }
}

// Get current question
$question_index = $current_question - 1;
$current_q = $questions[$question_index];

// Get quiz info
$quiz = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM quizzes WHERE id = $quiz_id"));

$page_title = 'Kuis: ' . $quiz['judul'];
include '../includes/header.php';
?>

<div class="container-fluid">
    <!-- Quiz Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4><?php echo $quiz['judul']; ?></h4>
                    <p class="text-muted mb-0"><?php echo $quiz['deskripsi']; ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="timer mb-2">
                        <i class="fas fa-clock me-2"></i>
                        <span id="timeRemaining">Loading...</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?php echo ($current_question / $total_questions) * 100; ?>%">
                        </div>
                    </div>
                    <small>Soal <?php echo $current_question; ?> dari <?php echo $total_questions; ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Question Card -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="" id="quizForm">
                <input type="hidden" name="question_index" value="<?php echo $question_index; ?>">
                
                <div class="mb-4">
                    <h5 class="card-title">Soal #<?php echo $current_question; ?></h5>
                    <p class="card-text"><?php echo $current_q['pertanyaan']; ?></p>
                </div>
                
                <!-- Options -->
                <div class="options mb-4">
                    <?php
                    $options = array(
                        'a' => $current_q['opsi_a'],
                        'b' => $current_q['opsi_b'],
                        'c' => $current_q['opsi_c'],
                        'd' => $current_q['opsi_d']
                    );
                    
                    foreach ($options as $key => $value):
                        $checked = (isset($_SESSION['quiz_answers'][$question_index]) && 
                                   $_SESSION['quiz_answers'][$question_index] == $key) ? 'checked' : '';
                    ?>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" 
                               name="answer" id="option_<?php echo $key; ?>" 
                               value="<?php echo $key; ?>" <?php echo $checked; ?> required>
                        <label class="form-check-label d-flex align-items-center" for="option_<?php echo $key; ?>">
                            <span class="option-letter me-3"><?php echo strtoupper($key); ?>.</span>
                            <span><?php echo $value; ?></span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation -->
                <div class="d-flex justify-content-between">
                    <div>
                        <?php if ($current_question > 1): ?>
                        <a href="quiz.php?q=<?php echo $current_question - 1; ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <?php if ($current_question < $total_questions): ?>
                        <button type="submit" class="btn btn-primary">
                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <?php else: ?>
                        <a href="submit_quiz.php" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>Selesai & Kirim
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Question List -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="card-title mb-0">Daftar Soal</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php for ($i = 1; $i <= $total_questions; $i++): ?>
                <div class="col-2 col-md-1 mb-2">
                    <a href="quiz.php?q=<?php echo $i; ?>" 
                       class="btn btn-sm w-100 
                              <?php echo $i == $current_question ? 'btn-primary' : 
                                     (isset($_SESSION['quiz_answers'][$i-1]) ? 'btn-success' : 'btn-outline-secondary'); ?>">
                        <?php echo $i; ?>
                    </a>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Timer functionality
    const startTime = <?php echo $_SESSION['quiz_start_time']; ?>;
    const duration = <?php echo $quiz['durasi']; ?> * 60 * 1000; // Convert to milliseconds
    const totalQuestions = <?php echo $total_questions; ?>;
    const currentQuestion = <?php echo $current_question; ?>;
    
    function updateTimer() {
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
    
    // Auto-save answer when selected
    document.querySelectorAll('input[name="answer"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const form = document.getElementById('quizForm');
            const formData = new FormData(form);
            
            // Submit via AJAX to save answer
            fetch('save_answer.php', {
                method: 'POST',
                body: formData
            });
        });
    });
    
    // Warn before leaving
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = 'Apakah Anda yakin ingin meninggalkan halaman? Jawaban yang belum disimpan mungkin akan hilang.';
    });
</script>

<?php include '../includes/footer.php'; ?>