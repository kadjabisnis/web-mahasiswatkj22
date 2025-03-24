<?php
// Panggil file koneksi.php untuk koneksi ke database
require_once "config/koneksi.php";
session_start(); // Mulai sesi untuk login/logout

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}

// Cek role pengguna
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
    exit();
}

// Jika tombol hapus diklik
if (isset($_GET['nim'])) {
    // Ambil data get dari form 
    $nim = $_GET['nim'];
    
    // Perintah query untuk menampilkan data foto mahasiswa berdasarkan nim
    $query = mysqli_query($db, "SELECT foto FROM tbl_mahasiswa_elk WHERE nim='$nim'") 
                                or die('Ada kesalahan pada query tampil data foto : '.mysqli_error($db));
    $data = mysqli_fetch_assoc($query);
    $foto = $data['foto'];

    // Hapus file foto dari folder foto
    if ($foto && file_exists("foto/$foto")) {
        $hapus_file = unlink("foto/$foto");
        // Cek hapus file
        if (!$hapus_file) {
            die('Gagal menghapus file foto.');
        }
    }

    // Jalankan perintah query untuk menghapus data pada tabel mahasiswa elektro
    $delete = mysqli_query($db, "DELETE FROM tbl_mahasiswa_elk WHERE nim='$nim'") 
                                    or die('Ada kesalahan pada query delete : '.mysqli_error($db));

    // Cek hasil query
    if ($delete) {
        // Jika berhasil, arahkan kembali ke halaman index dengan alert
        header("Location: index.php?page=tampil&alert=3");
        exit();
    } else {
        die('Gagal menghapus data mahasiswa.');
    }
}

// Tutup koneksi
mysqli_close($db);  
?>
