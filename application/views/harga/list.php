<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h5>Transaksi Harga</h5></div>
        <div class="col-sm-6 text-right">
          <a href="<?= site_url('harga/add') ?>" class="btn btn-primary btn-sm">+ Tambah</a>
          <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadModal">Upload Excel</button>
          <a href="<?= site_url('harga/download_template') ?>" class="btn btn-info btn-sm">Download Template</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Komoditas</th>
                <th>Harga</th>
                <th style="width: 15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($harga as $row): ?>
              <tr>
                <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                <td><?= $row->nama_komoditas ?></td>
                <td><?= number_format($row->harga, 0, ',', '.') ?></td>
                <td>
                  <a href="<?= site_url('harga/edit/'.$row->id_harga) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="<?= site_url('harga/delete/'.$row->id_harga) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
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
    <form action="<?= site_url('harga/upload') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Excel Harga</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Upload</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
