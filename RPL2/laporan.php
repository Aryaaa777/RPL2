<?php
session_start();

include('db_connect.php');
if (!isset($_SESSION['user'])) {
    header('location:login.php');
}

$user_id = $_SESSION['user']['id'];
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE user_id='$user_id' AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun' ORDER BY tanggal DESC");

$data = mysqli_fetch_all($query, MYSQLI_ASSOC);

$total_pemasukan = 0;
$total_pengeluaran = 0;

foreach ($data as $trx) {

    if ($trx['jenis'] == "Pemasukan") {
        $total_pemasukan += $trx['jumlah'];
    } else {
        $total_pengeluaran += $trx['jumlah'];
    }
}

$saldo = $total_pemasukan - $total_pengeluaran;

$query = mysqli_query($koneksi, "SELECT kategori, SUM(jumlah) AS total 
    FROM transaksi 
    WHERE user_id='$user_id' AND jenis='Pengeluaran'
    GROUP BY kategori
");

$kategori = [];
$total = [];

while($row = mysqli_fetch_assoc($query)){
    $kategori[] = $row['kategori'];
    $total[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <title>Laporan</title>
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
            <h3 class="mb-4 fw-bold">📊 Laporan Keuangan</h3>

            <form method="GET" class="row g-2 mb-4">

            <div class="col-md-3">
                <select name="bulan" class="form-select">
                    <?php for($i=1;$i<=12;$i++): ?>
                <option value="<?= $i ?>" <?= $bulan==$i?'selected':'' ?>>
                    <?= date('F', mktime(0,0,0,$i,1)) ?>
                </option>
                    <?php endfor ?>
                </select>
            </div>

            <div class="col-md-3">
                <input type="number" name="tahun" class="form-control"value="<?= $tahun ?>">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

            </form>


        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card shadow-sm border-success">
                    <div class="card-body text-success">
                        <h6>Total Pemasukan</h6>
                            <h4>Rp <?= number_format($total_pemasukan,0,',','.') ?></h4>
                    </div>
                </div>
            </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-danger">
                    <h6>Total Pengeluaran</h6>
                    <h4>Rp <?= number_format($total_pengeluaran,0,',','.') ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-primary">
                    <h6>Sisa Saldo</h6>
                    <h4>Rp <?= number_format($saldo,0,',','.') ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">
                Detail Transaksi
            </div>

        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
        </thead>

        <tbody>

            <?php if(count($data)>0): ?>

            <?php $no=1; foreach($data as $trx): ?>

        <tr>
            <td><?= $no++ ?></td>

            <td><?= date('d M Y', strtotime($trx['tanggal'])) ?></td>

            <td>Rp <?= number_format($trx['jumlah'],0,',','.') ?></td>

            <td>
            <?php if($trx['jenis']=="Pemasukan"): ?>
            <span class="badge bg-success">Pemasukan</span>
            <?php else: ?>
            <span class="badge bg-danger">Pengeluaran</span>
            <?php endif ?>
            </td>

            <td><?= $trx['kategori'] ?></td>
            <td><?= $trx['catatan'] ?: '-' ?></td>
            <td>
                <a href="edittransaksi.php?id=<?= $trx['id_transaksi'] ?>" 
                    class="btn btn-sm btn-warning">
                    Edit
                </a>

                <a href="hapustransaksi.php?id=<?= $trx['id_transaksi'] ?>" 
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Yakin hapus transaksi?')">
                    Hapus
                </a>
            </td>

        </tr>

            <?php endforeach ?>

            <?php else: ?>

        <tr>
            <td colspan="6" class="text-center text-muted">
            Tidak ada data
            </td>
        </tr>

            <?php endif ?>

        </tbody>
        </table>
        </div>
        
    <div class="container py-4">

        <h3 class="mb-3">📊 Laporan Pengeluaran Kategori</h3>

        <div class="card p-4" id="chartArea">

        <canvas id="myChart"></canvas>

        </div>

        <button onclick="downloadPDF()" class="btn btn-danger mt-3">
            Export PDF
        </button>

    </div>

<script>

const kategori = <?php echo json_encode($kategori); ?>;
const total = <?php echo json_encode($total); ?>;

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: kategori,
        datasets: [{
            data: total
        }]
    }
});


function downloadPDF(){

    html2canvas(document.querySelector("#chartArea")).then(canvas => {

        const imgData = canvas.toDataURL("image/png");

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        pdf.addImage(imgData,'PNG',10,10,190,190);
        pdf.save("laporan_pengeluaran.pdf");

    });
}

</script>
</body>
</html>
