<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Signup</title>
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                
                <h3 class="text-center">MyBudget</h3>
                <p class="text-center text-muted">Silahkan Masukan Nama, Email, dan Password Anda <a href="login.php">back</a></p>
                

                <form method="post">
                    <label>Nama:</label>
                    <input type="text" name="nama" class="form-control mb-3" placeholder="Masukan Nama Anda" required>

                    <label>Email:</label>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Masukan Email Anda" required>

                    <label>Password:</label>
                    <input type="password" name="password" class="form-control mb-3" placeholder="Masukan Password Anda" required>

                    <button name="simpan" class="btn btn-primary w-100">Simpan</button>
                </form>
                <?php
                include('db_connect.php');

                if (isset($_POST['simpan'])) {

                    $nama = trim($_POST['nama']);
                    $email = trim($_POST['email']);
                    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");

                        if(mysqli_num_rows($cek) > 0){
                            echo "<div class='alert alert-warning mt-3'>
                                    Email sudah digunakan!
                                </div>";
                        }
                    $password = trim($_POST['password']);

                    if ($nama == "" || $email == "" || $password == "") {

                        echo "<div class='alert alert-danger mt-3'>
                                Semua data wajib diisi!
                            </div>";

                    } else {

                        $password = md5($password);

                        $query = mysqli_query($koneksi, 
                            "INSERT INTO user(nama, email, password) 
                            VALUES ('$nama', '$email', '$password')"
                        );

                        if ($query) {
                            echo "<script>
                                    alert('Akun berhasil dibuat!');
                                    window.location='login.php';
                                </script>";
                        } else {
                            echo "<div class='alert alert-danger mt-3'>
                                    Gagal menyimpan akun
                                </div>";
                        }

                    }
                }
                ?>

            </div>
        </div>
    </div>

</div>

</body>
</html>
