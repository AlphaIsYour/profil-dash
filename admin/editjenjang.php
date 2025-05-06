<?php
include('../koneksi/koneksi.php');

$id_master_jenjang = null;
$jenjang_lama = ''; 

if (isset($_GET['data']) && filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
    $id_master_jenjang = (int)$_GET['data'];

    $sql_get = "SELECT `jenjang` FROM `master_jenjang` WHERE `id_master_jenjang` = ?";
    $stmt_get = mysqli_prepare($koneksi, $sql_get);

    if ($stmt_get) {
        mysqli_stmt_bind_param($stmt_get, 'i', $id_master_jenjang);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);

        if ($data_get = mysqli_fetch_assoc($result_get)) {
            $jenjang_lama = $data_get['jenjang'];
        } else {
             header("Location: jenjang.php?notif=datanotfound");
             exit;
        }
        mysqli_stmt_close($stmt_get);
    } else {
        echo "Error: Gagal menyiapkan query pengambilan data jenjang.";
        exit;
    }

} else {
    header("Location: jenjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Edit Jenjang Pendidikan</title>
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
            <h3><i class="fas fa-edit"></i> Edit Jenjang</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="jenjang.php">Jenjang Pendidikan</a></li>
              <li class="breadcrumb-item active">Edit Jenjang</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Jenjang</h3>
                <div class="card-tools">
                <a href="jenjang.php" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
                </div>
            </div>
            </br>
            <div class="col-sm-10 offset-sm-1">
                <?php if(!empty($_GET['notif'])){?>
                    <?php if($_GET['notif']=="editkosong"){?>
                    <div class="alert alert-danger" role="alert">
                        Maaf, nama jenjang wajib diisi.
                    </div>
                     <?php } else if($_GET['notif']=="duplikat"){ ?>
                    <div class="alert alert-warning" role="alert">
                        Maaf, nama jenjang tersebut sudah ada.
                    </div>
                    <?php } else if($_GET['notif']=="editgagal"){ ?>
                    <div class="alert alert-danger" role="alert">
                        Maaf, terjadi kesalahan saat mengubah data.
                    </div>
                    <?php }?>
                <?php }?>
            </div>

            <form class="form-horizontal" method="post" action="konfirmasieditjenjang.php">
                <input type="hidden" name="id_master_jenjang" value="<?php echo htmlspecialchars($id_master_jenjang); ?>">

                <div class="card-body">
                    <div class="form-group row">
                        <label for="jenjang" class="col-sm-3 col-form-label">Jenjang Pendidikan <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="jenjang" name="jenjang" value="<?php echo htmlspecialchars($jenjang_lama);?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-info float-right"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </div>
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