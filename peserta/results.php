<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('peserta');

$page_title = 'Hasil Saya';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Get user's results
$results = mysqli_query($conn,
    "SELECT r.*, q.judul, q.deskripsi 
     FROM results r 
     JOIN quizzes q ON r.quiz_id = q.id 
     WHERE r.user_id = $user_id 
     ORDER BY r.completed_at DESC");

// Get statistics
$total_quizzes = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as count FROM results WHERE user_id = $user_id"))['count'];

$average_score = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT AVG(score) as avg FROM results WHERE user_id = $user_id"))['avg'];

$best_score = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT MAX(score) as max FROM results WHERE user_id = $user_id"))['max'];

$total_time = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(waktu_pengerjaan) as total FROM results WHERE user_id = $user_id"))['total'];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hasil & Riwayat Kuis</h1>
    </div>
    
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-list-alt"></i>
                    <h3><?php echo $total_quizzes; ?></h3>
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
                    <i class="fas fa-trophy"></i>
                    <h3><?php echo $best_score; ?></h3>
                    <p>Nilai Tertinggi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-clock"></i>
                    <h3><?php echo floor($total_time / 3600); ?>h</h3>
                    <p>Total Waktu</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Results Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Riwayat Kuis</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kuis</th>
                            <th>Nilai</th>
                            <th>Persentase</th>
                            <th>Jawaban Benar</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = mysqli_fetch_assoc($results)): 
                            $percentage = ($result['score'] / ($result['total_questions'] * 10)) * 100;
                            $time_minutes = floor($result['waktu_pengerjaan'] / 60);
                            $time_seconds = $result['waktu_pengerjaan'] % 60;
                        ?>
                        <tr>
                            <td><?php echo date('d M Y H:i', strtotime($result['completed_at'])); ?></td>
                            <td>
                                <strong><?php echo $result['judul']; ?></strong><br>
                                <small class="text-muted"><?php echo substr($result['deskripsi'], 0, 50) . '...'; ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $percentage >= 70 ? 'success' : 
                                           ($percentage >= 50 ? 'warning' : 'danger'); 
                                ?>">
                                    <?php echo $result['score']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-<?php 
                                        echo $percentage >= 70 ? 'success' : 
                                               ($percentage >= 50 ? 'warning' : 'danger'); 
                                    ?>" 
                                         style="width: <?php echo $percentage; ?>%">
                                        <?php echo number_format($percentage, 1); ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php echo $result['correct_answers']; ?> / <?php echo $result['total_questions']; ?>
                            </td>
                            <td>
                                <?php echo $time_minutes; ?>:<?php echo str_pad($time_seconds, 2, '0', STR_PAD_LEFT); ?>
                            </td>
                            <td>
                                <a href="review.php?result_id=<?php echo $result['id']; ?>" 
                                   class="btn btn-sm btn-info" title="Review">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="certificate.php?result_id=<?php echo $result['id']; ?>" 
                                   class="btn btn-sm btn-success" title="Sertifikat">
                                    <i class="fas fa-award"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if (mysqli_num_rows($results) == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Riwayat Kuis</h5>
                                <p class="text-muted">Mulai kuis pertama Anda!</p>
                                <a href="take_quiz.php" class="btn btn-primary">
                                    <i class="fas fa-play-circle me-2"></i>Ikuti Kuis
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Performance Chart -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Grafik Performansi</h5>
        </div>
        <div class="card-body">
            <canvas id="performanceChart" height="100"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get performance data
    const results = <?php
        $chart_data = array();
        $chart_result = mysqli_query($conn,
            "SELECT score, DATE_FORMAT(completed_at, '%d %b') as date 
             FROM results 
             WHERE user_id = $user_id 
             ORDER BY completed_at ASC 
             LIMIT 10");
        
        while ($row = mysqli_fetch_assoc($chart_result)) {
            $chart_data[] = $row;
        }
        echo json_encode($chart_data);
    ?>;
    
    // Prepare chart data
    const labels = results.map(r => r.date);
    const scores = results.map(r => r.score);
    
    // Create chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai',
                data: scores,
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Nilai'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });
</script>

<?php include '../includes/footer.php'; ?>