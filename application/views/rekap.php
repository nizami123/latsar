<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 13px;
    }
    th, td {
        border: 1px solid #000;
        padding: 4px;
        text-align: center;
    }
    th {
        background-color: #17365D;
        color: white;
    }

  #populasiDashboard,
  #pemotonganDashboard {
    border-collapse: collapse;
    width: 100%;
    table-layout: auto;
  }

  #populasiDashboard th, 
  #populasiDashboard td,
  #pemotonganDashboard th, 
  #pemotonganDashboard td {
    white-space: nowrap;
    text-align: center;      /* Tengah horizontal */
    vertical-align: middle; 
  }

  #populasiDashboard thead th,
  #pemotonganDashboard thead th {
    position: sticky;
    top: 0;
    background: black;
    color: white;
    z-index: 2;
  }

  #populasiDashboard td:first-child,
  #populasiDashboard th:first-child,
  #pemotonganDashboard td:first-child,
  #pemotonganDashboard th:first-child {
    position: sticky;
    left: 0;
    z-index: 3;
    background: black;
    color: white;
  }
</style>  
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Rekap Data</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Rekap Data</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header bg-secondary text-white d-flex align-items-center">
            <h3 class="card-title mb-0">
              Data Populasi Ternak
            </h3>

            <!-- Filter Bulan Tahun di paling kanan -->
            <div class="form-inline ml-auto">
              <select id="filterBulan" class="form-control form-control-sm mr-2">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>" <?= ($i == $populasiBulan) ? 'selected' : '' ?>>
                    <?= nama_bulan($i) ?>
                  </option>
                <?php endfor; ?>
              </select>

              <select id="filterTahun" class="form-control form-control-sm mr-2">
                <?php 
                  $tahunSekarang = date('Y');
                  for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                  <option value="<?= $t ?>" <?= ($t == $populasiTahun) ? 'selected' : '' ?>>
                    <?= $t ?>
                  </option>
                <?php endfor; ?>
              </select>

              <button id="btnFilter" class="btn btn-sm btn-light">
                <i class="fas fa-search"></i> Tampilkan
              </button>&nbsp;&nbsp;
              <button id="btnFilterExport" class="btn btn-sm btn-light">
                <i class="fas fa-file"></i> Download
              </button>
            </div>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
          <div class="table-responsive">
            <div class="table-responsive">
            <table id="populasiDashboard" class="table table-bordered table-striped table-sm">
              <thead style="background-color: black; color: white;">
                <tr>
                  <th rowspan="3" style="min-width:150px; text-align:center;">Kecamatan</th>

                  <?php
                  $umurListPopulasi = ['Anak', 'Muda', 'Dewasa'];
                  $komoditasBertingkatPopulasi = [];
                  $komoditasAdaUmurPopulasi = [];

                  // Komoditas khusus
                  $khususJkPopulasi = ['Kerbau', 'Kuda'];
                  $paksaUmurPopulasi = ['Sapi Potong', 'Sapi Perah'];

                  // Deteksi otomatis komoditas bertingkat
                  foreach ($populasiKomoditas as $kom) {
                      foreach ($populasiPivot as $kec => $dataKec) {
                          if (isset($dataKec[$kom]) && is_array($dataKec[$kom])) {
                              $first = reset($dataKec[$kom]);
                              if (is_array($first)) {
                                  $komoditasBertingkatPopulasi[] = $kom;

                                  if (in_array($kom, $khususJkPopulasi)) {
                                      $komoditasAdaUmurPopulasi[$kom] = false; // Jantan/Betina saja
                                  } elseif (in_array($kom, $paksaUmurPopulasi)) {
                                      $komoditasAdaUmurPopulasi[$kom] = true; // Paksa 3 tingkat umur
                                  } else {
                                      $hasUmur = false;
                                      foreach (['Jantan','Betina'] as $jk) {
                                          if (isset($first[$jk]) && is_array($first[$jk]) && count($first[$jk]) > 0) {
                                              $hasUmur = true;
                                              break;
                                          }
                                      }
                                      $komoditasAdaUmurPopulasi[$kom] = $hasUmur;
                                  }
                              }
                          }
                      }
                  }
                  $komoditasBertingkatPopulasi = array_unique($komoditasBertingkatPopulasi);
                  ?>

                  <?php foreach ($populasiKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatPopulasi)): ?>
                      <?php if (!empty($komoditasAdaUmurPopulasi[$kom])): ?>
                        <th colspan="7" style="text-align:center;"><?= $kom ?></th>
                      <?php else: ?>
                        <th colspan="3" style="text-align:center;"><?= $kom ?></th>
                      <?php endif; ?>
                    <?php else: ?>
                      <th rowspan="3" style="text-align:center;"><?= $kom ?></th>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tr>

                <tr>
                  <?php foreach ($populasiKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatPopulasi)): ?>
                      <?php if (!empty($komoditasAdaUmurPopulasi[$kom])): ?>
                        <th colspan="3" style="text-align:center;">Jantan</th>
                        <th colspan="3" style="text-align:center;">Betina</th>
                        <th rowspan="2" style="text-align:center; background-color: black;">Total</th>
                      <?php else: ?>
                        <th rowspan="2" style="text-align:center;">Jantan</th>
                        <th rowspan="2" style="text-align:center;">Betina</th>
                        <th rowspan="2" style="text-align:center; background-color: black;">Total</th>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tr>

                <tr>
                  <?php foreach ($populasiKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatPopulasi) && !empty($komoditasAdaUmurPopulasi[$kom])): ?>
                      <?php foreach (['Jantan','Betina'] as $jk): ?>
                        <?php foreach ($umurListPopulasi as $umur): ?>
                          <th style="text-align:center;"><?= $umur ?></th>
                        <?php endforeach; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tr>
              </thead>

              <tbody id="populasiBody">
                <?php
                $totalPerKomoditasPopulasi = [];
                foreach ($populasiKomoditas as $kom) {
                    if (in_array($kom, $komoditasBertingkatPopulasi)) {
                        if (!empty($komoditasAdaUmurPopulasi[$kom])) {
                            foreach (['Jantan','Betina'] as $jk) {
                                foreach ($umurListPopulasi as $umur) {
                                    $totalPerKomoditasPopulasi[$kom][$jk][$umur] = 0;
                                }
                            }
                        } else {
                            foreach (['Jantan','Betina'] as $jk) {
                                $totalPerKomoditasPopulasi[$kom][$jk] = 0;
                            }
                        }
                    } else {
                        $totalPerKomoditasPopulasi[$kom] = 0;
                    }
                }
                ?>

                <?php foreach ($populasiPivot as $kec => $row): ?>
                  <tr>
                    <?php 
                      $status = $row['status'] ?? 0;
                      $bg = $status ? 'black' : 'red';
                      $color = $status ? 'white' : 'white';
                    ?>
                    <td style="background-color:<?= $bg ?>; color:<?= $color ?>;">
                      <?= $kec ?>
                    </td>

                    <?php foreach ($populasiKomoditas as $kom): ?>
                      <?php if (in_array($kom, $komoditasBertingkatPopulasi)): ?>
                        <?php if (!empty($komoditasAdaUmurPopulasi[$kom])): ?>
                          <?php $subtotal = 0; ?>
                          <?php foreach (['Jantan','Betina'] as $jk): ?>
                            <?php foreach ($umurListPopulasi as $umur): ?>
                              <?php
                                $val = isset($row[$kom][$jk][$umur]) ? (int)$row[$kom][$jk][$umur] : 0;
                                $subtotal += $val;
                                $totalPerKomoditasPopulasi[$kom][$jk][$umur] += $val;
                              ?>
                              <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                            <?php endforeach; ?>
                          <?php endforeach; ?>
                          <td style="background-color:<?= $bg ?>;text-align:center; font-weight:bold;"><?= number_format($subtotal) ?></td>
                        <?php else: ?>
                          <?php $subtotal = 0; ?>
                          <?php foreach (['Jantan', 'Betina'] as $jk): ?>
                            <?php
                                $val = 0;
                                if (isset($row[$kom][$jk]) && is_array($row[$kom][$jk])) {
                                    $val = array_sum($row[$kom][$jk]);
                                }
                                $subtotal += $val;
                                $totalPerKomoditasPopulasi[$kom][$jk] += $val;
                            ?>
                            <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                          <?php endforeach; ?>
                          <td style="background-color:<?= $bg ?>;text-align:center; font-weight:bold;"><?= number_format($subtotal) ?></td>
                        <?php endif; ?>
                      <?php else: ?>
                        <?php
                            $val = isset($row[$kom]) ? (int)$row[$kom] : 0;
                            $totalPerKomoditasPopulasi[$kom] += $val;
                        ?>
                        <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>

                <tr style="font-weight:bold; background-color:black; color:white;">
                  <td>Total</td>
                  <?php foreach ($populasiKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatPopulasi)): ?>
                      <?php if (!empty($komoditasAdaUmurPopulasi[$kom])): ?>
                        <?php $totalAll = 0; ?>
                        <?php foreach (['Jantan','Betina'] as $jk): ?>
                          <?php foreach ($umurListPopulasi as $umur): ?>
                            <?php
                              $val = $totalPerKomoditasPopulasi[$kom][$jk][$umur];
                              $totalAll += $val;
                            ?>
                            <td style="text-align:center;"><?= number_format($val) ?></td>
                          <?php endforeach; ?>
                        <?php endforeach; ?>
                        <td style="text-align:center; background-color:black;"><?= number_format($totalAll) ?></td>
                      <?php else: ?>
                        <?php $totalAll = 0; ?>
                        <?php foreach (['Jantan','Betina'] as $jk): ?>
                          <?php
                            $val = $totalPerKomoditasPopulasi[$kom][$jk];
                            $totalAll += $val;
                          ?>
                          <td style="text-align:center;"><?= number_format($val) ?></td>
                        <?php endforeach; ?>
                        <td style="text-align:center; background-color:black;"><?= number_format($totalAll) ?></td>
                      <?php endif; ?>
                    <?php else: ?>
                      <td style="text-align:center;"><?= number_format($totalPerKomoditasPopulasi[$kom]) ?></td>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tr>

              </tbody>
            </table>
          </div>

          </div>

        </div>

        </div>
      </div>
    </section>


    <section class="content">
      <div class="container-fluid">
          <div class="card">

              <div class="card-header bg-secondary text-white d-flex align-items-center">
                  <h3 class="card-title mb-0">
                      Data Pemotongan Ternak   <!-- GANTI JUDUL -->
                  </h3>

                  <div class="form-inline ml-auto">

                      <select id="filterPemotonganBulan" class="form-control form-control-sm mr-2">
                          <?php for ($i = 1; $i <= 12; $i++): ?>
                              <option value="<?= $i ?>" <?= ($i == $pemotonganBulan) ? 'selected' : '' ?>>
                                  <?= nama_bulan($i) ?>
                              </option>
                          <?php endfor; ?>
                      </select>

                      <select id="filterPemotonganTahun" class="form-control form-control-sm mr-2">
                          <?php 
                          $tahunSekarang = date('Y');
                          for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                              <option value="<?= $t ?>" <?= ($t == $pemotonganTahun) ? 'selected' : '' ?>>
                                  <?= $t ?>
                              </option>
                          <?php endfor; ?>
                      </select>

                      <button id="btnPemotonganFilter" class="btn btn-sm btn-light">
                          <i class="fas fa-search"></i> Tampilkan
                      </button>&nbsp;&nbsp;

                      <button id="btnPemotonganFilterExport" class="btn btn-sm btn-light">
                          <i class="fas fa-file"></i> Download
                      </button>

                  </div>

                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                      </button>
                  </div>
              </div>

              <div class="card-body">
                  <div class="table-responsive">

                      <table id="pemotonganDashboard" class="table table-bordered table-striped table-sm">
                          <thead style="background-color: black; color: white;">

                              <tr>
                                  <th rowspan="3" style="min-width:150px; text-align:center;">Kecamatan</th>
                                  <?php
                                  $umurListPemotongan = ['Anak','Muda','Dewasa'];
                                  $komoditasBertingkatPemotongan = [];
                                  $komoditasAdaUmurPemotongan = [];

                                  $khususJkPemotongan = ['Kerbau','Kuda','Sapi Potong','Sapi Perah'];
                                  $paksaUmurPemotongan = [];

                                  foreach ($pemotonganKomoditas as $kom) {
                                      foreach ($pemotonganPivot as $kec => $dataKec) {
                                          if (isset($dataKec[$kom]) && is_array($dataKec[$kom])) {
                                              $first = reset($dataKec[$kom]);
                                              if (is_array($first)) {
                                                  $komoditasBertingkatPemotongan[] = $kom;
                                                  if (in_array($kom, $khususJkPemotongan)) {
                                                      $komoditasAdaUmurPemotongan[$kom] = false;
                                                  } elseif (in_array($kom, $paksaUmurPemotongan)) {
                                                      $komoditasAdaUmurPemotongan[$kom] = true;
                                                  } else {
                                                      $hasUmur = false;
                                                      foreach (['Jantan','Betina'] as $jk) {
                                                          if (isset($first[$jk]) && is_array($first[$jk]) && count($first[$jk]) > 0) {
                                                              $hasUmur = true;
                                                              break;
                                                          }
                                                      }
                                                      $komoditasAdaUmurPemotongan[$kom] = $hasUmur;
                                                  }
                                              }
                                          }
                                      }
                                  }
                                  $komoditasBertingkatPemotongan = array_unique($komoditasBertingkatPemotongan);
                                  ?>

                                  <?php foreach ($pemotonganKomoditas as $kom): ?>
                                      <?php if (in_array($kom, $komoditasBertingkatPemotongan)): ?>
                                          <?php if (!empty($komoditasAdaUmurPemotongan[$kom])): ?>
                                              <th colspan="7" style="text-align:center;"><?= $kom ?></th>
                                          <?php else: ?>
                                              <th colspan="3" style="text-align:center;"><?= $kom ?></th>
                                          <?php endif; ?>
                                      <?php else: ?>
                                          <th rowspan="3" style="text-align:center;"><?= $kom ?></th>
                                      <?php endif; ?>
                                  <?php endforeach; ?>
                              </tr>

                              <tr>
                                  <?php foreach ($pemotonganKomoditas as $kom): ?>
                                      <?php if (in_array($kom, $komoditasBertingkatPemotongan)): ?>
                                          <?php if (!empty($komoditasAdaUmurPemotongan[$kom])): ?>
                                              <th colspan="3" style="text-align:center;">Jantan</th>
                                              <th colspan="3" style="text-align:center;">Betina</th>
                                              <th rowspan="2" style="text-align:center; background-color:black;">Total</th>
                                          <?php else: ?>
                                              <th rowspan="2" style="text-align:center;">Jantan</th>
                                              <th rowspan="2" style="text-align:center;">Betina</th>
                                              <th rowspan="2" style="text-align:center; background-color:black;">Total</th>
                                          <?php endif; ?>
                                      <?php endif; ?>
                                  <?php endforeach; ?>
                              </tr>

                              <tr>
                                  <?php foreach ($pemotonganKomoditas as $kom): ?>
                                      <?php if (in_array($kom, $komoditasBertingkatPemotongan) && !empty($komoditasAdaUmurPemotongan[$kom])): ?>
                                          <?php foreach (['Jantan','Betina'] as $jk): ?>
                                              <?php foreach ($umurListPemotongan as $umur): ?>
                                                  <th style="text-align:center;"><?= $umur ?></th>
                                              <?php endforeach; ?>
                                          <?php endforeach; ?>
                                      <?php endif; ?>
                                  <?php endforeach; ?>
                              </tr>
                          </thead>

                          <tbody id="pemotonganBody">

                              <?php
                              $totalPerKomoditasPemotongan = [];
                              foreach ($pemotonganKomoditas as $kom) {
                                  if (in_array($kom, $komoditasBertingkatPemotongan)) {
                                      if (!empty($komoditasAdaUmurPemotongan[$kom])) {
                                          foreach (['Jantan','Betina'] as $jk) {
                                              foreach ($umurListPemotongan as $umur) {
                                                  $totalPerKomoditasPemotongan[$kom][$jk][$umur] = 0;
                                              }
                                          }
                                      } else {
                                          foreach (['Jantan','Betina'] as $jk) {
                                              $totalPerKomoditasPemotongan[$kom][$jk] = 0;
                                          }
                                      }
                                  } else {
                                      $totalPerKomoditasPemotongan[$kom] = 0;
                                  }
                              }
                              ?>

                              <?php foreach ($pemotonganPivot as $kec => $row): ?>
                                  <tr>
                                      <?php 
                                      $status = $row['status'] ?? 0;
                                      $bg = $status ? 'black' : 'red';
                                      ?>
                                      <td style="background-color:<?= $bg ?>; color:white;"><?= $kec ?></td>

                                      <?php foreach ($pemotonganKomoditas as $kom): ?>
                                          <?php if (in_array($kom, $komoditasBertingkatPemotongan)): ?>

                                              <?php if (!empty($komoditasAdaUmurPemotongan[$kom])): ?>
                                                  <?php $subtotal = 0; ?>
                                                  <?php foreach (['Jantan','Betina'] as $jk): ?>
                                                      <?php foreach ($umurListPemotongan as $umur): ?>
                                                          <?php
                                                          $val = $row[$kom][$jk][$umur] ?? 0;
                                                          $totalPerKomoditasPemotongan[$kom][$jk][$umur] += $val;
                                                          $subtotal += $val;
                                                          ?>
                                                          <td style="background-color:<?= $bg ?>; text-align:center;"><?= number_format($val) ?></td>
                                                      <?php endforeach; ?>
                                                  <?php endforeach; ?>
                                                  <td style="background-color:<?= $bg ?>; text-align:center; font-weight:bold;"><?= number_format($subtotal) ?></td>

                                              <?php else: ?>
                                                  <?php
                                                  $subtotal = 0;
                                                  foreach (['Jantan','Betina'] as $jk):
                                                      $val = array_sum($row[$kom][$jk] ?? []);
                                                      $totalPerKomoditasPemotongan[$kom][$jk] += $val;
                                                      $subtotal += $val;
                                                      ?>
                                                      <td style="background-color:<?= $bg ?>; text-align:center;"><?= number_format($val) ?></td>
                                                  <?php endforeach; ?>
                                                  <td style="background-color:<?= $bg ?>; text-align:center; font-weight:bold;"><?= number_format($subtotal) ?></td>

                                              <?php endif; ?>

                                          <?php else: ?>

                                              <?php
                                              $val = $row[$kom] ?? 0;
                                              $totalPerKomoditasPemotongan[$kom] += $val;
                                              ?>
                                              <td style="background-color:<?= $bg ?>; text-align:center;"><?= number_format($val) ?></td>

                                          <?php endif; ?>
                                      <?php endforeach; ?>

                                  </tr>
                              <?php endforeach; ?>

                              <!-- TOTAL AKHIR -->
                              <tr style="font-weight:bold; background-color:black; color:white;">
                                  <td>Total</td>

                                  <?php foreach ($pemotonganKomoditas as $kom): ?>
                                      <?php if (in_array($kom, $komoditasBertingkatPemotongan)): ?>

                                          <?php if (!empty($komoditasAdaUmurPemotongan[$kom])): ?>
                                              <?php $totalAll = 0; ?>
                                              <?php foreach (['Jantan','Betina'] as $jk): ?>
                                                  <?php foreach ($umurListPemotongan as $umur): ?>
                                                      <?php
                                                      $val = $totalPerKomoditasPemotongan[$kom][$jk][$umur];
                                                      $totalAll += $val;
                                                      ?>
                                                      <td style="text-align:center;"><?= number_format($val) ?></td>
                                                  <?php endforeach; ?>
                                              <?php endforeach; ?>
                                              <td style="text-align:center; background-color:black;"><?= number_format($totalAll) ?></td>

                                          <?php else: ?>
                                              <?php $totalAll = 0; ?>
                                              <?php foreach (['Jantan','Betina'] as $jk): ?>
                                                  <?php
                                                  $val = $totalPerKomoditasPemotongan[$kom][$jk];
                                                  $totalAll += $val;
                                                  ?>
                                                  <td style="text-align:center;"><?= number_format($val) ?></td>
                                              <?php endforeach; ?>
                                              <td style="text-align:center; background-color:black;"><?= number_format($totalAll) ?></td>

                                          <?php endif; ?>

                                      <?php else: ?>
                                          <td style="text-align:center;"><?= number_format($totalPerKomoditasPemotongan[$kom]) ?></td>
                                      <?php endif; ?>
                                  <?php endforeach; ?>

                              </tr>

                          </tbody>
                      </table>

                  </div>
              </div>

          </div>
      </div>
    </section>

  </div>
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
   </body>
