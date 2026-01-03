<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('pimpinan');

$page_title = 'Laporan';
include '../includes/header.php';

// Get overall statistics
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_quizzes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM quizzes"))['count'];
$total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM results"))['count'];

// Get recent results
$recent_results = mysqli_query($conn, 
    "SELECT r.*, u.nama_lengkap, q.judul 
     FROM results r 
     JOIN users u ON r.user_id = u.id 
     JOIN quizzes q ON r.quiz_id = q.id 
     ORDER BY r.completed_at DESC LIMIT 10");

// Get top performers
$top_performers = mysqli_query($conn,
    "SELECT u.nama_lengkap, AVG(r.score) as avg_score, COUNT(r.id) as attempts
     FROM results r
     JOIN users u ON r.user_id = u.id
     GROUP BY u.id
     ORDER BY avg_score DESC LIMIT 5");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan & Analisis</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-2"></i>Ekspor
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-users"></i>
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Pengguna</p>
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
                    <i class="fas fa-chart-bar"></i>
                    <h3><?php echo $total_results; ?></h3>
                    <p>Total Percobaan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-percentage"></i>
                    <h3>
                        <?php
                        $avg_score = mysqli_fetch_assoc(mysqli_query($conn, 
                            "SELECT AVG(score) as avg FROM results"))['avg'];
                        echo number_format($avg_score, 1);
                        ?>
                    </h3>
                    <p>Rata-rata Nilai</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Results -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hasil Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Kuis</th>
                                    <th>Nilai</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($result = mysqli_fetch_assoc($recent_results)): ?>
                                <tr>
                                    <td><?php echo $result['nama_lengkap']; ?></td>
                                    <td><?php echo $result['judul']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $result['score'] >= 70 ? 'success' : 
                                                   ($result['score'] >= 50 ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo $result['score']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M H:i', strtotime($result['completed_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Performers -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Performers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Peserta</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Percobaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $rank = 1;
                                while ($performer = mysqli_fetch_assoc($top_performers)): 
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $rank == 1 ? 'warning' : 
                                                   ($rank == 2 ? 'secondary' : 
                                                   ($rank == 3 ? 'danger' : 'light text-dark')); 
                                        ?>">
                                            <?php echo $rank; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $performer['nama_lengkap']; ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: <?php echo $performer['avg_score']; ?>%">
                                                <?php echo number_format($performer['avg_score'], 1); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $performer['attempts']; ?></td>
                                </tr>
                                <?php 
                                $rank++;
                                endwhile; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quiz Performance -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performansi per Kuis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kuis</th>
                                    <th>Peserta</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Tertinggi</th>
                                    <th>Terendah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $quiz_stats = mysqli_query($conn,
                                    "SELECT q.judul, 
                                            COUNT(DISTINCT r.user_id) as participants,
                                            AVG(r.score) as avg_score,
                                            MAX(r.score) as max_score,
                                            MIN(r.score) as min_score
                                     FROM quizzes q
                                     LEFT JOIN results r ON q.id = r.quiz_id
                                     GROUP BY q.id
                                     ORDER BY participants DESC");
                                
                                while ($stat = mysqli_fetch_assoc($quiz_stats)):
                                ?>
                                <tr>
                                    <td><?php echo $stat['judul']; ?></td>
                                    <td><?php echo $stat['participants']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $stat['avg_score'] >= 70 ? 'success' : 
                                                   ($stat['avg_score'] >= 50 ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo number_format($stat['avg_score'], 1); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?php echo number_format($stat['max_score'], 1); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?php echo number_format($stat['min_score'], 1); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>