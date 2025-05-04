<?php
include('../koneksi/koneksi.php');

$nama_jenjang = trim($_POST['jenjang']);

if(empty($nama_jenjang)){
    header("Location:tambahjenjang.php?notif=tambahkosong");
    exit;
} else {
    // Check if jenjang name already exists
    $sql_check = "SELECT `id_master_jenjang` FROM `master_jenjang` WHERE `jenjang` = ?";
    $stmt_check = mysqli_prepare($koneksi, $sql_check);
    mysqli_stmt_bind_param($stmt_check, 's', $nama_jenjang);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    
    if(mysqli_num_rows($result) > 0) {
        // Jenjang name already exists
        header("Location:tambahjenjang.php?notif=tambahgagal");
        exit;
    }
    
    try {
        // Start transaction to ensure data integrity
        mysqli_begin_transaction($koneksi);
        
        // PENTING: Dari skema database, kita harus melakukan hal berikut:
        // 1. Insert ke riwayat_pendidikan dengan nilai-nilai yang valid
        // 2. Dapatkan id baru dari auto_increment
        // 3. Insert ke master_jenjang dengan id yang sama

        // Pertama, buat ID baru untuk jenjang
        $sql_max = "SELECT MAX(id_riwayat_pendidikan) + 1 as next_id FROM riwayat_pendidikan";
        $result_max = mysqli_query($koneksi, $sql_max);
        $next_id = mysqli_fetch_assoc($result_max)['next_id'];
        
        if(!$next_id) $next_id = 1; // Jika tabel kosong
        
        // Ambil nilai id_master_universitas yang ada (misalnya yang pertama)
        $sql_univ = "SELECT `id_master_universitas` FROM `master_universitas` LIMIT 1";
        $result_univ = mysqli_query($koneksi, $sql_univ);
        $row_univ = mysqli_fetch_assoc($result_univ);
        $id_master_universitas = $row_univ['id_master_universitas']; // Default: 1
        
        // Insert ke riwayat_pendidikan dengan nilai-nilai minimum yang diperlukan
        $sql_rp = "INSERT INTO `riwayat_pendidikan` 
                   (`id_riwayat_pendidikan`, `id_master_jenjang`, `id_master_universitas`) 
                   VALUES (?, ?, ?)";
        $stmt_rp = mysqli_prepare($koneksi, $sql_rp);
        mysqli_stmt_bind_param($stmt_rp, 'iii', $next_id, $next_id, $id_master_universitas);
        mysqli_stmt_execute($stmt_rp);
        
        // Insert ke master_jenjang dengan ID yang sama
        $sql = "INSERT INTO `master_jenjang` 
                (`id_master_jenjang`, `jenjang`) 
                VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $next_id, $nama_jenjang);
        mysqli_stmt_execute($stmt);
        
        // Commit transaction
        mysqli_commit($koneksi);
        
        header("Location:jenjang.php?notif=tambahberhasil");
        exit;
    } catch (Exception $e) {
        // Roll back changes if something went wrong
        mysqli_rollback($koneksi);
        
        // Untuk debugging
        echo "Error: " . $e->getMessage();
        
        // Uncomment baris di bawah ini dan hapus echo di atas saat sudah selesai debugging
        // header("Location:tambahjenjang.php?notif=tambahgagal");
        exit;
    } finally {
        if(isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        if(isset($stmt_rp)) {
            mysqli_stmt_close($stmt_rp);
        }
        if(isset($stmt_check)) {
            mysqli_stmt_close($stmt_check);
        }
    }
}
?>