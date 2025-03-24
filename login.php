<?php
session_start();
require_once "config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil hash password dari database
    $sql = "SELECT password, role FROM users WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedHash, $role);
    $stmt->fetch();
    $stmt->close();

    // Verifikasi password
    if (password_verify($password, $storedHash)) {
        // Password benar, set session dan arahkan ke halaman utama
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: index.php");
        exit();
    } else {
        echo "Username atau password salah!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap-4.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post" class="mt-3">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
    <script src="assets/js/jquery-3.3.1.js"></script>
    <script src="assets/plugins/bootstrap-4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
