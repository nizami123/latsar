document.getElementById('checkAll').addEventListener('change', function() {
    const checked = this.checked;
    document.querySelectorAll('.checkItem').forEach(cb => cb.checked = checked);
});

// Tombol hapus terpilih
document.getElementById('deleteSelected').addEventListener('click', function() {
    const selected = [];
    document.querySelectorAll('.checkItem:checked').forEach(cb => selected.push(cb.value));

    if(selected.length === 0){
    alert('Pilih data yang ingin dihapus!');
    return;
    }

    if(confirm('Hapus data yang terpilih?')){
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= site_url("masuk/delete_multiple") ?>';

    selected.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_masuk[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit(); // reload halaman setelah submit
    }
});

$('.btnEditMasuk').on('click', function () {
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
        url: '<?= base_url("masuk/getDesaByKecamatan") ?>',
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
  