<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data dari form
$id_riwayat_pendidikan = isset($_POST['id_riwayat_pendidikan']) ? filter_var($_POST['id_riwayat_pendidikan'], FILTER_VALIDATE_INT) : null;
$tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : '';
$id_jenjang = isset($_POST['id_master_jenjang']) ? filter_var($_POST['id_master_jenjang'], FILTER_VALIDATE_INT) : null;
$jurusan = isset($_POST['jurusan']) ? trim($_POST['jurusan']) : '';
$id_universitas = isset($_POST['id_master_universitas']) ? filter_var($_POST['id_master_universitas'], FILTER_VALIDATE_INT) : null;

// Validasi dasar
if (empty($id_riwayat_pendidikan)) {
    // ID tidak ada atau tidak valid
     header("Location: riwayatpendidikan.php?notif=editgagal&msg=invalidid");
     exit;
}
if (empty($tahun) || empty($id_jenjang) || empty($jurusan) || empty($id_universitas)) {
    header("Location: editriwayatpendidikan.php?data=". $id_riwayat_pendidikan ."¬if=editkosong");
    exit;
}

// Siapkan query UPDATE
$sql_update = "UPDATE `riwayat_pendidikan` SET
                    `tahun` = ?,
                    `id_master_jenjang` = ?,
                    `jurusan` = ?,
                    `id_master_universitas` = ?
               WHERE `id_riwayat_pendidikan` = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    // Bind parameter (s=string, i=integer)
    mysqli_stmt_bind_param($stmt_update, 'sisii', $tahun, $id_jenjang, $jurusan, $id_universitas, $id_riwayat_pendidikan);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt_update)) {
        mysqli_stmt_close($stmt_update);
        header("Location: riwayatpendidikan.php?notif=editberhasil");
        exit;
    } else {
        // Gagal eksekusi
        // error_log("Execute failed (update riwayat): " . mysqli_stmt_error($stmt_update));
        mysqli_stmt_close($stmt_update);
        header("Location: editriwayatpendidikan.php?data=". $id_riwayat_pendidikan ."¬if=editgagal&msg=db");
        exit;
    }
} else {
    // Gagal prepare statement
    // error_log("Prepare failed (update riwayat): " . mysqli_error($koneksi));
    header("Location: editriwayatpendidikan.php?data=". $id_riwayat_pendidikan ."¬if=editgagal&msg=prepare");
    exit;
}

// mysqli_close($koneksi); // Opsional
?>