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
    <title>Tambah Transaksi</title>
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

        <div class="col-lg-8">
                <div class="card shadow-sm border-top border-primary">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📝 Catat Transaksi</h5>

                    <form method="post">
                    <input name="jumlah" type="number" id="trx-amount"
                        class="form-control mb-3"
                        placeholder="Nominal Rp">

                    <select name="jenis" id="trx-type" class="form-select mb-3">
                        <option>Pengeluaran</option>
                        <option>Pemasukan</option>
                    </select>

                    <select name="kategori" id="trx-category" class="form-select mb-3">
                        <option>🍔 Makanan</option>
                        <option>🚗 Transportasi</option>
                        <option>🎮 Hiburan</option>
                        <option>📦 Lainnya</option>
                    </select>

                    <input name="catatan" type="text" id="trx-note"
                        class="form-control mb-3"
                        placeholder="Catatan singkat">

                    <button name="simpan_transaksi" id="btn-save-trx"
                        class="btn btn-dark w-100 fw-bold">
                        Simpan Data
                    </button>
                </form>
                </div>
            </div>

            <?php
                include('db_connect.php');
                if(isset($_POST['simpan_transaksi'])){

                    $user_id = $_SESSION['user']['id'];
                    $jumlah = $_POST['jumlah'];
                    $jenis = $_POST['jenis'];
                    $kategori = $_POST['kategori'];
                    $catatan = $_POST['catatan'];

                    $tanggal = date('Y-m-d');

                    $query = mysqli_query($koneksi, "INSERT INTO transaksi(user_id, jumlah, jenis, kategori, catatan, tanggal) VALUES 
                    ('$user_id','$jumlah','$jenis','$kategori','$catatan','$tanggal')");

                    echo "<script>alert('Transaksi berhasil disimpan');</script>";
                }
            ?>

            <br>
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light fw-bold">
                    Riwayat Transaksi Terkini
                </div>

                <div id="history-list" class="list-group list-group-flush" style="min-height:300px">

                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nominal</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Catatan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                        include('db_connect.php');

                        $user_id = $_SESSION['user']['id'];

                        $query = mysqli_query($koneksi, "
                            SELECT * FROM transaksi 
                            WHERE user_id='$user_id'
                            ORDER BY tanggal DESC
                        ");

                        if(mysqli_num_rows($query) > 0):

                        $no = 1;
                        while ($transaksi = mysqli_fetch_assoc($query)):
                    ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                Rp <?= number_format($transaksi['jumlah'], 0, ',', '.') ?>
                            </td>

                            <td>
                                <?php if($transaksi['jenis'] == "Pemasukan"): ?>
                                    <span class="badge bg-success">Pemasukan</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Pengeluaran</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $transaksi['kategori'] ?></td>

                            <td><?= $transaksi['catatan'] ?: '-' ?></td>

                            <td>
                                <?= date('d M Y', strtotime($transaksi['tanggal'])) ?>
                            </td>
                            <td>
                                <a href="edittransaksi.php?id=<?= $transaksi['id_transaksi'] ?>" 
                                class="btn btn-sm btn-warning">
                                Edit
                                </a>

                                <a href="hapustransaksi.php?id=<?= $transaksi['id_transaksi'] ?>" 
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin hapus transaksi?')">
                                Hapus
                                </a>
                            </td>
                        </tr>

                    <?php 
                        endwhile;
                        else:
                    ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada transaksi
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>

                </div>

            </div>
        </div>
</div>


</body>
</html>
