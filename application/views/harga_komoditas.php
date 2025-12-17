<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h5>Harga Komoditas</h5>
      <div>
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
          Upload Excel
        </button>
        <a href="<?= site_url('Harga_komoditas/download_template') ?>"
           class="btn btn-success btn-sm">
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
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Komoditas</th>
                <th>Satuan</th>
                <th style="text-align:right;">Harga</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($harga as $h): ?>
              <tr>
                <td><?= nama_bulan($h->bulan) ?></td>
                <td><?= $h->tahun ?></td>
                <td><?= $h->nama_komoditas ?></td>
                <td><?= $h->satuan ?></td>
                <td style="text-align:right;">
                  <?= number_format($h->harga, 0, ',', '.') ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="uploadModal">
  <div class="modal-dialog">
    <form action="<?= site_url('Harga_komoditas/import') ?>"
          method="post"
          enctype="multipart/form-data">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Upload Excel Harga Komoditas</h5>
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
            <input type="number"
                   name="tahun"
                   class="form-control"
                   required
                   value="<?= date('Y') ?>">
          </div>

          <div class="form-group">
            <label>File Excel</label>
            <input type="file"
                   name="file_excel"
                   class="form-control"
                   accept=".xls,.xlsx"
                   required>
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
