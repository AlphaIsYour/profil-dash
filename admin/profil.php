<?php
session_start();
include('../koneksi/koneksi.php');
$id_user = $_SESSION['id_user'];
$sql = "select `nama`, `email`,`foto` from `user`
where `id_user`='$id_user'";
$query = mysqli_query($koneksi, $sql);
while($data = mysqli_fetch_row($query)){
$nama = $data[0];
$email = $data[1];
$foto = $data[2];
}
?>


<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?> 
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<?php include("includes/header.php") ?>

  <?php include("includes/sidebar.php") ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3><i class="fas fa-user-tie"></i> Profil</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Profil</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <div class="card-tools">
                  <a href="editprofil.php" class="btn btn-sm btn-info float-right"><i class="fas fa-edit"></i> Edit Profil</a>
                </div>
              </div>
              <div class="card-body">
              <div class="col-sm-12">
                <?php if (!empty($_GET['notif'])) { ?>
                    <?php if ($_GET['notif'] == "tambahberhasil") { ?>
                    <?php } else if ($_GET['notif'] == "editberhasil") { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Diubah <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <?php } ?>
                <?php } ?>
              </div>
                <table class="table table-bordered">
 <tbody>
 <tr>
 <td colspan="2"><i class="fas fa-user-circle"></i>
<strong>PROFIL<strong></td>
 </tr>
 <tr>
 <td width="20%"><strong>Foto<strong></td>
 <td width="80%"><img src="foto/<?php echo $foto;?>"
class="img-fluid" width="200px;"></td>
 </tr>
 <tr>
 <td width="20%"><strong>Nama<strong></td>
 <td width="80%"><?php echo $nama; ?></td>
 </tr>
 <tr>
 <td width="20%"><strong>Email<strong></td>
 <td width="80%"><?php echo $email;?></td>
 </tr>
 </tbody>
 </table> 
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">&nbsp;</div>
            </div>
            <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include("includes/footer.php") ?>

</div>
<!-- ./wrapper -->

<?php include("includes/script.php") ?>
</body>
</html>
