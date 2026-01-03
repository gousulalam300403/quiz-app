<?php if (isset($_SESSION['user_id'])): ?>
            </div> <!-- Close main-content -->
        </div> <!-- Close row -->
    </div> <!-- Close container-fluid -->
<?php endif; ?>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>QuizMaster</h5>
                <p class="mb-0">Aplikasi kuis online modern dan elegan untuk berbagai kebutuhan pembelajaran.</p>
                <p class="mt-2">
                    <i class="fas fa-envelope me-2"></i>gousulalam30@gmail.com<br>
                    <i class="fas fa-phone me-2"></i>(021) 1234-5678
                </p>
            </div>
            <div class="col-md-3">
                <h5>Menu Cepat</h5>
                <ul class="list-unstyled">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li><a href="<?php echo BASE_URL; ?>admin/manage_users.php" class="text-white-50 text-decoration-none">Kelola Pengguna</a></li>
                            <li><a href="<?php echo BASE_URL; ?>admin/manage_quiz.php" class="text-white-50 text-decoration-none">Kelola Kuis</a></li>
                        <?php elseif ($_SESSION['role'] == 'peserta'): ?>
                            <li><a href="<?php echo BASE_URL; ?>peserta/take_quiz.php" class="text-white-50 text-decoration-none">Ikuti Kuis</a></li>
                            <li><a href="<?php echo BASE_URL; ?>peserta/results.php" class="text-white-50 text-decoration-none">Hasil Saya</a></li>
                        <?php elseif ($_SESSION['role'] == 'pimpinan'): ?>
                            <li><a href="<?php echo BASE_URL; ?>pimpinan/reports.php" class="text-white-50 text-decoration-none">Laporan</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>login.php" class="text-white-50 text-decoration-none">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Ikuti Kami</h5>
                <div class="social-links">
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white-50"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
                <p class="mt-3 text-white-50 small">
                    &copy; <?php echo date('Y'); ?> g_lamzzz. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo BASE_URL; ?>js/script.js"></script>

<script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('show')) {
                if (!sidebar.contains(event.target) && 
                    (!sidebarToggle || !sidebarToggle.contains(event.target))) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>

</body>
</html>