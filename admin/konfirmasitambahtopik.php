<?php
include('../koneksi/koneksi.php');

$topik = isset($_POST['topik']) ? trim($_POST['topik']) : '';

if (empty($topik)) {
    header("Location: tambahtopik.php?notif=tambahkosong");
    exit;
}

$sql_check = "SELECT `id_master_topik` FROM `master_topik` WHERE `topik` = ?";
$stmt_check = mysqli_prepare($koneksi, $sql_check);

if (!$stmt_check) {
    header("Location: tambahtopik.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_check, 's', $topik);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    header("Location: tambahtopik.php?notif=duplikat");
    exit;
}
mysqli_stmt_close($stmt_check);

$sql_insert = "INSERT INTO `master_topik` (`topik`) VALUES (?)";
$stmt_insert = mysqli_prepare($koneksi, $sql_insert);

if (!$stmt_insert) {
    header("Location: tambahtopik.php?notif=tambahgagal&msg=prepare");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, 's', $topik);

if (mysqli_stmt_execute($stmt_insert)) {
    mysqli_stmt_close($stmt_insert);
    header("Location: topik.php?notif=tambahberhasil");
    exit;
} else {
    mysqli_stmt_close($stmt_insert);
        header("Location: tambahtopik.php?notif=tambahgagal&msg=execute");
    exit;
}
?>