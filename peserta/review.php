<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('peserta');

$result_id = isset($_GET['result_id']) ? intval($_GET['result_id']) : 0;
$user_id = $_SESSION['user_id'];

// Get result details
$result = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT r.*, q.judul, q.deskripsi 
     FROM results r 
     JOIN quizzes q ON r.quiz_id = q.id 
     WHERE r.id = $result_id AND r.user_id = $user_id"));

if (!$result) {
    header('Location: results.php');
    exit();
}

// Get quiz questions and user's answers
// Note: In a real application, you would store user answers in a separate table
// For this example, we'll simulate it by getting the questions

$questions = mysqli_query($conn,
    "SELECT * FROM questions 
     WHERE quiz_id = {$result['quiz_id']} 
     ORDER BY id");

$page_title = 'Review Jawaban';
include '../includes/header.php';

$percentage = ($result['score'] / ($result['total_questions'] * 10)) * 100;
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Review Jawaban</h1>
        <a href="results.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
    
    <!-- Result Summary -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4><?php echo $result['judul']; ?></h4>
                    <p class="text-muted"><?php echo $result['deskripsi']; ?></p>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                            <div class="progress-bar bg-<?php 
                                echo $percentage >= 70 ? 'success' : 
                                       ($percentage >= 50 ? 'warning' : 'danger'); 
                            ?>" 
                                 style="width: <?php echo $percentage; ?>%">
                                <?php echo number_format($percentage, 1); ?>%
                            </div>
                        </div>
                        <span class="badge bg-<?php 
                            echo $percentage >= 70 ? 'success' : 
                                   ($percentage >= 50 ? 'warning' : 'danger'); 
                        ?> fs-6">
                            <?php echo $result['score']; ?> poin
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="fw-bold"><?php echo $result['correct_answers']; ?> benar</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        <span class="fw-bold"><?php echo $result['total_questions'] - $result['correct_answers']; ?> salah</span>
                    </div>
                    <div>
                        <i class="fas fa-clock text-info me-2"></i>
                        <span class="fw-bold">
                            <?php echo floor($result['waktu_pengerjaan'] / 60); ?>:<?php echo str_pad($result['waktu_pengerjaan'] % 60, 2, '0', STR_PAD_LEFT); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Questions Review -->
    <div class="row">
        <?php 
        $question_number = 1;
        while ($question = mysqli_fetch_assoc($questions)): 
            // Simulate user's answer (random for demo)
            $user_answer = ['a', 'b', 'c', 'd'][rand(0, 3)];
            $is_correct = $user_answer == $question['jawaban_benar'];
        ?>
        <div class="col-md-6 mb-4">
            <div class="card question-review-card <?php echo $is_correct ? 'border-success' : 'border-danger'; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">Soal #<?php echo $question_number; ?></h5>
                        <div>
                            <?php if ($is_correct): ?>
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Benar (+<?php echo $question['poin']; ?>)
                            </span>
                            <?php else: ?>
                            <span class="badge bg-danger">
                                <i class="fas fa-times me-1"></i>Salah
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <p class="card-text fw-bold"><?php echo $question['pertanyaan']; ?></p>
                    
                    <div class="options mt-3">
                        <?php
                        $options = array(
                            'a' => $question['opsi_a'],
                            'b' => $question['opsi_b'],
                            'c' => $question['opsi_c'],
                            'd' => $question['opsi_d']
                        );
                        
                        foreach ($options as $key => $value):
                            $is_user_answer = ($key == $user_answer);
                            $is_correct_answer = ($key == $question['jawaban_benar']);
                            $option_class = '';
                            
                            if ($is_user_answer && $is_correct_answer) {
                                $option_class = 'bg-success text-white';
                            } elseif ($is_user_answer && !$is_correct_answer) {
                                $option_class = 'bg-danger text-white';
                            } elseif (!$is_user_answer && $is_correct_answer) {
                                $option_class = 'border-success';
                            }
                        ?>
                        <div class="form-check mb-2 p-2 rounded <?php echo $option_class; ?>">
                            <input class="form-check-input" type="radio" disabled 
                                <?php echo $is_user_answer ? 'checked' : ''; ?>>
                            <label class="form-check-label d-flex align-items-center">
                                <span class="option-letter me-3 fw-bold"><?php echo strtoupper($key); ?>.</span>
                                <span><?php echo $value; ?></span>
                                <?php if ($is_correct_answer): ?>
                                <span class="ms-2">
                                    <i class="fas fa-check-circle text-success"></i>
                                </span>
                                <?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-3">
                        <?php if (!$is_correct): ?>
                        <div class="alert alert-info py-2">
                            <small>
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Jawaban yang benar:</strong> <?php echo strtoupper($question['jawaban_benar']); ?>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        $question_number++;
        endwhile; 
        ?>
    </div>
    
    <!-- Action Buttons -->
    <div class="card mt-4">
        <div class="card-body text-center">
            <div class="d-flex justify-content-center gap-3">
                <a href="take_quiz.php" class="btn btn-primary">
                    <i class="fas fa-play-circle me-2"></i>Ambil Kuis Lain
                </a>
                <a href="results.php" class="btn btn-success">
                    <i class="fas fa-list me-2"></i>Lihat Semua Hasil
                </a>
                <button onclick="window.print()" class="btn btn-info">
                    <i class="fas fa-print me-2"></i>Cetak Hasil
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.question-review-card {
    transition: transform 0.3s;
}

.question-review-card:hover {
    transform: translateY(-3px);
}

.bg-success {
    background-color: #d1e7dd !important;
}

.bg-danger {
    background-color: #f8d7da !important;
}

.border-success {
    border: 2px solid #198754 !important;
}

.border-danger {
    border: 2px solid #dc3545 !important;
}
</style>

<?php include '../includes/footer.php'; ?>