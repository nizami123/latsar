<style>
table {
    border-collapse: collapse;
    width: 100%;
    font-size: 13px;
}
th, td {
    border: 1px solid #000;
    padding: 5px;
    text-align: center;
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
    <h3 class="card-title">Rekap Pasar Hewan</h3>

    <div class="ml-auto form-inline">
        <select id="filterKecamatan" class="form-control form-control-sm mr-2">
            <option value="">Semua Kecamatan</option>
            <?php foreach($kecamatan as $k): ?>
                <option value="<?= $k->kode ?>">
                    <?= $k->nama_wilayah ?>
                </option>
            <?php endforeach; ?>
        </select>
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

        <button id="btnFilter" class="btn btn-sm btn-light">
            <i class="fas fa-search"></i> Tampilkan
        </button>
    </div>
</div>

<div class="card-body table-responsive">

<table id="tblHarga" class="table table-bordered table-sm">
    <thead>
        <tr>
            <th rowspan="2">Kecamatan</th>
            <?php foreach($komoditas as $k): ?>
                <th colspan="2"><?= $k ?></th>
            <?php endforeach; ?>
        </tr>
        <tr>
            <?php foreach($komoditas as $k): ?>
                <th>Masuk</th>
                <th>Laku</th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody id="bodyPasar">
        <?php foreach ($kecamatan as $kec): 
            if (!isset($pivot[$kec->nama_wilayah])) continue; ?>
            <?php
                $namaKec = $kec->nama_wilayah;
                $dataKec = $pivot[$namaKec] ?? [];
            ?>
            <tr>
                <td><?= $namaKec ?></td>

                <?php foreach ($komoditas as $kom): ?>
                    <?php
                        $masuk = $dataKec[$kom]['Masuk'] ?? 0;
                        $laku  = $dataKec[$kom]['Laku']  ?? 0;
                    ?>
                    <td class="text-center"><?= $masuk ?></td>
                    <td class="text-center"><?= $laku ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot id="footerPasar">
        <tr>
            <th><strong>Total</strong></th>
            <?php
            // total per komoditas
            $totalmasuk = [];
            $totallaku  = [];
            foreach ($komoditas as $kom) {
                $totalmasuk[$kom] = 0;
                $totallaku[$kom] = 0;
            }

            foreach ($pivot as $namaKec => $dataKec) {
                foreach ($komoditas as $kom) {
                    $masuk = $dataKec[$kom]['Masuk'] ?? 0;
                    $laku  = $dataKec[$kom]['Laku']  ?? 0;

                    $totalmasuk[$kom] += $masuk;
                    $totallaku[$kom] += $laku;
                }
            }

            foreach ($komoditas as $kom): ?>
                <th><strong><?= $totalmasuk[$kom] ?></strong></th>
                <th><strong><?= $totallaku[$kom] ?></strong></th>
            <?php endforeach; ?>
        </tr>
    </tfoot>

</table>

</div>
</div>
</section>
</div>

<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<script>
$(document).ready(function () {

    $('#btnFilter').on('click', function () {
        let kecamatan = $('#filterKecamatan').val();
        let bulan     = $('#filterBulan').val();
        let tahun     = $('#filterTahun').val();

        $.ajax({
            url: "<?= base_url('pasar_hewan/rekap_data') ?>",
            type: "GET",
            dataType: "json",
            data: {
                kecamatan: kecamatan,
                bulan: bulan,
                tahun: tahun
            },
            success: function (res) {
                let html = '';
                let footer = `<tr><th><strong>Total</strong></th>`;

                // total per komoditas
                let totalmasuk = {};
                let totallaku  = {};
                $.each(res.komoditas, function (i, kom) {
                    totalmasuk[kom] = 0;
                    totallaku[kom] = 0;
                });

                // BODY
                $.each(res.pivot, function (namaKec, dataKec) {
                    html += `<tr><td>${namaKec}</td>`;

                    $.each(res.komoditas, function (i, kom) {
                        let masuk = dataKec[kom]?.Masuk ?? 0;
                        let laku  = dataKec[kom]?.Laku  ?? 0;

                        totalmasuk[kom] += masuk;
                        totallaku[kom] += laku;

                        html += `<td>${masuk}</td><td>${laku}</td>`;
                    });

                    html += `</tr>`;
                });

                // FOOTER TOTAL PER KOMODITAS
                $.each(res.komoditas, function (i, kom) {
                    footer += `<th><strong>${totalmasuk[kom]}</strong></th><th><strong>${totallaku[kom]}</strong></th>`;
                });

                footer += `</tr>`;

                $('#bodyPasar').html(html);
                $('#footerPasar').html(footer);
            }

        });
    });

});
</script>



