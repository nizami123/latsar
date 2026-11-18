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
          <table id="example1" class="table table-bordered table-striped text-center">
            <thead>
              <tr>
                <th style="width: 5%;">
                  <div class="custom-checkbox">
                    <input type="checkbox" id="checkAll">
                    <label for="checkAll"></label>
                  </div>
                </th>
                <th>Tanggal</th>
                <?php foreach($kolom as $k): ?>
                <th><?= $k->nama_komoditas ?></th>
                <?php endforeach; ?>
                <th style="width: 15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($harga as $row): ?>
              <tr>
                <td>
                  <div class="custom-checkbox">
                    <input type="checkbox" class="checkItem" id="cb<?= $row['tanggal'] ?>" value="<?= $row['tanggal'] ?>">
                    <label for="cb<?= $row['tanggal'] ?>"></label>
                  </div>
                </td>
                <td data-order="<?= $row['tanggal'] ?>"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <?php foreach($kolom as $k): ?>
                <td class="text-right">
                  <?= isset($row['harga'][$k->id_komoditas]) ? number_format($row['harga'][$k->id_komoditas], 0, ',', '.') : '-' ?>
                </td>
                <?php endforeach; ?>
                <td>
                  <a href="<?= site_url('harga/upload_web/'.$row['tanggal']) ?>" class="btn btn-success btn-sm"
                     onclick="return confirm('Upload harga tanggal <?= date('d/m/Y', strtotime($row['tanggal'])) ?> ke web?')">Upload</a>
                  <a href="<?= site_url('harga/delete_tanggal/'.$row['tanggal']) ?>" class="btn btn-danger btn-sm"
                     onclick="return confirm('Hapus semua harga tanggal ini?')">Hapus</a>
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
