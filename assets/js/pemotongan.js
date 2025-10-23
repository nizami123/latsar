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
    form.action = '<?= site_url("pemotongan/delete_multiple") ?>';

    selected.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_pemotongan[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit(); // reload halaman setelah submit
    }
});