<?php
include('../koneksi/koneksi.php');

$id_master_jenjang = isset($_POST['id_master_jenjang']) && filter_var($_POST['id_master_jenjang'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_jenjang']
                        : null;
$jenjang = isset($_POST['jenjang']) ? trim($_POST['jenjang']) : '';

$redirect_url = "editjenjang.php?data=" . urlencode($id_master_jenjang);

if (empty($id_master_jenjang)) {
    header("Location: jenjang.php?notif=editgagal&msg=invalidid");
    exit;
}
if (empty($jenjang)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}

$sql_check = "SELECT `id_master_jenjang` FROM `master_jenjang`
              WHERE `jenjang` = ? AND `id_master_jenjang` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 'si', $jenjang, $id_master_jenjang);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: {$redirect_url}¬if=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

$sql_update = "UPDATE `master_jenjang` SET `jenjang` = ?
               WHERE `id_master_jenjang` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if (!$stmt_update) {
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_update, 'si', $jenjang, $id_master_jenjang);

if (mysqli_stmt_execute($stmt_update)) {
    mysqli_stmt_close($stmt_update);
    header("Location: jenjang.php?notif=editberhasil");
    exit;
} else {
    mysqli_stmt_close($stmt_update);
    header("Location: {$redirect_url}¬if=editgagal&msg=db");
    exit;
}

?>