<?php
// panggil file koneksi.php untuk koneksi ke database
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
?>

<!doctype html>
<html lang="en">
  	<head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Form Tambah Data Mahasiswa">
		<meta name="keywords" content="Form Tambah Data Mahasiswa">
		<meta name="author" content="Your Name">

		<!-- favicon -->
    	<link rel="shortcut icon" href="assets/img/favicon.png">
	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-4.1.3/css/bootstrap.min.css">
        <!-- datepicker CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/css/datepicker.min.css">
	    <!-- Font Awesome CSS -->
	    <link rel="stylesheet" type="text/css" href="assets/plugins/fontawesome-free-5.4.1-web/css/all.min.css">
	    <!-- Custom CSS -->
	    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <!-- title -->
	    <title>Form Tambah Data Mahasiswa</title>
  	</head>
  	<body>
  		<div class="container-fluid">
	    	<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
                <h5 class="my-0 mr-md-auto font-weight-normal"><i class="fas fa-user-plus title-icon"></i> Tambah Data Mahasiswa</h5>
                <a href="index.php" class="btn btn-secondary ml-auto">Kembali</a>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
		        <div class="col-md-12">
					<div class="alert alert-info" role="alert">
	  					<i class="fas fa-edit"></i> Input Data Mahasiswa
					</div>

					<div class="card">
					  	<div class="card-body">
					    	<!-- Form tambah data mahasiswa -->
					    	<form class="needs-validation" action="proses_simpan.php" method="post" enctype="multipart/form-data" novalidate>
							  	<div class="row">
							    	<div class="col">
							      		<div class="form-group col-md-12">
										<label>NIM</label>
										<input type="text" class="form-control" name="nim" maxlength="12" onKeyPress="return goodchars(event,'0123456789',this)" autocomplete="off" required>
										<div class="invalid-feedback">NIM tidak boleh kosong.</div>
									</div>

									<div class="form-group col-md-12">
										<label>Nama mahasiswa</label>
										<input type="text" class="form-control" name="nama" autocomplete="off" required>
										<div class="invalid-feedback">Nama mahasiswa tidak boleh kosong.</div>
									</div>

									<div class="form-group col-md-12">
										<label class="mb-3">Jenis Kelamin</label>
										<div class="custom-control custom-radio">
										    <input type="radio" class="custom-control-input" id="customControlValidation2" name="jenis_kelamin" value="Laki-laki" required>
										    <label class="custom-control-label" for="customControlValidation2">Laki-laki</label>
										</div>
										<div class="custom-control custom-radio mb-4">
										    <input type="radio" class="custom-control-input" id="customControlValidation3" name="jenis_kelamin" value="Perempuan" required>
										    <label class="custom-control-label" for="customControlValidation3">Perempuan</label>
										    <div class="invalid-feedback">Pilih salah satu jenis kelamin.</div>
										</div>
									</div>
									
							      		<div class="form-group col-md-12">
										<label>Agama</label>
										<select class="form-control" name="agama" autocomplete="off" required>
									      	<option value=""></option>
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
											<input type="text" class="form-control" name="tempat_lahir" autocomplete="off" required>
											<div class="invalid-feedback">Tempat Lahir tidak boleh kosong.</div>
										</div>

										<div class="form-group col-md-12">
											<label>Tanggal Lahir</label>
											<input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="tanggal_lahir" autocomplete="off" required>
											<div class="invalid-feedback">Tanggal Lahir tidak boleh kosong.</div>
										</div>

										<div class="form-group col-md-12">
											<label>Alamat</label>
											<textarea class="form-control" rows="2" name="alamat" autocomplete="off" required></textarea>
											<div class="invalid-feedback">Alamat tidak boleh kosong.</div>
										</div>

										<div class="form-group col-md-12">
											<label>No. HP</label>
											<input type="text" class="form-control" name="no_hp" maxlength="13" onKeyPress="return goodchars(event,'0123456789',this)" autocomplete="off" required>
											<div class="invalid-feedback">No. HP tidak boleh kosong.</div>
										</div>
							    	</div>

							    	<div class="col">
							    		<div class="form-group col-md-12">
											<label>Foto mahasiswa</label>
		    								<input type="file" class="form-control-file mb-3" id="foto" name="foto" onchange="return validasiFile()" autocomplete="off" required>
											<div id="imagePreview"><img class="foto-preview" src="foto/default.png"/></div>
											<div class="invalid-feedback">Foto mahasiswa tidak boleh kosong.</div>
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
		
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Bootstrap JS -->
        <script type="text/javascript" src="assets/js/jquery-3.3.1.js"></script>
	    <script type="text/javascript" src="assets/plugins/bootstrap-4.1.3/js/bootstrap.min.js"></script>
        <!-- fontawesome js -->
	    <script type="text/javascript" src="assets/plugins/fontawesome-free-5.4.1-web/js/all.min.js"></script>
        <!-- datepicker js -->
        <script type="text/javascript" src="assets/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>

        <script type="text/javascript">
        // Initiate plugin ====================================================================================
        // ----------------------------------------------------------------------------------------------------
        $(document).ready(function() {
            // Datepicker plugin
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            });
        } );
        // ====================================================================================================

        // Validasi Form Tambah =================================================================
        // ----------------------------------------------------------------------------------------------------
        // Validasi form input tidak boleh kosong
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
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
