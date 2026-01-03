// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('show')) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
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
    
    // Timer for quizzes
    function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        const interval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            
            if (display) {
                display.textContent = minutes + ":" + seconds;
            }
            
            if (--timer < 0) {
                clearInterval(interval);
                // Auto-submit form when time is up
                const quizForm = document.getElementById('quizForm');
                if (quizForm) {
                    quizForm.submit();
                }
            }
        }, 1000);
    }
    
    // Initialize timer if on quiz page
    const timeDisplay = document.getElementById('timeDisplay');
    if (timeDisplay && timeDisplay.dataset.duration) {
        const duration = parseInt(timeDisplay.dataset.duration) * 60;
        startTimer(duration, timeDisplay);
    }
});