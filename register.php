<?php
require_once "config/koneksi.php"; // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan username dan hashed password ke database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registrasi berhasil!";
    } else {
        echo "Terjadi kesalahan!";
    }

    $stmt->close();
}
?>
