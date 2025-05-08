<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Tambah Riwayat Pekerjaan</title>
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
            <h3><i class="fas fa-plus"></i> Tambah Riwayat Pekerjaan</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="riwayatpekerjaan.php">Riwayat Pekerjaan</a></li>
              <li class="breadcrumb-item active">Tambah</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Tambah Riwayat Pekerjaan</h3>
                <div class="card-tools">
                <a href="riwayatpekerjaan.php" class="btn btn-sm btn-warning float-right">
                <i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
                </div>
            </div>
            <!-- /.card-header -->
             <!-- form start -->
            </br>
            <div class="col-sm-10 offset-sm-1">
                <?php if (!empty($_GET['notif'])) { ?>
                    <?php if ($_GET['notif'] == "tambahkosong") { ?>
                        <div class="alert alert-danger" role="alert"> Maaf, semua field (Tahun, Posisi, Perusahaan) wajib diisi.</div>
                    <?php } else if ($_GET['notif'] == "tambahgagal") { ?>
                         <div class="alert alert-danger" role="alert"> Maaf, gagal menambahkan data. Terjadi kesalahan server.</div>
                    <?php } ?>
                <?php } ?>
            </div>
            <form class="form-horizontal" method="post" action="konfirmasitambahriwayatpekerjaan.php">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-3 col-form-label">Tahun <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="tahun" id="tahun" value="" placeholder="Contoh: 2021-2023" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="posisi" class="col-sm-3 col-form-label">Posisi <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="posisi" id="posisi" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="perusahaan" class="col-sm-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="perusahaan" id="perusahaan" value="" required>
                        </div>
                    </div>
                </div> 
                <div class="card-footer">
                    <div class="col-sm-10"> 
                        <button type="submit" class="btn btn-info float-right"><i class="fas fa-plus"></i> Tambah</button>
                    </div>
                </div> 
            </form>
        </div>
    </section>
  </div>
  <?php include("includes/footer.php") ?>
</div> <!-- ./wrapper -->
<?php include("includes/script.php") ?>
</body>
</html>