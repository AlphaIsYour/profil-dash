<?php
include('../koneksi/koneksi.php'); // Sesuaikan path

// --- Logic Hapus ---
if ((isset($_GET['aksi'])) && (isset($_GET['data']))) {
    if ($_GET['aksi'] == 'hapus') {
        // Validasi ID
        if (!filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
             header("Location: kontak.php?notif=hapusgagal&msg=invalidid");
             exit;
        }
        $id_kontak = (int)$_GET['data'];

        // Hapus data kontak
        $sql_delete = "DELETE FROM `kontak` WHERE `id_kontak` = ?";
        $stmt_delete = mysqli_prepare($koneksi, $sql_delete);

        if ($stmt_delete) {
            mysqli_stmt_bind_param($stmt_delete, 'i', $id_kontak);
            mysqli_stmt_execute($stmt_delete);

            if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                header("Location: kontak.php?notif=hapusberhasil");
            } else {
                // ID tidak ditemukan atau error lain
                header("Location: kontak.php?notif=hapusgagal&msg=notfound");
            }
            mysqli_stmt_close($stmt_delete);
        } else {
            // Gagal prepare
            header("Location: kontak.php?notif=hapusgagal&msg=prepare");
        }
        exit;
    }
}

// --- Logic Search & Pagination ---
$search_query = "";
if (isset($_GET['katakunci'])) {
    $search_query = mysqli_real_escape_string($koneksi, $_GET['katakunci']);
}

$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) : 1;
if ($page === false) $page = 1;
$start = ($page - 1) * $limit;

