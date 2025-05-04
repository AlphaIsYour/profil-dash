<?php
// Tidak perlu session_start() jika tidak menggunakan session untuk ID
include('../koneksi/koneksi.php'); // Sesuaikan path

$id_master_hard_skill = null;
$hardskill_lama = ''; // Default value

// Cek apakah ada data ID yang dikirim dan valid
if (isset($_GET['data']) && filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
    $id_master_hard_skill = (int)$_GET['data'];

    // Query untuk mengambil data hard skill berdasarkan ID menggunakan prepared statement
    $sql_get = "SELECT `hard_skill` FROM `master_hard_skill` WHERE `id_master_hard_skill` = ?";
    $stmt_get = mysqli_prepare($koneksi, $sql_get);

    if ($stmt_get) {
        mysqli_stmt_bind_param($stmt_get, 'i', $id_master_hard_skill); // 'i' for integer
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);

        if ($data_get = mysqli_fetch_assoc($result_get)) {
            $hardskill_lama = $data_get['hard_skill'];
        } else {
            // Data tidak ditemukan, redirect dengan notifikasi
             header("Location: hardskill.php?notif=datanotfound"); // Buat notif ini jika perlu
             exit;
        }
        mysqli_stmt_close($stmt_get);
    } else {
        // Gagal prepare query
        echo "Error: Gagal menyiapkan query pengambilan data."; // Atau redirect
        exit;
    }

} else {
    // Jika tidak ada ID atau ID tidak valid, redirect ke halaman utama
    header("Location: hardskill.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Edit Hard Skill</title>
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
            <h3><i class="fas fa-edit"></i> Edit Hard Skill</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="hardskill.php">Hard Skill</a></li>
              <li class="breadcrumb-item active">Edit Hard Skill</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Hard Skill</h3>
        <div class="card-tools">
          <a href="hardskill.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      </br>
       <div class="col-sm-10 offset-sm-1"> <!-- Atur posisi notifikasi -->
          <?php if (!empty($_GET['notif'])) { ?>
            <?php if ($_GET['notif'] == "editkosong") { ?>
              <div class="alert alert-danger" role="alert"> Maaf, data hard skill wajib diisi.</div>
            <?php } else if ($_GET['notif'] == "editgagal") { ?>
              <div class="alert alert-danger" role="alert"> Maaf, gagal mengubah data. Terjadi kesalahan server.</div>
            <?php } else if ($_GET['notif'] == "duplikat") { ?>
              <div class="alert alert-warning" role="alert"> Maaf, nama hard skill tersebut sudah digunakan oleh data lain.</div>
            <?php } ?>
          <?php } ?>
       </div>

      <form class="form-horizontal" method="post" action="konfirmasiedithardskill.php">
        <!-- Hidden input untuk menyimpan ID yang diedit -->
        <input type="hidden" name="id_master_hard_skill" value="<?php echo htmlspecialchars($id_master_hard_skill); ?>">

        <div class="card-body">
          <div class="form-group row">
            <label for="hardskill" class="col-sm-3 col-form-label">Hard Skill</label>
            <div class="col-sm-7">
               <!-- Perbaiki ID dan tambahkan required -->
              <input type="text" class="form-control" id="hardskill" name="hardskill" value="<?php echo htmlspecialchars($hardskill_lama); ?>" required>
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