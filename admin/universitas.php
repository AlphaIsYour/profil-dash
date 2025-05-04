<?php
include('../koneksi/koneksi.php');

if((isset($_GET['aksi']))&&(isset($_GET['data']))){
    if($_GET['aksi']=='hapus'){
        $id_master_universitas = mysqli_real_escape_string($koneksi, $_GET['data']);
        //hapus universitas
        $sql_dh = "DELETE FROM `master_universitas` WHERE `id_master_universitas` = ?";
        $stmt = mysqli_prepare($koneksi, $sql_dh);
        mysqli_stmt_bind_param($stmt, 's', $id_master_universitas);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        header("Location: universitas.php?notif=hapusberhasil");
        exit;
    }
}

$search_query = "";
if(isset($_GET['katakunci']) && !empty($_GET['katakunci'])) {
    $search_query = mysqli_real_escape_string($koneksi, $_GET['katakunci']);
}

$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3><i class="fas fa-university"></i> Universitas</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"> Universitas</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="fas fa-list-ul"></i> Daftar Universitas</h3>
                <div class="card-tools">
                  <a href="tambahuniversitas.php" class="btn btn-sm btn-info float-right"><i class="fas fa-plus"></i> Tambah Universitas</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <div class="col-md-12">
                  <form method="GET" action="universitas.php">
                    <div class="row">
                        <div class="col-md-4 bottom-10">
                          <input type="text" class="form-control" id="katakunci" name="katakunci" placeholder="Cari universitas..." value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <div class="col-md-5 bottom-10">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>&nbsp; Search</button>
                        </div>
                    </div><!-- .row -->
                  </form>
                </div><br>
                <div class="col-sm-12">
                <?php if(!empty($_GET['notif'])){?>
                    <?php if($_GET['notif']=="tambahberhasil"){?>
                    <div class="alert alert-success" role="alert">
                    Data Berhasil Ditambahkan</div>
                    <?php } else if($_GET['notif']=="editberhasil"){?>
                    <div class="alert alert-success" role="alert">
                    Data Berhasil Diubah</div>
                    <?php } else if($_GET['notif']=="hapusberhasil"){?>
                    <div class="alert alert-success" role="alert">
                    Data Berhasil Dihapus</div>
                    <?php }?>
                <?php }?>
                </div>
                
                <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                    <th width="5%">No</th>
                    <th width="80%">Universitas</th>
                    <th width="15%"><center>Aksi</center></th>
                    </tr>
                    </thead>
                      <tbody>
                        <?php
                        // Count total records for pagination
                        $count_query = "SELECT COUNT(*) as total FROM `master_universitas`";
                        if(!empty($search_query)) {
                            $count_query .= " WHERE `nama_universitas` LIKE ?";
                        }
                        $stmt = mysqli_prepare($koneksi, $count_query);
                        
                        if(!empty($search_query)) {
                            $search_param = "%$search_query%";
                            mysqli_stmt_bind_param($stmt, 's', $search_param);
                        }
                        
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        $total_records = $row['total'];
                        $total_pages = ceil($total_records / $limit);
                        
                        // Main query for fetching universities
                        $sql_u = "SELECT `id_master_universitas`, `nama_universitas` FROM `master_universitas`";
                        if(!empty($search_query)) {
                            $sql_u .= " WHERE `nama_universitas` LIKE ?";
                        }
                        $sql_u .= " ORDER BY `nama_universitas` LIMIT ?, ?";
                        
                        $stmt = mysqli_prepare($koneksi, $sql_u);
                        
                        if(!empty($search_query)) {
                            $search_param = "%$search_query%";
                            mysqli_stmt_bind_param($stmt, 'sii', $search_param, $start, $limit);
                        } else {
                            mysqli_stmt_bind_param($stmt, 'ii', $start, $limit);
                        }
                        
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        $no = $start + 1;
                        if(mysqli_num_rows($result) > 0) {
                            while($data_u = mysqli_fetch_assoc($result)){
                                $id_master_universitas = $data_u['id_master_universitas'];
                                $nama_universitas = $data_u['nama_universitas'];
                        ?>
                        <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo htmlspecialchars($nama_universitas);?></td>
                        <td align="center">
                        <a href="edituniversitas.php?data=<?php echo htmlspecialchars($id_master_universitas);?>"
                        class="btn btn-xs btn-info"><i class="fas fa-edit"></i> Edit</a>
                        <a href="javascript:if(confirm('Anda yakin ingin menghapus data <?php echo htmlspecialchars($nama_universitas); ?>?'))window.location.href = 'universitas.php?aksi=hapus&data=<?php echo htmlspecialchars($id_master_universitas);?>'"
                        class="btn btn-xs btn-warning"><i class="fas fa-trash"></i> Hapus</a>
                        </td>
                        </tr>
                        <?php 
                            $no++;
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data universitas</td>
                        </tr>
                        <?php } ?>
                      </tbody>
                </table>
                </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <?php if($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="universitas.php?page=1<?php echo !empty($search_query) ? '&katakunci='.urlencode($search_query) : ''; ?>">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="universitas.php?page=<?php echo $page-1; ?><?php echo !empty($search_query) ? '&katakunci='.urlencode($search_query) : ''; ?>">&lsaquo;</a></li>
                  <?php endif; ?>
                  
                  <?php 
                  $start_page = max(1, $page - 2);
                  $end_page = min($total_pages, $page + 2);
                  
                  for($i = $start_page; $i <= $end_page; $i++): 
                  ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                      <a class="page-link" href="universitas.php?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&katakunci='.urlencode($search_query) : ''; ?>"><?php echo $i; ?></a>
                    </li>
                  <?php endfor; ?>
                  
                  <?php if($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="universitas.php?page=<?php echo $page+1; ?><?php echo !empty($search_query) ? '&katakunci='.urlencode($search_query) : ''; ?>">&rsaquo;</a></li>
                    <li class="page-item"><a class="page-link" href="universitas.php?page=<?php echo $total_pages; ?><?php echo !empty($search_query) ? '&katakunci='.urlencode($search_query) : ''; ?>">&raquo;</a></li>
                  <?php endif; ?>
                </ul>
              </div>
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