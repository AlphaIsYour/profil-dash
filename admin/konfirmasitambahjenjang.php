<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form
$jenjang = isset($_POST['jenjang']) ? trim($_POST['jenjang']) : '';

// 1. Validasi: Cek apakah input kosong
if (empty($jenjang)) {
    header("Location: tambahjenjang.php?notif=tambahkosong");
    exit;
}

// 2. Validasi: Cek apakah nama jenjang sudah ada
$sql_check = "SELECT `id_master_jenjang` FROM `master_jenjang` WHERE `jenjang` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    // error_log("Prepare failed (check): " . mysqli_error($koneksi));
    header("Location: tambahjenjang.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $jenjang);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: tambahjenjang.php?notif=duplikat"); // Notif duplikat
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Insert data baru ke master_jenjang (Asumsi ID auto increment)
$sql_insert = "INSERT INTO `master_jenjang` (`jenjang`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if (!$stmt_insert) {
    // error_log("Prepare failed (insert): " . mysqli_error($koneksi));
    header("Location: tambahjenjang.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $jenjang);

// Eksekusi insert
if (mysqli_stmt_execute($stmt_insert)) {
    mysqli_stmt_close($stmt_insert);
    header("Location: jenjang.php?notif=tambahberhasil");
    exit;
} else {
    // $error_msg = mysqli_stmt_error($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    // error_log("Execute failed (insert jenjang): " . $error_msg);
    header("Location: tambahjenjang.php?notif=tambahgagal&msg=db");
    exit;
}

// mysqli_close($koneksi); // Opsional
?>