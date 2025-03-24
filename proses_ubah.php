<?php
// panggil file koneksi.php untuk koneksi ke database
require_once "config/koneksi.php";
// jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    if (isset($_POST['nim'])) {
        // ambil data hasil submit dari form
        $nim           = mysqli_real_escape_string($db, trim($_POST['nim']));
        $nama          = mysqli_real_escape_string($db, trim($_POST['nama']));
        $tempat_lahir  = mysqli_real_escape_string($db, trim($_POST['tempat_lahir']));
        $tanggal_lahir = mysqli_real_escape_string($db, trim(date('Y-m-d', strtotime($_POST['tanggal_lahir']))));
        $jenis_kelamin = mysqli_real_escape_string($db, trim($_POST['jenis_kelamin']));
        $agama         = mysqli_real_escape_string($db, trim($_POST['agama']));
        $alamat        = mysqli_real_escape_string($db, trim($_POST['alamat']));
        $no_hp         = mysqli_real_escape_string($db, trim($_POST['no_hp']));
        $nama_file     = $_FILES['foto']['name'];
        $tmp_file      = $_FILES['foto']['tmp_name'];
        $path          = "foto/" . $nama_file;

        // jika foto tidak diubah
        if (empty($nama_file)) {
            // Update data tanpa mengubah foto
            $update = mysqli_query($db, "UPDATE tbl_mahasiswa_elk SET nama          = '$nama',
                                                                      tempat_lahir  = '$tempat_lahir',
                                                                      tanggal_lahir = '$tanggal_lahir',
                                                                      jenis_kelamin = '$jenis_kelamin',
                                                                      agama         = '$agama',
                                                                      alamat        = '$alamat',
                                                                      no_hp         = '$no_hp'
                                                                WHERE nim           = '$nim'")
                                    or die('Ada kesalahan pada query update : ' . mysqli_error($db));
            if ($update) {
                // jika berhasil tampilkan pesan berhasil ubah data
                header("Location: index.php?alert=2");
                exit();
            }
        }
        // jika foto diubah
        else {
            // Upload file
            if (move_uploaded_file($tmp_file, $path)) {
                // Update data dengan foto baru
                $update = mysqli_query($db, "UPDATE tbl_mahasiswa_elk SET nama          = '$nama',
                                                                          tempat_lahir  = '$tempat_lahir',
                                                                          tanggal_lahir = '$tanggal_lahir',
                                                                          jenis_kelamin = '$jenis_kelamin',
                                                                          agama         = '$agama',
                                                                          alamat        = '$alamat',
                                                                          no_hp         = '$no_hp',
                                                                          foto          = '$nama_file'
                                                                    WHERE nim           = '$nim'")
                                        or die('Ada kesalahan pada query update : ' . mysqli_error($db));
                if ($update) {
                    // jika berhasil tampilkan pesan berhasil ubah data
                    header("Location: index.php?alert=2");
                    exit();
                }
            } else {
                // Jika gagal upload file
                header("Location: ubah.php?alert=4&nim=$nim");
                exit();
            }
        }
    }
}
// tutup koneksi
mysqli_close($db);
?>
