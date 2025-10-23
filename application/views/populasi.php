<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h5>Populasi Ternak</h5>
      <div>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">+ Tambah</button>
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">Upload Excel</button>
        <a href="<?= site_url('populasi/download_template') ?>" class="btn btn-success btn-sm">Download Template</a>
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
                <th style="width: 5%; text-align: center;">
                  <div class="custom-checkbox">
                    <input type="checkbox" id="checkAll">
                    <label for="checkAll"></label>
                  </div>
                </th>
                <th>Bulan Tahun</th>
                <th>Desa</th>
                <th>Kecamatan</th>
                <th>Komoditas</th>
                <th>Jumlah</th>
                <th style="width: 12%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($populasi as $p): ?>
              <tr>
                <td style="text-align: center;">
                  <div class="custom-checkbox">
                    <input type="checkbox" class="checkItem" id="cb<?= $p->id_populasi ?>" value="<?= $p->id_populasi ?>">
                    <label for="cb<?= $p->id_populasi ?>"></label>
                  </div>
                </td>
                <td><?= nama_bulan($p->bulan) ?> <?= $p->tahun ?></td>
                <td><?= $p->nama_desa ?></td>
                <td><?= $p->nama_wilayah ?></td>
                <td>
                    <?= $p->nama_komoditas ?>
                    <?php if (!empty($p->jenis_kelamin)): ?>
                        - <?= $p->jenis_kelamin ?>
                    <?php endif; ?>
                    <?php if (!empty($p->umur)): ?>
                        - <?= $p->umur ?>
                    <?php endif; ?>
                </td>
                <td><?= $p->jumlah ?></td>
                <td>
                  <button 
                    class="btn btn-warning btn-sm btnEditPopulasi" 
                    data-id="<?= $p->id_populasi ?>" 
                    data-bulan="<?= $p->bulan ?>" 
                    data-tahun="<?= $p->tahun ?>" 
                    data-komoditas="<?= $p->id_komoditas ?>"
                    data-wilayah="<?= $p->kode ?>"
                    data-jumlah="<?= $p->jumlah ?>"
                    data-kode_desa="<?= $p->kode_desa ?>"
                    data-toggle="modal" 
                    data-target="#editModal">
                    Edit
                  </button>
                  <a href="<?= site_url('populasi/delete/'.$p->id_populasi) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
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

<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <form action="<?= site_url('populasi/save') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Populasi</h5></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Bulan</label>
            <?= bulan_dropdown('bulan') ?>
          </div>
          <div class="form-group">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" required value="<?= date('Y') ?>">
          </div>

          <!-- Kecamatan -->
          <div class="form-group">
            <label>Kecamatan</label>
            <select name="id_wilayah" id="selectKecamatan" class="form-control" required>
              <option value="" selected>-- Pilih Kecamatan --</option>
              <?php foreach($wilayah as $w): ?>
                <option value="<?= $w->kode ?>"><?= $w->nama_wilayah ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Desa (baru) -->
          <div class="form-group">
            <label>Desa</label>
            <select name="kode_desa" id="selectDesa" class="form-control" required>
              <option value="">-- Pilih Desa --</option>
            </select>
          </div>

          <!-- Komoditas -->
          <div class="form-group">
            <label>Komoditas</label>
            <select name="id_komoditas" class="form-control" required>
              <option value="" selected>-- Pilih Komoditas --</option>
              <?php foreach($komoditas as $k): ?>
                <option value="<?= $k->id_komoditas ?>"><?= $k->nama_komoditas ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- 🔹 Modal Edit -->
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <form action="<?= site_url('populasi/update') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Populasi</h5></div>
        <div class="modal-body">
          <input type="hidden" name="id_populasi" id="edit_id">

          <div class="form-group">
            <label>Bulan</label>
            <?= bulan_dropdown('bulan', $p->bulan, "id='edit_bulan'") ?>
          </div>

          <div class="form-group">
            <label>Tahun</label>
            <input type="number" name="tahun" id="edit_tahun" class="form-control" required>
          </div>

          <!-- Kecamatan -->
          <div class="form-group">
            <label>Kecamatan</label>
            <select name="id_wilayah" id="edit_wilayah" class="form-control" required>
              <option value="">-- Pilih Kecamatan --</option>
              <?php foreach($wilayah as $w): ?>
              <option value="<?= $w->kode ?>"><?= $w->nama_wilayah ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Desa -->
          <div class="form-group">
            <label>Desa</label>
            <select name="kode_desa" id="edit_desa" class="form-control" required>
              <option value="">-- Pilih Desa --</option>
            </select>
          </div>

          <!-- Komoditas -->
          <div class="form-group">
            <label>Komoditas</label>
            <select name="id_komoditas" id="edit_komoditas" class="form-control" required>
              <?php foreach($komoditas as $k): ?>
              <option value="<?= $k->id_komoditas ?>"><?= $k->nama_komoditas ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" id="edit_jumlah" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- 🔹 Modal Upload -->
<div class="modal fade" id="uploadModal">
  <div class="modal-dialog">
    <form action="<?= site_url('populasi/upload_excel') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Upload Excel Populasi</h5></div>
        <div class="modal-body">
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
