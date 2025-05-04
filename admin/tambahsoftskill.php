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
            <h3><i class="fas fa-plus"></i> Tambah Soft Skill</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="softskill.php">Soft Skill</a></li>
              <li class="breadcrumb-item active">Tambah Soft Skill</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Tambah Soft Skill</h3>
        <div class="card-tools">
          <a href="softskill.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      </br>
      <?php if(!empty($_GET['notif'])){?>
        <?php if($_GET['notif']=="tambahkosong"){?>
          <div class="alert alert-danger" role="alert">
            Maaf data soft skill wajib diisi.
          </div>
        <?php } else if($_GET['notif']=="tambahgagal"){?>
          <div class="alert alert-danger" role="alert">
            Maaf, gagal menambahkan data. Mungkin nama soft skill sudah ada atau terjadi kesalahan server.
          </div>
        <?php } else if ($_GET['notif'] == "duplikat") { ?> <!-- Tambah notifikasi duplikat -->
          <div class="alert alert-danger" role="alert">
            Maaf, nama soft skill tersebut sudah ada.
          </div>
        <?php } ?>
      <?php }?>


      <form class="form-horizontal" method="post" action="konfirmasitambahsoftskill.php">
        <div class="card-body">
          <div class="form-group row">
            <label for="softskill" class="col-sm-3 col-form-label">Soft Skill</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="softskill" name="softskill" value="">
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-info float-right"><i class="fas fa-plus"></i> Tambah</button>
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
