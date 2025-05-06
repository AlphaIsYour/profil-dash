<?php
include('../koneksi/koneksi.php');

$id_riwayat_pekerjaan = null;
$data_pk = null;

if (isset($_GET['data']) && filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
    $id_riwayat_pekerjaan = (int)$_GET['data'];

    $sql_get = "SELECT `id_riwayat_pekerjaan`, `tahun`, `posisi`, `perusahaan`
                FROM `riwayat_pekerjaan` WHERE `id_riwayat_pekerjaan` = ?";
    $stmt_get = mysqli_prepare($koneksi, $sql_get);
    if($stmt_get){
        mysqli_stmt_bind_param($stmt_get, 'i', $id_riwayat_pekerjaan);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);
        $data_pk = mysqli_fetch_assoc($result_get);
        mysqli_stmt_close($stmt_get);
    } else {
        header("Location: riwayatpekerjaan.php?notif=editgagal&msg=prepare");
        exit;
    }

    if (!$data_pk) {
        header("Location: riwayatpekerjaan.php?notif=datanotfound");
        exit;
    }

} else {
    header("Location: riwayatpekerjaan.php?notif=invalidid");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Edit Riwayat Pekerjaan</title>
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
            <h3><i class="fas fa-edit"></i> Edit Riwayat Pekerjaan</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="riwayatpekerjaan.php">Riwayat Pekerjaan</a></li>
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
                <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Riwayat Pekerjaan</h3>
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
                     <?php if ($_GET['notif'] == "editkosong") { ?>
                        <div class="alert alert-danger" role="alert"> Maaf, semua field (Tahun, Posisi, Perusahaan) wajib diisi.</div>
                    <?php } else if ($_GET['notif'] == "editgagal") { ?>
                         <div class="alert alert-danger" role="alert"> Maaf, gagal mengubah data. <?php echo isset($_GET['msg']) ? '(' . htmlspecialchars($_GET['msg']) . ')' : ''; ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
            <form class="form-horizontal" method="post" action="konfirmasieditriwayatpekerjaan.php">
                <!-- Hidden input for ID -->
                <input type="hidden" name="id_riwayat_pekerjaan" value="<?php echo $id_riwayat_pekerjaan; ?>">

                <div class="card-body">
                    <div class="form-group row">
                        <label for="tahun" class="col-sm-3 col-form-label">Tahun <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <!-- Pastikan nama kolom benar -->
                        <input type="text" class="form-control" name="tahun" id="tahun" value="<?php echo htmlspecialchars($data_pk['tahun']); ?>" placeholder="Contoh: 2021-2023" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="posisi" class="col-sm-3 col-form-label">Posisi <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                         <!-- Pastikan nama kolom benar -->
                        <input type="text" class="form-control" name="posisi" id="posisi" value="<?php echo htmlspecialchars($data_pk['posisi']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="perusahaan" class="col-sm-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                         <!-- Pastikan nama kolom benar -->
                        <input type="text" class="form-control" name="perusahaan" id="perusahaan" value="<?php echo htmlspecialchars($data_pk['perusahaan']); ?>" required>
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
</body>
</html>