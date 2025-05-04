<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// --- Konfigurasi Upload ---
$target_dir = "../images/"; // Direktori tempat menyimpan logo (HARUS ADA dan WRITABLE!)
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
$max_file_size = 2 * 1024 * 1024; // 2 MB

// Ambil data dari form POST
$id_konfigurasi_web = isset($_POST['id_konfigurasi_web']) ? filter_var($_POST['id_konfigurasi_web'], FILTER_VALIDATE_INT) : null;
$nama_web = isset($_POST['nama_web']) ? trim($_POST['nama_web']) : '';
$tahun = isset($_POST['tahun']) ? filter_var($_POST['tahun'], FILTER_VALIDATE_INT) : null;
$logo_lama = isset($_POST['logo_lama']) ? $_POST['logo_lama'] : '';

// Validasi input dasar
if (empty($nama_web) || empty($tahun)) {
    header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=editkosong");
    exit;
}

$nama_file_logo_baru = $logo_lama; // Default pakai logo lama
$upload_success = true; // Tandai apakah ada upload baru yg sukses/gagal

// --- Proses Upload Logo Baru (Jika Ada) ---
if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0 && $_FILES['logo']['size'] > 0) {
    $file_tmp = $_FILES['logo']['tmp_name'];
    $file_name_original = basename($_FILES['logo']['name']);
    $file_size = $_FILES['logo']['size'];
    $file_ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));

    // 1. Validasi Tipe File
    if (!in_array($file_ext, $allowed_types)) {
        header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=tipegagal");
        exit;
    }

    // 2. Validasi Ukuran File
    if ($file_size > $max_file_size) {
         header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=sizefail");
         exit;
    }

    // 3. Buat Nama File Unik (contoh: logo_timestamp.ext)
    $nama_file_logo_baru = "logo_" . time() . "." . $file_ext;
    $target_file = $target_dir . $nama_file_logo_baru;

    // 4. Pindahkan File
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Upload baru sukses, hapus logo lama jika ada dan berbeda
        if (!empty($logo_lama) && $logo_lama != $nama_file_logo_baru && file_exists($target_dir . $logo_lama)) {
             @unlink($target_dir . $logo_lama); // Hapus file lama, @ untuk menekan error jika gagal hapus
        }
         $upload_success = true; // Sebenarnya tidak perlu karena sudah dipindah
    } else {
        // Gagal upload
        $upload_success = false; // Tandai gagal upload
        $nama_file_logo_baru = $logo_lama; // Kembalikan ke logo lama jika upload gagal
         header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=uploadgagal");
         exit;
    }
} // End if ada file upload baru

// --- Update Data ke Database ---
// Gunakan nama file logo yang sesuai ($nama_file_logo_baru, bisa jadi nama baru atau nama lama)

$sql_update = "";
$params = [];
$types = "";

// Cek apakah data konfigurasi sudah ada atau belum (berdasarkan ID)
if ($id_konfigurasi_web) {
    // Jika ID ada, lakukan UPDATE
    $sql_update = "UPDATE `konfigurasi_web` SET `nama_web` = ?, `tahun` = ?, `logo` = ? WHERE `id_konfigurasi_web` = ?";
    $params = [&$nama_web, &$tahun, &$nama_file_logo_baru, &$id_konfigurasi_web];
    $types = "sssi"; // string, string, string, integer
} else {
    // Jika ID tidak ada (kasus setup awal), lakukan INSERT
    // Anda mungkin perlu membuat ID manual jika tidak AUTO_INCREMENT
    // $id_konfigurasi_web = 1; // Contoh jika ID manual
    $sql_update = "INSERT INTO `konfigurasi_web` (`nama_web`, `tahun`, `logo`) VALUES (?, ?, ?)";
    $params = [&$nama_web, &$tahun, &$nama_file_logo_baru];
    $types = "sss"; // string, string, string
}

$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    mysqli_stmt_bind_param($stmt_update, $types, ...$params);
    if (mysqli_stmt_execute($stmt_update)) {
        mysqli_stmt_close($stmt_update);
        header("Location: konfigurasiweb.php?notif=editberhasil");
        exit;
    } else {
        // Gagal eksekusi
        $error_msg = mysqli_stmt_error($stmt_update);
        mysqli_stmt_close($stmt_update);
        // error_log("Execute failed (update/insert config): " . $error_msg);
        header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=editgagal&msg=db");
        exit;
    }
} else {
     // Gagal prepare statement
     // error_log("Prepare failed (update/insert config): " . mysqli_error($koneksi));
     header("Location: editkonfigurasiweb.php?data=" . $id_konfigurasi_web . "¬if=editgagal&msg=prepare");
     exit;
}

// mysqli_close($koneksi); // Opsional
?>