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
            <table id="populasiDashboard" class="table table-bordered table-striped table-sm">
              <thead style="background-color: black; color: white;">
                <tr>
                    <th rowspan="3" style="min-width:150px; text-align:center;">Kecamatan</th>

                    <?php
                    $umurList = ['Anak', 'Muda', 'Dewasa'];
                    $komoditasBertingkat = [];
                    $komoditasAdaUmur = [];

                    // Komoditas khusus
                    $khususJk = ['Kerbau', 'Kuda'];
                    $paksaUmur = ['Sapi Potong', 'Sapi Perah'];

                    // Deteksi otomatis komoditas bertingkat
                    foreach ($populasiKomoditas as $kom) {
                        foreach ($populasiPivot as $kec => $dataKec) {
                            if (isset($dataKec[$kom]) && is_array($dataKec[$kom])) {
                                $first = reset($dataKec[$kom]);
                                if (is_array($first)) {
                                    $komoditasBertingkat[] = $kom;

                                    if (in_array($kom, $khususJk)) {
                                        $komoditasAdaUmur[$kom] = false; // Jantan/Betina saja
                                    } elseif (in_array($kom, $paksaUmur)) {
                                        $komoditasAdaUmur[$kom] = true; // Paksa 3 tingkat umur
                                    } else {
                                        $hasUmur = false;
                                        foreach (['Jantan','Betina'] as $jk) {
                                            if (isset($first[$jk]) && is_array($first[$jk]) && count($first[$jk]) > 0) {
                                                $hasUmur = true;
                                                break;
                                            }
                                        }
                                        $komoditasAdaUmur[$kom] = $hasUmur;
                                    }
                                }
                            }
                        }
                    }
                    $komoditasBertingkat = array_unique($komoditasBertingkat);
                    ?>

                    <?php foreach ($populasiKomoditas as $kom): ?>
                        <?php if (in_array($kom, $komoditasBertingkat)): ?>
                            <?php if (!empty($komoditasAdaUmur[$kom])): ?>
                                <th colspan="6" style="text-align:center;"><?= $kom ?></th>
                            <?php else: ?>
                                <th colspan="2" style="text-align:center;"><?= $kom ?></th>
                            <?php endif; ?>
                        <?php else: ?>
                            <th rowspan="3" style="text-align:center;"><?= $kom ?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>

                <tr>
                  <?php foreach ($populasiKomoditas as $kom): ?>
                      <?php if (in_array($kom, $komoditasBertingkat)): ?>
                          <?php if (!empty($komoditasAdaUmur[$kom])): ?>
                              <!-- Ada umur → colspan 3 -->
                              <th colspan="3" style="text-align:center;">Jantan</th>
                              <th colspan="3" style="text-align:center;">Betina</th>
                          <?php else: ?>
                              <!-- Tidak ada umur → rowspan 2 -->
                              <th rowspan="2" style="text-align:center;">Jantan</th>
                              <th rowspan="2" style="text-align:center;">Betina</th>
                          <?php endif; ?>
                      <?php endif; ?>
                  <?php endforeach; ?>
                </tr>


                <tr>
                  <?php foreach ($populasiKomoditas as $kom): ?>
                      <?php if (in_array($kom, $komoditasBertingkat) && !empty($komoditasAdaUmur[$kom])): ?>
                          <?php foreach (['Jantan','Betina'] as $jk): ?>
                              <?php foreach ($umurList as $umur): ?>
                                  <th style="text-align:center;"><?= $umur ?></th>
                              <?php endforeach; ?>
                          <?php endforeach; ?>
                      <?php endif; ?>
                  <?php endforeach; ?>
                </tr>
              </thead>

            <tbody id="populasiBody">
                <?php
                $totalPerKomoditas = [];
                foreach ($populasiKomoditas as $kom) {
                    if (in_array($kom, $komoditasBertingkat)) {
                        if (!empty($komoditasAdaUmur[$kom])) {
                            foreach (['Jantan','Betina'] as $jk) {
                                foreach ($umurList as $umur) {
                                    $totalPerKomoditas[$kom][$jk][$umur] = 0;
                                }
                            }
                        } else {
                            foreach (['Jantan','Betina'] as $jk) {
                                $totalPerKomoditas[$kom][$jk] = 0;
                            }
                        }
                    } else {
                        $totalPerKomoditas[$kom] = 0;
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
                        <?php if (in_array($kom, $komoditasBertingkat)): ?>
                            <?php if (!empty($komoditasAdaUmur[$kom])): ?>
                                <?php foreach (['Jantan','Betina'] as $jk): ?>
                                    <?php foreach ($umurList as $umur): ?>
                                        <?php
                                            $val = isset($row[$kom][$jk][$umur]) ? (int)$row[$kom][$jk][$umur] : 0;
                                            $totalPerKomoditas[$kom][$jk][$umur] += $val;
                                        ?>
                                        <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                              <?php foreach (['Jantan', 'Betina'] as $jk): ?>
                                  <?php
                                      $val = 0;
                                      if (isset($row[$kom][$jk]) && is_array($row[$kom][$jk])) {
                                          // jumlahkan semua umur
                                          $val = array_sum($row[$kom][$jk]);
                                      }
                                      $totalPerKomoditas[$kom][$jk] += $val;
                                  ?>
                                  <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                              <?php endforeach; ?>
                          <?php endif; ?>
                        <?php else: ?>
                            <?php
                                $val = isset($row[$kom]) ? (int)$row[$kom] : 0;
                                $totalPerKomoditas[$kom] += $val;
                            ?>
                            <td style="background-color:<?= $bg ?>;text-align:center;"><?= number_format($val) ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>

                <tr style="font-weight:bold; background-color:black; color:white;">
                    <td>Total</td>
                    <?php foreach ($populasiKomoditas as $kom): ?>
                        <?php if (in_array($kom, $komoditasBertingkat)): ?>
                            <?php if (!empty($komoditasAdaUmur[$kom])): ?>
                                <?php foreach (['Jantan','Betina'] as $jk): ?>
                                    <?php foreach ($umurList as $umur): ?>
                                        <td style="text-align:center;"><?= number_format($totalPerKomoditas[$kom][$jk][$umur]) ?></td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php foreach (['Jantan','Betina'] as $jk): ?>
                                    <td style="text-align:center;"><?= number_format($totalPerKomoditas[$kom][$jk]) ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <td style="text-align:center;"><?= number_format($totalPerKomoditas[$kom]) ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <!-- TOTAL UTAMA DI BAWAH -->
                <tr style="font-weight:bold; background-color:black; color:white;">
                    <td></td>
                    <?php foreach ($populasiKomoditas as $kom): ?>
                        <?php if (in_array($kom, $komoditasBertingkat)): ?>
                            <?php if (!empty($komoditasAdaUmur[$kom])): ?>
                                <?php
                                    // Total semua umur Jantan + Betina
                                    $total = 0;
                                    foreach (['Jantan','Betina'] as $jk) {
                                        foreach ($umurList as $umur) {
                                            $total += $totalPerKomoditas[$kom][$jk][$umur];
                                        }
                                    }
                                ?>
                                <td colspan="6" style="text-align:center;"><?= number_format($total) ?></td>
                            <?php else: ?>
                                <?php foreach (['Jantan','Betina'] as $jk): ?>
                                    <td style="text-align:center;"><?= number_format($totalPerKomoditas[$kom][$jk]) ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <td style="text-align:center;"><?= number_format($totalPerKomoditas[$kom]) ?></td>
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


    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header bg-secondary text-white d-flex align-items-center">
            <h3 class="card-title mb-0">
              Data Pemotongan Ternak
            </h3>

            <!-- Filter Bulan Tahun di paling kanan -->
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
                    <th style="min-width: 150px;">Wilayah</th>
                    <?php foreach ($pemotonganKomoditas as $kom): ?>
                      <th style="min-width: 100px; text-align:center"><?= $kom ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody id="pemotonganBody">
                  <!-- isi pertama kali -->
                  <?php
                    $totalPerKomoditas = array_fill_keys($pemotonganKomoditas, 0);
                  ?>
                  <?php foreach ($pemotonganPivot as $kec => $row): ?>
                    <tr>
                      <td style="background-color: black"><?= $kec ?></td>
                      <?php foreach ($pemotonganKomoditas as $kom): ?>
                        <?php 
                          $val = isset($row[$kom]) ? $row[$kom] : 0; 
                          $totalPerKomoditas[$kom] += $val;
                        ?>
                        <td style="text-align:center"><?= number_format($val) ?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                  <tr style="font-weight:bold; background-color: black;">
                    <td>Total</td>
                    <?php foreach ($pemotonganKomoditas as $kom): ?>
                      <td style="text-align:center"><?= number_format($totalPerKomoditas[$kom]) ?></td>
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

  function getVal(row, komoditas, jk, umurList = null) {
    if (umurList) {
        return umurList.map(umur => parseInt(row[komoditas]?.[jk]?.[umur] || 0));
    }
    if (row[komoditas]?.[jk] && typeof row[komoditas][jk] === 'object') {
        return [parseInt(Object.values(row[komoditas][jk])[0] || 0)];
    }
    return [parseInt(row[komoditas] || 0)];
  }


  const komoditasBertingkat = <?= json_encode(array_values($komoditasBertingkat)) ?>;
  const komoditasAdaUmur = <?= json_encode($komoditasAdaUmur) ?>;
  const umurList = <?= json_encode($umurList) ?>;

  $('#btnFilterExport').on('click', function() {
    var bulan = document.getElementById('filterBulan').value;
    var tahun = document.getElementById('filterTahun').value;

    // Redirect ke file PHP export, kirim bulan & tahun via GET
    window.location.href = '<?= base_url("rekap/export") ?>?bulan=' + bulan + '&tahun=' + tahun;
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
            let totalPerKomoditas = {};

            // Inisialisasi total
            res.komoditas.forEach(k => {
                // cek apakah ini komoditas bertingkat
                if (komoditasBertingkat.includes(k)) {
                    if (komoditasAdaUmur[k]) {
                        totalPerKomoditas[k] = { 'Jantan': {}, 'Betina': {} };
                        umurList.forEach(u => {
                            totalPerKomoditas[k]['Jantan'][u] = 0;
                            totalPerKomoditas[k]['Betina'][u] = 0;
                        });
                    } else {
                        totalPerKomoditas[k] = { 'Jantan': 0, 'Betina': 0 };
                    }
                } else {
                    totalPerKomoditas[k] = 0;
                }
            });

            // Isi data per kecamatan
            $.each(res.populasi, function(kec, row) {
                // Tentukan warna berdasarkan status
                let status = row.status ?? 0; // default 0 jika tidak ada
                let bgColor = status ? 'black' : 'red';
                let textColor = status ? 'white' : 'white';

                // Baris pertama: kecamatan
                tbody += `<tr><td style="background-color:${bgColor}; color:${textColor}">${kec}</td>`;

                res.komoditas.forEach(k => {
                    if (komoditasBertingkat.includes(k)) {
                        if (komoditasAdaUmur[k]) {
                            ['Jantan','Betina'].forEach(jk => {
                                umurList.forEach(umur => {
                                    let val = parseInt(row[k]?.[jk]?.[umur] || 0);
                                    totalPerKomoditas[k][jk][umur] += val;
                                    tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                                });
                            });
                        } else {
                            ['Jantan','Betina'].forEach(jk => {
                                let val = parseInt(Object.values(row[k]?.[jk] || {0:0})[0]); // aman
                                totalPerKomoditas[k][jk] += val;
                                tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                            });
                        }
                    } else {
                        let val = parseInt(row[k] || 0);
                        totalPerKomoditas[k] += val;
                        tbody += `<td style="background-color:${bgColor};text-align:center">${Number(val).toLocaleString()}</td>`;
                    }
                });

                tbody += `</tr>`;
            });


            // Total per kategori
            tbody += `<tr style="font-weight:bold; background-color:black; color:white"><td>Total</td>`;
            res.komoditas.forEach(k => {
                if (komoditasBertingkat.includes(k)) {
                    if (komoditasAdaUmur[k]) {
                        ['Jantan','Betina'].forEach(jk => {
                            umurList.forEach(umur => {
                                tbody += `<td style="text-align:center">${Number(totalPerKomoditas[k][jk][umur]).toLocaleString()}</td>`;
                            });
                        });
                    } else {
                        ['Jantan','Betina'].forEach(jk => {
                            tbody += `<td style="text-align:center">${Number(totalPerKomoditas[k][jk]).toLocaleString()}</td>`;
                        });
                    }
                } else {
                    tbody += `<td style="text-align:center">${Number(totalPerKomoditas[k]).toLocaleString()}</td>`;
                }
            });
            tbody += `</tr>`;

            // Total utama (semua umur dan jenis kelamin)
            tbody += `<tr style="font-weight:bold; background-color:black; color:white"><td></td>`;
            res.komoditas.forEach(k => {
                if (komoditasBertingkat.includes(k) && komoditasAdaUmur[k]) {
                    let total = 0;
                    ['Jantan','Betina'].forEach(jk => {
                        umurList.forEach(umur => total += totalPerKomoditas[k][jk][umur]);
                    });
                    tbody += `<td colspan="6" style="text-align:center">${Number(total).toLocaleString()}</td>`;
                } else if (komoditasBertingkat.includes(k)) {
                    ['Jantan','Betina'].forEach(jk => {
                        tbody += `<td style="text-align:center">${Number(totalPerKomoditas[k][jk]).toLocaleString()}</td>`;
                    });
                } else {
                    tbody += `<td style="text-align:center">${Number(totalPerKomoditas[k]).toLocaleString()}</td>`;
                }
            });
            tbody += `</tr>`;

            $('#populasiBody').html(tbody);
            $('#judulBulanTahun').text(res.nama_bulan + " " + res.tahun);
        }
    });
  });



  $('#btnPemotonganFilter').on('click', function(){
    let bulan = $('#filterPemotonganBulan').val();
    let tahun = $('#filterPemotonganTahun').val();

    $.ajax({
      url: "<?= base_url('rekap/get_data_pemotongan') ?>",
      type: "GET",
      data: { bulan: bulan, tahun: tahun },
      dataType: "json",
      success: function(res){
        let tbody = "";
        let total = {};

        // inisialisasi total
        res.komoditas.forEach(k => total[k] = 0);

        // isi data per kecamatan
        $.each(res.pemotongan, function(kec, row){
          tbody += `<tr><td style="background-color:black">${kec}</td>`;
          res.komoditas.forEach(k => {
            let val = row[k] ? row[k] : 0;
            total[k] += parseInt(val);
            tbody += `<td style="text-align:center">${Number(val).toLocaleString()}</td>`;
          });
          tbody += `</tr>`;
        });

        // total
        tbody += `<tr style="font-weight:bold; background-color:black"><td>Total</td>`;
        res.komoditas.forEach(k => {
          tbody += `<td style="text-align:center">${Number(total[k]).toLocaleString()}</td>`;
        });
        tbody += `</tr>`;

        $('#pemotonganBody').html(tbody);
        $('#judulBulanTahun').text(res.nama_bulan + " " + res.tahun);
      }
    });
  });
});
</script>
</body>
</html>

  