<?php
session_start();
include('../koneksi/koneksi.php');
if(isset($_GET['data'])){
    $id_master_jenjang = mysqli_real_escape_string($koneksi, $_GET['data']);
    $_SESSION['id_master_jenjang'] = $id_master_jenjang;
    
    // Get jenjang data using prepared statement
    $sql_d = "SELECT `jenjang` FROM `master_jenjang` WHERE `id_master_jenjang` = ?";
    $stmt = mysqli_prepare($koneksi, $sql_d);
    mysqli_stmt_bind_param($stmt, 's', $id_master_jenjang);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $data_d = mysqli_fetch_assoc($result);
        $jenjang = $data_d['jenjang'];
    } else {
        // Redirect if jenjang doesn't exist
        header("Location: jenjang.php");
        exit;
    }
    mysqli_stmt_close($stmt);
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3><i class="fas fa-edit"></i> Edit Jenjang</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="jenjang.php">jenjang</a></li>
              <li class="breadcrumb-item active">Edit Jenjang</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Jenjang</h3>
        <div class="card-tools">
          <a href="jenjang.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      </br>
      <?php if(!empty($_GET['notif'])){?>
        <?php if($_GET['notif']=="editkosong"){?>
          <div class="alert alert-danger" role="alert">
            Maaf data jenjang wajib di isi
          </div>
        <?php } else if($_GET['notif']=="editgagal"){?>
          <div class="alert alert-danger" role="alert">
            Maaf nama jenjang sudah ada
          </div>
        <?php }?>
      <?php }?>
      <form class="form-horizontal" method="post" action="konfirmasieditjenjang.php">
        <div class="card-body">
          <div class="form-group row">
            <label for="jenjang" class="col-sm-3 col-form-label">Jenjang</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="jenjang" name="jenjang" value="<?php echo htmlspecialchars($jenjang);?>">
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-info float-right"><i class="fas fa-save"></i> Simpan</button>
          </div>  
        </div>
        <!-- /.card-footer -->
      </form>
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
