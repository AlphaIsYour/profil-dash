<?php
include('../koneksi/koneksi.php');
$softskill = isset($_POST['softskill']) ? trim($_POST['softskill']) : '';
if (empty($softskill)) {
    header("Location: tambahsoftskill.php?notif=tambahkosong");
    exit;
}

$sql_check = "SELECT `id_master_soft_skill` FROM `master_soft_skill` WHERE `soft_skill` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    header("Location: tambahsoftskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $softskill);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: tambahsoftskill.php?notif=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

$sql_insert = "INSERT INTO `master_soft_skill` (`soft_skill`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert === false) {
    header("Location: tambahsoftskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $softskill);

if (mysqli_stmt_execute($stmt_insert)) {
    mysqli_stmt_close($stmt_insert);
    header("Location: softskill.php?notif=tambahberhasil");
    exit;
} else {
    mysqli_stmt_close($stmt_insert);
    header("Location: tambahsoftskill.php?notif=tambahgagal");
    exit;
}
?>