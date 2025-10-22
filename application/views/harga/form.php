<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h5><?= isset($row) ? 'Edit' : 'Tambah' ?> Harga</h5>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form method="post">
            <div class="form-group">
              <div class="row">
                <div class="col-6">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" value="<?= isset($row) ? $row->tanggal : '' ?>" required>
                </div>
                <div class="col-6">
                  <label>Komoditas</label>
                    <select name="id_komoditas" class="form-control" required>
                      <option value="">-- Pilih --</option>
                      <?php foreach($komoditas as $k): ?>
                        <option value="<?= $k->id_komoditas ?>" <?= isset($row) && $row->id_komoditas == $k->id_komoditas ? 'selected' : '' ?>>
                          <?= $k->nama_komoditas ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                </div>
              </div>
              
            </div>

            <div class="form-group">
              <label>Harga</label>
              <input type="number" name="harga" class="form-control" value="<?= isset($row) ? number_format($row->harga, 0, '.', '') : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="<?= site_url('harga') ?>" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
