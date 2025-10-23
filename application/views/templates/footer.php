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
      order: [],
      columnDefs: [
        { orderable: false, targets: 0 } // kolom pertama tidak bisa di-sort
      ],
      buttons: [
        {
          extend: 'excel',
          title: '<?=$title?>',
          filename: '<?=$filename?>',
          exportOptions: {
            columns: ':not(:last-child)'
          },
          customize: function (xlsx) {
            var sheet = xlsx.xl.worksheets['sheet1.xml'];

            // set lebar tiap kolom
            $('col', sheet).each(function() {
              $(this).attr('width', 20); // sesuaikan angka untuk lebar
            });
          }
        },
        {
          extend: 'pdf',
          title: '<?=$title?>',
          filename: '<?=$filename?>',
          exportOptions: {
            columns: ':not(:last-child)'
          },
          customize: function (doc) {
            doc.styles.tableHeader.alignment = 'center';
            doc.styles.tableHeader.fillColor = '#d3d3d3';
            
            // beri padding pada seluruh sel
            doc.content[1].table.body.forEach(function(row) {
              row.forEach(function(cell) {
                cell.margin = [5, 5, 5, 2]; // kiri, atas, kanan, bawah
              });
            });
          }
        },
        {
          extend: 'print',
          title: '<?=$title?>',
          exportOptions: {
            columns: ':not(:last-child)'
          },
          customize: function (win) {
            $(win.document.body).find('table').css({
              'border-collapse': 'collapse',
              'width': '100%'
            });
            $(win.document.body).find('th, td').css({
              'padding': '8px',  // jarak antar kolom
              'border': '1px solid #ccc'
            });
          }
        },
        {
          text: 'Hapus',
          className: 'btn btn-danger',
          action: function (e, dt, node, config) {
            let selected = [];
            dt.rows().nodes().to$().find('.checkItem:checked').each(function() {
              selected.push($(this).val());
            });

            if (selected.length === 0) {
              alert('Pilih data yang ingin dihapus!');
              return;
            }

            if (confirm('Hapus data yang terpilih?')) {
              let form = $('<form>', {
                method: 'POST',
                action: '<?= site_url($this->uri->segment(1) . "/delete_multiple") ?>'
              });

              selected.forEach(function(id) {
                form.append($('<input>', {
                  type: 'hidden',
                  name: 'id_<?=$this->uri->segment(1)?>[]',
                  value: id
                }));
              });

              $('body').append(form);
              form.submit();
            }
          }
        }
      ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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
        url: "<?= site_url($this->uri->segment(1) .'/get_desa_by_kecamatan') ?>/" + codeKecamatan,
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

