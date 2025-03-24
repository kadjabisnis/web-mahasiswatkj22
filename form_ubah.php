<?php
// panggil file koneksi.php untuk koneksi ke database
require_once "config/koneksi.php";
session_start(); // Mulai sesi untuk login/logout

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Jika pengguna adalah mahasiswa, ambil data mahasiswa berdasarkan username
if ($_SESSION['role'] === 'mahasiswa') {
    // Jika mahasiswa, ambil data mahasiswa berdasarkan username (nim)
    $query = mysqli_query($db, "SELECT * FROM tbl_mahasiswa_elk WHERE nim='$username'") or die('Query Error : '.mysqli_error($db));
    $data = mysqli_fetch_assoc($query);

    // Set data mahasiswa
    $nim           = $data['nim'];
    $nama          = $data['nama'];
    $tempat_lahir  = $data['tempat_lahir'];
    $tanggal_lahir = date('d-m-Y', strtotime($data['tanggal_lahir']));
    $jenis_kelamin = $data['jenis_kelamin'];
    $agama         = $data['agama'];
    $alamat        = $data['alamat'];
    $no_hp         = $data['no_hp'];
    $foto          = $data['foto'];
    
    // Jika data tidak ditemukan, redirect ke halaman index
    if (!$data) {
        header("Location: index.php?alert=4");
        exit();
    }
} else {
    // Jika bukan mahasiswa, cek apakah ada nim di GET
    if (isset($_GET['nim'])) {
        $nim = $_GET['nim'];
        $query = mysqli_query($db, "SELECT * FROM tbl_mahasiswa_elk WHERE nim='$nim'") or die('Query Error : '.mysqli_error($db));
        $data = mysqli_fetch_assoc($query);

        // Set data mahasiswa
        $nim           = $data['nim'];
        $nama          = $data['nama'];
        $tempat_lahir  = $data['tempat_lahir'];
        $tanggal_lahir = date('d-m-Y', strtotime($data['tanggal_lahir']));
        $jenis_kelamin = $data['jenis_kelamin'];
        $agama         = $data['agama'];
        $alamat        = $data['alamat'];
        $no_hp         = $data['no_hp'];
        $foto          = $data['foto'];
    } else {
        // Jika nim tidak ada di GET, redirect ke halaman index
        header("Location: index.php?alert=4");
        exit();
    }
}

// tutup koneksi
mysqli_close($db);  
?>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">
            <i class="fas fa-edit"></i> Ubah Data mahasiswa
        </div>

        <div class="card">
            <div class="card-body">
                <!-- form ubah data mahasiswa -->
                <form class="needs-validation" action="proses_ubah.php" method="post" enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col">
                            <div class="form-group col-md-12">
                                <label>NIM</label>
                                <input type="text" class="form-control" name="nim" maxlength="12" onKeyPress="return goodchars(event,'0123456789',this)" autocomplete="off" value="<?php echo $nim; ?>" readonly required>
                                <div class="invalid-feedback">NIM tidak boleh kosong.</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Nama mahasiswa</label>
                                <input type="text" class="form-control" name="nama" autocomplete="off" value="<?php echo $nama; ?>" required>
                                <div class="invalid-feedback">Nama mahasiswa tidak boleh kosong.</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="mb-3">Jenis Kelamin</label>
                                <?php
                                if ($jenis_kelamin == 'Laki-laki') { ?>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="customControlValidation2" name="jenis_kelamin" value="Laki-laki" checked required>
                                        <label class="custom-control-label" for="customControlValidation2">Laki-laki</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-4">
                                        <input type="radio" class="custom-control-input" id="customControlValidation3" name="jenis_kelamin" value="Perempuan" required>
                                        <label class="custom-control-label" for="customControlValidation3">Perempuan</label>
                                        <div class="invalid-feedback">Pilih salah satu jenis kelamin.</div>
                                    </div>
                                <?php
                                } else { ?>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="customControlValidation2" name="jenis_kelamin" value="Laki-laki" required>
                                        <label class="custom-control-label" for="customControlValidation2">Laki-laki</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-4">
                                        <input type="radio" class="custom-control-input" id="customControlValidation3" name="jenis_kelamin" value="Perempuan" checked required>
                                        <label class="custom-control-label" for="customControlValidation3">Perempuan</label>
                                        <div class="invalid-feedback">Pilih salah satu jenis kelamin.</div>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label>Agama</label>
                                <select class="form-control" name="agama" autocomplete="off" required>
                                    <option value="<?php echo $agama; ?>"><?php echo $agama; ?></option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Kristen Katolik">Kristen Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                </select>
                                <div class="invalid-feedback">Agama tidak boleh kosong.</div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group col-md-12">
                                <label>Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir" autocomplete="off" value="<?php echo $tempat_lahir; ?>" required>
                                <div class="invalid-feedback">Tempat Lahir tidak boleh kosong.</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Tanggal Lahir</label>
                                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="tanggal_lahir" autocomplete="off" value="<?php echo $tanggal_lahir; ?>" required>
                                <div class="invalid-feedback">Tanggal Lahir tidak boleh kosong.</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Alamat</label>
                                <textarea class="form-control" rows="2" name="alamat" autocomplete="off" required><?php echo $alamat; ?></textarea>
                                <div class="invalid-feedback">Alamat tidak boleh kosong.</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>No. HP</label>
                                <input type="text" class="form-control" name="no_hp" maxlength="13" onKeyPress="return goodchars(event,'0123456789',this)" autocomplete="off" value="<?php echo $no_hp; ?>" required>
                                <div class="invalid-feedback">No. HP tidak boleh kosong.</div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group col-md-12">
                                <label>Foto mahasiswa</label>
                                <input type="file" class="form-control-file mb-3" id="foto" name="foto" onchange="return validasiFile()" autocomplete="off">
                                <div id="imagePreview"><img class="foto-preview" src="foto/<?php echo $foto; ?>"/></div>
                            </div>
                        </div>
                    </div>

                    <div class="my-md-4 pt-md-1 border-top"> </div>

                    <div class="form-group col-md-12 right">
                        <input type="submit" class="btn btn-info btn-submit mr-2" name="simpan" value="Simpan">
                        <a href="index.php" class="btn btn-secondary btn-reset"> Batal </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- jQuery, Bootstrap JS, datepicker JS -->
<script src="assets/plugins/jquery-3.3.1.min.js"></script>
<script src="assets/plugins/bootstrap-4.1.3/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/datepicker/js/datepicker.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/script.js"></script>

<script>
// Validasi form dengan Bootstrap
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Validasi file upload =================================================================
// ----------------------------------------------------------------------------------------------------
function validasiFile() {
    var foto = document.getElementById('foto');
    var file = foto.files[0];
    var allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
    if (file && !allowedExtensions.includes(file.type)) {
        alert('Hanya file gambar yang diperbolehkan (jpeg, png, jpg).');
        foto.value = ''; // Clear the file input
    }
}
</script>
</body>
</html>
