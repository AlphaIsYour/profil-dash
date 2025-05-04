<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Website Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link href="css/style.css" rel="stylesheet" />
  </head>
  <body>
    <nav class="navbar  navbar-expand-lg pt-4 pb-4" style="border-bottom: 2px solid blue">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/logo.png" alt="AlphaBeta" height="50" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ps-lg-4">
                  <li class="nav-item">
                    <a class="nav-link" href="#">Beranda</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#profil">Profil</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#pendidikan">Pendidikan dan Pekerjaan</a>
                  </li>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                  </li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="profil">
        <div class="container pt-4 pb-4">
            <div class="row align-items-center">
                <div id="profil-content" class="col-md-8 col-xs-12 float-sm-end">
                  <h3 class="text-white">Hai, Nama saya Vokasioner</h3>
                  <p class="fs-2">Saya adalah seorang profesional dengan pengalaman di bidang TI.
                    Saya memiliki berbagai keterampilan baik soft skill maupun hard skill yang mendukung karir saya.</p>
                </div>
                <div id="profil-img" class="col-md-4 col-xs-12 float-sm-start">
                  <img class="img-fluid" src="images/images.png">
                </div>
            </div>
        </div>
    </section>
    <section id="pendidikan">
        <div class="container border-bottom border-dark pt-3 py-5 my-5">
            <div class="row">
              <div class="col-md-8 col-xs-12">
                <h4>Riwayat Pendidikan</h4>
                <div class="table-responsive mb-4">
                  <table class="table table-bordered border-dark">
                    <thead>
                    <tr>
                        <th rowspan="2">Tahun</th>
                        <th colspan="2">Pendidikan</th>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <th>Universitas</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2015-2019</td>
                            <td>S1 Teknik Informatika</td>
                            <td>Universitas Brawijaya</td>
                        </tr>
                        <tr>
                            <td>2019-2023</td>
                            <td>S2 Sistem Informasi</td>
                            <td>Universitas Brawijaya</td>
                        </tr>
                    </tbody>
                  </table>
                </div>
    
                <h4>Riwayat Pekerjaan</h4>
                <div class="table-responsive">
                  <table class="table table-bordered border-dark">
                    <thead>
                      <tr>
                          <th>Tahun</th>
                          <th>Posisi</th>
                          <th>Perusahaan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>2019-2021</td>
                          <td>Junior Web Developer</td>
                          <td>XYZ Corp</td>
                      </tr>
                      <tr>
                          <td>2021-2023</td>
                          <td>Senior Web Developer</td>
                          <td>ABC Ltd.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <aside class="p-4">
                  <h4>Soft Skills</h4>
                  <ol>
                      <li>Komunikasi</li>
                      <li>Kepemimpinan</li>
                      <li>Manajemen Waktu</li>
                      <li>Kerja Tim</li>
                  </ol>
          
                  <h4>Hard Skills</h4>
                  <ul>
                      <li>HTML, CSS, JavaScript</li>
                      <li>PHP, Python</li>
                      <li>SQL, NoSQL</li>
                      <li>Framework Laravel, Django</li>
                  </ul>
                </aside>
              </div>
            </div>
        </div>
    </section>
    <section id="kontak" class="pt-3">
        <div class="container">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <h3>Kontak Kami</h3>
                <div id="form-kontak">
                  <form>
                    <div class="row mb-3">
                      <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="email" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="email">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="topik" class="col-sm-2 col-form-label">Topik</label>
                      <div class="col-sm-10">
                        <select class="form-select" id="topik">
                          <option selected>Pilih Topik</option>
                          <option value="1">Saran</option>
                          <option value="2">Kritik</option>
                        </select>
                      </div>
                    </div>
                    </div>
                    <div class="row mb-3">
                      <label for="saran" class="col-sm-2 col-form-label">Pesan</label>
                      <div class="col-sm-10">
                        <textarea rows="5" class="form-control" id="saran"></textarea>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                  </form>
                </div>
              </div>
            </div>
        </div>
    </section>
    <footer class="mt-5">
        <div class="container py-4">
            <div class="row">
              <div class="col text-center text-white">&copy; 2025 WebQ. Semua hak cipta dilindungi.</div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>