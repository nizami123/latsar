<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h5>Pasar Hewan</h5>
      <div>
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
          Upload Excel
        </button>
        <a href="<?= site_url('pasar_hewan/download_template') ?>" class="btn btn-success btn-sm">
          Download Template
        </a>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">

          <table id="example1" class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th style="width:5%">No</th>
                <th>Bulan Tahun</th>
                <th>Desa</th>
                <th>Kecamatan</th>
                <th>Komoditas</th>
                <th>Status</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach($pasar as $p): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= nama_bulan($p->bulan) ?> <?= $p->tahun ?></td>
                <td><?= $p->nama_desa ?></td>
                <td><?= $p->nama_wilayah ?></td>
                <td><?= $p->nama_komoditas ?></td>
                <td>
                  <span class="badge badge-<?= $p->status_pasar == 'Masuk' ? 'primary' : 'success' ?>">
                    <?= $p->status_pasar ?>
                  </span>
                </td>
                <td><?= $p->jumlah ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </section>
</div>

<!-- 🔹 Modal Upload Excel -->
<div class="modal fade" id="uploadModal">
  <div class="modal-dialog">
    <form action="<?= site_url('pasar_hewan/upload_excel') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Excel Pasar Hewan</h5>
        </div>
        <div class="modal-body">

          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
              <?= $this->session->flashdata('error'); ?>
            </div>
          <?php endif; ?>

          <div class="form-group">
            <label>Bulan</label>
            <?= bulan_dropdown('bulan') ?>
          </div>

          <div class="form-group">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" required value="<?= date('Y') ?>">
          </div>

          <div class="form-group">
            <label>File Excel</label>
            <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Upload</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
