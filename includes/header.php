<?php
if (!isset($page_title)) {
    $page_title = 'Aplikasi Kuis Soal';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Quiz App</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            color: white;
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 2px 0;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .stat-card {
            text-align: center;
            padding: 25px 15px;
        }
        
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-card p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .table th {
            font-weight: 600;
            border-top: none;
            color: var(--dark-color);
        }
        
        .quiz-card {
            cursor: pointer;
            height: 100%;
        }
        
        .quiz-card h5 {
            color: var(--primary-color);
        }
        
        .badge-admin {
            background-color: #dc3545;
        }
        
        .badge-peserta {
            background-color: #28a745;
        }
        
        .badge-pimpinan {
            background-color: #6f42c1;
        }
        
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        footer {
            margin-top: auto;
            background-color: var(--dark-color);
            color: white;
            padding: 20px 0;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
                position: fixed;
                z-index: 1000;
            }
            
            .sidebar.show {
                width: 250px;
            }
            
            .main-content {
                width: 100%;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
        
        .question-item {
            border-left: 4px solid var(--primary-color);
            padding-left: 15px;
            margin-bottom: 20px;
        }
        
        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <button class="btn d-lg-none me-3 mobile-menu-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>dashboard.php">
                <i class="fas fa-brain me-2"></i>QuizMaster
            </a>
            
            <div class="navbar-nav ms-auto align-items-center">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; border-radius: 50%;">
                            <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
                        </div>
                        <span><?php echo $_SESSION['nama_lengkap']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2 sidebar d-none d-lg-block" id="sidebar">
                <div class="px-3 pt-4">
                    <div class="user-info text-center mb-4">
                        <div class="avatar-circle bg-light text-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px; border-radius: 50%; font-size: 1.8rem; font-weight: bold;">
                            <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
                        </div>
                        <h6 class="mb-1"><?php echo $_SESSION['nama_lengkap']; ?></h6>
                        <span class="badge rounded-pill bg-light text-dark">
                            <?php echo $_SESSION['role']; ?>
                        </span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/manage_users.php">
                                    <i class="fas fa-users"></i> Kelola Pengguna
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_quiz.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/manage_quiz.php">
                                    <i class="fas fa-list-alt"></i> Kelola Kuis
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_questions.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/manage_questions.php">
                                    <i class="fas fa-question-circle"></i> Kelola Soal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_results.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/view_results.php">
                                    <i class="fas fa-chart-bar"></i> Hasil Kuis
                                </a>
                            </li>
                            
                        <?php elseif ($_SESSION['role'] == 'peserta'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'take_quiz.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>peserta/take_quiz.php">
                                    <i class="fas fa-play-circle"></i> Ikuti Kuis
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>peserta/results.php">
                                    <i class="fas fa-chart-line"></i> Hasil Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>peserta/history.php">
                                    <i class="fas fa-history"></i> Riwayat
                                </a>
                            </li>
                            
                        <?php elseif ($_SESSION['role'] == 'pimpinan'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>pimpinan/reports.php">
                                    <i class="fas fa-chart-pie"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-eye"></i> Lihat Hasil
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10 main-content" id="mainContent">
    <?php endif; ?>