

<style>
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
                    <th style="min-width: 150px;">Kecamatan</th>
                    <?php foreach ($populasiKomoditas as $kom): ?>
                      <th style="min-width: 100px; text-align:center"><?= $kom ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody id="populasiBody">
                  <!-- isi pertama kali -->
                  <?php
                    $totalPerKomoditas = array_fill_keys($populasiKomoditas, 0);
                  ?>
                  <?php foreach ($populasiPivot as $kec => $row): ?>
                    <tr>
                      <td style="background-color: black"><?= $kec ?></td>
                      <?php foreach ($populasiKomoditas as $kom): ?>
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
                    <?php foreach ($populasiKomoditas as $kom): ?>
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
  $('#btnFilter').on('click', function(){
    let bulan = $('#filterBulan').val();
    let tahun = $('#filterTahun').val();

    $.ajax({
      url: "<?= base_url('rekap/get_data_populasi') ?>",
      type: "GET",
      data: { bulan: bulan, tahun: tahun },
      dataType: "json",
      success: function(res){
        let tbody = "";
        let total = {};

        // inisialisasi total
        res.komoditas.forEach(k => total[k] = 0);

        // isi data per kecamatan
        $.each(res.populasi, function(kec, row){
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

  