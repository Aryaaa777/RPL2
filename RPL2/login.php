<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                
                <h3 class="text-center" href="login.php">MyBudget</h3>
                <p class="text-center text-muted">Silahkan Masukan Email dan Password Anda</p>

                <form method="post">
                    
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Masukan Email Anda">

                    <label>Password:</label>
                    <input type="password" name="password" class="form-control mb-3" placeholder="Masukan Password Anda">

                    <div class="d-flex gap-2">
                        <button name="login" class="btn btn-primary w-50">Login</button>
                        <a href="signup.php" class="btn btn-success w-50">SignUp</a>
                    </div>

                </form>

                <?php
                session_start();
                include('db_connect.php');

                if (isset($_POST['login'])){
                    $email = $_POST['email'];
                    $password = md5($_POST['password']);

                    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email' AND password='$password'");
                    $user = mysqli_fetch_assoc($query); 

                    if ($user) {
                        $_SESSION['user'] = $user;
                        header('location:index.php');
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Email atau Password salah</div>";
                    }
                }
                ?>

            </div>
        </div>
    </div>

</div>

</body>
</html>
