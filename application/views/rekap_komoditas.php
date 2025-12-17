<style>
table {
    border-collapse: collapse;
    width: 100%;
    font-size: 13px;
}
th, td {
    border: 1px solid #000;
    padding: 5px;
}

#tblHarga th,
#tblHarga td {
    text-align: center !important;
    vertical-align: middle !important;
}


th,td {
    background: black;
    color: white;
}
td:first-child, th:first-child {
    position: sticky;
    left: 0;
    z-index: 3;
    background: black;
    color: white;
}
thead th {
    position: sticky;
    top: 0;
    z-index: 2;
}
</style>

<div class="content-wrapper">
<section class="content">
<div class="card">

<div class="card-header bg-secondary text-white d-flex align-items-center">
    <h3 class="card-title">Rekap <?= $tabel ?></h3>

    <div class="ml-auto form-inline">
        <select id="filterBulan" class="form-control form-control-sm mr-2">
            <?php for ($i=1;$i<=12;$i++): ?>
                <option value="<?= $i ?>" <?= ($i==$bulan?'selected':'') ?>>
                    <?= nama_bulan($i) ?>
                </option>
            <?php endfor; ?>
        </select>

        <select id="filterTahun" class="form-control form-control-sm mr-2">
            <?php for ($t=date('Y');$t>=2020;$t--): ?>
                <option value="<?= $t ?>" <?= ($t==$tahun?'selected':'') ?>>
                    <?= $t ?>
                </option>
            <?php endfor; ?>
        </select>

        <button id="btnFilter" class="btn btn-sm btn-light mr-2">
            <i class="fas fa-search"></i> Tampilkan
        </button>

        <button id="btnExport" class="btn btn-sm btn-light">
            <i class="fas fa-file"></i> Export
        </button>
    </div>
</div>

<div class="card-body table-responsive">

<?php
// GROUP KOMODITAS BY KLASIFIKASI
$group = [];
foreach ($komoditas as $k) {
    $group[$k->klasifikasi][] = $k;
}
?>

<table id="tblHarga" class="table table-bordered table-sm">
<thead>

<tr>
    <th rowspan="2">Kecamatan</th>
    <?php foreach ($group as $klasifikasi => $items): ?>
        <th colspan="<?= count($items) ?>" style="text-align:center;">
            <?= $klasifikasi ?>
        </th>
    <?php endforeach; ?>
</tr>

<tr>
    <?php foreach ($group as $items): ?>
        <?php foreach ($items as $k): ?>
            <th><?= $k->nama_komoditas ?></th>
        <?php endforeach; ?>
    <?php endforeach; ?>
</tr>

</thead>

<tbody id="bodyHarga">

<?php
$jumlahKec = count($pivot);
$rata2 = [];

// init
foreach ($group as $items) {
    foreach ($items as $k) {
        $rata2[$k->nama_komoditas] = 0;
    }
}
?>

<?php foreach ($pivot as $kec => $row): ?>
<tr>
    <td><?= $kec ?></td>
    <?php foreach ($group as $items): ?>
        <?php foreach ($items as $k): ?>
            <?php
                $val = $row[$k->nama_komoditas] ?? 0;
                $rata2[$k->nama_komoditas] += $val;
            ?>
            <td style="text-align:right;">
                <?= number_format($val) ?>
            </td>
        <?php endforeach; ?>
    <?php endforeach; ?>
</tr>
<?php endforeach; ?>

<!-- RATA-RATA -->
<tr style="font-weight:bold;">
    <td>Rata-rata</td>
    <?php foreach ($group as $items): ?>
        <?php foreach ($items as $k): ?>
            <td style="text-align:right;">
                <?= number_format(
                    $jumlahKec ? ($rata2[$k->nama_komoditas] / $jumlahKec) : 0
                ) ?>
            </td>
        <?php endforeach; ?>
    <?php endforeach; ?>
</tr>

</tbody>
</table>

</div>
</div>
</section>
</div>

<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>

<script>
$('#btnFilter').click(function(){
    $.ajax({
        url: "<?= base_url('harga_komoditas/get_data_harga') ?>",
        data: {
            bulan: $('#filterBulan').val(),
            tahun: $('#filterTahun').val()
        },
        dataType: 'json',
        success: function(res){
            let html = '';
            let total = {};
            let jumlahKec = Object.keys(res.data).length;

            res.komoditas.forEach(k => total[k] = 0);

            $.each(res.data, function(kec, row){
                html += `<tr><td>${kec}</td>`;
                res.komoditas.forEach(function(kom){
                    let val = row?.[kom] ?? 0;
                    total[kom] += val;
                    html += `<td style="text-align:right;">${Number(val).toLocaleString()}</td>`;
                });
                html += `</tr>`;
            });

            // RATA-RATA
           html += `<tr style="font-weight:bold;"><td>Rata-rata</td>`;
            res.komoditas.forEach(function(kom){
                let avg = jumlahKec ? (total[kom] / jumlahKec) : 0;
                html += `<td style="text-align:right;">${Math.round(avg).toLocaleString('en-US')}</td>`;
            });
            html += `</tr>`;


            $('#bodyHarga').html(html);
        }
    });
});

// EXPORT
$('#btnExport').click(function(){
    let bulan = $('#filterBulan').val();
    let tahun = $('#filterTahun').val();
    window.location.href =
        "<?= base_url('harga_komoditas/export') ?>?bulan="+bulan+"&tahun="+tahun;
});
</script>
