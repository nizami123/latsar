<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid"><h5>Tambah Komoditas</h5></div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary">
        <div class="card-body">
          <form method="post" action="<?= site_url('komoditas/save') ?>">
            <div class="form-group">
              <label>Nama Komoditas</label>
              <input type="text" name="nama_komoditas" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Jenis</label>
              <select name="jenis" class="form-control" required>
                <option value="">--Pilih Jenis--</option>
                <option value="Ternak Besar">Ternak Besar</option>
                <option value="Ternak Kecil">Ternak Kecil</option>
                <option value="Unggas">Unggas</option>
                <option value="Aneka Ternak">Aneka Ternak</option>
                <option value="Hasil Ternak">Hasil Ternak</option>
              </select>
            </div>
            <div class="form-group">
              <label>Satuan</label>
              <input type="text" name="satuan" class="form-control" required placeholder="Contoh: ekor, kg, liter">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="<?= site_url('komoditas') ?>" class="btn btn-secondary">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
