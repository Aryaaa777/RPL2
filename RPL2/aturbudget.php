<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Atur Budget</title>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow navbar-expand-lg">
    <div class="container">

        <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand fw-bold fst-italic" href="index.php">
            MyBudget
        </a>

        <div class="collapse navbar-collapse" id="menuNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aturbudget.php">Atur Budget</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tambahtransaksi.php">Tambah Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laporan.php">Laporan</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <span class="text-white">
                    Hallo, <?= $_SESSION['user']['nama'] ?>
                </span>

                <form method="post" class="mb-0">
                    <button name="logout" class="btn btn-sm btn-warning">
                        Logout
                    </button>
                </form>
                <?php
                if (isset($_POST["logout"])) {
                    session_destroy();
                    header('location:login.php');
                }
            ?>
            </div>
        </div>

    </div>
</nav>


<div class="container py-4">

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">🎯 Atur Budget</h5>

                    <form method="post">
                        <input type="number" name="budget" class="form-control mb-3" placeholder="Batas pengeluaran">

                        <button name="set_budget" class="btn btn-primary w-100">
                            Aktifkan Pemantauan
                        </button>
                    </form>
                    <?php
                        include('db_connect.php');

                        if(isset($_POST['set_budget'])){

                            $user_id = $_SESSION['user']['id'];
                            $budget = $_POST['budget'];

                            $bulan = date('m');
                            $tahun = date('Y');

                            $cek = mysqli_query($koneksi,"SELECT * FROM atur_budget 
                                WHERE user_id='$user_id' 
                                AND bulan='$bulan' 
                                AND tahun='$tahun'");

                            if(mysqli_num_rows($cek) > 0){

                                mysqli_query($koneksi,"UPDATE atur_budget SET budget='$budget'
                                    WHERE user_id='$user_id'
                                    AND bulan='$bulan'
                                    AND tahun='$tahun'");

                            } else {

                                mysqli_query($koneksi,"INSERT INTO atur_budget(user_id,budget,bulan,tahun)
                                    VALUES('$user_id','$budget','$bulan','$tahun')");
                            }
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php

$user_id = $_SESSION['user']['id'];
$bulan = date('m');
$tahun = date('Y');

$budget = 0;
$total_pengeluaran = 0;

$qBudget = mysqli_query($koneksi,"SELECT budget FROM atur_budget
    WHERE user_id='$user_id'
    AND bulan='$bulan'
    AND tahun='$tahun'");

if($dataBudget = mysqli_fetch_assoc($qBudget)){
    $budget = $dataBudget['budget'];
}

$qPengeluaran = mysqli_query($koneksi,"SELECT SUM(jumlah) as total FROM transaksi
    WHERE user_id='$user_id'
    AND jenis='Pengeluaran'
    AND MONTH(tanggal)='$bulan'
    AND YEAR(tanggal)='$tahun'");

if($dataPengeluaran = mysqli_fetch_assoc($qPengeluaran)){
    $total_pengeluaran = $dataPengeluaran['total'] ?? 0;
}

$persen = 0;

if($budget > 0){
    $persen = ($total_pengeluaran / $budget) * 100;
}
?>

<?php
$status = "Aman";
$warna = "bg-success";

if($persen >= 80 && $persen < 100){
    $status = "Hampir Habis";
    $warna = "bg-warning";
}

if($persen >= 100){
    $status = "Over Budget";
    $warna = "bg-danger";
}
?>

<div class="container py-4">

        <div class="col-lg-8">

            <div class="card shadow-sm mb-4">
                <div class="card-body d-md-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-muted fw-bold text-uppercase">
                            Total Pengeluaran Bulan Ini
                        </small>
                        <h2 class="text-danger fw-bold">
                            Rp <?= number_format($total_pengeluaran) ?>

                            <?php if($budget > 0): ?>
                                <span class="text-dark fs-5">
                                    / Rp <?= number_format($budget) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted fs-6">
                                    / Belum set budget
                                </span>
                            <?php endif; ?>
                        </h2>

                    </div>

                    <div style="width:250px">
                        <small class="text-muted">Status Budget</small>

                        <div class="progress mt-1">
                            <div class="progress-bar <?= $warna ?>"
                                style="width: <?= min($persen,100) ?>%">
                            </div>
                        </div>

                        <small class="fw-bold text-muted float-end">
                            <?= $status ?>
                        </small>
                    </div>

                </div>
            </div>
</body>
</html>
