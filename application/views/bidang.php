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

  #bidangDashboard {
    border-collapse: collapse;
    width: 100%;
    table-layout: auto;
  }

  #bidangDashboard th, 
  #bidangDashboard td {
    white-space: nowrap;
    text-align: center;      /* Tengah horizontal */
    vertical-align: middle; 
  }

  #bidangDashboard thead th {
    position: sticky;
    top: 0;
    background: black;
    color: white;
    z-index: 2;
  }

  #bidangDashboard td:first-child,
  #bidangDashboard th:first-child {
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
            <h1 class="m-0">Rekap <?=$tabel?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Rekap <?=$tabel?></li>
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
              Data <?=$tabel?> Ternak
            </h3>

            <!-- Filter Bulan Tahun di paling kanan -->
            <div class="form-inline ml-auto">
              <select id="filterBulan" class="form-control form-control-sm mr-2">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>" <?= ($i == $bidangBulan) ? 'selected' : '' ?>>
                    <?= nama_bulan($i) ?>
                  </option>
                <?php endfor; ?>
              </select>

              <select id="filterTahun" class="form-control form-control-sm mr-2">
                <?php 
                  $tahunSekarang = date('Y');
                  for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                  <option value="<?= $t ?>" <?= ($t == $bidangTahun) ? 'selected' : '' ?>>
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
            <table id="bidangDashboard" class="table table-bordered table-striped table-sm">
              <thead style="background-color: black; color: white;">
                <tr>
                  <th rowspan="3" style="min-width:150px; text-align:center;">Kecamatan</th>

                  <?php
                  $umurListBidang = ['Anak', 'Muda', 'Dewasa'];
                  $komoditasBertingkatBidang = [];
                  $komoditasAdaUmurBidang = [];

                  // Komoditas khusus
                  $khususJkBidang = ['Kerbau', 'Kuda'];
                  $paksaUmurBidang = ['Sapi Potong', 'Sapi Perah'];

                  // Deteksi otomatis komoditas bertingkat
                  foreach ($bidangKomoditas as $kom) {
                      foreach ($bidangPivot as $kec => $dataKec) {
                          if (isset($dataKec[$kom]) && is_array($dataKec[$kom])) {
                              $first = reset($dataKec[$kom]);
                              if (is_array($first)) {
                                  $komoditasBertingkatBidang[] = $kom;

                                  if (in_array($kom, $khususJkBidang)) {
                                      $komoditasAdaUmurBidang[$kom] = false; // Jantan/Betina saja
                                  } elseif (in_array($kom, $paksaUmurBidang)) {
                                      $komoditasAdaUmurBidang[$kom] = true; // Paksa 3 tingkat umur
                                  } else {
                                      $hasUmur = false;
                                      foreach (['Jantan','Betina'] as $jk) {
                                          if (isset($first[$jk]) && is_array($first[$jk]) && count($first[$jk]) > 0) {
                                              $hasUmur = true;
                                              break;
                                          }
                                      }
                                      $komoditasAdaUmurBidang[$kom] = $hasUmur;
                                  }
                              }
                          }
                      }
                  }
                  $komoditasBertingkatBidang = array_unique($komoditasBertingkatBidang);
                  ?>

                  <?php foreach ($bidangKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatBidang)): ?>
                      <?php if (!empty($komoditasAdaUmurBidang[$kom])): ?>
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
                  <?php foreach ($bidangKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatBidang)): ?>
                      <?php if (!empty($komoditasAdaUmurBidang[$kom])): ?>
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
                  <?php foreach ($bidangKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatBidang) && !empty($komoditasAdaUmurBidang[$kom])): ?>
                      <?php foreach (['Jantan','Betina'] as $jk): ?>
                        <?php foreach ($umurListBidang as $umur): ?>
                          <th style="text-align:center;"><?= $umur ?></th>
                        <?php endforeach; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tr>
              </thead>

              <tbody id="bidangBody">
                <?php
                $totalPerKomoditasBidang = [];
                foreach ($bidangKomoditas as $kom) {
                    if (in_array($kom, $komoditasBertingkatBidang)) {
                        if (!empty($komoditasAdaUmurBidang[$kom])) {
                            foreach (['Jantan','Betina'] as $jk) {
                                foreach ($umurListBidang as $umur) {
                                    $totalPerKomoditasBidang[$kom][$jk][$umur] = 0;
                                }
                            }
                        } else {
                            foreach (['Jantan','Betina'] as $jk) {
                                $totalPerKomoditasBidang[$kom][$jk] = 0;
                            }
                        }
                    } else {
                        $totalPerKomoditasBidang[$kom] = 0;
                    }
                }
                ?>

                <?php foreach ($bidangPivot as $kec => $row): ?>
                  <tr>
                    <?php 
                      $status = $row['status'] ?? 0;
                      $bg = $status ? 'black' : 'red';
                      $color = $status ? 'white' : 'white';
                    ?>
                    <td style="background-color:<?= $bg ?>; color:<?= $color ?>;">
                      <?= $kec ?>
                    </td>

                    <?php foreach ($bidangKomoditas as $kom): ?>
                      <?php if (in_array($kom, $komoditasBertingkatBidang)): ?>
                        <?php if (!empty($komoditasAdaUmurBidang[$kom])): ?>
                          <?php $subtotal = 0; ?>
                          <?php foreach (['Jantan','Betina'] as $jk): ?>
                            <?php foreach ($umurListBidang as $umur): ?>
                              <?php
                                $val = isset($row[$kom][$jk][$umur]) ? (int)$row[$kom][$jk][$umur] : 0;
                                $subtotal += $val;
                                $totalPerKomoditasBidang[$kom][$jk][$umur] += $val;
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
                                $totalPerKomoditasBidang[$kom][$jk] += $val;
                            ?>
                            <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                          <?php endforeach; ?>
                          <td style="background-color:<?= $bg ?>;text-align:center; font-weight:bold;"><?= number_format($subtotal) ?></td>
                        <?php endif; ?>
                      <?php else: ?>
                        <?php
                            $val = isset($row[$kom]) ? (int)$row[$kom] : 0;
                            $totalPerKomoditasBidang[$kom] += $val;
                        ?>
                        <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>

                <tr style="font-weight:bold; background-color:black; color:white;">
                  <td>Total</td>
                  <?php foreach ($bidangKomoditas as $kom): ?>
                    <?php if (in_array($kom, $komoditasBertingkatBidang)): ?>
                      <?php if (!empty($komoditasAdaUmurBidang[$kom])): ?>
                        <?php $totalAll = 0; ?>
                        <?php foreach (['Jantan','Betina'] as $jk): ?>
                          <?php foreach ($umurListBidang as $umur): ?>
                            <?php
                              $val = $totalPerKomoditasBidang[$kom][$jk][$umur];
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
                            $val = $totalPerKomoditasBidang[$kom][$jk];
                            $totalAll += $val;
                          ?>
                          <td style="text-align:center;"><?= number_format($val) ?></td>
                        <?php endforeach; ?>
                        <td style="text-align:center; background-color:black;"><?= number_format($totalAll) ?></td>
                      <?php endif; ?>
                    <?php else: ?>
                      <td style="text-align:center;"><?= number_format($totalPerKomoditasBidang[$kom]) ?></td>
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

  const komoditasBertingkatBidang = <?= json_encode(array_values($komoditasBertingkatBidang)) ?>;
  const komoditasAdaUmurBidang = <?= json_encode($komoditasAdaUmurBidang) ?>;
  const umurListBidang = <?= json_encode($umurListBidang) ?>;

  $('#btnFilterExport').on('click', function() {
    var bulan = document.getElementById('filterBulan').value;
    var tahun = document.getElementById('filterTahun').value;
    var tabel = '<?= $tabel ?>';

    // Redirect ke file PHP export, kirim bulan & tahun via GET
    window.location.href = '<?= base_url("bidang/export") ?>?tabel=' + tabel + '&bulan=' + bulan + '&tahun=' + tahun;
  });

  $('#btnFilter').on('click', function() {
    let bulan = $('#filterBulan').val();
    let tahun = $('#filterTahun').val();
    let tabel = '<?= $tabel ?>';

    $.ajax({
        url: "<?= base_url('bidang/get_data_bidang') ?>",
        type: "GET",
        data: { tabel: tabel, bulan: bulan, tahun: tahun },
        dataType: "json",
        success: function(res) {
            let tbody = "";
            let totalPerKomoditasBidang = {};

            // Inisialisasi total
            res.komoditas.forEach(k => {
                if (komoditasBertingkatBidang.includes(k)) {
                    if (komoditasAdaUmurBidang[k]) {
                        totalPerKomoditasBidang[k] = { 'Jantan': {}, 'Betina': {} };
                        umurListBidang.forEach(u => {
                            totalPerKomoditasBidang[k]['Jantan'][u] = 0;
                            totalPerKomoditasBidang[k]['Betina'][u] = 0;
                        });
                    } else {
                        totalPerKomoditasBidang[k] = { 'Jantan': 0, 'Betina': 0 };
                    }
                } else {
                    totalPerKomoditasBidang[k] = 0;
                }
            });

            // Isi data per kecamatan
            $.each(res.bidang, function(kec, row) {
                let status = row.status ?? 0;
                let bgColor = status ? 'black' : 'red';
                let textColor = 'white';
                tbody += `<tr><td style="background-color:${bgColor}; color:${textColor}">${kec}</td>`;

                res.komoditas.forEach(k => {
                    if (komoditasBertingkatBidang.includes(k)) {
                        if (komoditasAdaUmurBidang[k]) {
                            let subtotal = 0;
                            ['Jantan','Betina'].forEach(jk => {
                                umurListBidang.forEach(umur => {
                                    let val = parseInt(row[k]?.[jk]?.[umur] || 0);
                                    totalPerKomoditasBidang[k][jk][umur] += val;
                                    subtotal += val;
                                    tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                                });
                            });
                            tbody += `<td style="background-color:${bgColor};text-align:center; font-weight:bold;">${Number(subtotal).toLocaleString()}</td>`;
                        } else {
                            let subtotal = 0;
                            ['Jantan','Betina'].forEach(jk => {
                                let val = parseInt(Object.values(row[k]?.[jk] || {0:0})[0]);
                                totalPerKomoditasBidang[k][jk] += val;
                                subtotal += val;
                                tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                            });
                            tbody += `<td style="background-color:${bgColor};text-align:center; font-weight:bold;">${Number(subtotal).toLocaleString()}</td>`;
                        }
                    } else {
                        let val = parseInt(row[k] || 0);
                        totalPerKomoditasBidang[k] += val;
                        tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                    }
                });

                tbody += `</tr>`;
            });

            // Total per kategori (baris hitam)
            tbody += `<tr style="font-weight:bold; background-color:black; color:white"><td>Total</td>`;
            res.komoditas.forEach(k => {
                if (komoditasBertingkatBidang.includes(k)) {
                    if (komoditasAdaUmurBidang[k]) {
                        let totalAll = 0;
                        ['Jantan','Betina'].forEach(jk => {
                            umurListBidang.forEach(umur => {
                                let val = totalPerKomoditasBidang[k][jk][umur];
                                totalAll += val;
                                tbody += `<td style="text-align:center">${Number(val).toLocaleString()}</td>`;
                            });
                        });
                        tbody += `<td style="text-align:center; background-color:black;">${Number(totalAll).toLocaleString()}</td>`;
                    } else {
                        let totalAll = 0;
                        ['Jantan','Betina'].forEach(jk => {
                            let val = totalPerKomoditasBidang[k][jk];
                            totalAll += val;
                            tbody += `<td style="text-align:center">${Number(val).toLocaleString()}</td>`;
                        });
                        tbody += `<td style="text-align:center; background-color:black;">${Number(totalAll).toLocaleString()}</td>`;
                    }
                } else {
                    tbody += `<td style="text-align:center">${Number(totalPerKomoditasBidang[k]).toLocaleString()}</td>`;
                }
            });
            tbody += `</tr>`;

            $('#bidangBody').html(tbody);
        }
    });
  });

});
</script>
</body>
</html>

  