<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form
$tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : '';
$posisi = isset($_POST['posisi']) ? trim($_POST['posisi']) : '';
$perusahaan = isset($_POST['perusahaan']) ? trim($_POST['perusahaan']) : '';


// Validasi dasar (semua wajib diisi)
if (empty($tahun) || empty($posisi) || empty($perusahaan)) {
    header("Location: tambahriwayatpekerjaan.php?notif=tambahkosong");
    exit;
}

// Siapkan query INSERT
$sql_insert = "INSERT INTO `riwayat_pekerjaan` (`tahun`, `posisi`, `perusahaan`)
               VALUES (?, ?, ?)"; // Ganti nama tabel jika perlu
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert) {
    // Bind parameter (s=string)
    mysqli_stmt_bind_param($stmt_insert, 'sss', $tahun, $posisi, $perusahaan);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt_insert)) {
        mysqli_stmt_close($stmt_insert);
        header("Location: riwayatpekerjaan.php?notif=tambahberhasil");
        exit;
    } else {
        // Gagal eksekusi
        // error_log("Execute failed (insert pekerjaan): " . mysqli_stmt_error($stmt_insert));
        mysqli_stmt_close($stmt_insert);
        header("Location: tambahriwayatpekerjaan.php?notif=tambahgagal&msg=db");
        exit;
    }
} else {
    // Gagal prepare statement
    // error_log("Prepare failed (insert pekerjaan): " . mysqli_error($koneksi));
    header("Location: tambahriwayatpekerjaan.php?notif=tambahgagal&msg=prepare");
    exit;
}

// mysqli_close($koneksi); // Opsional
?>