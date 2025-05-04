<?php
session_start();
include('../koneksi/koneksi.php');

if(isset($_SESSION['id_master_universitas'])){
    $id_master_universitas = $_SESSION['id_master_universitas'];
    $nama_universitas = trim($_POST['universitas']);
    
    // Validate input
    if(empty($nama_universitas)){
        header("Location:edituniversitas.php?data=".$id_master_universitas."&notif=editkosong");
        exit;
    } else {
        // Check if university name already exists (except the current one)
        $sql_check = "SELECT `id_master_universitas` FROM `master_universitas` 
                     WHERE `nama_universitas` = ? AND `id_master_universitas` != ?";
        $stmt_check = mysqli_prepare($koneksi, $sql_check);
        mysqli_stmt_bind_param($stmt_check, 'ss', $nama_universitas, $id_master_universitas);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);
        
        if(mysqli_num_rows($result) > 0) {
            // University name already exists
            header("Location:edituniversitas.php?data=".$id_master_universitas."&notif=editgagal");
            exit;
        }
        
        // Update university name
        $sql = "UPDATE `master_universitas` SET `nama_universitas` = ? 
                WHERE `id_master_universitas` = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $nama_universitas, $id_master_universitas);
        
        if(mysqli_stmt_execute($stmt)) {
            // Success, clear session and redirect
            unset($_SESSION['id_master_universitas']);
            mysqli_stmt_close($stmt);
            header("Location:universitas.php?notif=editberhasil");
            exit;
        } else {
            // Database error
            mysqli_stmt_close($stmt);
            header("Location:edituniversitas.php?data=".$id_master_universitas."&notif=editgagal");
            exit;
        }
    }
} else {
    // No session data, redirect to university list
    header("Location:universitas.php");
    exit;
}
?>