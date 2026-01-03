<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireLogin();

$page_title = 'Dashboard';
include 'includes/header.php';

// Get user statistics based on role
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Admin dashboard stats
if ($role == 'admin') {
    $quizzes_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM quizzes"))['count'];
    $users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
    $questions_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM questions"))['count'];
    $results_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM results"))['count'];
    
} elseif ($role == 'peserta') {
    // Peserta dashboard stats
    $completed_quizzes = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT COUNT(*) as count FROM results WHERE user_id = $user_id"))['count'];
    
    $average_score = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT AVG(score) as avg FROM results WHERE user_id = $user_id"))['avg'];
    
    $available_quizzes = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT COUNT(*) as count FROM quizzes WHERE id NOT IN (SELECT quiz_id FROM results WHERE user_id = $user_id)"))['count'];
    
    $total_time = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT SUM(waktu_pengerjaan) as total FROM results WHERE user_id = $user_id"))['total'];
    
} elseif ($role == 'pimpinan') {
    // Pimpinan dashboard stats
    $total_participants = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT COUNT(DISTINCT user_id) as count FROM results"))['count'];
    
    $total_quizzes = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT COUNT(*) as count FROM quizzes"))['count'];
    
    $avg_score_all = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT AVG(score) as avg FROM results"))['avg'];
    
    $total_attempts = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT COUNT(*) as count FROM results"))['count'];
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div>
            <span class="badge bg-primary"><?php echo ucfirst($role); ?></span>
        </div>
    </div>
    
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title">Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h4>
                            <p class="card-text">
                                <?php 
                                if ($role == 'admin') {
                                    echo "Anda dapat mengelola semua aspek aplikasi kuis dari dashboard ini.";
                                } elseif ($role == 'peserta') {
                                    echo "Selesaikan kuis untuk menguji pengetahuan Anda dan lihat hasilnya.";
                                } elseif ($role == 'pimpinan') {
                                    echo "Anda dapat melihat laporan dan hasil kuis dari semua peserta.";
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-chart-line fa-4x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row">
        <?php if ($role == 'admin'): ?>
            <!-- Admin Stats -->
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-users"></i>
                        <h3><?php echo $users_count; ?></h3>
                        <p>Total Pengguna</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-list-alt"></i>
                        <h3><?php echo $quizzes_count; ?></h3>
                        <p>Total Kuis</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h3><?php echo $questions_count; ?></h3>
                        <p>Total Soal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-chart-bar"></i>
                        <h3><?php echo $results_count; ?></h3>
                        <p>Hasil Kuis</p>
                    </div>
                </div>
            </div>
            
        <?php elseif ($role == 'peserta'): ?>
            <!-- Peserta Stats -->
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-check-circle"></i>
                        <h3><?php echo $completed_quizzes; ?></h3>
                        <p>Kuis Diselesaikan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-chart-line"></i>
                        <h3><?php echo number_format($average_score, 1); ?></h3>
                        <p>Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-play-circle"></i>
                        <h3><?php echo $available_quizzes; ?></h3>
                        <p>Kuis Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-clock"></i>
                        <h3><?php echo floor($total_time / 60); ?>m</h3>
                        <p>Total Waktu</p>
                    </div>
                </div>
            </div>
            
        <?php elseif ($role == 'pimpinan'): ?>
            <!-- Pimpinan Stats -->
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
                        <i class="fas fa-list-alt"></i>
                        <h3><?php echo $total_quizzes; ?></h3>
                        <p>Total Kuis</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-chart-line"></i>
                        <h3><?php echo number_format($avg_score_all, 1); ?></h3>
                        <p>Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <i class="fas fa-file-alt"></i>
                        <h3><?php echo $total_attempts; ?></h3>
                        <p>Total Percobaan</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get recent activities based on user role
                                $query = "";
                                if ($role == 'admin') {
                                    $query = "SELECT 'Pengguna Baru' as activity, username as detail, created_at as time FROM users ORDER BY created_at DESC LIMIT 5";
                                } elseif ($role == 'peserta') {
                                    $query = "SELECT 'Menyelesaikan Kuis' as activity, q.judul as detail, r.completed_at as time 
                                              FROM results r 
                                              JOIN quizzes q ON r.quiz_id = q.id 
                                              WHERE r.user_id = $user_id 
                                              ORDER BY r.completed_at DESC LIMIT 5";
                                } elseif ($role == 'pimpinan') {
                                    $query = "SELECT 'Kuis Diselesaikan' as activity, CONCAT(u.nama_lengkap, ' - ', q.judul) as detail, r.completed_at as time 
                                              FROM results r 
                                              JOIN users u ON r.user_id = u.id 
                                              JOIN quizzes q ON r.quiz_id = q.id 
                                              ORDER BY r.completed_at DESC LIMIT 5";
                                }
                                
                                if ($query) {
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td>' . date('d M Y H:i', strtotime($row['time'])) . '</td>';
                                        echo '<td>' . $row['activity'] . '</td>';
                                        echo '<td>' . $row['detail'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if ($role == 'admin'): ?>
                            <div class="col-md-3 mb-3">
                                <a href="admin/manage_users.php" class="btn btn-primary w-100">
                                    <i class="fas fa-users me-2"></i>Kelola Pengguna
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/manage_quiz.php" class="btn btn-primary w-100">
                                    <i class="fas fa-plus-circle me-2"></i>Buat Kuis Baru
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/manage_questions.php" class="btn btn-primary w-100">
                                    <i class="fas fa-question-circle me-2"></i>Tambah Soal
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/view_results.php" class="btn btn-primary w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Lihat Hasil
                                </a>
                            </div>
                            
                        <?php elseif ($role == 'peserta'): ?>
                            <div class="col-md-4 mb-3">
                                <a href="peserta/take_quiz.php" class="btn btn-primary w-100">
                                    <i class="fas fa-play-circle me-2"></i>Ikuti Kuis
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="peserta/results.php" class="btn btn-primary w-100">
                                    <i class="fas fa-chart-line me-2"></i>Lihat Hasil
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="peserta/history.php" class="btn btn-primary w-100">
                                    <i class="fas fa-history me-2"></i>Riwayat Saya
                                </a>
                            </div>
                            
                        <?php elseif ($role == 'pimpinan'): ?>
                            <div class="col-md-6 mb-3">
                                <a href="pimpinan/reports.php" class="btn btn-primary w-100">
                                    <i class="fas fa-chart-pie me-2"></i>Lihat Laporan
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="#" class="btn btn-primary w-100">
                                    <i class="fas fa-download me-2"></i>Ekspor Data
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>