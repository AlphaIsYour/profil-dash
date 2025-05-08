<?php
include('../koneksi/koneksi.php');

$tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : '';
$posisi = isset($_POST['posisi']) ? trim($_POST['posisi']) : '';
$perusahaan = isset($_POST['perusahaan']) ? trim($_POST['perusahaan']) : '';

if (empty($tahun) || empty($posisi) || empty($perusahaan)) {
    header("Location: tambahriwayatpekerjaan.php?notif=tambahkosong");
    exit;
}

// Siapkan query INSERT
$sql_insert = "INSERT INTO `riwayat_pekerjaan` (`tahun`, `posisi`, `perusahaan`)
               VALUES (?, ?, ?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert) {
    mysqli_stmt_bind_param($stmt_insert, 'sss', $tahun, $posisi, $perusahaan);

    if (mysqli_stmt_execute($stmt_insert)) {
        mysqli_stmt_close($stmt_insert);
        header("Location: riwayatpekerjaan.php?notif=tambahberhasil");
        exit;
    } else {
        mysqli_stmt_close($stmt_insert);
        header("Location: tambahriwayatpekerjaan.php?notif=tambahgagal&msg=db");
        exit;
    }
} else {
    header("Location: tambahriwayatpekerjaan.php?notif=tambahgagal&msg=prepare");
    exit;
}
?>