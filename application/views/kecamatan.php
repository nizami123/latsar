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
        background-color: #000;
        color: white;
    }

  #kecamatanDashboard {
    border-collapse: collapse;
    width: 100%;
    table-layout: auto;
  }

  #kecamatanDashboard th, 
  #kecamatanDashboard td {
    white-space: nowrap;
    text-align: center;      /* Tengah horizontal */
    vertical-align: middle; 
  }

  #kecamatanDashboard thead th {
    position: sticky;
    top: 0;
    background: black;
    color: white;
    z-index: 2;
  }

  #kecamatanDashboard td:first-child,
  #kecamatanDashboard th:first-child {
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
            <h1 class="m-0">Rekap Kecamatan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Rekap Kecamatan</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <?php foreach ($dataKecamatan as $namaTabel => $data): ?>

    <?php
        $kecamatanPivot     = $data['pivot'];
        $kecamatanKomoditas = $data['komoditas'];
        $kecamatanBulan     = $data['bulan'];
        $kecamatanTahun     = $data['tahun'];
        $uid = $namaTabel;
    ?>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header bg-secondary text-white d-flex align-items-center">

            <h3 class="card-title mb-0">
              Data <?= ucfirst($namaTabel) ?> Ternak
            </h3>

            <div class="form-inline ml-auto">

              <select id="filterBulan_<?= $uid ?>" class="form-control form-control-sm mr-2">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>" <?= ($i == $kecamatanBulan) ? 'selected' : '' ?>>
                    <?= nama_bulan($i) ?>
                  </option>
                <?php endfor; ?>
              </select>

              <select id="filterTahun_<?= $uid ?>" class="form-control form-control-sm mr-2">
                <?php for ($t = date('Y'); $t >= 2020; $t--): ?>
                  <option value="<?= $t ?>" <?= ($t == $kecamatanTahun) ? 'selected' : '' ?>>
                    <?= $t ?>
                  </option>
                <?php endfor; ?>
              </select>

              <button class="btn btn-sm btn-light btnFilter" data-tabel="<?= $uid ?>">
                <i class="fas fa-search"></i> Tampilkan
              </button>&nbsp;

              <button class="btn btn-sm btn-light btnExport" data-tabel="<?= $uid ?>">
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

              <table id="kecamatanDashboard" class="table table-bordered table-striped table-sm">
              <thead id="kecamatanHead_<?= $uid ?>" style="background:black;color:white;">
              <tr>
                <th rowspan="3" style="text-align:center;min-width:150px;">Kecamatan</th>

              <?php
              $umurListKecamatan = ['Anak','Muda','Dewasa'];
              $komoditasBertingkatKecamatan = [];
              $komoditasAdaUmurKecamatan = [];
              $khususJK = ['Kerbau','Kuda'];
              $paksaUmur = ['Sapi Potong','Sapi Perah'];

              foreach ($kecamatanKomoditas as $kom) {
                  foreach ($kecamatanPivot as $row) {
                      if (isset($row[$kom]) && is_array($row[$kom])) {
                          $first = reset($row[$kom]);
                          if (is_array($first)) {
                              $komoditasBertingkatKecamatan[] = $kom;
                              if (in_array($kom,$khususJK)) $komoditasAdaUmurKecamatan[$kom]=false;
                              elseif (in_array($kom,$paksaUmur)) $komoditasAdaUmurKecamatan[$kom]=true;
                              else $komoditasAdaUmurKecamatan[$kom]=isset($first['Jantan']) && is_array($first['Jantan']);
                          }
                      }
                  }
              }
              $komoditasBertingkatKecamatan = array_unique($komoditasBertingkatKecamatan);
              ?>

              <?php foreach ($kecamatanKomoditas as $kom): ?>
                <?php if (in_array($kom,$komoditasBertingkatKecamatan)): ?>
                  <th colspan="<?= !empty($komoditasAdaUmurKecamatan[$kom]) ? 7 : 3 ?>" style="text-align:center;"><?= $kom ?></th>
                <?php else: ?>
                  <th rowspan="3" style="text-align:center;"><?= $kom ?></th>
                <?php endif; ?>
              <?php endforeach; ?>
              </tr>

              <tr>
              <?php foreach ($kecamatanKomoditas as $kom): ?>
                <?php if (in_array($kom,$komoditasBertingkatKecamatan)): ?>
                  <?php if (!empty($komoditasAdaUmurKecamatan[$kom])): ?>
                    <th colspan="3">Jantan</th>
                    <th colspan="3">Betina</th>
                    <th rowspan="2">Total</th>
                  <?php else: ?>
                    <th rowspan="2">Jantan</th>
                    <th rowspan="2">Betina</th>
                    <th rowspan="2">Total</th>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endforeach; ?>
              </tr>

              <tr>
              <?php foreach ($kecamatanKomoditas as $kom): ?>
                <?php if (in_array($kom,$komoditasBertingkatKecamatan) && !empty($komoditasAdaUmurKecamatan[$kom])): ?>
                  <?php foreach (['Jantan','Betina'] as $jk): ?>
                    <?php foreach ($umurListKecamatan as $u): ?>
                      <th><?= $u ?></th>
                    <?php endforeach; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
              <?php endforeach; ?>
              </tr>
              </thead>

              <tbody id="kecamatanBody_<?= $uid ?>">
                <?php foreach ($kecamatanPivot as $kec => $row): ?>
                <tr>
                  <?php $bg = !empty($row['status']) ? 'black':'red'; ?>
                  <td style="background:<?= $bg ?>;color:white;"><?= $kec ?></td>

                <?php foreach ($kecamatanKomoditas as $kom): ?>
                  <?php if (in_array($kom,$komoditasBertingkatKecamatan)): ?>
                    <?php $subtotal=0; ?>
                    <?php if (!empty($komoditasAdaUmurKecamatan[$kom])): ?>
                      <?php foreach (['Jantan','Betina'] as $jk): ?>
                        <?php foreach ($umurListKecamatan as $u): ?>
                          <?php $v = (int)($row[$kom][$jk][$u] ?? 0); $subtotal+=$v; ?>
                          <td style="background:<?= $bg ?>;text-align:center;"><?= number_format($v) ?></td>
                        <?php endforeach; ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <?php foreach (['Jantan','Betina'] as $jk): ?>
                        <?php $v = isset($row[$kom][$jk]) ? array_sum($row[$kom][$jk]) : 0; $subtotal+=$v; ?>
                        <td style="background:<?= $bg ?>;text-align:center;"><?= number_format($v) ?></td>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <td style="background:<?= $bg ?>;font-weight:bold;text-align:center;"><?= number_format($subtotal) ?></td>
                  <?php else: ?>
                    <td style="background:<?= $bg ?>;text-align:center;"><?= number_format((int)($row[$kom] ?? 0)) ?></td>
                  <?php endif; ?>
                <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>  
                <tr style="background:black;color:white;font-weight:bold">
                  <td>Total</td>

                  <?php foreach ($kecamatanKomoditas as $kom): ?>

                    <?php if (in_array($kom,$komoditasBertingkatKecamatan)): ?>
                      <?php $grand = 0; ?>

                      <?php if (!empty($komoditasAdaUmurKecamatan[$kom])): ?>
                        <?php foreach (['Jantan','Betina'] as $jk): ?>
                          <?php foreach ($umurListKecamatan as $u): ?>
                            <?php
                              $v = 0;
                              foreach ($kecamatanPivot as $row) {
                                $v += (int)($row[$kom][$jk][$u] ?? 0);
                              }
                              $grand += $v;
                            ?>
                            <td><?= number_format($v) ?></td>
                          <?php endforeach; ?>
                        <?php endforeach; ?>

                      <?php else: ?>
                        <?php foreach (['Jantan','Betina'] as $jk): ?>
                          <?php
                            $v = 0;
                            foreach ($kecamatanPivot as $row) {
                              $v += array_sum($row[$kom][$jk] ?? []);
                            }
                            $grand += $v;
                          ?>
                          <td><?= number_format($v) ?></td>
                        <?php endforeach; ?>
                      <?php endif; ?>

                      <td><?= number_format($grand) ?></td>

                    <?php else: ?>
                      <?php
                        $v = 0;
                        foreach ($kecamatanPivot as $row) {
                          $v += (int)($row[$kom] ?? 0);
                        }
                      ?>
                      <td><?= number_format($v) ?></td>
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

    <?php endforeach; ?>

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

  // data statis dari PHP (cukup 1x)
  const komoditasBertingkatKecamatan = <?= json_encode(array_values($komoditasBertingkatKecamatan)) ?>;
  const komoditasAdaUmurKecamatan    = <?= json_encode($komoditasAdaUmurKecamatan) ?>;
  const umurListKecamatan            = <?= json_encode($umurListKecamatan) ?>;

  /* =========================
     EXPORT
  ==========================*/
  $(document).on('click', '.btnExport', function () {

    let tabel     = $(this).data('tabel');
    let bulan     = $('#filterBulan_' + tabel).val();
    let tahun     = $('#filterTahun_' + tabel).val();

    window.location.href =
      '<?= base_url("kecamatan/export") ?>' +
      '?tabel=' + tabel +
      '&bulan=' + bulan +
      '&tahun=' + tahun ;
  });

  /* =========================
     FILTER (AJAX)
  ==========================*/
  $(document).on('click', '.btnFilter', function () {

    let tabel     = $(this).data('tabel');
    let bulan     = $('#filterBulan_' + tabel).val();
    let tahun     = $('#filterTahun_' + tabel).val();

    $.ajax({
      url: "<?= base_url('kecamatan/get_data_kecamatan') ?>",
      type: "GET",
      dataType: "json",
      data: {
        tabel: tabel,
        bulan: bulan,
        tahun: tahun
      },
      success: function(res) {

        let thead = `<tr>
          <th rowspan="3" style="min-width:150px; text-align:center;">Kecamatan</th>`;

        /* =========================
           HEADER BARIS 1 (KOMODITAS)
        ==========================*/
        res.komoditas.forEach(kom => {
          if (komoditasBertingkatKecamatan.includes(kom)) {
            thead += `<th colspan="${komoditasAdaUmurKecamatan[kom] ? 7 : 3}" style="text-align:center;">${kom}</th>`;
          } else {
            thead += `<th rowspan="3" style="text-align:center;">${kom}</th>`;
          }
        });

        thead += `</tr><tr>`;

        /* =========================
           HEADER BARIS 2 (JK / TOTAL)
        ==========================*/
        res.komoditas.forEach(kom => {
          if (komoditasBertingkatKecamatan.includes(kom)) {
            if (komoditasAdaUmurKecamatan[kom]) {
              thead += `
                <th colspan="3">Jantan</th>
                <th colspan="3">Betina</th>
                <th rowspan="2">Total</th>`;
            } else {
              thead += `
                <th rowspan="2">Jantan</th>
                <th rowspan="2">Betina</th>
                <th rowspan="2">Total</th>`;
            }
          }
        });

        thead += `</tr><tr>`;

        /* =========================
           HEADER BARIS 3 (UMUR)
        ==========================*/
        res.komoditas.forEach(kom => {
          if (komoditasBertingkatKecamatan.includes(kom) && komoditasAdaUmurKecamatan[kom]) {
            ['Jantan','Betina'].forEach(() => {
              umurListKecamatan.forEach(u => {
                thead += `<th>${u}</th>`;
              });
            });
          }
        });

        thead += `</tr>`;

        $('#kecamatanHead_' + tabel).html(thead);

        /* =========================
           BODY
        ==========================*/
        let tbody = '';
        let total = {};

        res.komoditas.forEach(k => {
          if (komoditasBertingkatKecamatan.includes(k)) {
            total[k] = komoditasAdaUmurKecamatan[k]
              ? { Jantan:{}, Betina:{} }
              : { Jantan:0, Betina:0 };

            if (komoditasAdaUmurKecamatan[k]) {
              umurListKecamatan.forEach(u => {
                total[k].Jantan[u] = 0;
                total[k].Betina[u] = 0;
              });
            }
          } else {
            total[k] = 0;
          }
        });

        $.each(res.kecamatan, function(kec, row) {

          let bg = row.status ? 'black' : 'red';
          tbody += `<tr><td style="background:${bg};color:white">${kec}</td>`;

          res.komoditas.forEach(k => {

            if (komoditasBertingkatKecamatan.includes(k)) {

              let subtotal = 0;

              if (komoditasAdaUmurKecamatan[k]) {
                ['Jantan','Betina'].forEach(jk => {
                  umurListKecamatan.forEach(u => {
                    let v = parseInt(row[k]?.[jk]?.[u] || 0);
                    total[k][jk][u] += v;
                    subtotal += v;
                    tbody += `<td style="background:${bg};text-align:center">${v.toLocaleString()}</td>`;
                  });
                });
              } else {
                ['Jantan','Betina'].forEach(jk => {
                  let v = parseInt(Object.values(row[k]?.[jk] || {0:0})[0]);
                  total[k][jk] += v;
                  subtotal += v;
                  tbody += `<td style="background:${bg};text-align:center">${v.toLocaleString()}</td>`;
                });
              }

              tbody += `<td style="background:${bg};font-weight:bold;text-align:center">${subtotal.toLocaleString()}</td>`;

            } else {
              let v = parseInt(row[k] || 0);
              total[k] += v;
              tbody += `<td style="background:${bg};text-align:center">${v.toLocaleString()}</td>`;
            }
          });

          tbody += `</tr>`;
        });

        /* =========================
           TOTAL BARIS
        ==========================*/
        tbody += `<tr style="background:black;color:white;font-weight:bold"><td>Total</td>`;

        res.komoditas.forEach(k => {
          if (komoditasBertingkatKecamatan.includes(k)) {
            let sum = 0;
            if (komoditasAdaUmurKecamatan[k]) {
              ['Jantan','Betina'].forEach(jk => {
                umurListKecamatan.forEach(u => {
                  let v = total[k][jk][u];
                  sum += v;
                  tbody += `<td>${v.toLocaleString()}</td>`;
                });
              });
            } else {
              ['Jantan','Betina'].forEach(jk => {
                let v = total[k][jk];
                sum += v;
                tbody += `<td>${v.toLocaleString()}</td>`;
              });
            }
            tbody += `<td>${sum.toLocaleString()}</td>`;
          } else {
            tbody += `<td>${total[k].toLocaleString()}</td>`;
          }
        });

        tbody += `</tr>`;

        $('#kecamatanBody_' + tabel).html(tbody);
      }
    });
  });

});
</script>

</body>
</html>

  