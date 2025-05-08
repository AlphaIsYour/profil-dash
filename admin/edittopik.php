<?php
include('../koneksi/koneksi.php');

$id_master_topik = null;
$topik_lama = '';

if (isset($_GET['data']) && filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
    $id_master_topik = (int)$_GET['data'];

    $sql_get = "SELECT `topik` FROM `master_topik` WHERE `id_master_topik` = ?";
    $stmt_get = mysqli_prepare($koneksi, $sql_get);

    if ($stmt_get) {
        mysqli_stmt_bind_param($stmt_get, 'i', $id_master_topik);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);

        if ($data_get = mysqli_fetch_assoc($result_get)) {
            $topik_lama = $data_get['topik'];
        } else {
             header("Location: topik.php?notif=datanotfound");
             exit;
        }
        mysqli_stmt_close($stmt_get);
    } else {
        echo "Error: Gagal menyiapkan query pengambilan data topik.";
        exit;
    }

} else {
    header("Location: topik.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Edit Topik</title>
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
            <h3><i class="fas fa-edit"></i> Edit Topik</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="topik.php">Topik</a></li>
              <li class="breadcrumb-item active">Edit Topik</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Topik</h3>
        <div class="card-tools">
          <a href="topik.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      </br>
       <div class="col-sm-10 offset-sm-1"> <!-- Atur posisi notifikasi -->
          <?php if (!empty($_GET['notif'])) { ?>
            <?php if ($_GET['notif'] == "editkosong") { ?>
              <div class="alert alert-danger" role="alert"> Maaf, data topik wajib diisi.</div>
            <?php } else if ($_GET['notif'] == "editgagal") { ?>
              <div class="alert alert-danger" role="alert"> Maaf, gagal mengubah data. Terjadi kesalahan server.</div>
            <?php } else if ($_GET['notif'] == "duplikat") { ?>
              <div class="alert alert-warning" role="alert"> Maaf, nama topik tersebut sudah digunakan oleh data lain.</div>
            <?php } ?>
          <?php } ?>
       </div>

      <form class="form-horizontal" method="post" action="konfirmasiedittopik.php">
        <!-- Hidden input untuk ID -->
        <input type="hidden" name="id_master_topik" value="<?php echo htmlspecialchars($id_master_topik); ?>">

        <div class="card-body">
          <div class="form-group row">
            <label for="topik" class="col-sm-3 col-form-label">Topik</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="topik" name="topik" value="<?php echo htmlspecialchars($topik_lama); ?>" required>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-info float-right"><i class="fas fa-save"></i> Simpan Perubahan</button>
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