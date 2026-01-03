<?php
require_once 'includes/config.php';

// Redirect to dashboard if logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$page_title = 'Home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizMaster - Aplikasi Kuis Online</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: center;
        }
        
        .feature-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
            height: 100%;
            padding: 30px;
            text-align: center;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .cta-button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .testimonial-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .stats-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .footer {
            background-color: #212529;
            color: white;
            padding: 60px 0 30px;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-brain me-2"></i>QuizMaster
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimoni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="login.php" class="btn btn-primary">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Aplikasi Kuis Online Modern</h1>
                    <p class="lead mb-4">Platform kuis online yang elegan dan responsif untuk kebutuhan pembelajaran, assessment, dan evaluasi dengan berbagai fitur lengkap.</p>
                    <div class="d-flex gap-3">
                        <a href="login.php" class="cta-button">
                            <i class="fas fa-sign-in-alt me-2"></i>Mulai Sekarang
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle me-2"></i>Pelajari Fitur
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-brain fa-10x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Fitur Unggulan</h2>
                <p class="text-muted">QuizMaster dilengkapi dengan berbagai fitur modern untuk pengalaman terbaik</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4>Multi-Role System</h4>
                        <p class="text-muted">3 jenis pengguna: Admin, Peserta, dan Pimpinan dengan akses berbeda sesuai kebutuhan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>Responsive Design</h4>
                        <p class="text-muted">Akses dari berbagai perangkat (desktop, tablet, mobile) dengan tampilan optimal.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4>Analisis Mendalam</h4>
                        <p class="text-muted">Laporan dan statistik detail untuk pemantauan performa peserta.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Timer Real-time</h4>
                        <p class="text-muted">Timer otomatis dengan notifikasi waktu habis dan auto-submit.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h4>Bank Soal Lengkap</h4>
                        <p class="text-muted">Kelola soal dengan mudah, pilihan ganda, dan sistem penilaian otomatis.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h4>Export Data</h4>
                        <p class="text-muted">Ekspor hasil kuis ke format Excel/CSV untuk analisis lebih lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-5">
                    <div class="stat-number">3</div>
                    <p class="text-muted">Jenis Pengguna</p>
                </div>
                <div class="col-md-3 mb-5">
                    <div class="stat-number">100%</div>
                    <p class="text-muted">Responsive</p>
                </div>
                <div class="col-md-3 mb-5">
                    <div class="stat-number">24/7</div>
                    <p class="text-muted">Akses</p>
                </div>
                <div class="col-md-3 mb-5">
                    <div class="stat-number">1000+</div>
                    <p class="text-muted">Soal Tersedia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Testimoni Pengguna</h2>
                <p class="text-muted">Apa kata mereka tentang QuizMaster</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">A</div>
                            <div>
                                <h5 class="mb-0">Admin Sekolah</h5>
                                <small class="text-muted">Pengelola Ujian</small>
                            </div>
                        </div>
                        <p>"Sangat mudah digunakan untuk membuat dan mengelola soal ujian. Fitur ekspor datanya sangat membantu untuk analisis hasil siswa."</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">B</div>
                            <div>
                                <h5 class="mb-0">Guru Matematika</h5>
                                <small class="text-muted">Pendidik</small>
                            </div>
                        </div>
                        <p>"Timer otomatis dan sistem penilaian instan membuat proses assessment menjadi lebih efisien. Siswa juga bisa langsung melihat hasil."</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">C</div>
                            <div>
                                <h5 class="mb-0">Direktur Perusahaan</h5>
                                <small class="text-muted">HR Development</small>
                            </div>
                        </div>
                        <p>"Sempurna untuk training karyawan. Laporan yang detail membantu kami memantau perkembangan skill karyawan dengan mudah."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center">
                <h2 class="fw-bold mb-4">Siap Menggunakan QuizMaster?</h2>
                <p class="lead mb-4">Bergabung dengan ribuan pengguna yang telah merasakan kemudahan dalam pembuatan dan pengelolaan kuis online.</p>
                <a href="login.php" class="cta-button">
                    <i class="fas fa-rocket me-2"></i>Mulai Gratis Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3">
                        <i class="fas fa-brain me-2"></i>QuizMaster
                    </h4>
                    <p class="text-white-50">Platform kuis online modern yang dirancang untuk memenuhi berbagai kebutuhan assessment dan evaluasi.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features" class="text-white-50 text-decoration-none">Fitur</a></li>
                        <li class="mb-2"><a href="#testimonials" class="text-white-50 text-decoration-none">Testimoni</a></li>
                        <li class="mb-2"><a href="#about" class="text-white-50 text-decoration-none">Tentang</a></li>
                        <li><a href="login.php" class="text-white-50 text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-white-50">
                            <i class="fas fa-envelope me-2"></i>gousulalam30@gmail.com
                        </li>
                        <li class="mb-2 text-white-50">
                            <i class="fas fa-phone me-2"></i>(021) 1234-5678
                        </li>
                        <li class="text-white-50">
                            <i class="fas fa-map-marker-alt me-2"></i>Banten, Indonesia
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Roles</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge bg-primary me-1">Admin</span>
                            <span class="text-white-50">Akses penuh</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-success me-1">Peserta</span>
                            <span class="text-white-50">Ikuti kuis & lihat hasil</span>
                        </li>
                        <li>
                            <span class="badge bg-purple me-1">Pimpinan</span>
                            <span class="text-white-50">Lihat laporan</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">&copy; <?php echo date('Y'); ?> g_lamzzz. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 me-3 text-decoration-none">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white', 'shadow-sm');
            } else {
                navbar.classList.remove('bg-white', 'shadow-sm');
            }
        });
    </script>
</body>
</html>