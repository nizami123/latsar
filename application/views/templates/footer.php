</body>
</html>
<footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="#">Ahmad Alfian Nizami</a></strong>
  </footer>
</div>
<!-- jQuery -->
<script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=base_url()?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?=base_url()?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?=base_url()?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>assets/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    
    $(".btnEdit").click(function(){
      $("#edit_id").val($(this).data("id"));
      $("#edit_nama").val($(this).data("nama"));
    });

   $("#example1").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      buttons: [
        {
          extend: 'excel',
          title: '<?=$title?>',
          filename: '<?=$filename?>',
          exportOptions: {
            columns: ':not(:last-child)' // semua kolom kecuali kolom terakhir
          }
        },
        {
          extend: 'pdf',
          title: '<?=$title?>',
          filename: '<?=$filename?>',
          exportOptions: {
            columns: ':not(:last-child)'
          }
        },
        {
          extend: 'print',
          title: '<?=$title?>',
          exportOptions: {
            columns: ':not(:last-child)'
          }
        },
        {
          text: 'Hapus',
          className: 'btn btn-danger',
          action: function (e, dt, node, config) {
            let selected = [];
            // ambil semua checkbox tercentang di tabel
            dt.rows().nodes().to$().find('.checkItem:checked').each(function() {
              selected.push($(this).val());
            });

            if (selected.length === 0) {
              alert('Pilih data yang ingin dihapus!');
              return;
            }

            if (confirm('Hapus data yang terpilih?')) {
              // buat form dinamis untuk kirim POST
              let form = $('<form>', {
                method: 'POST',
                action: '<?= site_url("populasi/delete_multiple") ?>'
              });

              selected.forEach(function(id) {
                form.append($('<input>', {
                  type: 'hidden',
                  name: 'id_populasi[]',
                  value: id
                }));
              });

              $('body').append(form);
              form.submit(); // reload halaman otomatis setelah hapus
            }
          }
        }
      ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  $('.btnEditProduksi').on('click', function () {
    $('#edit_id').val($(this).data('id'));
    $('#edit_bulan').val($(this).data('bulan')).trigger('change');
    $('#edit_tahun').val($(this).data('tahun'));
    $('#edit_komoditas').val($(this).data('komoditas')).trigger('change');
    $('#edit_jumlah').val($(this).data('jumlah'));
    $('#edit_user').val($(this).data('user')).trigger('change');
    $('#edit_jenis').val($(this).data('jenis')).trigger('change'); // 🔹 jenis
  });

  $('.btnEditPopulasi').on('click', function () {
    var id         = $(this).data('id');
    var bulan      = $(this).data('bulan');
    var tahun      = $(this).data('tahun');
    var wilayah    = $(this).data('wilayah'); // kecamatan
    var komoditas  = $(this).data('komoditas');
    var jumlah     = $(this).data('jumlah');
    var desa       = $(this).data('kode_desa');    // tambahkan data-desa di tombol edit

    // Isi nilai input
    $('#edit_id').val(id);
    $('#edit_bulan').val(bulan);
    $('#edit_tahun').val(tahun);
    $('#edit_wilayah').val(wilayah);
    $('#edit_komoditas').val(komoditas);
    $('#edit_jumlah').val(jumlah);

    // Kosongkan dropdown desa dulu
    $('#edit_desa').html('<option value="">-- Pilih Desa --</option>');
    if (wilayah) {
      $.ajax({
        url: '<?= base_url("populasi/getDesaByKecamatan") ?>',
        type: 'POST',
        data: { kode: wilayah },
        dataType: 'json',
        success: function(response) {
          if (response && response.length > 0) {
            $.each(response, function(index, item) {
              console.log(desa);
              console.log(item);
              let selected = (item.kode_desa == desa) ? 'selected' : '';
              $('#edit_desa').append(
                `<option value="${item.kode_desa}" ${selected}>${item.nama_desa}</option>`
              );
            });
          } else {
            $('#edit_desa').append('<option value="">Tidak ada desa</option>');
          }
        },
        error: function() {
          alert('Gagal mengambil data desa.');
        }
      });
    }

    // Tampilkan modal edit
    $('#modalEdit').modal('show');
  });


  $('.btnEditPemotongan').on('click', function () {
    $('#edit_id').val($(this).data('id'));
    $('#edit_bulan').val($(this).data('bulan'));
    $('#edit_tahun').val($(this).data('tahun'));
    $('#edit_wilayah').val($(this).data('wilayah'));
    $('#edit_komoditas').val($(this).data('komoditas'));
    $('#edit_jumlah').val($(this).data('jumlah'));
  });


  $('#selectKecamatan').on('change', function() {
    const codeKecamatan = $(this).val();
    const desaSelect = $('#selectDesa');

    desaSelect.html('<option value="">Loading...</option>');

    if (codeKecamatan) {
      $.ajax({
        url: "<?= site_url('populasi/get_desa_by_kecamatan') ?>/" + codeKecamatan,
        type: "GET",
        dataType: "json",
        success: function(res) {
          desaSelect.empty().append('<option value="">-- Pilih Desa --</option>');
          if (res.length > 0) {
            $.each(res, function(i, item) {
              desaSelect.append('<option value="' + item.kode_desa + '">' + item.nama_desa + '</option>');
            });
          } else {
            desaSelect.append('<option value="">(Tidak ada desa)</option>');
          }
        },
        error: function() {
          desaSelect.html('<option value="">Gagal memuat desa</option>');
        }
      });
    } else {
      desaSelect.html('<option value="">-- Pilih Desa --</option>');
    }
  });

  

</script>
<?php if (!empty($js)): ?>
  <script src="<?= base_url('assets/js/' . $js) ?>"></script>
<?php endif; ?>
</body>
</html>

