<?php
include('../koneksi/koneksi.php');

$hardskill = isset($_POST['hardskill']) ? trim($_POST['hardskill']) : '';

if (empty($hardskill)) {
    header("Location: tambahhardskill.php?notif=tambahkosong");
    exit;
}

$sql_check = "SELECT `id_master_hard_skill` FROM `master_hard_skill` WHERE `hard_skill` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if ($stmt_check === false) {
    header("Location: tambahhardskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $hardskill);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: tambahhardskill.php?notif=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

$sql_insert = "INSERT INTO `master_hard_skill` (`hard_skill`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if ($stmt_insert === false) {
    header("Location: tambahhardskill.php?notif=tambahgagal");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $hardskill);

if (mysqli_stmt_execute($stmt_insert)) {
    mysqli_stmt_close($stmt_insert);
    header("Location: hardskill.php?notif=tambahberhasil");
    exit;
} else {
    $error_msg = mysqli_stmt_error($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    header("Location: tambahhardskill.php?notif=tambahgagal");
    exit;
}

?>