<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form POST
// Validasi tipe data ID
$id_master_hard_skill = isset($_POST['id_master_hard_skill']) && filter_var($_POST['id_master_hard_skill'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_hard_skill']
                        : null;
$hardskill_baru = isset($_POST['hardskill']) ? trim($_POST['hardskill']) : '';

// Buat URL redirect kembali ke form edit jika ada error
$redirect_url = "edithardskill.php?data=" . urlencode($id_master_hard_skill); // ID mungkin null di awal

// 1. Validasi dasar
if (empty($id_master_hard_skill)) {
    // Jika ID tidak valid atau hilang, kembali ke list utama
    header("Location: hardskill.php?notif=editgagal&msg=invalidid");
    exit;
}
if (empty($hardskill_baru)) {
    // Jika nama baru kosong
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}


// 2. Validasi: Cek duplikasi nama baru (untuk ID yang BERBEDA)
$sql_check = "SELECT `id_master_hard_skill` FROM `master_hard_skill` WHERE `hard_skill` = ? AND `id_master_hard_skill` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    // Log error: error_log("Prepare failed (check edit): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 'si', $hardskill_baru, $id_master_hard_skill); // 's' for hardskill baru, 'i' for ID
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    // Nama baru sudah digunakan oleh data lain
    mysqli_stmt_close($stmt_check);
    header("Location: {$redirect_url}¬if=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Update data di database
$sql_update = "UPDATE `master_hard_skill` SET `hard_skill` = ? WHERE `id_master_hard_skill` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update === false) {
     // Log error: error_log("Prepare failed (update): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_update, 'si', $hardskill_baru, $id_master_hard_skill);

// Eksekusi update dan cek hasil
if (mysqli_stmt_execute($stmt_update)) {
    // Cek apakah ada baris yang benar-benar terupdate
    // if (mysqli_stmt_affected_rows($stmt_update) > 0) {
        mysqli_stmt_close($stmt_update);
        header("Location: hardskill.php?notif=editberhasil");
        exit;
    // } else {
        // Tidak ada baris terupdate (mungkin data sama atau ID tidak ada)
        // mysqli_stmt_close($stmt_update);
        // header("Location: {$redirect_url}¬if=nodataupdated"); // Opsional: notif jika tidak ada perubahan
        // exit;
    // }
} else {
    // Jika gagal eksekusi
    $error_msg = mysqli_stmt_error($stmt_update);
    mysqli_stmt_close($stmt_update);
    // Log error: error_log("Execute failed (update): " . $error_msg);
    header("Location: {$redirect_url}¬if=editgagal");
    exit;
}

// Tutup koneksi (opsional)
// mysqli_close($koneksi);
?>