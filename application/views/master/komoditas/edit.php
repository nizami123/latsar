<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid"><h5>Edit Komoditas</h5></div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary">
        <div class="card-body">
          <form method="post" action="<?= site_url('komoditas/update/'.$komoditas->id_komoditas) ?>">
            <div class="form-group">
              <label>Nama Komoditas</label>
              <input type="text" name="nama_komoditas" value="<?= $komoditas->nama_komoditas ?>" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Jenis</label>
              <select name="jenis" class="form-control" required>
                <option value="Ternak Besar" <?= ($komoditas->jenis=="Ternak Besar")?'selected':'' ?>>Ternak Besar</option>
                <option value="Ternak Kecil" <?= ($komoditas->jenis=="Ternak Kecil")?'selected':'' ?>>Ternak Kecil</option>
                <option value="Unggas" <?= ($komoditas->jenis=="Unggas")?'selected':'' ?>>Unggas</option>
                <option value="Aneka Ternak" <?= ($komoditas->jenis=="Aneka Ternak")?'selected':'' ?>>Aneka Ternak</option>
                <option value="Hasil Ternak" <?= ($komoditas->jenis=="Hasil Ternak")?'selected':'' ?>>Hasil Ternak</option>
              </select>
            </div>
            <div class="form-group">
              <label>Satuan</label>
              <input type="text" name="satuan" value="<?= $komoditas->satuan ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= site_url('komoditas') ?>" class="btn btn-secondary">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
