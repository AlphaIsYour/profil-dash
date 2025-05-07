<?php
include('../koneksi/koneksi.php');

$id_riwayat_pendidikan = null;
$data_rp = null;

if (isset($_GET['data']) && filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
    $id_riwayat_pendidikan = (int)$_GET['data'];

    $sql_get = "SELECT `id_riwayat_pendidikan`, `tahun`, `id_master_jenjang`, `jurusan`, `id_master_universitas`
                FROM `riwayat_pendidikan` WHERE `id_riwayat_pendidikan` = ?";
    $stmt_get = mysqli_prepare($koneksi, $sql_get);
    if($stmt_get){
        mysqli_stmt_bind_param($stmt_get, 'i', $id_riwayat_pendidikan);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);
        $data_rp = mysqli_fetch_assoc($result_get);
        mysqli_stmt_close($stmt_get);
    }

    if (!$data_rp) {
        header("Location: riwayatpendidikan.php?notif=datanotfound"); 
        exit;
    }

} else {
    // Jika ID tidak valid atau tidak ada
    header("Location: riwayatpendidikan.php?notif=invalidid"); // Buat notif ini jika perlu
    exit;
}


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
<title>Edit Riwayat Pendidikan</title>
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
            <h3><i class="fas fa-edit"></i> Edit Riwayat Pendidikan</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="riwayatpendidikan.php">Riwayat Pendidikan</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Riwayat Pendidikan</h3>
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
                     <?php if ($_GET['notif'] == "editkosong") { ?>
                        <div class="alert alert-danger" role="alert"> Maaf, semua field (Tahun, Jenjang, Jurusan, Universitas) wajib diisi.</div>
                    <?php } else if ($_GET['notif'] == "editgagal") { ?>
                         <div class="alert alert-danger" role="alert"> Maaf, gagal mengubah data. Terjadi kesalahan server.</div>
                    <?php } ?>
                <?php } ?>
            </div>
            <form class="form-horizontal" method="post" action="konfirmasieditriwayatpendidikan.php">
                <!-- Hidden input for ID -->
                <input type="hidden" name="id_riwayat_pendidikan" value="<?php echo $id_riwayat_pendidikan; ?>">

                <div class="card-body">
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-3 col-form-label">Tahun <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="tahun" id="tahun" value="<?php echo htmlspecialchars($data_rp['tahun']); ?>" placeholder="Contoh: 2019-2023" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jenjang" class="col-sm-3 col-form-label">Jenjang Pendidikan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <select class="form-control select2" id="jenjang" name="id_master_jenjang" required>
                            <option value="">- Pilih Jenjang -</option>
                            <?php mysqli_data_seek($query_jenjang, 0); // Reset pointer query jenjang ?>
                            <?php while($data_j = mysqli_fetch_assoc($query_jenjang)) :
                                $selected_jenjang = ($data_j['id_master_jenjang'] == $data_rp['id_master_jenjang']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $data_j['id_master_jenjang']; ?>" <?php echo $selected_jenjang; ?>>
                                    <?php echo htmlspecialchars($data_j['jenjang']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jurusan" class="col-sm-3 col-form-label">Jurusan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="jurusan" id="jurusan" value="<?php echo htmlspecialchars($data_rp['jurusan']); ?>" required>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="universitas" class="col-sm-3 col-form-label">Universitas <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <select class="form-control select2" id="universitas" name="id_master_universitas" required>
                            <option value="">- Pilih Universitas -</option>
                             <?php mysqli_data_seek($query_univ, 0); // Reset pointer query univ ?>
                             <?php while($data_u = mysqli_fetch_assoc($query_univ)) :
                                $selected_univ = ($data_u['id_master_universitas'] == $data_rp['id_master_universitas']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $data_u['id_master_universitas']; ?>" <?php echo $selected_univ; ?>>
                                    <?php echo htmlspecialchars($data_u['nama_universitas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        </div>
                    </div>
                </div> <!-- /.card-body -->
                <div class="card-footer">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-info float-right"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </div> <!-- /.card-footer -->
            </form>
        </div> <!-- /.card -->
    </section> <!-- /.content -->
  </div> <!-- /.content-wrapper -->
  <?php include("includes/footer.php") ?>
</div> <!-- ./wrapper -->

<?php include("includes/script.php") ?>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script>
  $(function () {
    $('.select2').select2()
    $('.select2bs4').select2({ theme: 'bootstrap4' })
  })
</script>
</body>
</html>