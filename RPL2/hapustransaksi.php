<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user'])) {
    header('location:login.php');
}

$id = $_GET['id'];
$user_id = $_SESSION['user']['id'];

mysqli_query($koneksi, "
    DELETE FROM transaksi 
    WHERE id_transaksi='$id' 
    AND user_id='$user_id'
");

header('location:tambahtransaksi.php');
?>
