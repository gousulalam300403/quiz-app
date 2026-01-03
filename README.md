# QuizMaster - Aplikasi Kuis Multi-Role

## Deskripsi
Aplikasi web kuis online dengan 3 role pengguna:
- **Admin**: Akses penuh (kelola pengguna, kuis, soal, hasil)
- **Peserta**: Kerjakan kuis dan lihat hasil
- **Pimpinan**: Lihat laporan dan statistik

## Teknologi
- PHP, MySQL, JavaScript
- HTML, CSS, Bootstrap 5
- Responsive untuk semua device

## Instalasi Cepat
1. Import file `database/quiz_db.sql` ke phpMyAdmin
2. Edit `includes/config.php` sesuaikan koneksi database
3. Akses `http://localhost/quiz-app`

## Login Default
- Admin: username: admin password: demo123
- Peserta: username: peserta1 password: demo123
- Pimpinan: username: pimpinan1 password: demo123


## Fitur Utama
- ✅ Sistem multi-role dengan hak akses berbeda
- ✅ Timer real-time dengan auto-submit
- ✅ Export data ke format Excel/CSV
- ✅ Dashboard dengan chart dan statistik
- ✅ Review jawaban setelah selesai
- ✅ Responsive untuk semua device
- ✅ Modern UI dengan Bootstrap 5

## Troubleshooting
1. **Database error**: Periksa konfigurasi di `includes/config.php`
2. **Session error**: Pastikan `session_start()` ada di setiap halaman
3. **CSS tidak load**: Cek path di `header.php`

## Developer
**Gousul Alam**  
*"Boleh disempurnakan sesuai kebutuhan"*

---

⭐ **Jika proyek ini bermanfaat, beri star di GitHub!**