?>
<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Daftar Kontak Masuk</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<?php include("includes/header.php") ?>
<?php include("includes/sidebar.php") ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3><i class="fas fa-envelope"></i> Kontak Masuk</h3> <!-- Icon ganti -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"> Kontak</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="fas fa-list-ul"></i> Daftar Kontak</h3>
                <div class="card-tools">
                  <!-- Tidak ada tombol tambah di sini -->
                </div>
              </div>
              <div class="card-body">
              <div class="col-md-12">
                  <form method="GET" action="kontak.php"> <!-- Method GET -->
                    <div class="row">
                        <div class="col-md-4 mb-2">
                           <!-- Name katakunci -->
                          <input type="text" class="form-control" id="katakunci" name="katakunci" placeholder="Cari Nama/Email/Pesan..." value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <div class="col-md-5 mb-2">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>  Cari</button>
                        </div>
                    </div>
                  </form>
                </div><br>
              <div class="col-sm-12">
                 <?php if (!empty($_GET['notif'])) { ?>
                      <?php if ($_GET['notif'] == "hapusberhasil") { ?>
                      <div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Dihapus <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                       <?php } else if ($_GET['notif'] == "hapusgagal") { ?>
                      <div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Gagal Dihapus <?php echo (isset($_GET['msg']) && $_GET['msg'] == 'notfound') ? '(Data tidak ditemukan)' : ''; ?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                      <?php } ?>
                  <?php } ?>
                  <!-- Notif tambah/edit dihapus karena tidak relevan -->
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">Nama</th>
                        <th width="15%">Email</th>
                        <th width="10%">Topik</th>
                        <th width="40%">Pesan</th>
                        <th width="15%"><center>Aksi</center></th>
                      </tr>
                    </thead>
                    <tbody>
                       <?php
                            // Query count dengan search
                            $count_sql = "SELECT COUNT(k.`id_kontak`) as total
                                          FROM `kontak` k
                                          LEFT JOIN `master_topik` mt ON k.`id_topik` = mt.`id_master_topik`";
                            $params_count = []; $types_count = '';
                            if (!empty($search_query)) {
                                // Cari di nama, email, atau pesan
                                $count_sql .= " WHERE k.`nama` LIKE ? OR k.`email` LIKE ? OR k.`pesan` LIKE ?";
                                $search_param_like = "%" . $search_query . "%";
                                $params_count[] = &$search_param_like;
                                $params_count[] = &$search_param_like;
                                $params_count[] = &$search_param_like;
                                $types_count .= 'sss';
                            }

                            $total_records = 0; $total_pages = 0;
                            $stmt_count = mysqli_prepare($koneksi, $count_sql);
                            if($stmt_count){
                                if(!empty($search_query)){ mysqli_stmt_bind_param($stmt_count, $types_count, ...$params_count); }
                                mysqli_stmt_execute($stmt_count);
                                $result_count = mysqli_stmt_get_result($stmt_count);
                                if($result_count){ $row_count = mysqli_fetch_assoc($result_count); $total_records = $row_count['total']; $total_pages = ceil($total_records / $limit); }
                                mysqli_stmt_close($stmt_count);
                            } else { echo "<tr><td colspan='6' class='text-center text-danger'>Error menghitung data kontak.</td></tr>"; }


                            // Query data dengan JOIN dan search
                            $sql_data = "SELECT k.`id_kontak`, k.`nama`, k.`email`, mt.`topik`, k.`pesan`
                                         FROM `kontak` k
                                         LEFT JOIN `master_topik` mt ON k.`id_topik` = mt.`id_master_topik`";
                            $params_data = []; $types_data = '';
                            if (!empty($search_query)) {
                                $sql_data .= " WHERE k.`nama` LIKE ? OR k.`email` LIKE ? OR k.`pesan` LIKE ?";
                                // Parameter sama dengan count
                                $params_data[] = &$search_param_like; $params_data[] = &$search_param_like; $params_data[] = &$search_param_like; $types_data .= 'sss';
                            }
                            $sql_data .= " ORDER BY k.`id_kontak` DESC LIMIT ?, ?"; // Order by ID terbaru
                            $params_data[] = &$start; $params_data[] = &$limit; $types_data .= 'ii';

                            $stmt_data = mysqli_prepare($koneksi, $sql_data);
                            if ($stmt_data) {
                                if (!empty($params_data)) { mysqli_stmt_bind_param($stmt_data, $types_data, ...$params_data); }
                                mysqli_stmt_execute($stmt_data);
                                $result_data = mysqli_stmt_get_result($stmt_data);

                                if ($result_data && mysqli_num_rows($result_data) > 0) {
                                    $no = $start + 1;
                                    while ($data_k = mysqli_fetch_assoc($result_data)) {
                                        $id_k = $data_k['id_kontak'];
                                        $nama_k = $data_k['nama'];
                                        $email_k = $data_k['email'];
                                        $topik_k = $data_k['topik'] ?? '<span class="text-muted">N/A</span>'; // Handle jika topik null/hilang
                                        $pesan_k = $data_k['pesan'];
                                        // Potong pesan jika terlalu panjang untuk tampilan tabel
                                        $pesan_tampil = strlen($pesan_k) > 100 ? substr($pesan_k, 0, 100) . "..." : $pesan_k;
                            ?>
                      <tr>
                        <td class="text-center"><?php echo $no; ?></td>
                        <td><?php echo htmlspecialchars($nama_k); ?></td>
                        <td><?php echo htmlspecialchars($email_k); ?></td>
                        <td><?php echo $topik_k; // Sudah dihandle null ?></td>
                        <td title="<?php echo htmlspecialchars($pesan_k); // Full pesan di title ?>">
                            <?php echo nl2br(htmlspecialchars($pesan_tampil)); // Tampilkan potongan pesan, nl2br untuk baris baru ?>
                        </td>
                        <td align="center">
                          <!-- Hanya tombol Hapus -->
                           <a href="javascript:void(0);" class="btn btn-xs btn-warning" title="Hapus" onclick="konfirmasiHapusKontak('Pesan dari <?php echo htmlspecialchars(addslashes($nama_k)); ?>', '<?php echo $id_k; ?>', '<?php echo urlencode($search_query); ?>', '<?php echo $page; ?>')">
                             <i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                      <?php
                                    $no++;
                                    } // End while
                                } else { echo "<tr><td colspan='6' class='text-center'>" . ($total_records > 0 ? "Tidak ada data di halaman ini." : "Belum ada pesan kontak masuk.") . "</td></tr>"; }
                                mysqli_stmt_close($stmt_data);
                            } else { echo "<tr><td colspan='6' class='text-center text-danger'>Error mengambil data kontak.</td></tr>"; }
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
                      if ($page > 1) { echo "<li class='page-item'><a class='page-link' href='kontak.php?page=1{$query_string}'>« First</a></li>"; echo "<li class='page-item'><a class='page-link' href='kontak.php?page=".($page - 1)."{$query_string}'>‹ Prev</a></li>"; } else { echo "<li class='page-item disabled'><span class='page-link'>« First</span></li>"; echo "<li class='page-item disabled'><span class='page-link'>‹ Prev</span></li>"; }
                      $num_links = 2; $start_loop = max(1, $page - $num_links); $end_loop = min($total_pages, $page + $num_links);
                      if ($start_loop > 1) { echo "<li class='page-item'><a class='page-link' href='kontak.php?page=1{$query_string}'>1</a></li>"; if ($start_loop > 2) { echo "<li class='page-item disabled'><span class='page-link'>...</span></li>"; } }
                      for ($i = $start_loop; $i <= $end_loop; $i++) { echo "<li class='page-item ".($i == $page ? 'active' : '')."'><a class='page-link' href='kontak.php?page={$i}{$query_string}'>{$i}</a></li>"; }
                      if ($end_loop < $total_pages) { if ($end_loop < $total_pages - 1) { echo "<li class='page-item disabled'><span class='page-link'>...</span></li>"; } echo "<li class='page-item'><a class='page-link' href='kontak.php?page={$total_pages}{$query_string}'>{$total_pages}</a></li>"; }
                      if ($page < $total_pages) { echo "<li class='page-item'><a class='page-link' href='kontak.php?page=".($page + 1)."{$query_string}'>Next ›</a></li>"; echo "<li class='page-item'><a class='page-link' href='kontak.php?page={$total_pages}{$query_string}'>Last »</a></li>"; } else { echo "<li class='page-item disabled'><span class='page-link'>Next ›</span></li>"; echo "<li class='page-item disabled'><span class='page-link'>Last »</span></li>"; }
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
function konfirmasiHapusKontak(deskripsi, id, katakunci, page) {
  if(id && !isNaN(id)){
      if (confirm(`Anda yakin ingin menghapus ${deskripsi}?`)) {
        window.location.href = `kontak.php?aksi=hapus&data=${id}&katakunci=${encodeURIComponent(katakunci)}&page=${page}`;
      }
  } else {
       alert('Error: ID data kontak tidak valid.');
  }
}
</script>
</body>
</html>