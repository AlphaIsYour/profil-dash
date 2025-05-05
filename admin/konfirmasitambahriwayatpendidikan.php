<?php
include('../koneksi/koneksi.php');

$tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : '';
$id_jenjang = isset($_POST['id_master_jenjang']) ? filter_var($_POST['id_master_jenjang'], FILTER_VALIDATE_INT) : null;
$jurusan = isset($_POST['jurusan']) ? trim($_POST['jurusan']) : '';
$id_universitas = isset($_POST['id_master_universitas']) ? filter_var($_POST['id_master_universitas'], FILTER_VALIDATE_INT) : null;

if (empty($tahun) || empty($id_jenjang) || empty($jurusan) || empty($id_universitas)) {
    header("Location: tambahriwayatpendidikan.php?notif=tambahkosong");
    exit;
}

$sql_insert = "INSERT INTO `riwayat_pendidikan` (`tahun`, `id_master_jenjang`, `jurusan`, `id_master_universitas`)
               VALUES (?, ?, ?, ?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert) {
    mysqli_stmt_bind_param($stmt_insert, 'sisi', $tahun, $id_jenjang, $jurusan, $id_universitas);

    if (mysqli_stmt_execute($stmt_insert)) {
        mysqli_stmt_close($stmt_insert);
        header("Location: riwayatpendidikan.php?notif=tambahberhasil");
        exit;
    } else {
        mysqli_stmt_close($stmt_insert);
        header("Location: tambahriwayatpendidikan.php?notif=tambahgagal&msg=db");
        exit;
    }
} else {
    header("Location: tambahriwayatpendidikan.php?notif=tambahgagal&msg=prepare");
    exit;
}

?>