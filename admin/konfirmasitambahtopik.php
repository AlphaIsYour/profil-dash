<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form
$topik = isset($_POST['topik']) ? trim($_POST['topik']) : '';

// 1. Validasi: Cek apakah input kosong
if (empty($topik)) {
    header("Location: tambahtopik.php?notif=tambahkosong");
    exit;
}

// 2. Validasi: Cek apakah nama topik sudah ada
$sql_check = "SELECT `id_master_topik` FROM `master_topik` WHERE `topik` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    // error_log("Prepare failed (check): " . mysqli_error($koneksi));
    header("Location: tambahtopik.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $topik);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: tambahtopik.php?notif=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Insert data baru ke master_topik
$sql_insert = "INSERT INTO `master_topik` (`topik`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if (!$stmt_insert) {
    // error_log("Prepare failed (insert): " . mysqli_error($koneksi));
    header("Location: tambahtopik.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $topik);

// Eksekusi insert
if (mysqli_stmt_execute($stmt_insert)) {
    mysqli_stmt_close($stmt_insert);
    header("Location: topik.php?notif=tambahberhasil");
    exit;
} else {
    // $error_msg = mysqli_stmt_error($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    // error_log("Execute failed (insert): " . $error_msg);
    // Cek jika error karena UNIQUE constraint (jika Anda menambahkannya)
    // if(mysqli_errno($koneksi) == 1062) { // Error code for duplicate entry
    //    header("Location: tambahtopik.php?notif=duplikat");
    // } else {
        header("Location: tambahtopik.php?notif=tambahgagal&msg=execute");
    // }
    exit;
}

// mysqli_close($koneksi); // Opsional
?>