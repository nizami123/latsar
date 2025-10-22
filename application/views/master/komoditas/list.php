<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h5>Master Komoditas</h5></div>
        <div class="col-sm-6 text-right">
          <a href="<?= site_url('komoditas/add') ?>" class="btn btn-primary btn-sm">+ Tambah</a>
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
                <th style="width: 50%">Nama Komoditas</th>
                <th>Jenis</th>
                <th>Satuan</th>
                <th style="width: 20%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach($komoditas as $row): ?>
              <tr>
                <td><?= $row->nama_komoditas ?></td>
                <td><?= $row->jenis ?></td>
                <td><?= $row->satuan ?></td>
                <td>
                  <a href="<?= site_url('komoditas/edit/'.$row->id_komoditas) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="<?= site_url('komoditas/delete/'.$row->id_komoditas) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
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
