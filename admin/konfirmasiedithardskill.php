<?php
include('../koneksi/koneksi.php');

$id_master_hard_skill = isset($_POST['id_master_hard_skill']) && filter_var($_POST['id_master_hard_skill'], FILTER_VALIDATE_INT)
                        ? (int)$_POST['id_master_hard_skill']
                        : null;
$hardskill_baru = isset($_POST['hardskill']) ? trim($_POST['hardskill']) : '';

$redirect_url = "edithardskill.php?data=" . urlencode($id_master_hard_skill);

if (empty($id_master_hard_skill)) {
    header("Location: hardskill.php?notif=editgagal&msg=invalidid");
    exit;
}
if (empty($hardskill_baru)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}

$sql_check = "SELECT `id_master_hard_skill` FROM `master_hard_skill` WHERE `hard_skill` = ? AND `id_master_hard_skill` != ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    header("Location: {$redirect_url}¬if=editgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 'si', $hardskill_baru, $id_master_hard_skill); 
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: {$redirect_url}¬if=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

$sql_update = "UPDATE `master_hard_skill` SET `hard_skill` = ? WHERE `id_master_hard_skill` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update === false) {
    header("Location: {$redirect_url}¬if=editgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_update, 'si', $hardskill_baru, $id_master_hard_skill);

if (mysqli_stmt_execute($stmt_update)) {
        mysqli_stmt_close($stmt_update);
        header("Location: hardskill.php?notif=editberhasil");
        exit;
} else {
    $error_msg = mysqli_stmt_error($stmt_update);
    mysqli_stmt_close($stmt_update);
    header("Location: {$redirect_url}¬if=editgagal");
    exit;
}
?>