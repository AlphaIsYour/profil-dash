<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Tambah Hard Skill</title>
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
            <h3><i class="fas fa-plus"></i> Tambah Hard Skill</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="hardskill.php">Hard Skill</a></li>
              <li class="breadcrumb-item active">Tambah Hard Skill</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Tambah Hard Skill</h3>
        <div class="card-tools">
          <a href="hardskill.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      </br>
      <div class="col-sm-10 offset-sm-1">>
          <?php if (!empty($_GET['notif'])) { ?>
              <?php if ($_GET['notif'] == "tambahkosong") { ?>
                  <div class="alert alert-danger" role="alert"> Maaf, data hard skill wajib diisi.</div>
              <?php } else if ($_GET['notif'] == "tambahgagal") { ?>
                  <div class="alert alert-danger" role="alert"> Maaf, gagal menambahkan data. Terjadi kesalahan server.</div>
              <?php } else if ($_GET['notif'] == "duplikat") { ?>
                  <div class="alert alert-warning" role="alert"> Maaf, nama hard skill tersebut sudah ada.</div>
              <?php } ?>
          <?php } ?>
      </div>

      <form class="form-horizontal" method="post" action="konfirmasitambahhardskill.php">
        <div class="card-body">
          <div class="form-group row">
            <label for="hardskill" class="col-sm-3 col-form-label">Hard Skill</label>
            <div class="col-sm-7">
              <!-- Pastikan name="hardskill" dan tambahkan required -->
              <input type="text" class="form-control" id="hardskill" name="hardskill" value="" required>
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