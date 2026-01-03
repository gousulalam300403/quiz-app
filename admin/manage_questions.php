<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

$page_title = 'Kelola Soal';
include '../includes/header.php';

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

// Get quiz info
$quiz = null;
if ($quiz_id > 0) {
    $quiz = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM quizzes WHERE id = $quiz_id"));
}

// Handle delete question
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM questions WHERE id = $delete_id");
    $_SESSION['message'] = 'Soal berhasil dihapus';
    header('Location: manage_questions.php?quiz_id=' . $quiz_id);
    exit();
}

// Handle add/edit question
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $quiz_id = intval($_POST['quiz_id']);
    $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
    $opsi_a = mysqli_real_escape_string($conn, $_POST['opsi_a']);
    $opsi_b = mysqli_real_escape_string($conn, $_POST['opsi_b']);
    $opsi_c = mysqli_real_escape_string($conn, $_POST['opsi_c']);
    $opsi_d = mysqli_real_escape_string($conn, $_POST['opsi_d']);
    $jawaban_benar = mysqli_real_escape_string($conn, $_POST['jawaban_benar']);
    $poin = intval($_POST['poin']);
    
    if ($id > 0) {
        // Update existing question
        $query = "UPDATE questions SET 
                  pertanyaan='$pertanyaan',
                  opsi_a='$opsi_a',
                  opsi_b='$opsi_b',
                  opsi_c='$opsi_c',
                  opsi_d='$opsi_d',
                  jawaban_benar='$jawaban_benar',
                  poin=$poin 
                  WHERE id=$id";
        $message = 'Soal berhasil diperbarui';
    } else {
        // Add new question
        $query = "INSERT INTO questions (quiz_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, poin) 
                  VALUES ($quiz_id, '$pertanyaan', '$opsi_a', '$opsi_b', '$opsi_c', '$opsi_d', '$jawaban_benar', $poin)";
        $message = 'Soal berhasil ditambahkan';
    }
    
    mysqli_query($conn, $query);
    $_SESSION['message'] = $message;
    header('Location: manage_questions.php?quiz_id=' . $quiz_id);
    exit();
}