</html>
  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="#">Ahmad Alfian Nizami</a></strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?=base_url()?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>assets/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="<?=base_url()?>assets/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<?=base_url()?>assets/plugins/raphael/raphael.min.js"></script>
<script src="<?=base_url()?>assets/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?=base_url()?>assets/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="<?=base_url()?>assets/plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=base_url()?>assets/js/pages/dashboard2.js"></script>

<script>
$(document).ready(function(){

  function getValPopulasi(row, komoditas, jk, umurListPopulasi = null) {
    if (umurListPopulasi) {
        return umurListPopulasi.map(umur => parseInt(row[komoditas]?.[jk]?.[umur] || 0));
    }
    if (row[komoditas]?.[jk] && typeof row[komoditas][jk] === 'object') {
        return [parseInt(Object.values(row[komoditas][jk])[0] || 0)];
    }
    return [parseInt(row[komoditas] || 0)];
  }

  function getValPemotongan(row, komoditas, jk, umurListPemotongan = null) {
    if (umurListPemotongan) {
        return umurListPemotongan.map(umur => parseInt(row[komoditas]?.[jk]?.[umur] || 0));
    }
    if (row[komoditas]?.[jk] && typeof row[komoditas][jk] === 'object') {
        return [parseInt(Object.values(row[komoditas][jk])[0] || 0)];
    }
    return [parseInt(row[komoditas] || 0)];
  }


  const komoditasBertingkatPopulasi = <?= json_encode(array_values($komoditasBertingkatPopulasi)) ?>;
  const komoditasBertingkatPemotongan = <?= json_encode(array_values($komoditasBertingkatPemotongan)) ?>;
  const komoditasAdaUmurPopulasi = <?= json_encode($komoditasAdaUmurPopulasi) ?>;
  const komoditasAdaUmurPemotongan = <?= json_encode($komoditasAdaUmurPemotongan) ?>;
  const umurListPopulasi = <?= json_encode($umurListPopulasi) ?>;
  const umurListPemotongan = <?= json_encode($umurListPemotongan) ?>;

  $('#btnFilterExport').on('click', function() {
    var bulan = document.getElementById('filterBulan').value;
    var tahun = document.getElementById('filterTahun').value;

    // Redirect ke file PHP export, kirim bulan & tahun via GET
    window.location.href = '<?= base_url("rekap/export") ?>?bulan=' + bulan + '&tahun=' + tahun;
  });

  $('#btnPemotonganFilterExport').on('click', function() {
    var bulan = document.getElementById('filterPemotonganBulan').value;
    var tahun = document.getElementById('filterPemotonganTahun').value;

    // Redirect ke file PHP export, kirim bulan & tahun via GET
    window.location.href = '<?= base_url("rekap/export_pemotongan") ?>?bulan=' + bulan + '&tahun=' + tahun;
  });

  $('#btnFilter').on('click', function() {
    let bulan = $('#filterBulan').val();
    let tahun = $('#filterTahun').val();

    $.ajax({
        url: "<?= base_url('rekap/get_data_populasi') ?>",
        type: "GET",
        data: { bulan: bulan, tahun: tahun },
        dataType: "json",
        success: function(res) {
            let tbody = "";
            let totalPerKomoditasPopulasi = {};

            // Inisialisasi total
            res.komoditas.forEach(k => {
                if (komoditasBertingkatPopulasi.includes(k)) {
                    if (komoditasAdaUmurPopulasi[k]) {
                        totalPerKomoditasPopulasi[k] = { 'Jantan': {}, 'Betina': {} };
                        umurListPopulasi.forEach(u => {
                            totalPerKomoditasPopulasi[k]['Jantan'][u] = 0;
                            totalPerKomoditasPopulasi[k]['Betina'][u] = 0;
                        });
                    } else {
                        totalPerKomoditasPopulasi[k] = { 'Jantan': 0, 'Betina': 0 };
                    }
                } else {
                    totalPerKomoditasPopulasi[k] = 0;
                }
            });

            // Isi data per kecamatan
            $.each(res.populasi, function(kec, row) {
                let status = row.status ?? 0;
                let bgColor = status ? 'black' : 'red';
                let textColor = 'white';
                tbody += `<tr><td style="background-color:${bgColor}; color:${textColor}">${kec}</td>`;

                res.komoditas.forEach(k => {
                    if (komoditasBertingkatPopulasi.includes(k)) {
                        if (komoditasAdaUmurPopulasi[k]) {
                            let subtotal = 0;
                            ['Jantan','Betina'].forEach(jk => {
                                umurListPopulasi.forEach(umur => {
                                    let val = parseInt(row[k]?.[jk]?.[umur] || 0);
                                    totalPerKomoditasPopulasi[k][jk][umur] += val;
                                    subtotal += val;
                                    tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                                });
                            });
                            tbody += `<td style="background-color:${bgColor};text-align:center; font-weight:bold;">${Number(subtotal).toLocaleString()}</td>`;
                        } else {
                            let subtotal = 0;
                            ['Jantan','Betina'].forEach(jk => {
                                let val = parseInt(Object.values(row[k]?.[jk] || {0:0})[0]);
                                totalPerKomoditasPopulasi[k][jk] += val;
                                subtotal += val;
                                tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                            });
                            tbody += `<td style="background-color:${bgColor};text-align:center; font-weight:bold;">${Number(subtotal).toLocaleString()}</td>`;
                        }
                    } else {
                        let val = parseInt(row[k] || 0);
                        totalPerKomoditasPopulasi[k] += val;
                        tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                    }
                });

                tbody += `</tr>`;
            });

            // Total per kategori (baris hitam)
            tbody += `<tr style="font-weight:bold; background-color:black; color:white"><td>Total</td>`;
            res.komoditas.forEach(k => {
                if (komoditasBertingkatPopulasi.includes(k)) {
                    if (komoditasAdaUmurPopulasi[k]) {
                        let totalAll = 0;
                        ['Jantan','Betina'].forEach(jk => {
                            umurListPopulasi.forEach(umur => {
                                let val = totalPerKomoditasPopulasi[k][jk][umur];
                                totalAll += val;
                                tbody += `<td style="text-align:center">${Number(val).toLocaleString()}</td>`;
                            });
                        });
                        tbody += `<td style="text-align:center; background-color:black;">${Number(totalAll).toLocaleString()}</td>`;
                    } else {
                        let totalAll = 0;
                        ['Jantan','Betina'].forEach(jk => {
                            let val = totalPerKomoditasPopulasi[k][jk];
                            totalAll += val;
                            tbody += `<td style="text-align:center">${Number(val).toLocaleString()}</td>`;
                        });
                        tbody += `<td style="text-align:center; background-color:black;">${Number(totalAll).toLocaleString()}</td>`;
                    }
                } else {
                    tbody += `<td style="text-align:center">${Number(totalPerKomoditasPopulasi[k]).toLocaleString()}</td>`;
                }
            });
            tbody += `</tr>`;

            $('#populasiBody').html(tbody);
        }
    });
  });

  $('#btnPemotonganFilter').on('click', function() {
      let bulan = $('#filterPemotonganBulan').val();
      let tahun = $('#filterPemotonganTahun').val();

      $.ajax({
          url: "<?= base_url('rekap/get_data_pemotongan') ?>", // GANTI ENDPOINT
          type: "GET",
          data: { bulan: bulan, tahun: tahun },
          dataType: "json",
          success: function(res) {
              let tbody = "";
              let totalPerKomoditasPemotongan = {};
            console.log(res.komoditas);
              // Inisialisasi total
              res.komoditas.forEach(k => {
                  if (komoditasBertingkatPemotongan.includes(k)) {
                      if (komoditasAdaUmurPemotongan[k]) {
                          totalPerKomoditasPemotongan[k] = { 'Jantan': {}, 'Betina': {} };
                          umurListPemotongan.forEach(u => {
                              totalPerKomoditasPemotongan[k]['Jantan'][u] = 0;
                              totalPerKomoditasPemotongan[k]['Betina'][u] = 0;
                          });
                      } else {
                          totalPerKomoditasPemotongan[k] = { 'Jantan': 0, 'Betina': 0 };
                      }
                  } else {
                      totalPerKomoditasPemotongan[k] = 0;
                  }
              });

              $.each(res.pemotongan, function(kec, row) { // GANTI res.populasi → res.pemotongan
                  let status = row.status ?? 0;
                  let bgColor = status ? 'black' : 'red';
                  let textColor = 'white';

                  tbody += `<tr>
                      <td style="background-color:${bgColor}; color:${textColor}">
                          ${kec}
                      </td>`;

                  res.komoditas.forEach(k => {
                      if (komoditasBertingkatPemotongan.includes(k)) {
                          if (komoditasAdaUmurPemotongan[k]) {
                              let subtotal = 0;
                              ['Jantan','Betina'].forEach(jk => {
                                  umurListPemotongan.forEach(umur => {
                                      let val = parseInt(row[k]?.[jk]?.[umur] || 0);
                                      totalPerKomoditasPemotongan[k][jk][umur] += val;
                                      subtotal += val;

                                      tbody += `<td style="background-color:${bgColor}; text-align:center">
                                          ${Number(val).toLocaleString()}
                                      </td>`;
                                  });
                              });

                              tbody += `<td style="background-color:${bgColor}; text-align:center; font-weight:bold;">
                                  ${Number(subtotal).toLocaleString()}
                              </td>`;

                          } else {
                              let subtotal = 0;
                              ['Jantan','Betina'].forEach(jk => {
                                  let val = parseInt(Object.values(row[k]?.[jk] || {0:0})[0]);
                                  totalPerKomoditasPemotongan[k][jk] += val;
                                  subtotal += val;

                                  tbody += `<td style="background-color:${bgColor}; text-align:center">
                                      ${Number(val).toLocaleString()}
                                  </td>`;
                              });

                              tbody += `<td style="background-color:${bgColor}; text-align:center; font-weight:bold;">
                                  ${Number(subtotal).toLocaleString()}
                              </td>`;
                          }

                      } else {
                          let val = parseInt(row[k] || 0);
                          totalPerKomoditasPemotongan[k] += val;

                          tbody += `<td style="background-color:${bgColor}; text-align:center">
                              ${Number(val).toLocaleString()}
                          </td>`;
                      }
                  });

                  tbody += `</tr>`;
              });

              tbody += `<tr style="font-weight:bold; background-color:black; color:white">
                  <td>Total</td>`;

              res.komoditas.forEach(k => {
                  if (komoditasBertingkatPopulasi.includes(k)) {
                      let totalAll = 0;
                      ['Jantan','Betina'].forEach(jk => {
                          // Ambil nilai langsung, tidak pakai umurList
                          let val = totalPerKomoditasPemotongan[k][jk] || 0;
                          totalAll += val;

                          tbody += `
                              <td style="text-align:center">
                                  ${Number(val).toLocaleString()}
                              </td>`;
                      });
                      tbody += `
                          <td style="text-align:center; background-color:black;">
                              ${Number(totalAll).toLocaleString()}
                          </td>`;
                  } else {
                      // Komoditas biasa
                      let val = totalPerKomoditasPemotongan[k] || 0;
                      tbody += `
                          <td style="text-align:center">
                              ${Number(val).toLocaleString()}
                          </td>`;
                  }
              });
              tbody += `</tr>`;

              $('#pemotonganBody').html(tbody); // GANTI ID
          }
      });
  });

});
</script>
</body>
</html>

  