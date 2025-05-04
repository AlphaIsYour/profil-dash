<?php
include('../koneksi/koneksi.php');

// Ambil data dari form, pastikan name="softskill" di form HTML
// Gunakan isset untuk menghindari error jika form tidak disubmit dengan benar
$softskill = isset($_POST['softskill']) ? trim($_POST['softskill']) : '';

// 1. Validasi: Cek apakah input kosong
if (empty($softskill)) {
    header("Location: tambahsoftskill.php?notif=tambahkosong");
    exit;
}

// 2. Validasi: Cek apakah nama softskill sudah ada di database
$sql_check = "SELECT `id_master_soft_skill` FROM `master_soft_skill` WHERE `soft_skill` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    // Gagal prepare statement, mungkin ada error SQL
    header("Location: tambahsoftskill.php?notif=tambahgagal"); // Notif gagal umum
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $softskill);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check); // Simpan hasil untuk cek jumlah baris

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    // Nama softskill sudah ada
    mysqli_stmt_close($stmt_check);
    header("Location: tambahsoftskill.php?notif=duplikat"); // Redirect dengan notif duplikat
    exit;
}
mysqli_stmt_close($stmt_check); // Tutup statement check

// 3. Insert data baru ke master_soft_skill (Asumsi id_master_soft_skill adalah AUTO_INCREMENT)
$sql_insert = "INSERT INTO `master_soft_skill` (`soft_skill`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert === false) {
    // Gagal prepare statement insert
    header("Location: tambahsoftskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $softskill);

// Eksekusi insert dan cek hasilnya
if (mysqli_stmt_execute($stmt_insert)) {
    // Jika berhasil
    mysqli_stmt_close($stmt_insert);
    header("Location: softskill.php?notif=tambahberhasil");
    exit;
} else {
    // Jika gagal eksekusi
    mysqli_stmt_close($stmt_insert);
    // Opsional: Log error mysqli_stmt_error($stmt_insert)
    header("Location: tambahsoftskill.php?notif=tambahgagal");
    exit;
}

// Tutup koneksi jika skrip berakhir di sini (biasanya ditutup otomatis)
// mysqli_close($koneksi);
?>