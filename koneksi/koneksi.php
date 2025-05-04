<?php
$koneksi = mysqli_connect("localhost","root","","profil", 3307);
// cek koneksi
if (!$koneksi){
 die("Error koneksi: " . mysqli_connect_errno());
}
?>