<?php
include('../koneksi/koneksi.php');

$id_konfigurasi_web_url = isset($_GET['data']) ? filter_var($_GET['data'], FILTER_VALIDATE_INT) : null;

$sql_k = "SELECT `id_konfigurasi_web`, `logo`, `nama_web`, `tahun` FROM `konfigurasi_web`";
if ($id_konfigurasi_web_url) {
    $sql_k .= " WHERE `id_konfigurasi_web` = $id_konfigurasi_web_url";
}
$sql_k .= " LIMIT 1";

$query_k = mysqli_query($koneksi, $sql_k);
$data_k = mysqli_fetch_assoc($query_k);

$id_konfigurasi_web = $data_k['id_konfigurasi_web'] ?? null;
$logo_lama = $data_k['logo'] ?? '';
$nama_web_lama = $data_k['nama_web'] ?? '';
$tahun_lama = $data_k['tahun'] ?? date('Y');
$path_logo = "../image/";

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Edit Konfigurasi Website</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<?php include("includes/header.php") ?>
<?php include("includes/sidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3><i class="fas fa-edit"></i> Edit Konfigurasi Website</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="konfigurasiweb.php">Konfigurasi Website</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Konfigurasi</h3>
                <div class="card-tools">
                    <a href="konfigurasiweb.php" class="btn btn-sm btn-warning float-right">
                    <i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
                </div>
            </div>

            <form class="form-horizontal" method="post" action="konfirmasieditkonfigurasiweb.php" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="col-sm-10 offset-sm-1">
                        <?php if (!empty($_GET['notif'])) { ?>
                            <?php if ($_GET['notif'] == "editkosong") { ?>
                                <div class="alert alert-danger" role="alert"> Maaf, Nama Website dan Tahun wajib diisi.</div>
                            <?php } else if ($_GET['notif'] == "editgagal") { ?>
                                <div class="alert alert-danger" role="alert"> Maaf, gagal memperbarui data. Terjadi kesalahan server.</div>
                            <?php } else if ($_GET['notif'] == "tipegagal") { ?>
                                <div class="alert alert-danger" role="alert"> Maaf, tipe file logo tidak diizinkan (hanya .jpg, .jpeg, .png, .gif).</div>
                             <?php } else if ($_GET['notif'] == "sizefail") { ?>
                                <div class="alert alert-danger" role="alert"> Maaf, ukuran file logo terlalu besar (maks 2MB).</div>
                            <?php } else if ($_GET['notif'] == "uploadgagal") { ?>
                                <div class="alert alert-danger" role="alert"> Maaf, gagal mengupload file logo.</div>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <input type="hidden" name="id_konfigurasi_web" value="<?php echo $id_konfigurasi_web; ?>">
                    <input type="hidden" name="logo_lama" value="<?php echo htmlspecialchars($logo_lama); ?>">


                    <div class="form-group row">
                        <label for="nama_web" class="col-sm-3 col-form-label">Nama Website</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="nama_web" name="nama_web" value="<?php echo htmlspecialchars($nama_web_lama); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                        <div class="col-sm-7">
                        <input type="number" class="form-control" id="tahun" name="tahun" value="<?php echo htmlspecialchars($tahun_lama); ?>" required min="1900" max="<?php echo date('Y') + 5; // Batas tahun ?>">
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="logo" class="col-sm-3 col-form-label">Upload Logo Baru</label>
                        <div class="col-sm-7">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="logo" name="logo" accept=".jpg, .jpeg, .png, .gif">
                                <label class="custom-file-label" for="logo">Pilih file (jpg, png, gif maks 2MB)</label>
                            </div>
                             <small class="text-muted">Kosongkan jika tidak ingin mengganti logo.</small><br>
                             <?php if (!empty($logo_lama) && file_exists($path_logo . $logo_lama)) : ?>
                                <label class="mt-2">Logo Saat Ini:</label><br>
                                <img src="<?php echo $path_logo . htmlspecialchars($logo_lama); ?>" class="img-thumbnail" width="150px;">
                             <?php endif; ?>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-info float-right"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </div>
                </div>
            </form>
            <!-- Form End -->
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
<!-- Script untuk menampilkan nama file di input file bootstrap -->
<script>
  $(function () {
    bsCustomFileInput.init();
  });
</script>
</body>
</html>