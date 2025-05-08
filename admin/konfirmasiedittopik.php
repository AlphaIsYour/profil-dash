<?php
include('../koneksi/koneksi.php');

$id_master_topik = isset($_POST['id_master_topik']) && filter_var($_POST['id_master_topik'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_topik']
                        : null;
$topik_baru = isset($_POST['topik']) ? trim($_POST['topik']) : '';

$redirect_url = "edittopik.php?data=" . urlencode($id_master_topik);

if (empty($id_master_topik)) {
    header("Location: topik.php?notif=editgagal&msg=invalidid");
    exit;
}
if (empty($topik_baru)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}

$sql_check = "SELECT `id_master_topik` FROM `master_topik` WHERE `topik` = ? AND `id_master_topik` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
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

$sql_update = "UPDATE `master_topik` SET `topik` = ? WHERE `id_master_topik` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if (!$stmt_update) {
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_update, 'si', $topik_baru, $id_master_topik);

if (mysqli_stmt_execute($stmt_update)) {
    mysqli_stmt_close($stmt_update);
    header("Location: topik.php?notif=editberhasil");
    exit;
} else {
    mysqli_stmt_close($stmt_update);
        header("Location: {$redirect_url}¬if=editgagal&msg=execute");
    exit;
}

?>