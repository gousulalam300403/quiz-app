<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

$page_title = 'Kelola Kuis';
include '../includes/header.php';

// Handle delete quiz
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM quizzes WHERE id = $delete_id");
    $_SESSION['message'] = 'Kuis berhasil dihapus';
    header('Location: manage_quiz.php');
    exit();
}

// Handle add/edit quiz
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $durasi = intval($_POST['durasi']);
    $created_by = $_SESSION['user_id'];
    
    if ($id > 0) {
        // Update existing quiz
        $query = "UPDATE quizzes SET judul='$judul', deskripsi='$deskripsi', durasi=$durasi WHERE id=$id";
        $message = 'Kuis berhasil diperbarui';
    } else {
        // Add new quiz
        $query = "INSERT INTO quizzes (judul, deskripsi, durasi, created_by) VALUES ('$judul', '$deskripsi', $durasi, $created_by)";
        $message = 'Kuis berhasil ditambahkan';
    }
    
    mysqli_query($conn, $query);
    $_SESSION['message'] = $message;
    header('Location: manage_quiz.php');
    exit();
}

// Get quizzes list
$quizzes = mysqli_query($conn, 
    "SELECT q.*, u.nama_lengkap as creator, 
            (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) as question_count
     FROM quizzes q 
     LEFT JOIN users u ON q.created_by = u.id 
     ORDER BY q.created_at DESC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Kuis</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">
            <i class="fas fa-plus me-2"></i>Tambah Kuis
        </button>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Durasi</th>
                            <th>Jumlah Soal</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal Buat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($quiz = mysqli_fetch_assoc($quizzes)): ?>
                        <tr>
                            <td><?php echo $quiz['id']; ?></td>
                            <td><?php echo $quiz['judul']; ?></td>
                            <td><?php echo substr($quiz['deskripsi'], 0, 50) . '...'; ?></td>
                            <td><?php echo $quiz['durasi']; ?> menit</td>
                            <td>
                                <span class="badge bg-info"><?php echo $quiz['question_count']; ?> soal</span>
                            </td>
                            <td><?php echo $quiz['creator']; ?></td>
                            <td><?php echo date('d M Y', strtotime($quiz['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-quiz" data-quiz='<?php echo json_encode($quiz); ?>'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kuis ini? Semua soal terkait juga akan dihapus.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="manage_questions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-question-circle"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kuis Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="id" id="quizId" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Kuis</label>
                        <input type="text" class="form-control" name="judul" id="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi (menit)</label>
                        <input type="number" class="form-control" name="durasi" id="durasi" value="60" min="1" required>
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
    // Edit quiz functionality
    document.querySelectorAll('.edit-quiz').forEach(button => {
        button.addEventListener('click', function() {
            const quiz = JSON.parse(this.getAttribute('data-quiz'));
            document.getElementById('quizId').value = quiz.id;
            document.getElementById('judul').value = quiz.judul;
            document.getElementById('deskripsi').value = quiz.deskripsi;
            document.getElementById('durasi').value = quiz.durasi;
            document.querySelector('.modal-title').textContent = 'Edit Kuis';
            
            const modal = new bootstrap.Modal(document.getElementById('addQuizModal'));
            modal.show();
        });
    });
    
    // Reset modal when closed
    document.getElementById('addQuizModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('quizId').value = '0';
        document.getElementById('judul').value = '';
        document.getElementById('deskripsi').value = '';
        document.getElementById('durasi').value = '60';
        document.querySelector('.modal-title').textContent = 'Tambah Kuis Baru';
    });
</script>

<?php include '../includes/footer.php'; ?>