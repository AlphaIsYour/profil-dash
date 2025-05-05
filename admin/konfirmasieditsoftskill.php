<?php
include('../koneksi/koneksi.php');

$id_master_soft_skill = isset($_POST['id_master_soft_skill']) ? mysqli_real_escape_string($koneksi, $_POST['id_master_soft_skill']) : null;
$softskill_baru = isset($_POST['softskill']) ? trim($_POST['softskill']) : '';

// 1. Validasi dasar
if (empty($id_master_soft_skill) || empty($softskill_baru)) {
    header("Location: editsoftskill.php?data=" . urlencode($id_master_soft_skill) . "¬if=editkosong");
    exit;
}

// 2. Validasi: Cek duplikasi nama baru (untuk ID yang BERBEDA)
$sql_check = "SELECT `id_master_soft_skill` FROM `master_soft_skill` WHERE `soft_skill` = ? AND `id_master_soft_skill` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    header("Location: editsoftskill.php?data=" . urlencode($id_master_soft_skill) . "¬if=editgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 'si', $softskill_baru, $id_master_soft_skill);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: editsoftskill.php?data=" . urlencode($id_master_soft_skill) . "¬if=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

// 3. Update data di database
$sql_update = "UPDATE `master_soft_skill` SET `soft_skill` = ? WHERE `id_master_soft_skill` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update === false) {
    header("Location: editsoftskill.php?data=" . urlencode($id_master_soft_skill) . "¬if=editgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_update, 'si', $softskill_baru, $id_master_soft_skill);

if (mysqli_stmt_execute($stmt_update)) {
    mysqli_stmt_close($stmt_update);
    header("Location: softskill.php?notif=editberhasil");
    exit;
} else {
    mysqli_stmt_close($stmt_update);
    header("Location: editsoftskill.php?data=" . urlencode($id_master_soft_skill) . "¬if=editgagal");
    exit;
}
?>