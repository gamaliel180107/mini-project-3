# Product Manager Mini Project

Ini adalah mini project untuk modul Pemrograman Web Pertemuan 3.
Aplikasi ini melakukan operasi CRUD dengan PHP dan MySQL, menggunakan implementasi keamanan seperti PDO (Prepared Statements), CSRF token, validasi sisi server, pencegahan XSS, dan menggunakan PRG (Post-Redirect-Get) pattern. UI dibuat responsif menggunakan Flexbox dan Box Model.

## Cara Menjalankan

1. Pastikan Anda memiliki server lokal dengan PHP dan MySQL (misal: XAMPP, Laragon, dll).
2. Buat database baru jika belum ada, atau biarkan skema mengurusnya.
3. Import file `database/store_db.sql` ke dalam MySQL Anda, atau cukup jalankan perintah di dalamnya.
4. Letakkan folder proyek ini ke dalam document root web server Anda (misal: `htdocs` untuk XAMPP).
5. Konfigurasi kredensial database di `config/db.php` jika diperlukan (default: localhost, root, tanpa password, db: store_db).
6. Buka URL proyek di browser, contoh: `http://localhost/mini_project_3/public/index.php`.

## Fitur
- **Create**: Tambah produk (Nama, Kategori, Harga, Stok). Validasi minimal 3 karakter, harga > 0, stok >= 0. Nama unik.
- **Read**: Menampilkan produk dalam bentuk card yang responsif dengan fitur pencarian (BONUS feature).
- **Update**: Mengubah data produk yang ada.
- **Delete**: Menghapus produk (dengan POST request dan validasi CSRF token).
- **Keamanan**: Filter dan validasi input, sanitasi output (htmlspecialchars), pencegahan duplikasi saat refresh (PRG pattern).