<?php
include('../koneksi/koneksi.php');

if ((isset($_GET['aksi'])) && (isset($_GET['data']))) {
    if ($_GET['aksi'] == 'hapus') {

        if (!filter_var($_GET['data'], FILTER_VALIDATE_INT)) {
             header("Location: hardskill.php?notif=hapusgagal"); 
             exit;
        }
        $id_master_hard_skill = (int)$_GET['data'];

        mysqli_begin_transaction($koneksi);

        try {
            $sql_dh_detail = "DELETE FROM `hard_skill` WHERE `id_master_hard_skill` = ?";
            $stmt_detail = mysqli_prepare($koneksi, $sql_dh_detail);
            mysqli_stmt_bind_param($stmt_detail, 'i', $id_master_hard_skill);
            mysqli_stmt_execute($stmt_detail);
            mysqli_stmt_close($stmt_detail);

            $sql_dh_master = "DELETE FROM `master_hard_skill` WHERE `id_master_hard_skill` = ?";
            $stmt_master = mysqli_prepare($koneksi, $sql_dh_master);
            mysqli_stmt_bind_param($stmt_master, 'i', $id_master_hard_skill); 
            mysqli_stmt_execute($stmt_master);

            if (mysqli_stmt_affected_rows($stmt_master) > 0) {
                mysqli_commit($koneksi);
                header("Location: hardskill.php?notif=hapusberhasil");
            } else {

                mysqli_rollback($koneksi);
                header("Location: hardskill.php?notif=hapusgagal&msg=notfound"); 
            }
            mysqli_stmt_close($stmt_master);
            exit;

        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($koneksi);
            // Log error jika perlu: error_log("Error deleting hard skill: " . $exception->getMessage());
            header("Location: hardskill.php?notif=hapusgagal");
            exit;
        }
    }
}

// --- Logic Search & Pagination ---
$search_query = "";
if (isset($_GET['katakunci'])) { // Cukup cek isset, empty() tidak perlu jika string kosong diizinkan
    $search_query = mysqli_real_escape_string($koneksi, $_GET['katakunci']); // Tetap escape untuk query LIKE
}

$limit = 10;
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) : 1;
if ($page === false) $page = 1; // Jika filter gagal, default ke halaman 1
$start = ($page - 1) * $limit;
?>

