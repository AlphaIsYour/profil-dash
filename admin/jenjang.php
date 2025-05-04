<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// --- Logic Hapus ---
if ((isset($_GET['aksi'])) && (isset($_GET['data']))) {
    if ($_GET['aksi'] == 'hapus') {
        // Validasi ID adalah integer
        if (!filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
             header("Location: jenjang.php?notif=hapusgagal&msg=invalidid");
             exit;
        }
        $id_master_jenjang = (int)$_GET['data'];

        // Hapus dari tabel master
        // Ingat potensi masalah dengan FK ON DELETE RESTRICT dari riwayat_pendidikan
        $sql_delete = "DELETE FROM `master_jenjang` WHERE `id_master_jenjang` = ?";
        $stmt_delete = mysqli_prepare($koneksi, $sql_delete);

        if ($stmt_delete) {
            mysqli_stmt_bind_param($stmt_delete, 'i', $id_master_jenjang); // 'i' for integer
            mysqli_stmt_execute($stmt_delete);

            if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                header("Location: jenjang.php?notif=hapusberhasil");
            } else {
                $error_info = mysqli_stmt_error($stmt_delete); // Cek error DB
                // Log error jika perlu: error_log("Gagal hapus jenjang $id_master_jenjang: $error_info");
                header("Location: jenjang.php?notif=hapusgagal&msg=failed" . (!empty($error_info) ? '_db' : ''));
            }
            mysqli_stmt_close($stmt_delete);
        } else {
            // Gagal prepare
            header("Location: jenjang.php?notif=hapusgagal&msg=prepare");
        }
        exit;
    }
}

// --- Logic Search & Pagination ---
$search_query = "";
if (isset($_GET['katakunci'])) {
    $search_query = mysqli_real_escape_string($koneksi, $_GET['katakunci']);
}

$limit = 10;
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) : 1;
if ($page === false) $page = 1;
$start = ($page - 1) * $limit;
?>

