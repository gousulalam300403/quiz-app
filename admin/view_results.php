<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

$page_title = 'Hasil Kuis';
include '../includes/header.php';

// Filter options
$quiz_filter = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$user_filter = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Build query
$query = "SELECT r.*, u.nama_lengkap, u.username, q.judul as quiz_title 
          FROM results r 
          JOIN users u ON r.user_id = u.id 
          JOIN quizzes q ON r.quiz_id = q.id 
          WHERE 1=1";
          
if ($quiz_filter > 0) {
    $query .= " AND r.quiz_id = $quiz_filter";
}

if ($user_filter > 0) {
    $query .= " AND r.user_id = $user_filter";
}

$query .= " ORDER BY r.completed_at DESC";

$results = mysqli_query($conn, $query);

// Get all quizzes for filter
$quizzes = mysqli_query($conn, "SELECT * FROM quizzes ORDER BY judul");

// Get all users for filter
$users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'peserta' ORDER BY nama_lengkap");

// Statistics
$total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM results"))['count'];
$avg_score = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(score) as avg FROM results"))['avg'];
$total_participants = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as count FROM results"))['count'];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hasil Kuis</h1>
        <div>
            <a href="export_results.php" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Ekspor Excel
            </a>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-chart-bar"></i>
                    <h3><?php echo $total_results; ?></h3>
                    <p>Total Percobaan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-users"></i>
                    <h3><?php echo $total_participants; ?></h3>
                    <p>Total Peserta</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-percentage"></i>
                    <h3><?php echo number_format($avg_score, 1); ?></h3>
                    <p>Rata-rata Nilai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-clock"></i>
                    <h3>
                        <?php
                        $total_time = mysqli_fetch_assoc(mysqli_query($conn, 
                            "SELECT SUM(waktu_pengerjaan) as total FROM results"))['total'];
                        echo floor($total_time / 3600);
                        ?>
                    </h3>
                    <p>Jam Pengerjaan</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">Filter Hasil</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Pilih Kuis</label>
                    <select name="quiz_id" class="form-control">
                        <option value="0">Semua Kuis</option>
                        <?php while ($quiz = mysqli_fetch_assoc($quizzes)): ?>
                        <option value="<?php echo $quiz['id']; ?>" 
                            <?php echo $quiz_filter == $quiz['id'] ? 'selected' : ''; ?>>
                            <?php echo $quiz['judul']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Pilih Peserta</label>
                    <select name="user_id" class="form-control">
                        <option value="0">Semua Peserta</option>
                        <?php while ($user = mysqli_fetch_assoc($users)): ?>
                        <option value="<?php echo $user['id']; ?>"
                            <?php echo $user_filter == $user['id'] ? 'selected' : ''; ?>>
                            <?php echo $user['nama_lengkap']; ?> (<?php echo $user['username']; ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Results Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Peserta</th>
                            <th>Kuis</th>
                            <th>Nilai</th>
                            <th>Detail</th>
                            <th>Waktu</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = mysqli_fetch_assoc($results)): 
                            $percentage = ($result['score'] / ($result['total_questions'] * 10)) * 100;
                        ?>
                        <tr>
                            <td><?php echo $result['id']; ?></td>
                            <td>
                                <strong><?php echo $result['nama_lengkap']; ?></strong><br>
                                <small class="text-muted">@<?php echo $result['username']; ?></small>
                            </td>
                            <td><?php echo $result['quiz_title']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-<?php 
                                            echo $percentage >= 70 ? 'success' : 
                                                   ($percentage >= 50 ? 'warning' : 'danger'); 
                                        ?>" 
                                             style="width: <?php echo $percentage; ?>%">
                                        </div>
                                    </div>
                                    <span class="badge bg-<?php 
                                        echo $percentage >= 70 ? 'success' : 
                                               ($percentage >= 50 ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo $result['score']; ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <?php echo $result['correct_answers']; ?> dari <?php echo $result['total_questions']; ?> soal
                            </td>
                            <td>
                                <?php echo floor($result['waktu_pengerjaan'] / 60); ?> menit
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($result['completed_at'])); ?></td>
                            <td>
                                <a href="result_detail.php?id=<?php echo $result['id']; ?>" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="?delete=<?php echo $result['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus hasil ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
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

<?php include '../includes/footer.php'; ?>