<!DOCTYPE html>
<html>
<head>
<?php include("includes/head.php") ?>
<title>Data Hard Skill</title> <!-- Tambahkan title spesifik -->
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
            <h3><i class="fas fa-cogs"></i> Hard Skill</h3> <!-- Ganti ikon -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"> Hard Skill</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="fas fa-list-ul"></i> Daftar Hard Skill</h3>
                <div class="card-tools">
                  <a href="tambahhardskill.php" class="btn btn-sm btn-info float-right"><i class="fas fa-plus"></i> Tambah Hard Skill</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <div class="col-md-12">
                <form method="GET" action="hardskill.php">
                    <div class="row">
                        <div class="col-md-4 mb-2"> <!-- Ganti bottom-10 jadi mb-2 (Bootstrap margin bottom) -->
                          <input type="text" class="form-control" id="katakunci" name="katakunci" placeholder="Cari hard skill..." value="<?php echo htmlspecialchars($search_query); ?>">
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
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Gagal Dihapus <?php echo isset($_GET['msg']) && $_GET['msg'] == 'notfound' ? '(ID tidak ditemukan)' : ''; ?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <?php } ?>
                <?php } ?>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped"> <!-- Tambah table-striped -->
                  <thead>
                    <tr>
                      <th width="5%" class="text-center">No</th> <!-- text-center -->
                      <th width="80%">Hard Skill</th>
                      <th width="15%"><center>Aksi</center></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                        // Count total records for pagination with prepared statement
                        $count_sql = "SELECT COUNT(*) as total FROM `master_hard_skill`";
                        $params_count = [];
                        $types_count = '';
                        if (!empty($search_query)) {
                            $count_sql .= " WHERE `hard_skill` LIKE ?";
                            $search_param_count = "%" . $search_query . "%";
                            $params_count[] = &$search_param_count; // Pass by reference
                            $types_count .= 's';
                        }

                        $stmt_count = mysqli_prepare($koneksi, $count_sql);
                        if ($stmt_count && !empty($search_query)) {
                           mysqli_stmt_bind_param($stmt_count, $types_count, ...$params_count);
                        }
                        if ($stmt_count) {
                            mysqli_stmt_execute($stmt_count);
                            $result_count = mysqli_stmt_get_result($stmt_count);
                            $row_count = mysqli_fetch_assoc($result_count);
                            $total_records = $row_count['total'];
                            $total_pages = ceil($total_records / $limit);
                            mysqli_stmt_close($stmt_count);
                        } else {
                            // Fallback jika prepare gagal
                            $total_records = 0;
                            $total_pages = 0;
                            echo "<tr><td colspan='3' class='text-center text-danger'>Error menghitung data.</td></tr>";
                        }


                        // Main query for fetching hardskill with prepared statement
                        $sql_data = "SELECT `id_master_hard_skill`, `hard_skill` FROM `master_hard_skill`";
                        $params_data = [];
                        $types_data = '';

                        if (!empty($search_query)) {
                            $sql_data .= " WHERE `hard_skill` LIKE ?";
                            $search_param_data = "%" . $search_query . "%";
                            $params_data[] = &$search_param_data; // Pass by reference
                            $types_data .= 's';
                        }
                        $sql_data .= " ORDER BY `hard_skill` LIMIT ?, ?";
                        $params_data[] = &$start;  // Pass by reference
                        $params_data[] = &$limit;  // Pass by reference
                        $types_data .= 'ii';

                        $stmt_data = mysqli_prepare($koneksi, $sql_data);
                        if ($stmt_data) {
                             // Bind parameter hanya jika ada parameter
                            if (!empty($params_data)) {
                               mysqli_stmt_bind_param($stmt_data, $types_data, ...$params_data);
                            }
                            mysqli_stmt_execute($stmt_data);
                            $result_data = mysqli_stmt_get_result($stmt_data);

                            $no = $start + 1;
                            if (mysqli_num_rows($result_data) > 0) {
                                while ($data_u = mysqli_fetch_assoc($result_data)) {
                                    $id_master_hard_skill = $data_u['id_master_hard_skill'];
                                    $hard_skill_nama = $data_u['hard_skill']; // Ganti nama variabel
                            ?>
                    <tr>
                      <td class="text-center"><?php echo $no; ?></td>
                      <td><?php echo htmlspecialchars($hard_skill_nama); ?></td>
                      <td align="center">
                        <a href="edithardskill.php?data=<?php echo htmlspecialchars($id_master_hard_skill); ?>" class="btn btn-xs btn-info" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-warning" title="Hapus"
                           onclick="konfirmasiHapus('<?php echo htmlspecialchars(addslashes($hard_skill_nama)); ?>', '<?php echo htmlspecialchars($id_master_hard_skill); ?>', '<?php echo urlencode($search_query); ?>', '<?php echo $page; ?>')">
                           <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center'>Data tidak ditemukan</td></tr>";
                            }
                             mysqli_stmt_close($stmt_data); // Tutup statement data
                        } else {
                             echo "<tr><td colspan='3' class='text-center text-danger'>Error mengambil data.</td></tr>";
                        }
                        ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <?php if ($total_records > 0) : // Tampilkan pagination hanya jika ada data ?>
                <ul class="pagination pagination-sm m-0 float-right">
                  <?php
                  $query_string = !empty($search_query) ? '&katakunci='.urlencode($search_query) : '';

                  // Tombol First dan Previous
                  if ($page > 1) {
                      echo "<li class='page-item'><a class='page-link' href='hardskill.php?page=1{$query_string}'>« First</a></li>";
                      echo "<li class='page-item'><a class='page-link' href='hardskill.php?page=".($page - 1)."{$query_string}'>‹ Prev</a></li>";
                  } else {
                      echo "<li class='page-item disabled'><span class='page-link'>« First</span></li>";
                      echo "<li class='page-item disabled'><span class='page-link'>‹ Prev</span></li>";
                  }

                  // Nomor Halaman (Logic sama seperti softskill.php)
                  $num_links = 2; // Jumlah link sebelum dan sesudah halaman aktif
                  $start_loop = max(1, $page - $num_links);
                  $end_loop = min($total_pages, $page + $num_links);

                  if ($start_loop > 1) {
                      echo "<li class='page-item'><a class='page-link' href='hardskill.php?page=1{$query_string}'>1</a></li>";
                      if ($start_loop > 2) {
                          echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                      }
                  }

                  for ($i = $start_loop; $i <= $end_loop; $i++) {
                      if ($i == $page) {
                          echo "<li class='page-item active'><span class='page-link'>{$i}</span></li>";
                      } else {
                          echo "<li class='page-item'><a class='page-link' href='hardskill.php?page={$i}{$query_string}'>{$i}</a></li>";
                      }
                  }

                   if ($end_loop < $total_pages) {
                       if ($end_loop < $total_pages - 1) {
                          echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                      }
                      echo "<li class='page-item'><a class='page-link' href='hardskill.php?page={$total_pages}{$query_string}'>{$total_pages}</a></li>";
                  }

                  // Tombol Next dan Last
                  if ($page < $total_pages) {
                      echo "<li class='page-item'><a class='page-link' href='hardskill.php?page=".($page + 1)."{$query_string}'>Next ›</a></li>";
                      echo "<li class='page-item'><a class='page-link' href='hardskill.php?page={$total_pages}{$query_string}'>Last »</a></li>";
                  } else {
                      echo "<li class='page-item disabled'><span class='page-link'>Next ›</span></li>";
                      echo "<li class='page-item disabled'><span class='page-link'>Last »</span></li>";
                  }
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
// Fungsi konfirmasi hapus yang lebih baik
function konfirmasiHapus(nama, id, katakunci, page) {
  if (confirm(`Anda yakin ingin menghapus data: ${nama}? Data terkait di tabel lain (jika ada) juga akan dihapus.`)) {
    // Arahkan ke URL hapus dengan parameter state (katakunci & page)
    window.location.href = `hardskill.php?aksi=hapus&data=${id}&katakunci=${encodeURIComponent(katakunci)}&page=${page}`;
  }
}
</script>
</body>
</html>