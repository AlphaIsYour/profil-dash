<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// Ambil data master jenjang untuk dropdown
$sql_jenjang = "SELECT `id_master_jenjang`, `jenjang` FROM `master_jenjang` ORDER BY `jenjang`";
$query_jenjang = mysqli_query($koneksi, $sql_jenjang);

// Ambil data master universitas untuk dropdown
$sql_univ = "SELECT `id_master_universitas`, `nama_universitas` FROM `master_universitas` ORDER BY `nama_universitas`";
$query_univ = mysqli_query($koneksi, $sql_univ);

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Tambah Riwayat Pendidikan</title>
<!-- Tambahkan CSS untuk Select2 jika menggunakan -->
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
            <h3><i class="fas fa-plus"></i> Tambah Riwayat Pendidikan</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="riwayatpendidikan.php">Riwayat Pendidikan</a></li>
              <li class="breadcrumb-item active">Tambah</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Tambah Riwayat Pendidikan</h3>
                <div class="card-tools">
                <a href="riwayatpendidikan.php" class="btn btn-sm btn-warning float-right">
                <i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
                </div>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            </br>
             <div class="col-sm-10 offset-sm-1">
                <?php if (!empty($_GET['notif'])) { ?>
                    <?php if ($_GET['notif'] == "tambahkosong") { ?>
                        <div class="alert alert-danger" role="alert"> Maaf, semua field (Tahun, Jenjang, Jurusan, Universitas) wajib diisi.</div>
                    <?php } else if ($_GET['notif'] == "tambahgagal") { ?>
                         <div class="alert alert-danger" role="alert"> Maaf, gagal menambahkan data. Terjadi kesalahan server.</div>
                    <?php } ?>
                <?php } ?>
            </div>
            <form class="form-horizontal" method="post" action="konfirmasitambahriwayatpendidikan.php">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-3 col-form-label">Tahun <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="tahun" id="tahun" value="" placeholder="Contoh: 2019-2023" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jenjang" class="col-sm-3 col-form-label">Jenjang Pendidikan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <select class="form-control select2" id="jenjang" name="id_master_jenjang" required>
                            <option value="">- Pilih Jenjang -</option>
                            <?php while($data_j = mysqli_fetch_assoc($query_jenjang)) : ?>
                                <option value="<?php echo $data_j['id_master_jenjang']; ?>">
                                    <?php echo htmlspecialchars($data_j['jenjang']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jurusan" class="col-sm-3 col-form-label">Jurusan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="jurusan" id="jurusan" value="" required>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="universitas" class="col-sm-3 col-form-label">Universitas <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <select class="form-control select2" id="universitas" name="id_master_universitas" required>
                            <option value="">- Pilih Universitas -</option>
                             <?php while($data_u = mysqli_fetch_assoc($query_univ)) : ?>
                                <option value="<?php echo $data_u['id_master_universitas']; ?>">
                                    <?php echo htmlspecialchars($data_u['nama_universitas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        </div>
                    </div>
                </div> <!-- /.card-body -->
                <div class="card-footer">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-info float-right"><i class="fas fa-plus"></i> Tambah</button>
                    </div>
                </div> <!-- /.card-footer -->
            </form>
        </div> <!-- /.card -->
    </section> <!-- /.content -->
  </div> <!-- /.content-wrapper -->
  <?php include("includes/footer.php") ?>
</div> <!-- ./wrapper -->

<?php include("includes/script.php") ?>
<!-- Tambahkan JS untuk Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements with Bootstrap 4 theme
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>
</body>
</html>