<?php
include('../koneksi/koneksi.php');

// Cek apakah ada data ID yang dikirim
if (isset($_GET['data'])) {
    $id_master_soft_skill = mysqli_real_escape_string($koneksi, $_GET['data']);

    $sql_get = "SELECT `soft_skill` FROM `master_soft_skill` WHERE `id_master_soft_skill` = ?";
    $stmt_get = mysqli_prepare($koneksi, $sql_get);

    if ($stmt_get) {
        mysqli_stmt_bind_param($stmt_get, 'i', $id_master_soft_skill);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);

        if ($data_get = mysqli_fetch_assoc($result_get)) {
            $softskill_lama = $data_get['soft_skill'];
        } else {
            echo "Error: Data soft skill tidak ditemukan.";
            exit;
        }
        mysqli_stmt_close($stmt_get);
    } else {
        echo "Error: Gagal menyiapkan query.";
        exit;
    }

} else {
    header("Location: softskill.php");
    exit;
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

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3><i class="fas fa-edit"></i> Edit Soft Skill</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="softskill.php">Soft Skill</a></li>
              <li class="breadcrumb-item active">Edit Soft Skill</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Soft Skill</h3>
        <div class="card-tools">
          <a href="softskill.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>

      </br>
      <?php if (!empty($_GET['notif'])) { ?>
        <?php if ($_GET['notif'] == "editkosong") { ?>
          <div class="alert alert-danger" role="alert">
            Maaf data soft skill wajib diisi.
          </div>
        <?php } else if ($_GET['notif'] == "editgagal") { ?>
          <div class="alert alert-danger" role="alert">
            Maaf, gagal mengubah data. Mungkin nama soft skill sudah ada atau terjadi kesalahan server.
          </div>
         <?php } else if ($_GET['notif'] == "duplikat") { ?>
          <div class="alert alert-danger" role="alert">
            Maaf, nama soft skill tersebut sudah digunakan oleh data lain.
          </div>
        <?php } ?>
      <?php } ?>

      <form class="form-horizontal" method="post" action="konfirmasieditsoftskill.php">
        <!-- Hidden input untuk menyimpan ID yang diedit -->
        <input type="hidden" name="id_master_soft_skill" value="<?php echo htmlspecialchars($id_master_soft_skill); ?>">

        <div class="card-body">
          <div class="form-group row">
            <label for="softskill" class="col-sm-3 col-form-label">Soft Skill</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="softskill" name="softskill" value="<?php echo htmlspecialchars($softskill_lama); ?>" required>
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