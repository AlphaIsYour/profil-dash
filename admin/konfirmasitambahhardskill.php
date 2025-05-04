<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form, pastikan name="hardskill" di form HTML
$hardskill = isset($_POST['hardskill']) ? trim($_POST['hardskill']) : '';

// 1. Validasi: Cek apakah input kosong
if (empty($hardskill)) {
    header("Location: tambahhardskill.php?notif=tambahkosong");
    exit;
}

// 2. Validasi: Cek apakah nama hardskill sudah ada di database
$sql_check = "SELECT `id_master_hard_skill` FROM `master_hard_skill` WHERE `hard_skill` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    // Gagal prepare statement, mungkin ada error SQL
    // Log error: error_log("Prepare failed (check): " . mysqli_error($koneksi));
    header("Location: tambahhardskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $hardskill);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check); // Simpan hasil untuk cek jumlah baris

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    // Nama hardskill sudah ada
    mysqli_stmt_close($stmt_check);
    header("Location: tambahhardskill.php?notif=duplikat"); // Redirect dengan notif duplikat
    exit;
}
mysqli_stmt_close($stmt_check); // Tutup statement check

// 3. Insert data baru ke master_hard_skill (Asumsi id_master_hard_skill adalah AUTO_INCREMENT)
$sql_insert = "INSERT INTO `master_hard_skill` (`hard_skill`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert === false) {
    // Gagal prepare statement insert
    // Log error: error_log("Prepare failed (insert): " . mysqli_error($koneksi));
    header("Location: tambahhardskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $hardskill);

// Eksekusi insert dan cek hasilnya
if (mysqli_stmt_execute($stmt_insert)) {
    // Jika berhasil
    mysqli_stmt_close($stmt_insert);
    header("Location: hardskill.php?notif=tambahberhasil");
    exit;
} else {
    // Jika gagal eksekusi
    $error_msg = mysqli_stmt_error($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    // Log error: error_log("Execute failed (insert): " . $error_msg);
    header("Location: tambahhardskill.php?notif=tambahgagal");
    exit;
}

// Tutup koneksi jika skrip berakhir di sini (biasanya ditutup otomatis)
// mysqli_close($koneksi);
?>