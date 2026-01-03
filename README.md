QuizMaster - Aplikasi Kuis Multi-Role
📌 Deskripsi
Aplikasi web kuis online dengan 3 role pengguna:
👑 Admin - Akses penuh (kelola pengguna, kuis, soal, hasil)
👤 Peserta - Kerjakan kuis dan lihat hasil
👔 Pimpinan - Lihat laporan dan statistik

🚀 Teknologi
PHP, MySQL, JavaScript
HTML, CSS, Bootstrap 5
Responsive semua device

🔧 Instalasi Cepat
Import database/quiz_db.sql ke phpMyAdmin
Edit includes/config.php sesuaikan database
Akses http://localhost/quizmaster-app/

🔐 Login Default
Admin:     admin / admin123
Peserta:   peserta1 / demo123  
Pimpinan:  pimpinan1 / demo123

📁 Struktur File
quizmaster-app/
├── index.php
├── login.php
├── dashboard.php
├── database/quiz_db.sql
├── includes/ (config, auth, header, footer)
├── admin/ (kelola user, kuis, soal)
├── peserta/ (kerjakan kuis, lihat hasil)
├── pimpinan/ (laporan)
├── css/style.css
└── js/script.js

✨ Fitur Utama
✅ Multi-role system
✅ Timer real-time dengan auto-submit
✅ Export data ke Excel/CSV
✅ Dashboard dengan chart dan statistik
✅ Review jawaban setelah selesai
✅ Responsive semua device
✅ Modern UI dengan Bootstrap 5

🐛 Troubleshooting
Database error? Periksa includes/config.php
Session error? Pastikan session_start() ada
CSS tidak load? Cek path di header.php

👨‍💻 Developer
Gousul Alam
"Boleh disempurnakan sesuai kebutuhan"

