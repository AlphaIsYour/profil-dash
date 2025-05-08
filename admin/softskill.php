<?php
include('../koneksi/koneksi.php');

if ((isset($_GET['aksi'])) && (isset($_GET['data']))) {
    if ($_GET['aksi'] == 'hapus') {
        $id_master_soft_skill = mysqli_real_escape_string($koneksi, $_GET['data']);

        mysqli_begin_transaction($koneksi);

        try {
            $sql_dh_detail = "DELETE FROM `soft_skill` WHERE `id_master_soft_skill` = ?";
            $stmt_detail = mysqli_prepare($koneksi, $sql_dh_detail);
            mysqli_stmt_bind_param($stmt_detail, 'i', $id_master_soft_skill);
            mysqli_stmt_execute($stmt_detail);
            mysqli_stmt_close($stmt_detail);

            $sql_dh_master = "DELETE FROM `master_soft_skill` WHERE `id_master_soft_skill` = ?";
            $stmt_master = mysqli_prepare($koneksi, $sql_dh_master);
            mysqli_stmt_bind_param($stmt_master, 'i', $id_master_soft_skill);
            mysqli_stmt_execute($stmt_master);
            mysqli_stmt_close($stmt_master);

            mysqli_commit($koneksi);
            header("Location: softskill.php?notif=hapusberhasil");
            exit;

        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($koneksi);
            header("Location: softskill.php?notif=hapusgagal");
            exit;
        }
    }
}

$search_query = "";
if (isset($_GET['katakunci']) && !empty($_GET['katakunci'])) {
    $search_query = mysqli_real_escape_string($koneksi, $_GET['katakunci']);
}

