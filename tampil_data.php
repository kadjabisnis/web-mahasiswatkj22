<?php 
// pengecekan pencarian data
// jika dilakukan pencarian data, maka tampilkan kata kunci pencarian
if (isset($_POST['cari'])) {
    $cari = $_POST['cari'];
} 
// jika tidak maka kosong
else {
    $cari = "";
}
?>

<div class="row">
    <div class="col-md-12">
    <?php
    // fungsi untuk menampilkan pesan
    if (empty($_GET['alert'])) {
        echo "";
    } 
    // jika alert = 1
    elseif ($_GET['alert'] == 1) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Sukses!</strong> Data mahasiswa berhasil disimpan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php
    } 
    // jika alert = 2
    elseif ($_GET['alert'] == 2) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Sukses!</strong> Data mahasiswa berhasil diubah.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php
    } 
    // jika alert = 3
    elseif ($_GET['alert'] == 3) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Sukses!</strong> Data mahasiswa berhasil dihapus.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php
    } 
    // jika alert = 4 (Gagal, NIM sudah ada)
    elseif ($_GET['alert'] == 4) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-times-circle"></i> Gagal!</strong> NIM 
            <!-- Cek apakah nim tersedia di GET parameter -->
            <?php 
            if (isset($_GET['nim'])) {
                echo '<b>' . htmlspecialchars($_GET['nim']) . '</b>';
            } 
            ?>
            sudah ada.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php
    }
    ?>
  
        <form class="mb-3" action="tampil_data.php" method="post">
            <div class="form-row">
                <!-- form cari -->
                <div class="col-3">
                    <input type="text" class="form-control" name="cari" placeholder="Cari NIM atau Nama Mahasiswa" value="<?php echo $cari; ?>">
                </div>
                <!-- tombol cari -->
                <div class="col-8">
                    <button type="submit" class="btn btn-info">Cari</button>
                </div>
                <!-- tombol tambah data -->
                <div class="col">
                    <a class="btn btn-info" href="?page=tambah" role="button"><i class="fas fa-plus"></i> Tambah</a>
                </div>
            </div>
        </form>

        <!-- Tabel mahasiswa untuk menampilkan data mahasiswa dari database -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Foto</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <?php
            // Pagination --------------------------------------------------------------------------------------------
            // jumlah data yang ditampilkan setiap halaman
            $batas = 5;
            // perintah query untuk menampilkan jumlah data mahasiswa dari database
            if (!empty($cari)) {
                $query_jumlah = mysqli_query($db, "SELECT count(nim) as jumlah FROM tbl_mahasiswa_elk WHERE nim LIKE '%$cari%' OR nama LIKE '%$cari%'")
                                                   or die('Ada kesalahan pada query jumlah_record: '.mysqli_error($db));
            } else {
                $query_jumlah = mysqli_query($db, "SELECT count(nim) as jumlah FROM tbl_mahasiswa_elk")
                                                   or die('Ada kesalahan pada query jumlah_record: '.mysqli_error($db));
            }

            // tampilkan jumlah data
            $data_jumlah = mysqli_fetch_assoc($query_jumlah);
            $jumlah      = $data_jumlah['jumlah'];
            $halaman     = ceil($jumlah / $batas);
            $page        = (isset($_GET['hal'])) ? (int)$_GET['hal'] : 1;
            $mulai       = ($page - 1) * $batas;
            // ------------------------------------------------------------------------------------------------------
            // nomor urut tabel
            $no = $mulai + 1;

            // perintah query untuk menampilkan data mahasiswa dari database
            if (!empty($cari)) {
                $query = mysqli_query($db, "SELECT * FROM tbl_mahasiswa_elk WHERE nim LIKE '%$cari%' OR nama LIKE '%$cari%' ORDER BY nim DESC LIMIT $mulai, $batas")
                                            or die('Ada kesalahan pada query mahasiswa: '.mysqli_error($db));
            } else {
                $query = mysqli_query($db, "SELECT * FROM tbl_mahasiswa_elk ORDER BY nim DESC LIMIT $mulai, $batas")
                                            or die('Ada kesalahan pada query mahasiswa: '.mysqli_error($db));
            }

            // tampilkan data
            while ($data = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td width="30" class="center"><?php echo $no; ?></td>
                    <td width="45" class="center"><img class="foto-thumbnail" src='foto/<?php echo $data['foto']; ?>' alt="Foto Mahasiswa"></td>
                    <td width="80" class="center"><?php echo $data['nim']; ?></td>
                    <td width="180"><?php echo $data['nama']; ?></td>
                    <td width="180"><?php echo $data['tempat_lahir']; ?>, <?php echo date('d-m-Y', strtotime($data['tanggal_lahir'])); ?></td>
                    <td width="120"><?php echo $data['jenis_kelamin']; ?></td>
                    <td width="100"><?php echo $data['agama']; ?></td>
                    <td width="180"><?php echo $data['alamat']; ?></td>
                    <td width="70" class="center"><?php echo $data['no_hp']; ?></td>

                    <td width="120" class="center">
                        <a title="Ubah" class="btn btn-outline-info" href="?page=ubah&nim=<?php echo $data['nim']; ?>"><i class="fas fa-edit"></i></a>
                        <a title="Hapus" class="btn btn-outline-danger" href="proses_hapus.php?nim=<?php echo $data['nim'];?>" onclick="return confirm('Anda yakin ingin menghapus mahasiswa <?php echo $data['nama']; ?>?');"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php
                $no++;
            } ?>
            </tbody>
        </table>
        <!-- Tampilkan Pagination -->
        <?php
        // fungsi untuk pengecekan halaman aktif
        $halaman_aktif = (empty($_GET['hal'])) ? 1 : $_GET['hal'];
        ?>
        <div class="row">
            <div class="col">
                <!-- tampilkan informasi jumlah halaman dan jumlah data -->
                <a>
                    Halaman <?php echo $halaman_aktif; ?> dari <?php echo $halaman; ?> - 
                    Total <?php echo $jumlah; ?> data
                </a>
            </div>
            <div class="col">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                    <!-- Button untuk halaman sebelumnya -->
                    <?php 
                    // jika halaman aktif = 0 atau = 1, maka button Sebelumnya = disable 
                    if ($halaman_aktif<='1') { ?>
                        <li class="page-item disabled"> <span class="page-link">Sebelumnya</span></li>
                    <?php
                    } 
                    // jika halaman aktif > 1, maka button Sebelumnya = aktif 
                    else { ?>
                        <li class="page-item"><a class="page-link" href="?hal=<?php echo $page -1 ?>">Sebelumnya</a></li>
                    <?php } ?>
                    
                    <!-- Button untuk link halaman 1 2 3 ... -->
                    <?php 
                    for($x=1; $x<=$halaman; $x++) { ?>
                        <li class="page-item"><a class="page-link" href="?hal=<?php echo $x ?>"><?php echo $x ?></a></li>
                    <?php } ?>

                    <!-- Button untuk halaman selanjutnya -->
                    <?php 
                    // jika halaman aktif >= jumlah halaman, maka button Selanjutnya = disable 
                    if ($halaman_aktif>=$halaman) { ?>
                        <li class="page-item disabled"> <span class="page-link">Selanjutnya</span></li>
                    <?php
                    } 
                    // jika halaman aktif <= jumlah halaman, maka button Selanjutnya = aktif 
                    else { ?>
                        <li class="page-item"><a class="page-link" href="?hal=<?php echo $page +1 ?>">Selanjutnya</a></li>
                    <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
