<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        require_once 'includes/auth.php';
        if (loginUser($username, $password)) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Username atau password salah!';
        }
    }
}

$page_title = 'Login';
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
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            margin: 20px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background-color: white;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header i {
            font-size: 3.5rem;
            color: #4361ee;
            margin-bottom: 15px;
        }
        
        .login-header h2 {
            color: #333;
            font-weight: 700;
        }
        
        .login-header p {
            color: #666;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .role-selector {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .role-option {
            flex: 1;
            text-align: center;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .role-option:hover {
            background-color: #f8f9fa;
        }
        
        .role-option.active {
            background-color: #4361ee;
            color: white;
        }
        
        .demo-credentials {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 25px;
            font-size: 0.9rem;
        }
        
        .demo-credentials h6 {
            color: #4361ee;
            margin-bottom: 10px;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-brain"></i>
            <h2>QuizMaster</h2>
            <p>Login ke akun Anda</p>
        </div>
        
        <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>
        
        <div class="demo-credentials">
            <h6><i class="fas fa-info-circle me-2"></i>Kredensial Demo:</h6>
            <div class="row">
                <div class="col-6">
                    <strong>Admin:</strong><br>
                    Username: admin<br>
                    Password: demo123
                </div>
                <div class="col-6">
                    <strong>Peserta:</strong><br>
                    Username: peserta1<br>
                    Password: demo123
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <strong>Pimpinan:</strong><br>
                    Username: pimpinan1<br>
                    Password: demo123
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Role selector functionality
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // Auto-fill credentials based on selected role
                const role = this.getAttribute('data-role');
                const credentials = {
                    'admin': {username: 'admin', password: 'admin123'},
                    'peserta': {username: 'peserta1', password: 'demo123'},
                    'pimpinan': {username: 'pimpinan1', password: 'demo123'}
                };
                
                if (credentials[role]) {
                    document.getElementById('username').value = credentials[role].username;
                    document.getElementById('password').value = credentials[role].password;
                }
            });
        });
        
        // Activate peserta by default
        document.querySelector('.role-option[data-role="peserta"]').classList.add('active');
    </script>
</body>
</html>