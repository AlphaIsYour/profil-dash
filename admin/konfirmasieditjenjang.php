<?php
// Tidak perlu session_start()
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form POST
$id_master_jenjang = isset($_POST['id_master_jenjang']) && filter_var($_POST['id_master_jenjang'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_jenjang']
                        : null;
$jenjang = isset($_POST['jenjang']) ? trim($_POST['jenjang']) : '';

// Buat URL redirect kembali ke form edit
$redirect_url = "editjenjang.php?data=" . urlencode($id_master_jenjang);

// 1. Validasi dasar
if (empty($id_master_jenjang)) {
    header("Location: jenjang.php?notif=editgagal&msg=invalidid"); // ID tidak valid/hilang
    exit;
}
if (empty($jenjang)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}

// 2. Validasi: Cek duplikasi nama baru (untuk ID yang BERBEDA)
$sql_check = "SELECT `id_master_jenjang` FROM `master_jenjang`
              WHERE `jenjang` = ? AND `id_master_jenjang` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    // error_log("Prepare failed (check edit jenjang): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

// Tipe data: string nama, integer ID
mysqli_stmt_bind_param($stmt_check, 'si', $jenjang, $id_master_jenjang);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: {$redirect_url}¬if=duplikat"); // Notif duplikat
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Update data di database
$sql_update = "UPDATE `master_jenjang` SET `jenjang` = ?
               WHERE `id_master_jenjang` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if (!$stmt_update) {
    // error_log("Prepare failed (update jenjang): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

// Tipe data: string nama, integer ID
mysqli_stmt_bind_param($stmt_update, 'si', $jenjang, $id_master_jenjang);

// Eksekusi update
if (mysqli_stmt_execute($stmt_update)) {
    mysqli_stmt_close($stmt_update);
    header("Location: jenjang.php?notif=editberhasil");
    exit;
} else {
    // $error_msg = mysqli_stmt_error($stmt_update);
    mysqli_stmt_close($stmt_update);
    // error_log("Execute failed (update jenjang): " . $error_msg);
    header("Location: {$redirect_url}¬if=editgagal&msg=db");
    exit;
}

// mysqli_close($koneksi); // Opsional
?>