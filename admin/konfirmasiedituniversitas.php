<?php
// Tidak perlu session_start()
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form POST
$id_master_universitas = isset($_POST['id_master_universitas']) && filter_var($_POST['id_master_universitas'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_universitas']
                        : null;
$nama_universitas = isset($_POST['universitas']) ? trim($_POST['universitas']) : '';

// Buat URL redirect kembali ke form edit
$redirect_url = "edituniversitas.php?data=" . urlencode($id_master_universitas);

// 1. Validasi dasar
if (empty($id_master_universitas)) {
    header("Location: universitas.php?notif=editgagal&msg=invalidid"); // ID tidak valid/hilang
    exit;
}
if (empty($nama_universitas)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}


// 2. Validasi: Cek duplikasi nama baru (untuk ID yang BERBEDA)
$sql_check = "SELECT `id_master_universitas` FROM `master_universitas`
              WHERE `nama_universitas` = ? AND `id_master_universitas` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    // error_log("Prepare failed (check edit univ): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

// Tipe data: string nama, integer ID
mysqli_stmt_bind_param($stmt_check, 'si', $nama_universitas, $id_master_universitas);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: {$redirect_url}¬if=duplikat"); // Notif duplikat
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Update data di database
$sql_update = "UPDATE `master_universitas` SET `nama_universitas` = ?
               WHERE `id_master_universitas` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if (!$stmt_update) {
    // error_log("Prepare failed (update univ): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

// Tipe data: string nama, integer ID
mysqli_stmt_bind_param($stmt_update, 'si', $nama_universitas, $id_master_universitas);

// Eksekusi update
if (mysqli_stmt_execute($stmt_update)) {
    mysqli_stmt_close($stmt_update);
    header("Location: universitas.php?notif=editberhasil");
    exit;
} else {
    // $error_msg = mysqli_stmt_error($stmt_update);
    mysqli_stmt_close($stmt_update);
    // error_log("Execute failed (update univ): " . $error_msg);
    header("Location: {$redirect_url}¬if=editgagal&msg=db");
    exit;
}

// mysqli_close($koneksi); // Opsional
?>