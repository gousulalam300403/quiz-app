<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

$page_title = 'Kelola Pengguna';
include '../includes/header.php';

// Handle delete user
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    if ($delete_id != $_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id");
        $_SESSION['message'] = 'Pengguna berhasil dihapus';
        header('Location: manage_users.php');
        exit();
    }
}

// Handle add/edit user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];
    
    if ($id > 0) {
        // Update existing user
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', email='$email', role='$role', password='$hashed_password' WHERE id=$id";
        } else {
            $query = "UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', email='$email', role='$role' WHERE id=$id";
        }
        $message = 'Pengguna berhasil diperbarui';
    } else {
        // Add new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, nama_lengkap, email, role) VALUES ('$username', '$hashed_password', '$nama_lengkap', '$email', '$role')";
        $message = 'Pengguna berhasil ditambahkan';
    }
    
    mysqli_query($conn, $query);
    $_SESSION['message'] = $message;
    header('Location: manage_users.php');
    exit();
}

// Get users list
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus me-2"></i>Tambah Pengguna
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
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['nama_lengkap']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-user" data-user='<?php echo json_encode($user); ?>'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="id" id="userId" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="admin">Admin</option>
                            <option value="peserta">Peserta</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password (untuk edit)</small>
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
    // Edit user functionality
    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', function() {
            const user = JSON.parse(this.getAttribute('data-user'));
            document.getElementById('userId').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('nama_lengkap').value = user.nama_lengkap;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            document.getElementById('password').required = false;
            document.querySelector('.modal-title').textContent = 'Edit Pengguna';
            
            const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
            modal.show();
        });
    });
    
    // Reset modal when closed
    document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('userId').value = '0';
        document.getElementById('username').value = '';
        document.getElementById('nama_lengkap').value = '';
        document.getElementById('email').value = '';
        document.getElementById('role').value = 'peserta';
        document.getElementById('password').value = '';
        document.getElementById('password').required = true;
        document.querySelector('.modal-title').textContent = 'Tambah Pengguna Baru';
    });
</script>

<?php include '../includes/footer.php'; ?>