// Get questions list for this quiz
$questions = mysqli_query($conn, 
    "SELECT * FROM questions 
     WHERE quiz_id = $quiz_id 
     ORDER BY id ASC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Kelola Soal
            <?php if ($quiz): ?>
            - <?php echo $quiz['judul']; ?>
            <?php endif; ?>
        </h1>
        <div>
            <?php if ($quiz): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                <i class="fas fa-plus me-2"></i>Tambah Soal
            </button>
            <?php endif; ?>
            <a href="manage_quiz.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (!$quiz): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-exclamation-circle fa-4x text-warning mb-3"></i>
            <h4>Pilih Kuis Terlebih Dahulu</h4>
            <p class="text-muted">Silakan pilih kuis dari halaman Kelola Kuis untuk mengelola soalnya.</p>
            <a href="manage_quiz.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Ke Kelola Kuis
            </a>
        </div>
    </div>
    <?php else: ?>
    <!-- Quiz Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4><?php echo $quiz['judul']; ?></h4>
                    <p class="text-muted"><?php echo $quiz['deskripsi']; ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-2">
                        <span class="badge bg-info me-2">
                            <i class="fas fa-clock me-1"></i><?php echo $quiz['durasi']; ?> menit
                        </span>
                        <?php
                        $question_count = mysqli_fetch_assoc(mysqli_query($conn, 
                            "SELECT COUNT(*) as count FROM questions WHERE quiz_id = $quiz_id"))['count'];
                        ?>
                        <span class="badge bg-success">
                            <i class="fas fa-question-circle me-1"></i><?php echo $question_count; ?> soal
                        </span>
                    </div>
                    <a href="manage_quiz.php" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1"></i>Edit Kuis
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Questions List -->
    <div class="row">
        <?php 
        $question_number = 1;
        while ($question = mysqli_fetch_assoc($questions)): 
        ?>
        <div class="col-md-6 mb-4">
            <div class="card question-item">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">Soal #<?php echo $question_number; ?></h5>
                        <div>
                            <span class="badge bg-primary"><?php echo $question['poin']; ?> poin</span>
                            <button class="btn btn-sm btn-warning edit-question" data-question='<?php echo json_encode($question); ?>'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?quiz_id=<?php echo $quiz_id; ?>&delete=<?php echo $question['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Hapus soal ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    
                    <p class="card-text"><strong><?php echo $question['pertanyaan']; ?></strong></p>
                    
                    <div class="options mt-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" disabled 
                                <?php echo $question['jawaban_benar'] == 'a' ? 'checked' : ''; ?>>
                            <label class="form-check-label">
                                <strong>A.</strong> <?php echo $question['opsi_a']; ?>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" disabled 
                                <?php echo $question['jawaban_benar'] == 'b' ? 'checked' : ''; ?>>
                            <label class="form-check-label">
                                <strong>B.</strong> <?php echo $question['opsi_b']; ?>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" disabled 
                                <?php echo $question['jawaban_benar'] == 'c' ? 'checked' : ''; ?>>
                            <label class="form-check-label">
                                <strong>C.</strong> <?php echo $question['opsi_c']; ?>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" disabled 
                                <?php echo $question['jawaban_benar'] == 'd' ? 'checked' : ''; ?>>
                            <label class="form-check-label">
                                <strong>D.</strong> <?php echo $question['opsi_d']; ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Jawaban Benar: <?php echo strtoupper($question['jawaban_benar']); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        $question_number++;
        endwhile; 
        
        if ($question_number == 1): 
        ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-question-circle fa-4x text-muted mb-3"></i>
                    <h4>Belum Ada Soal</h4>
                    <p class="text-muted">Tambahkan soal pertama untuk kuis ini.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                        <i class="fas fa-plus me-2"></i>Tambah Soal Pertama
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Soal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="id" id="questionId" value="0">
                <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <textarea class="form-control" name="pertanyaan" id="pertanyaan" rows="3" required></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Opsi A</label>
                            <input type="text" class="form-control" name="opsi_a" id="opsi_a" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opsi B</label>
                            <input type="text" class="form-control" name="opsi_b" id="opsi_b" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Opsi C</label>
                            <input type="text" class="form-control" name="opsi_c" id="opsi_c" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opsi D</label>
                            <input type="text" class="form-control" name="opsi_d" id="opsi_d" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jawaban Benar</label>
                            <select class="form-control" name="jawaban_benar" id="jawaban_benar" required>
                                <option value="a">Opsi A</option>
                                <option value="b">Opsi B</option>
                                <option value="c">Opsi C</option>
                                <option value="d">Opsi D</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Poin</label>
                            <input type="number" class="form-control" name="poin" id="poin" value="10" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Edit question functionality
    document.querySelectorAll('.edit-question').forEach(button => {
        button.addEventListener('click', function() {
            const question = JSON.parse(this.getAttribute('data-question'));
            document.getElementById('questionId').value = question.id;
            document.getElementById('pertanyaan').value = question.pertanyaan;
            document.getElementById('opsi_a').value = question.opsi_a;
            document.getElementById('opsi_b').value = question.opsi_b;
            document.getElementById('opsi_c').value = question.opsi_c;
            document.getElementById('opsi_d').value = question.opsi_d;
            document.getElementById('jawaban_benar').value = question.jawaban_benar;
            document.getElementById('poin').value = question.poin;
            document.querySelector('.modal-title').textContent = 'Edit Soal';
            
            const modal = new bootstrap.Modal(document.getElementById('addQuestionModal'));
            modal.show();
        });
    });
    
    // Reset modal when closed
    document.getElementById('addQuestionModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('questionId').value = '0';
        document.getElementById('pertanyaan').value = '';
        document.getElementById('opsi_a').value = '';
        document.getElementById('opsi_b').value = '';
        document.getElementById('opsi_c').value = '';
        document.getElementById('opsi_d').value = '';
        document.getElementById('jawaban_benar').value = 'a';
        document.getElementById('poin').value = '10';
        document.querySelector('.modal-title').textContent = 'Tambah Soal Baru';
    });
</script>

<?php include '../includes/footer.php'; ?>