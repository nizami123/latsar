<style>
  table.dashboard {
    border-collapse: collapse;
    width: 100%;
    table-layout: auto;
  }

  table.dashboard th, 
  table.dashboard td {
    white-space: nowrap;
  }

  table.dashboard thead th {
    position: sticky;
    top: 0;
    background: black;
    color: white;
    z-index: 2;
  }

  table.dashboard td:first-child,
  table.dashboard th:first-child {
    position: sticky;
    left: 0;
    z-index: 3;
    background: black;
    color: white;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Rekap Data Ternak</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Rekap Data</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- ======= TABEL MASUK ======= -->
      <div class="card">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          <h3 class="card-title mb-0">
            Data Masuk Ternak Kecamatan <?= $kecamatan['nama_wilayah'] ?>
          </h3>
          <div class="form-inline ml-auto">
            <select id="filterBulanMasuk" class="form-control form-control-sm mr-2">
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($i == $masukBulan) ? 'selected' : '' ?>><?= nama_bulan($i) ?></option>
              <?php endfor; ?>
            </select>
            <select id="filterTahunMasuk" class="form-control form-control-sm mr-2">
              <?php $tahunSekarang = date('Y'); for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                <option value="<?= $t ?>" <?= ($t == $masukTahun) ? 'selected' : '' ?>><?= $t ?></option>
              <?php endfor; ?>
            </select>
            <button id="btnFilterMasuk" class="btn btn-sm btn-light"><i class="fas fa-search"></i> Tampilkan</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="masukDashboard" class="table table-bordered table-striped table-sm dashboard">
              <thead>
                <tr>
                  <th style="min-width:150px;">Desa</th>
                  <?php foreach ($masukKomoditas as $kom): ?>
                    <th style="min-width:100px; text-align:center"><?= $kom ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody id="masukBody">
                <?php
                  $totalPerKomoditas = array_fill_keys($masukKomoditas, 0);
                  foreach ($masukPivot as $kec => $row):
                ?>
                <tr>
                  <td style="background-color:black"><?= $kec ?></td>
                  <?php foreach ($masukKomoditas as $kom):
                    $val = isset($row[$kom]) ? $row[$kom] : 0;
                    $totalPerKomoditas[$kom] += $val;
                  ?>
                  <td style="text-align:center"><?= number_format($val) ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background-color:black;">
                  <td>Total</td>
                  <?php foreach ($masukKomoditas as $kom): ?>
                    <td style="text-align:center"><?= number_format($totalPerKomoditas[$kom]) ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ======= TABEL KELUAR ======= -->
      <div class="card">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          <h3 class="card-title mb-0">
            Data Keluar Ternak Kecamatan <?= $kecamatan['nama_wilayah'] ?>
          </h3>
          <div class="form-inline ml-auto">
            <select id="filterBulanKeluar" class="form-control form-control-sm mr-2">
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($i == $keluarBulan) ? 'selected' : '' ?>><?= nama_bulan($i) ?></option>
              <?php endfor; ?>
            </select>
            <select id="filterTahunKeluar" class="form-control form-control-sm mr-2">
              <?php $tahunSekarang = date('Y'); for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                <option value="<?= $t ?>" <?= ($t == $keluarTahun) ? 'selected' : '' ?>><?= $t ?></option>
              <?php endfor; ?>
            </select>
            <button id="btnFilterKeluar" class="btn btn-sm btn-light"><i class="fas fa-search"></i> Tampilkan</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="keluarDashboard" class="table table-bordered table-striped table-sm dashboard">
              <thead>
                <tr>
                  <th style="min-width:150px;">Desa</th>
                  <?php foreach ($keluarKomoditas as $kom): ?>
                    <th style="min-width:100px; text-align:center"><?= $kom ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody id="keluarBody">
                <?php
                  $totalPerKomoditas = array_fill_keys($keluarKomoditas, 0);
                  foreach ($keluarPivot as $kec => $row):
                ?>
                <tr>
                  <td style="background-color:black"><?= $kec ?></td>
                  <?php foreach ($keluarKomoditas as $kom):
                    $val = isset($row[$kom]) ? $row[$kom] : 0;
                    $totalPerKomoditas[$kom] += $val;
                  ?>
                  <td style="text-align:center"><?= number_format($val) ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background-color:black;">
                  <td>Total</td>
                  <?php foreach ($keluarKomoditas as $kom): ?>
                    <td style="text-align:center"><?= number_format($totalPerKomoditas[$kom]) ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ======= TABEL KELAHIRAN ======= -->
      <div class="card">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          <h3 class="card-title mb-0">
            Data Kelahiran Ternak Kecamatan <?= $kecamatan['nama_wilayah'] ?>
          </h3>
          <div class="form-inline ml-auto">
            <select id="filterBulanKelahiran" class="form-control form-control-sm mr-2">
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($i == $kelahiranBulan) ? 'selected' : '' ?>><?= nama_bulan($i) ?></option>
              <?php endfor; ?>
            </select>
            <select id="filterTahunKelahiran" class="form-control form-control-sm mr-2">
              <?php $tahunSekarang = date('Y'); for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                <option value="<?= $t ?>" <?= ($t == $kelahiranTahun) ? 'selected' : '' ?>><?= $t ?></option>
              <?php endfor; ?>
            </select>
            <button id="btnFilterKelahiran" class="btn btn-sm btn-light"><i class="fas fa-search"></i> Tampilkan</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="kelahiranDashboard" class="table table-bordered table-striped table-sm dashboard">
              <thead>
                <tr>
                  <th style="min-width:150px;">Desa</th>
                  <?php foreach ($kelahiranKomoditas as $kom): ?>
                    <th style="min-width:100px; text-align:center"><?= $kom ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody id="kelahiranBody">
                <?php
                  $totalPerKomoditas = array_fill_keys($kelahiranKomoditas, 0);
                  foreach ($kelahiranPivot as $kec => $row):
                ?>
                <tr>
                  <td style="background-color:black"><?= $kec ?></td>
                  <?php foreach ($kelahiranKomoditas as $kom):
                    $val = isset($row[$kom]) ? $row[$kom] : 0;
                    $totalPerKomoditas[$kom] += $val;
                  ?>
                  <td style="text-align:center"><?= number_format($val) ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background-color:black;">
                  <td>Total</td>
                  <?php foreach ($kelahiranKomoditas as $kom): ?>
                    <td style="text-align:center"><?= number_format($totalPerKomoditas[$kom]) ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ======= TABEL KEMATIAN ======= -->
      <div class="card">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          <h3 class="card-title mb-0">
            Data Kematian Ternak Kecamatan <?= $kecamatan['nama_wilayah'] ?>
          </h3>
          <div class="form-inline ml-auto">
            <select id="filterBulanKematian" class="form-control form-control-sm mr-2">
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($i == $kematianBulan) ? 'selected' : '' ?>><?= nama_bulan($i) ?></option>
              <?php endfor; ?>
            </select>
            <select id="filterTahunKematian" class="form-control form-control-sm mr-2">
              <?php $tahunSekarang = date('Y'); for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                <option value="<?= $t ?>" <?= ($t == $kematianTahun) ? 'selected' : '' ?>><?= $t ?></option>
              <?php endfor; ?>
            </select>
            <button id="btnFilterKematian" class="btn btn-sm btn-light"><i class="fas fa-search"></i> Tampilkan</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="kematianDashboard" class="table table-bordered table-striped table-sm dashboard">
              <thead>
                <tr>
                  <th style="min-width:150px;">Desa</th>
                  <?php foreach ($kematianKomoditas as $kom): ?>
                    <th style="min-width:100px; text-align:center"><?= $kom ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody id="kematianBody">
                <?php
                  $totalPerKomoditas = array_fill_keys($kematianKomoditas, 0);
                  foreach ($kematianPivot as $kec => $row):
                ?>
                <tr>
                  <td style="background-color:black"><?= $kec ?></td>
                  <?php foreach ($kematianKomoditas as $kom):
                    $val = isset($row[$kom]) ? $row[$kom] : 0;
                    $totalPerKomoditas[$kom] += $val;
                  ?>
                  <td style="text-align:center"><?= number_format($val) ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background-color:black;">
                  <td>Total</td>
                  <?php foreach ($kematianKomoditas as $kom): ?>
                    <td style="text-align:center"><?= number_format($totalPerKomoditas[$kom]) ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ======= TABEL PEMOTONGAN ======= -->
      <div class="card">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          <h3 class="card-title mb-0">
            Data Pemotongan Ternak Kecamatan <?= $kecamatan['nama_wilayah'] ?>
          </h3>
          <div class="form-inline ml-auto">
            <select id="filterBulanPemotongan" class="form-control form-control-sm mr-2">
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= ($i == $pemotonganBulan) ? 'selected' : '' ?>><?= nama_bulan($i) ?></option>
              <?php endfor; ?>
            </select>
            <select id="filterTahunPemotongan" class="form-control form-control-sm mr-2">
              <?php $tahunSekarang = date('Y'); for ($t = $tahunSekarang; $t >= 2020; $t--): ?>
                <option value="<?= $t ?>" <?= ($t == $pemotonganTahun) ? 'selected' : '' ?>><?= $t ?></option>
              <?php endfor; ?>
            </select>
            <button id="btnFilterPemotongan" class="btn btn-sm btn-light"><i class="fas fa-search"></i> Tampilkan</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="pemotonganDashboard" class="table table-bordered table-striped table-sm dashboard">
              <thead>
                <tr>
                  <th style="min-width:150px;">Desa</th>
                  <?php foreach ($pemotonganKomoditas as $kom): ?>
                    <th style="min-width:100px; text-align:center"><?= $kom ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody id="pemotonganBody">
                <?php
                  $totalPerKomoditas = array_fill_keys($pemotonganKomoditas, 0);
                  foreach ($pemotonganPivot as $kec => $row):
                ?>
                <tr>
                  <td style="background-color:black"><?= $kec ?></td>
                  <?php foreach ($pemotonganKomoditas as $kom):
                    $val = isset($row[$kom]) ? $row[$kom] : 0;
                    $totalPerKomoditas[$kom] += $val;
                  ?>
                  <td style="text-align:center"><?= number_format($val) ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background-color:black;">
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
  function setupFilter(btnId, bulanId, tahunId, tbodyId, url) {
  $(btnId).on('click', function(){
    let bulan = $(bulanId).val();
    let tahun = $(tahunId).val();
    $.ajax({
      url: url,
      type: "GET",
      data: { bulan: bulan, tahun: tahun },
      dataType: "json",
      success: function(res){
        let tbody = "";
        let total = {};
        res.komoditas.forEach(k => total[k] = 0);
        $.each(res.data, function(kec, row){
          tbody += `<tr><td style="background-color:black">${kec}</td>`;
          res.komoditas.forEach(k => {
            let val = row[k] ? row[k] : 0;
            total[k] += parseInt(val);
            tbody += `<td style="text-align:center">${Number(val).toLocaleString()}</td>`;
          });
          tbody += `</tr>`;
        });
        tbody += `<tr style="font-weight:bold; background-color:black"><td>Total</td>`;
        res.komoditas.forEach(k => { tbody += `<td style="text-align:center">${Number(total[k]).toLocaleString()}</td>`; });
        tbody += `</tr>`;
        $(tbodyId).html(tbody);
      }
    });
  });
}

// Setup semua filter
setupFilter('#btnFilterPopulasi', '#filterBulanPopulasi', '#filterTahunPopulasi', '#populasiBody', "<?= base_url('rekap/get_data_populasi') ?>");
setupFilter('#btnFilterMasuk', '#filterBulanMasuk', '#filterTahunMasuk', '#masukBody', "<?= base_url('rekap/get_data_masuk') ?>");
setupFilter('#btnFilterKeluar', '#filterBulanKeluar', '#filterTahunKeluar', '#keluarBody', "<?= base_url('rekap/get_data_keluar') ?>");
setupFilter('#btnFilterKelahiran', '#filterBulanKelahiran', '#filterTahunKelahiran', '#kelahiranBody', "<?= base_url('rekap/get_data_kelahiran') ?>");
setupFilter('#btnFilterKematian', '#filterBulanKematian', '#filterTahunKematian', '#kematianBody', "<?= base_url('rekap/get_data_kematian') ?>");
setupFilter('#btnFilterPemotongan','#filterBulanPemotongan','#filterTahunPemotongan','#pemotonganBody',"<?= base_url('rekap/get_data_pemotongan') ?>");
</script>