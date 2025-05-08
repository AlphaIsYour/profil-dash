<?php
include('../koneksi/koneksi.php');

$id_riwayat_pekerjaan = isset($_POST['id_riwayat_pekerjaan']) ? filter_var($_POST['id_riwayat_pekerjaan'], FILTER_VALIDATE_INT) : null;
$tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : '';
$posisi = isset($_POST['posisi']) ? trim($_POST['posisi']) : '';
$perusahaan = isset($_POST['perusahaan']) ? trim($_POST['perusahaan']) : '';

$redirect_url = "editriwayatpekerjaan.php?data=". $id_riwayat_pekerjaan;

if (empty($id_riwayat_pekerjaan)) {
     header("Location: riwayatpekerjaan.php?notif=editgagal&msg=invalidid");
     exit;
}
if (empty($tahun) || empty($posisi) || empty($perusahaan)) {
    header("Location: {$redirect_url}¬if=editkosong");
    exit;
}

$sql_update = "UPDATE `riwayat_pekerjaan` SET
                    `tahun` = ?,
                    `posisi` = ?,
                    `perusahaan` = ?
               WHERE `id_riwayat_pekerjaan` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    mysqli_stmt_bind_param($stmt_update, 'sssi', $tahun, $posisi, $perusahaan, $id_riwayat_pekerjaan);

    if (mysqli_stmt_execute($stmt_update)) {
        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
             mysqli_stmt_close($stmt_update);
             header("Location: riwayatpekerjaan.php?notif=editberhasil");
             exit;
        } else {
             mysqli_stmt_close($stmt_update);
             header("Location: riwayatpekerjaan.php?notif=editgagal&msg=nochange");
             exit;
        }
    } else {
        mysqli_stmt_close($stmt_update);
        header("Location: {$redirect_url}¬if=editgagal&msg=db");
        exit;
    }
} else {
    header("Location: {$redirect_url}¬if=editgagal&msg=prepare");
    exit;
}

?>