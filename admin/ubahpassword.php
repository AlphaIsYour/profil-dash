<?php
session_start();
include("../koneksi/koneksi.php");

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php?gagal=aksesditolak");
    exit;
}
$id_user_login = $_SESSION['id_user'];

$notif = '';
$alert_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pass_lama_input = trim($_POST['pass_lama'] ?? '');
    $pass_baru = trim($_POST['pass_baru'] ?? '');
    $konfirmasi_pass = trim($_POST['konfirmasi'] ?? '');

    if (empty($pass_lama_input) || empty($pass_baru) || empty($konfirmasi_pass)) {
        $notif = 'Semua field password wajib diisi.';
        $alert_type = 'danger';
    } elseif ($pass_baru !== $konfirmasi_pass) {
        $notif = 'Password Baru dan Konfirmasi Password Baru tidak cocok.';
        $alert_type = 'danger';
    } else {
        $sql_get_pass = "SELECT `password` FROM `user` WHERE `id_user` = ?";
        $stmt_get = mysqli_prepare($koneksi, $sql_get_pass);

        if ($stmt_get) {
            mysqli_stmt_bind_param($stmt_get, 'i', $id_user_login);
            mysqli_stmt_execute($stmt_get);
            $result_get = mysqli_stmt_get_result($stmt_get);
            $user_data = mysqli_fetch_assoc($result_get);
            mysqli_stmt_close($stmt_get);

            if ($user_data && isset($user_data['password'])) {
              $password_db = $user_data['password'];
              $password_lama_cocok = false;
              if (password_verify($pass_lama_input, $password_db)) {
                  $password_lama_cocok = true;
              }
              else if (strlen($password_db) === 32 && md5($pass_lama_input) === $password_db) {
                   $password_lama_cocok = true;
              }

              if ($password_lama_cocok) {

                  $hashed_pass_baru = password_hash($pass_baru, PASSWORD_DEFAULT);

                  $sql_update = "UPDATE `user` SET `password` = ? WHERE `id_user` = ?";
                  $stmt_update = mysqli_prepare($koneksi, $sql_update);

                  if ($stmt_update) {
                      mysqli_stmt_bind_param($stmt_update, 'si', $hashed_pass_baru, $id_user_login);

                      if (mysqli_stmt_execute($stmt_update)) {
                          $notif = 'Password berhasil diubah.';
                          $alert_type = 'success';
                          $_POST = [];
                      } else {
                          $notif = 'Gagal mengubah password. Terjadi kesalahan database.';
                          $alert_type = 'danger';
                      }
                      mysqli_stmt_close($stmt_update);
                  } else {
                       $notif = 'Gagal mengubah password. Terjadi kesalahan (prepare update).';
                       $alert_type = 'danger';
                  }

              } else {
                  $notif = 'Password Lama yang Anda masukkan salah.';
                  $alert_type = 'danger';
              }
          } else {
              $notif = 'Gagal mengambil data user.';
              $alert_type = 'danger';
          }
        } else {
             $notif = 'Gagal memverifikasi password lama. Terjadi kesalahan (prepare get).';
             $alert_type = 'danger';
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Ubah Password</title>
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
            <h3><i class="fas fa-user-lock"></i> Ubah Password</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"> Ubah Password</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Pengaturan Password</h3>
            </div>
            <!-- /.card-header -->
            <?php if (!empty($notif)): ?>
            <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show m-3" role="alert">
                <?php echo htmlspecialchars($notif); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php endif; ?>

            <!-- form start -->
            <form class="form-horizontal" method="POST" action="ubahpassword.php">
                <div class="card-body">
                <h6>
                    <i class="text-blue"><i class="fas fa-info-circle"></i> Silahkan memasukkan password lama dan password baru Anda untuk mengubah password.</i>
                </h6><br>

                <div class="form-group row">
                    <label for="pass_lama" class="col-sm-3 col-form-label">Password Lama <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                    <input type="password" class="form-control" id="pass_lama" name="pass_lama" value="" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pass_baru" class="col-sm-3 col-form-label">Password Baru <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                    <input type="password" class="form-control" id="pass_baru" name="pass_baru" value="" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="konfirmasi" class="col-sm-3 col-form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                    <input type="password" class="form-control" id="konfirmasi" name="konfirmasi" value="" required>
                    </div>
                </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-info float-right"><i class="fas fa-save"></i> Simpan</button>
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