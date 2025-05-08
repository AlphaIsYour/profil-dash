<?php
include('../koneksi/koneksi.php');

$target_dir = "../images/";
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
$max_file_size = 2 * 1024 * 1024;

$id_konfigurasi_web = isset($_POST['id_konfigurasi_web']) ? filter_var($_POST['id_konfigurasi_web'], FILTER_VALIDATE_INT) : null;
$nama_web = isset($_POST['nama_web']) ? trim($_POST['nama_web']) : '';
$tahun = isset($_POST['tahun']) ? filter_var($_POST['tahun'], FILTER_VALIDATE_INT) : null;
$logo_lama = isset($_POST['logo_lama']) ? $_POST['logo_lama'] : '';

if (empty($nama_web) || empty($tahun)) {
    header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=editkosong");
    exit;
}

$nama_file_logo_baru = $logo_lama;
$upload_success = true;

if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0 && $_FILES['logo']['size'] > 0) {
    $file_tmp = $_FILES['logo']['tmp_name'];
    $file_name_original = basename($_FILES['logo']['name']);
    $file_size = $_FILES['logo']['size'];
    $file_ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_types)) {
        header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=tipegagal");
        exit;
    }

    if ($file_size > $max_file_size) {
         header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=sizefail");
         exit;
    }

    $nama_file_logo_baru = "logo_" . time() . "." . $file_ext;
    $target_file = $target_dir . $nama_file_logo_baru;

    if (move_uploaded_file($file_tmp, $target_file)) {
        if (!empty($logo_lama) && $logo_lama != $nama_file_logo_baru && file_exists($target_dir . $logo_lama)) {
             @unlink($target_dir . $logo_lama);
        }
         $upload_success = true;
    } else {
        $upload_success = false;
        $nama_file_logo_baru = $logo_lama;
         header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=uploadgagal");
         exit;
    }
}

$sql_update = "";
$params = [];
$types = "";

if ($id_konfigurasi_web) {
    $sql_update = "UPDATE `konfigurasi_web` SET `nama_web` = ?, `tahun` = ?, `logo` = ? WHERE `id_konfigurasi_web` = ?";
    $params = [&$nama_web, &$tahun, &$nama_file_logo_baru, &$id_konfigurasi_web];
    $types = "sssi";
} else {
    $sql_update = "INSERT INTO `konfigurasi_web` (`nama_web`, `tahun`, `logo`) VALUES (?, ?, ?)";
    $params = [&$nama_web, &$tahun, &$nama_file_logo_baru];
    $types = "sss";
}

$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    mysqli_stmt_bind_param($stmt_update, $types, ...$params);
    if (mysqli_stmt_execute($stmt_update)) {
        mysqli_stmt_close($stmt_update);
        header("Location: konfigurasiweb.php?notif=editberhasil");
        exit;
    } else {
        $error_msg = mysqli_stmt_error($stmt_update);
        mysqli_stmt_close($stmt_update);
        header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=editgagal&msg=db");
        exit;
    }
} else {
     header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=editgagal&msg=prepare");
     exit;
}

?>