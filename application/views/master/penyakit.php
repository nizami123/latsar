<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h5>Master Penyakit</h5>
      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">+ Tambah Penyakit</button>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Nama Penyakit</th>
                <th style="width: 20%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($penyakit as $p): ?>
              <tr>
                <td><?= $p->nama_penyakit ?></td>
                <td>
                  <button 
                    class="btn btn-warning btn-sm btnEdit" 
                    data-id="<?= $p->id_penyakit ?>" 
                    data-nama="<?= $p->nama_penyakit ?>"
                    data-toggle="modal" 
                    data-target="#editModal">
                    Edit
                  </button>
                  <a href="<?= site_url('penyakit/delete/'.$p->id_penyakit) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
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

<!-- 🔹 Modal Add -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= site_url('penyakit/save') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Penyakit</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Penyakit</label>
            <input type="text" name="nama_penyakit" class="form-control" required>
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
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= site_url('penyakit/update') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Penyakit</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_penyakit" id="edit_id">
          <div class="form-group">
            <label>Nama Penyakit</label>
            <input type="text" name="nama_penyakit" id="edit_nama" class="form-control" required>
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