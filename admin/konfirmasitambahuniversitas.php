<?php
include('../koneksi/koneksi.php');

$nama_universitas = isset($_POST['universitas']) ? trim($_POST['universitas']) : '';

// 1. Validasi: Cek apakah input kosong
if (empty($nama_universitas)) {
    header("Location: tambahuniversitas.php?notif=tambahkosong");
    exit;
}

// 2. Validasi: Cek apakah nama universitas sudah ada
$sql_check = "SELECT `id_master_universitas` FROM `master_universitas` WHERE `nama_universitas` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    header("Location: tambahuniversitas.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $nama_universitas);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: tambahuniversitas.php?notif=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Insert data baru ke master_universitas (Asumsi ID auto increment)
$sql_insert = "INSERT INTO `master_universitas` (`nama_universitas`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if (!$stmt_insert) {
    header("Location: tambahuniversitas.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $nama_universitas);

// Eksekusi insert
if (mysqli_stmt_execute($stmt_insert)) {
    mysqli_stmt_close($stmt_insert);
    header("Location: universitas.php?notif=tambahberhasil");
    exit;
} else {
    mysqli_stmt_close($stmt_insert);
    header("Location: tambahuniversitas.php?notif=tambahgagal&msg=db");
    exit;
}

?>