$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
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
            <h3><i class="fas fa-puzzle-piece"></i> Soft Skill</h3> <!-- Icon diganti agar lebih relevan -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"> Soft Skill</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="margin-top:5px;"><i class="fas fa-list-ul"></i> Daftar Soft Skill</h3>
                <div class="card-tools">
                  <a href="tambahsoftskill.php" class="btn btn-sm btn-info float-right"><i class="fas fa-plus"></i> Tambah Soft Skill</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <div class="col-md-12">
              <form method="GET" action="softskill.php">
                    <div class="row">
                        <div class="col-md-4 mb-2"> <!-- mb-2 untuk margin bawah -->
                          <input type="text" class="form-control" id="katakunci" name="katakunci" placeholder="Cari soft skill..." value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <div class="col-md-5 mb-2"> <!-- mb-2 -->
                          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>  Cari</button>
                        </div>
                    </div><!-- .row -->
                  </form>
                </div><br>
              <div class="col-sm-12">
                <?php if (!empty($_GET['notif'])) { ?>
                    <?php if ($_GET['notif'] == "tambahberhasil") { ?>
                    <div class="alert alert-success" role="alert"> Data Berhasil Ditambahkan</div>
                    <?php } else if ($_GET['notif'] == "editberhasil") { ?>
                    <div class="alert alert-success" role="alert"> Data Berhasil Diubah</div>
                    <?php } else if ($_GET['notif'] == "hapusberhasil") { ?>
                    <div class="alert alert-success" role="alert"> Data Berhasil Dihapus</div>
                    <?php } else if ($_GET['notif'] == "hapusgagal") { ?> <!-- Tambahan notif gagal -->
                    <div class="alert alert-danger" role="alert"> Data Gagal Dihapus</div>
                    <?php } ?>
                <?php } ?>
              </div>

              <div class="table-responsive"> <!-- Tambahkan wrapper untuk responsivitas tabel -->
                <table class="table table-bordered table-striped"> <!-- Tambah class table-striped -->
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th width="80%">Soft Skill</th>
                      <th width="15%"><center>Aksi</center></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                        // Count total records for pagination
                        $count_sql = "SELECT COUNT(*) as total FROM `master_soft_skill`";
                        $params_count = [];
                        $types_count = '';
                        if (!empty($search_query)) {
                            $count_sql .= " WHERE `soft_skill` LIKE ?";
                            $search_param = "%" . $search_query . "%";
                            $params_count[] = &$search_param; // Pass by reference
                            $types_count .= 's';
                        }

                        $stmt_count = mysqli_prepare($koneksi, $count_sql);
                        if (!empty($search_query)) {
                           mysqli_stmt_bind_param($stmt_count, $types_count, ...$params_count);
                        }
                        mysqli_stmt_execute($stmt_count);
                        $result_count = mysqli_stmt_get_result($stmt_count);
                        $row_count = mysqli_fetch_assoc($result_count);
                        $total_records = $row_count['total'];
                        $total_pages = ceil($total_records / $limit);
                        mysqli_stmt_close($stmt_count);


                        // Main query for fetching softskill
                        $sql_u = "SELECT `id_master_soft_skill`, `soft_skill` FROM `master_soft_skill`";
                        $params_data = [];
                        $types_data = '';

                        if (!empty($search_query)) {
                            $sql_u .= " WHERE `soft_skill` LIKE ?";
                            $search_param_data = "%" . $search_query . "%";
                            $params_data[] = &$search_param_data; // Pass by reference
                            $types_data .= 's';
                        }
                        $sql_u .= " ORDER BY `soft_skill` LIMIT ?, ?";
                        $params_data[] = &$start;  // Pass by reference
                        $params_data[] = &$limit;  // Pass by reference
                        $types_data .= 'ii';

                        $stmt_data = mysqli_prepare($koneksi, $sql_u);
                        // Periksa apakah statement berhasil diprepare
                         if ($stmt_data === false) {
                            die("Error preparing statement: " . mysqli_error($koneksi)); // Tampilkan error jika gagal prepare
                        }

                        // Bind parameter hanya jika ada parameter
                        if (!empty($params_data)) {
                           mysqli_stmt_bind_param($stmt_data, $types_data, ...$params_data);
                        }

                        mysqli_stmt_execute($stmt_data);
                        $result_data = mysqli_stmt_get_result($stmt_data);

                        $no = $start + 1;
                        if (mysqli_num_rows($result_data) > 0) {
                            while ($data_u = mysqli_fetch_assoc($result_data)) {
                                $id_master_soft_skill = $data_u['id_master_soft_skill'];
                                $soft_skill_nama = $data_u['soft_skill']; // Ganti nama variabel agar tidak bentrok
                        ?>
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo htmlspecialchars($soft_skill_nama); ?></td>
                      <td align="center">
                        <a href="editsoftskill.php?data=<?php echo htmlspecialchars($id_master_soft_skill); ?>" class="btn btn-xs btn-info" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-warning" title="Hapus"
                           onclick="if(confirm('Anda yakin ingin menghapus data: <?php echo htmlspecialchars(addslashes($soft_skill_nama)); ?>? Data terkait di tabel lain (jika ada) juga akan dihapus.')) window.location.href = 'softskill.php?aksi=hapus&data=<?php echo htmlspecialchars($id_master_soft_skill); ?>&katakunci=<?php echo urlencode($search_query); ?>&page=<?php echo $page; ?>'">
                           <i class="fas fa-trash"></i>
                        </a>
                        <!-- Tambahkan katakunci dan page ke URL hapus agar kembali ke halaman/filter yang sama -->
                      </td>
                    </tr>
                    <?php
                                $no++;
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        <?php
                        }
                        mysqli_stmt_close($stmt_data); // Tutup statement data
                        ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <?php
                  $query_string = !empty($search_query) ? '&katakunci='.urlencode($search_query) : '';

                  // Tombol First dan Previous
                  if ($page > 1) {
                      echo "<li class='page-item'><a class='page-link' href='softskill.php?page=1{$query_string}'>« First</a></li>";
                      echo "<li class='page-item'><a class='page-link' href='softskill.php?page=".($page - 1)."{$query_string}'>‹ Prev</a></li>";
                  } else {
                      echo "<li class='page-item disabled'><span class='page-link'>« First</span></li>";
                      echo "<li class='page-item disabled'><span class='page-link'>‹ Prev</span></li>";
                  }

                  // Nomor Halaman
                  $num_links = 3; // Jumlah link nomor halaman di sekitar halaman aktif
                  $start_loop = max(1, $page - $num_links);
                  $end_loop = min($total_pages, $page + $num_links);

                  if ($start_loop > 1) {
                      echo "<li class='page-item'><a class='page-link' href='softskill.php?page=1{$query_string}'>1</a></li>";
                      if ($start_loop > 2) {
                          echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                      }
                  }

                  for ($i = $start_loop; $i <= $end_loop; $i++) {
                      if ($i == $page) {
                          echo "<li class='page-item active'><span class='page-link'>{$i}</span></li>";
                      } else {
                          echo "<li class='page-item'><a class='page-link' href='softskill.php?page={$i}{$query_string}'>{$i}</a></li>";
                      }
                  }

                  if ($end_loop < $total_pages) {
                       if ($end_loop < $total_pages - 1) {
                          echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                      }
                      echo "<li class='page-item'><a class='page-link' href='softskill.php?page={$total_pages}{$query_string}'>{$total_pages}</a></li>";
                  }


                  // Tombol Next dan Last
                  if ($page < $total_pages) {
                      echo "<li class='page-item'><a class='page-link' href='softskill.php?page=".($page + 1)."{$query_string}'>Next ›</a></li>";
                      echo "<li class='page-item'><a class='page-link' href='softskill.php?page={$total_pages}{$query_string}'>Last »</a></li>";
                  } else {
                      echo "<li class='page-item disabled'><span class='page-link'>Next ›</span></li>";
                      echo "<li class='page-item disabled'><span class='page-link'>Last »</span></li>";
                  }
                  ?>
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