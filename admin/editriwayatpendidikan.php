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
            <h3><i class="fas fa-edit"></i> Edit Data Riwayat Pendidikan</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="riwayatpendidikan.php">Data Riwayat Pendidikan</a></li>
              <li class="breadcrumb-item active">Edit Data Riwayat Pendidikan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"style="margin-top:5px;"><i class="far fa-list-alt"></i> Form Edit Data Riwayat Pendidikan</h3>
        <div class="card-tools">
          <a href="riwayatpendidikan.php" class="btn btn-sm btn-warning float-right">
          <i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        </div>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      </br></br>
      <div class="col-sm-10">
          <div class="alert alert-danger" role="alert">Maaf tahun wajib di isi</div>
      </div>
      <form class="form-horizontal">
        <div class="card-body">
          <div class="form-group row">
            <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="tahun" id="tahun" value="2019-2022">
            </div>
          </div>          
          <div class="form-group row">
            <label for="jenjang" class="col-sm-3 col-form-label">Jenjang Pendidikan</label>
            <div class="col-sm-7">
              <select class="form-control" id="jenjang">
                <option value="0">- Pilih Jenjang Pendidikan -</option>
                <option value="1">D3</option>
                <option value="2">D4</option>
                <option value="3" selected>S1</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="jurusan" class="col-sm-3 col-form-label">Jurusan</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="jurusan" id="jurusan" value="Teknik Informatika">
            </div>
          </div>
          <div class="form-group row">
            <label for="universitas" class="col-sm-3 col-form-label">Universitas</label>
            <div class="col-sm-7">
              <select class="form-control" id="universitas">
                <option value="0">- Pilih Universitas -</option>
                <option value="1" selected>Universitas Brawijaya</option>
                <option value="2">Universitas Gajah Mada</option>
              </select>
            </div>
          </div>
          
          </div>
        </div>

      </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <div class="col-sm-12">
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
