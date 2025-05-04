<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form POST
$id_master_topik = isset($_POST['id_master_topik']) && filter_var($_POST['id_master_topik'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_topik']
                        : null;
$topik_baru = isset($_POST['topik']) ? trim($_POST['topik']) : '';

// Buat URL redirect kembali ke form edit jika ada error
$redirect_url = "edittopik.php?data=" . urlencode($id_master_topik);

// 1. Validasi dasar
if (empty($id_master_topik)) {
    header("Location: topik.php?notif=editgagal&msg=invalidid");
    exit;
}
if (empty($topik_baru)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}


// 2. Validasi: Cek duplikasi nama baru (untuk ID yang BERBEDA)
$sql_check = "SELECT `id_master_topik` FROM `master_topik` WHERE `topik` = ? AND `id_master_topik` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    // error_log("Prepare failed (check edit): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 'si', $topik_baru, $id_master_topik);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: {$redirect_url}¬if=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Update data di database
$sql_update = "UPDATE `master_topik` SET `topik` = ? WHERE `id_master_topik` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if (!$stmt_update) {
    // error_log("Prepare failed (update): " . mysqli_error($koneksi));
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_update, 'si', $topik_baru, $id_master_topik);

// Eksekusi update
if (mysqli_stmt_execute($stmt_update)) {
    mysqli_stmt_close($stmt_update);
    header("Location: topik.php?notif=editberhasil");
    exit;
} else {
    // $error_msg = mysqli_stmt_error($stmt_update);
    mysqli_stmt_close($stmt_update);
    // error_log("Execute failed (update): " . $error_msg);
     // Cek jika error karena UNIQUE constraint
    // if(mysqli_errno($koneksi) == 1062) {
    //    header("Location: {$redirect_url}¬if=duplikat");
    // } else {
        header("Location: {$redirect_url}¬if=editgagal&msg=execute");
    // }
    exit;
}

// mysqli_close($koneksi); // Opsional
?>