<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Data Jenjang Pendidikan</title>
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
            <h3><i class="fas fa-layer-group"></i> Jenjang Pendidikan</h3> <!-- Icon ganti -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"> Jenjang Pendidikan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="fas fa-list-ul"></i> Daftar Jenjang Pendidikan</h3>
                <div class="card-tools">
                  <a href="tambahjenjang.php" class="btn btn-sm btn-info float-right"><i class="fas fa-plus"></i> Tambah Jenjang Pendidikan</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <div class="col-md-12">
              <form method="GET" action="jenjang.php">
                    <div class="row">
                        <div class="col-md-4 mb-2"> <!-- mb-2 -->
                          <input type="text" class="form-control" id="katakunci" name="katakunci" placeholder="Cari jenjang..." value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <div class="col-md-5 mb-2">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>  Cari</button>
                        </div>
                    </div><!-- .row -->
                  </form>
                </div><br>
                <div class="col-sm-12">
                <?php if (!empty($_GET['notif'])) { ?>
                    <?php if ($_GET['notif'] == "tambahberhasil") { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Ditambahkan <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <?php } else if ($_GET['notif'] == "editberhasil") { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Diubah <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <?php } else if ($_GET['notif'] == "hapusberhasil") { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Dihapus <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <?php } else if ($_GET['notif'] == "hapusgagal") { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Gagal Dihapus. <?php echo (isset($_GET['msg']) && $_GET['msg'] == 'failed_db') ? ' Kemungkinan data masih digunakan (misal: Riwayat Pendidikan).' : ''; ?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                     <?php } else if ($_GET['notif'] == "datanotfound") { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert"> Data tidak ditemukan. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <?php } ?>
                <?php } ?>
              </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="80%">Jenjang Pendidikan</th>
                        <th width="15%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Count total records
                            $count_sql = "SELECT COUNT(*) as total FROM `master_jenjang`";
                            $params_count = []; $types_count = '';
                            if (!empty($search_query)) {
                                $count_sql .= " WHERE `jenjang` LIKE ?";
                                $search_param_count = "%" . $search_query . "%";
                                $params_count[] = &$search_param_count; $types_count .= 's';
                            }

                            $total_records = 0; $total_pages = 0;
                            $stmt_count = mysqli_prepare($koneksi, $count_sql);
                            if ($stmt_count) {
                                if (!empty($search_query)) { mysqli_stmt_bind_param($stmt_count, $types_count, ...$params_count); }
                                mysqli_stmt_execute($stmt_count);
                                $result_count = mysqli_stmt_get_result($stmt_count);
                                if ($result_count) {
                                    $row_count = mysqli_fetch_assoc($result_count);
                                    $total_records = $row_count['total'];
                                    $total_pages = ceil($total_records / $limit);
                                } else { echo "<tr><td colspan='3' class='text-center text-danger'>Error mendapatkan hasil count.</td></tr>"; }
                                mysqli_stmt_close($stmt_count);
                            } else { echo "<tr><td colspan='3' class='text-center text-danger'>Error menghitung data jenjang.</td></tr>"; }

                            // Main query
                            $sql_data = "SELECT `id_master_jenjang`, `jenjang` FROM `master_jenjang`";
                            $params_data = []; $types_data = '';
                            if (!empty($search_query)) {
                                $sql_data .= " WHERE `jenjang` LIKE ?";
                                $search_param_data = "%" . $search_query . "%";
                                $params_data[] = &$search_param_data; $types_data .= 's';
                            }
                            $sql_data .= " ORDER BY `jenjang` LIMIT ?, ?";
                            $params_data[] = &$start; $params_data[] = &$limit; $types_data .= 'ii';

                            $stmt_data = mysqli_prepare($koneksi, $sql_data);
                            if ($stmt_data) {
                                if (!empty($params_data)) { mysqli_stmt_bind_param($stmt_data, $types_data, ...$params_data); }
                                mysqli_stmt_execute($stmt_data);
                                $result_data = mysqli_stmt_get_result($stmt_data);

                                if ($result_data && mysqli_num_rows($result_data) > 0) {
                                    $no = $start + 1;
                                    while ($data_j = mysqli_fetch_assoc($result_data)) {
                                        $id_master_jenjang = $data_j['id_master_jenjang'];
                                        $jenjang = $data_j['jenjang'];
                            ?>
                        <tr>
                        <td class="text-center"><?php echo $no;?></td>
                        <td><?php echo htmlspecialchars($jenjang);?></td>
                        <td align="center">
                        <a href="editjenjang.php?data=<?php echo htmlspecialchars($id_master_jenjang);?>" class="btn btn-xs btn-info" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-warning" title="Hapus" onclick="konfirmasiHapusJenjang('<?php echo htmlspecialchars(addslashes($jenjang)); ?>', '<?php echo htmlspecialchars($id_master_jenjang);?>', '<?php echo urlencode($search_query); ?>', '<?php echo $page; ?>')">
                            <i class="fas fa-trash"></i></a>
                        </td>
                        </tr>
                        <?php
                                $no++;
                                }
                            } else { echo "<tr><td colspan='3' class='text-center'>" . ($total_records > 0 ? "Tidak ada data di halaman ini." : "Data jenjang tidak ditemukan.") . "</td></tr>"; }
                            mysqli_stmt_close($stmt_data);
                            } else { echo "<tr><td colspan='3' class='text-center text-danger'>Error mengambil data jenjang.</td></tr>"; }
                        ?>
                    </tbody>
                    </table>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <?php if ($total_records > 0 && $total_pages > 1) : ?>
                <ul class="pagination pagination-sm m-0 float-right">
                  <?php
                  $query_string = !empty($search_query) ? '&katakunci='.urlencode($search_query) : '';
                  // Logika Pagination (copy dari sebelumnya)
                  if ($page > 1) { echo "<li class='page-item'><a class='page-link' href='jenjang.php?page=1{$query_string}'>« First</a></li>"; echo "<li class='page-item'><a class='page-link' href='jenjang.php?page=".($page - 1)."{$query_string}'>‹ Prev</a></li>"; } else { echo "<li class='page-item disabled'><span class='page-link'>« First</span></li>"; echo "<li class='page-item disabled'><span class='page-link'>‹ Prev</span></li>"; }
                  $num_links = 2; $start_loop = max(1, $page - $num_links); $end_loop = min($total_pages, $page + $num_links);
                  if ($start_loop > 1) { echo "<li class='page-item'><a class='page-link' href='jenjang.php?page=1{$query_string}'>1</a></li>"; if ($start_loop > 2) { echo "<li class='page-item disabled'><span class='page-link'>...</span></li>"; } }
                  for ($i = $start_loop; $i <= $end_loop; $i++) { echo "<li class='page-item ".($i == $page ? 'active' : '')."'><a class='page-link' href='jenjang.php?page={$i}{$query_string}'>{$i}</a></li>"; }
                  if ($end_loop < $total_pages) { if ($end_loop < $total_pages - 1) { echo "<li class='page-item disabled'><span class='page-link'>...</span></li>"; } echo "<li class='page-item'><a class='page-link' href='jenjang.php?page={$total_pages}{$query_string}'>{$total_pages}</a></li>"; }
                  if ($page < $total_pages) { echo "<li class='page-item'><a class='page-link' href='jenjang.php?page=".($page + 1)."{$query_string}'>Next ›</a></li>"; echo "<li class='page-item'><a class='page-link' href='jenjang.php?page={$total_pages}{$query_string}'>Last »</a></li>"; } else { echo "<li class='page-item disabled'><span class='page-link'>Next ›</span></li>"; echo "<li class='page-item disabled'><span class='page-link'>Last »</span></li>"; }
                  ?>
                </ul>
                 <?php endif; ?>
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
<script>
function konfirmasiHapusJenjang(nama, id, katakunci, page) {
  let pesanKonfirmasi = `Anda yakin ingin menghapus jenjang: ${nama}?`;
  // pesanKonfirmasi += "\n\nCATATAN: Jika jenjang ini masih digunakan dalam riwayat pendidikan, penghapusan mungkin gagal.";
  if (confirm(pesanKonfirmasi)) {
    window.location.href = `jenjang.php?aksi=hapus&data=${id}&katakunci=${encodeURIComponent(katakunci)}&page=${page}`;
  }
}
</script>
</body>
</html>