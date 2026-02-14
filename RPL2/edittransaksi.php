<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user'])) {
    header('location:login.php');
}

$id = $_GET['id'];
$user_id = $_SESSION['user']['id'];

$query = mysqli_query($koneksi, "
    SELECT * FROM transaksi 
    WHERE id_transaksi='$id' 
    AND user_id='$user_id'
");

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $jumlah = $_POST['jumlah'];
    $jenis = $_POST['jenis'];
    $kategori = $_POST['kategori'];
    $catatan = $_POST['catatan'];

    mysqli_query($koneksi, "
        UPDATE transaksi SET
        jumlah='$jumlah',
        jenis='$jenis',
        kategori='$kategori',
        catatan='$catatan'
        WHERE id_transaksi='$id'
        AND user_id='$user_id'
    ");

    header('location:tambahtransaksi.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="col-lg-6 mx-auto">

            <div class="card shadow">
                <div class="card-body">

                    <h4>Edit Transaksi</h4>

                    <form method="post">

                        <input type="number" name="jumlah"
                        class="form-control mb-3"
                        value="<?= $data['jumlah'] ?>">

                    <select name="jenis" class="form-select mb-3">

                    <option <?= $data['jenis']=="Pengeluaran"?'selected':'' ?>>
                        Pengeluaran
                    </option>

                    <option <?= $data['jenis']=="Pemasukan"?'selected':'' ?>>
                        Pemasukan
                    </option>

                    </select>

                    <select name="kategori" class="form-select mb-3">

                    <option <?= $data['kategori']=="🍔 Makanan"?'selected':'' ?>>🍔 Makanan</option>
                    <option <?= $data['kategori']=="🚗 Transportasi"?'selected':'' ?>>🚗 Transportasi</option>
                    <option <?= $data['kategori']=="🎮 Hiburan"?'selected':'' ?>>🎮 Hiburan</option>
                    <option <?= $data['kategori']=="📦 Lainnya"?'selected':'' ?>>📦 Lainnya</option>

                    </select>

                    <input type="text" name="catatan"
                    class="form-control mb-3"
                    value="<?= $data['catatan'] ?>">

                    <button name="update" class="btn btn-primary w-100">
                        Update Transaksi
                    </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
