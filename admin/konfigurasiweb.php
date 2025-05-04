<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?> 
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
            <h3><i class="fas fa-user-tie"></i> Konfigurasi Web</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Konfigurasi Web</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <div class="card-tools">
                  <a href="editkonfigurasiweb.php" class="btn btn-sm btn-info float-right"><i class="fas fa-edit"></i> Edit Konfigurasi Web</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="col-sm-12">
                    <div class="alert alert-success" role="alert">Data Berhasil Diubah</div>
                </div>
                <table class="table table-bordered">
                    <tbody>  
                      <tr>
                        <td colspan="2"><i class="fas fa-user-circle"></i> <strong>KONFIGURASI WEB<strong></td>
                      </tr> 
                      <tr>
                        <td width="20%"><strong>Logo<strong></td>
                        <td width="80%"><img src="image/logo.png" class="img-fluid" width="400px;"></td>
                      </tr>                
                      <tr>
                        <td width="20%"><strong>Nama Web<strong></td>
                        <td width="80%">Alpha Beta</td>
                      </tr>                
                      <tr>
                        <td width="20%"><strong>Tahun<strong></td>
                        <td width="80%">2025</td>
                      </tr> 
                    </tbody>
                  </table>  
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">&nbsp;</div>
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
