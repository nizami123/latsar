
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


    <!-- /.content-header -->
     <section class="content">
      <div class="container-fluid">
        <div class="col-sm-6">
            <h6 class="m-0">Update Harga Komoditas per <span style="font-weight: bold"><?= date('d F Y', strtotime($last_date)) ?></span></h6><br>
        </div><!-- /.col -->
        <div class="row">
          <?php foreach ($harga as $h): ?>
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box">
                <!-- Pilih icon berdasarkan komoditas -->
                <span class="info-box-icon 
                  <?php 
                    switch ($h->id_komoditas) {
                      case 7: echo 'bg-info'; break;        // Sapi
                      case 8: echo 'bg-danger'; break;      // Daging Sapi
                      case 9: echo 'bg-success'; break;     // Ayam Broiler
                      case 10: echo 'bg-warning'; break;    // Karkas Broiler
                      case 11: echo 'bg-primary'; break;    // Telur Ras (P)
                      case 12: echo 'bg-secondary'; break;  // Telur Ras (K)
                      default: echo 'bg-dark';
                    }
                  ?> elevation-1">
                  <i class="fas 
                    <?php 
                      switch ($h->id_komoditas) {
                        case 7: echo 'fa-horse'; break;            // Sapi
                        case 8: echo 'fa-drumstick-bite'; break;   // Daging Sapi
                        case 9: echo 'fa-kiwi-bird'; break;        // Ayam Broiler
                        case 10: echo 'fa-drumstick-bite'; break;  // Karkas
                        case 11: echo 'fa-egg'; break;             // Telur P
                        case 12: echo 'fa-egg'; break;             // Telur K
                        default: echo 'fa-chart-line';
                      }
                    ?>">
                  </i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text"><?= $h->nama_komoditas ?></span>
                  <span class="info-box-number"><?= number_format($h->harga, 0, ',', '.') ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            <h3 class="card-title">
              Data Populasi Ternak Per <span style="font-weight: bold"><?= nama_bulan($populasiBulan) ?> <?= $populasiTahun ?></span>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">

            <!-- Tabel gabung untuk HP -->
            <div class="d-block d-md-none">
              <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th style="width:50px; text-align:center;">No</th>
                    <th>Komoditas</th>
                    <th style="width:150px; text-align:right;">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no=1; foreach ($populasiTotal as $p): ?>
                      <tr>
                        <td style="text-align:center;"><?= $no++ ?></td>
                        <td><?= $p->nama_komoditas ?></td>
                        <td style="text-align:right;"><?= number_format($p->total, 0, ',', '.') ?></td>
                      </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Tabel terpisah jadi 2 kolom untuk desktop -->
            <div class="row d-none d-md-flex">
              <?php 
                $chunked = array_chunk($populasiTotal, ceil(count($populasiTotal)/2)); 
                $no = 1;
              ?>
              <?php foreach ($chunked as $group): ?>
                <div class="col-md-6">
                  <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                      <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Komoditas</th>
                        <th style="width:150px; text-align:right;">Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($group as $p): ?>
                          <tr>
                            <td style="text-align:center;"><?= $no++ ?></td>
                            <td><?= $p->nama_komoditas ?></td>
                            <td style="text-align:right;"><?= number_format($p->total, 0, ',', '.') ?></td>
                          </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endforeach; ?>
            </div>

          </div>
        </div>
      </div>
    </section>


    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            <h3 class="card-title">
              Data Pemotongan Ternak Per <span style="font-weight: bold"><?= nama_bulan($pemotonganBulan) ?> <?= $pemotonganTahun ?></span>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">

            <!-- Tabel gabung untuk HP -->
            <div class="d-block d-md-none">
              <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th style="width:50px; text-align:center;">No</th>
                    <th>Komoditas</th>
                    <th style="width:150px; text-align:right;">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no=1; foreach ($pemotonganTotal as $p): ?>
                      <tr>
                        <td style="text-align:center;"><?= $no++ ?></td>
                        <td><?= $p->nama_komoditas ?></td>
                        <td style="text-align:right;"><?= number_format($p->total, 0, ',', '.') ?></td>
                      </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Tabel terpisah jadi 2 kolom untuk desktop -->
            <div class="row d-none d-md-flex">
             <?php 
                if (!empty($pemotonganTotal)) {
                    $chunkSize = ceil(count($pemotonganTotal) / 2);
                    $chunked = array_chunk($pemotonganTotal, max($chunkSize, 1));
                } else {
                    $chunked = []; // biar foreach di bawah tidak error
                }
                $no = 1;
                ?>
                <?php foreach ($chunked as $group): ?>

                <div class="col-md-6">
                  <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                      <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Komoditas</th>
                        <th style="width:150px; text-align:right;">Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($group as $p): ?>
                          <tr>
                            <td style="text-align:center;"><?= $no++ ?></td>
                            <td><?= $p->nama_komoditas ?></td>
                            <td style="text-align:right;"><?= number_format($p->total, 0, ',', '.') ?></td>
                          </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endforeach; ?>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
     <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">Data Produksi Tahun 2025</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="produksiChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
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
      var areaChartData = {
      labels  : ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
      datasets: [
        {
          label               : 'Daging',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: <?= json_encode(array_values($chartData['Daging'])) ?>
        },
        {
          label               : 'Telur',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: <?= json_encode(array_values($chartData['Telur'])) ?>
        },
      ]
    }

    var produksiChartCanvas = $('#produksiChart').get(0).getContext('2d')
    var produksiChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    produksiChartData.datasets[0] = temp1
    produksiChartData.datasets[1] = temp0

    var produksiChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      datasetFill: false,
      scales: {
        yAxes: [{
          ticks: {
            callback: function(value) {
              // Format ribuan dengan titik
              return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var value = tooltipItem.yLabel;
            return data.datasets[tooltipItem.datasetIndex].label + ': ' +
              value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
        }
      }
    }

    new Chart(produksiChartCanvas, {
      type: 'bar',
      data: produksiChartData,
      options: produksiChartOptions
    })

</script>
</body>
</html>

  