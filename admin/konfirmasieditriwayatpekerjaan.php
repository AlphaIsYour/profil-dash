<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form
$id_riwayat_pekerjaan = isset($_POST['id_riwayat_pekerjaan']) ? filter_var($_POST['id_riwayat_pekerjaan'], FILTER_VALIDATE_INT) : null;
$tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : '';
$posisi = isset($_POST['posisi']) ? trim($_POST['posisi']) : '';
$perusahaan = isset($_POST['perusahaan']) ? trim($_POST['perusahaan']) : '';

// Buat URL redirect kembali (jika error)
$redirect_url = "editriwayatpekerjaan.php?data=". $id_riwayat_pekerjaan;

// Validasi dasar
if (empty($id_riwayat_pekerjaan)) {
     header("Location: riwayatpekerjaan.php?notif=editgagal&msg=invalidid");
     exit;
}
if (empty($tahun) || empty($posisi) || empty($perusahaan)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}

// PERIKSA NAMA TABEL DAN KOLOM DI QUERY UPDATE INI!
$sql_update = "UPDATE `riwayat_pekerjaan` SET
                    `tahun` = ?,
                    `posisi` = ?,
                    `perusahaan` = ?
               WHERE `id_riwayat_pekerjaan` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    // Bind parameter (s=string, i=integer)
    mysqli_stmt_bind_param($stmt_update, 'sssi', $tahun, $posisi, $perusahaan, $id_riwayat_pekerjaan);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt_update)) {
        // Cek apakah ada baris yang terupdate (opsional, tapi bagus)
        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
             mysqli_stmt_close($stmt_update);
             header("Location: riwayatpekerjaan.php?notif=editberhasil");
             exit;
        } else {
            // Tidak ada baris terupdate (mungkin data sama atau ID salah?)
             mysqli_stmt_close($stmt_update);
             header("Location: riwayatpekerjaan.php?notif=editgagal&msg=nochange"); // Beri notif beda
             exit;
        }
    } else {
        // Gagal eksekusi query
        // error_log("Execute failed (update pekerjaan): " . mysqli_stmt_error($stmt_update));
        mysqli_stmt_close($stmt_update);
        header("Location: {$redirect_url}¬if=editgagal&msg=db");
        exit;
    }
} else {
    // Gagal prepare statement (kemungkinan besar typo nama tabel/kolom)
    // error_log("Prepare failed (update pekerjaan): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

// mysqli_close($koneksi); // Opsional
?>