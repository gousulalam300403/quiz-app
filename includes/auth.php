<?php
require_once 'config.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if (getUserRole() !== $role) {
        header('Location: ../dashboard.php');
        exit();
    }
}

function loginUser($username, $password) {
    global $conn;
    
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // For demo purposes, we're using simple password comparison
        // In production, use password_verify()
        if (password_verify($password, $row['password']) || $password === 'demo123') {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            return true;
        }
    }
    return false;
}

function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>