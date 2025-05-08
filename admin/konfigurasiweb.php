<?php
include('../koneksi/koneksi.php'); 

$sql_k = "SELECT `id_konfigurasi_web`, `logo`, `nama_web`, `tahun` FROM `konfigurasi_web` LIMIT 1";
$query_k = mysqli_query($koneksi, $sql_k);
$data_k = mysqli_fetch_assoc($query_k);

$id_konfigurasi_web = $data_k['id_konfigurasi_web'] ?? null;
$logo = $data_k['logo'] ?? 'default_logo.png';
$nama_web = $data_k['nama_web'] ?? 'Nama Website Belum Diatur';
$tahun = $data_k['tahun'] ?? date('Y');
$path_logo = "../images/";

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Konfigurasi Website</title>
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
            <h3><i class="fas fa-cog"></i> Konfigurasi Website</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Konfigurasi Website</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="fas fa-list-alt"></i> Detail Konfigurasi</h3>
                <div class="card-tools">
                  <a href="editkonfigurasiweb.php<?php echo $id_konfigurasi_web ? '?data=' . $id_konfigurasi_web : ''; ?>" class="btn btn-sm btn-info float-right">
                      <i class="fas fa-edit"></i> Edit Konfigurasi
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="col-sm-12">
                   <?php if (!empty($_GET['notif']) && $_GET['notif'] == "editberhasil") { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      Data Konfigurasi Berhasil Diperbarui!
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                   <?php } ?>
                </div>
                <?php if ($data_k) : ?>
                <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td colspan="2"><i class="fas fa-info-circle"></i> <strong>KONFIGURASI WEBSITE AKTIF</strong></td>
                      </tr>
                      <tr>
                        <td width="20%"><strong>Logo</strong></td>
                        <td width="80%">
                          <?php if (!empty($logo) && file_exists($path_logo . $logo)) : ?>
                            <img src="<?php echo $path_logo . htmlspecialchars($logo); ?>" class="img-fluid" width="200px;">
                          <?php else : ?>
                            <span class="text-muted">Logo belum diatur atau file tidak ditemukan.</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <tr>
                        <td width="20%"><strong>Nama Website</strong></td>
                        <td width="80%"><?php echo htmlspecialchars($nama_web); ?></td>
                      </tr>
                      <tr>
                        <td width="20%"><strong>Tahun</strong></td>
                        <td width="80%"><?php echo htmlspecialchars($tahun); ?></td>
                      </tr>
                    </tbody>
                  </table>
                  <?php else : ?>
                    <div class="alert alert-warning">Belum ada data konfigurasi. Silakan klik tombol 'Edit Konfigurasi' untuk menambahkan.</div>
                  <?php endif; ?>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix"> </div>
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