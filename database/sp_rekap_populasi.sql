CREATE PROCEDURE `sp_rekap_populasi`(
    IN p_bulan VARCHAR(2),
    IN p_tahun VARCHAR(4)
)
BEGIN
    DECLARE p_bulan_sebelum INT;
    DECLARE p_tahun_sebelum INT;

    -- Hitung bulan dan tahun sebelumnya
IF p_tahun > 2025 OR (p_tahun = 2025 AND p_bulan >= 10) THEN
    SET p_bulan_sebelum = MONTH(DATE_SUB(CONCAT(p_tahun, '-', LPAD(p_bulan,2,'0'), '-01'), INTERVAL 1 MONTH));
    SET p_tahun_sebelum  = YEAR(DATE_SUB(CONCAT(p_tahun, '-', LPAD(p_bulan,2,'0'), '-01'), INTERVAL 1 MONTH));
ELSE
    SET p_bulan_sebelum = p_bulan;
    SET p_tahun_sebelum  = p_tahun;
END IF;


    -- Hapus data populasi lama untuk bulan/tahun tersebut
    DELETE FROM trx_populasi WHERE bulan = p_bulan AND tahun = p_tahun;

    -- Masukkan hasil rekap baru
    INSERT INTO trx_populasi (
        bulan,
        tahun,
        id_komoditas,
        jumlah,
        id_wilayah,
        id_user,
        kode_desa,
        jenis_kelamin,
        umur
    )
        SELECT
        p_bulan,
        p_tahun,
        k.id_komoditas,
        (
        COALESCE(p.pop_jumlah, 0)
        + COALESCE(kb.kelahiran_jumlah, 0)
        + COALESCE(ms.masuk_jumlah, 0)
        - COALESCE(kl.keluar_jumlah, 0)
        - COALESCE(kt.kematian_jumlah, 0)
        - CASE 
            WHEN k.id_komoditas = 18 THEN 0 
            ELSE COALESCE(pm.pemotongan_jumlah, 0)
          END
    	) AS jumlah,
        d.id_wilayah,
        3 AS id_user,
        NULL AS kode_desa,
        COALESCE(p.jenis_kelamin, kb.jenis_kelamin, ms.jenis_kelamin, kl.jenis_kelamin, kt.jenis_kelamin, pm.jenis_kelamin) AS jenis_kelamin,
        COALESCE(p.umur, kb.umur, ms.umur, kl.umur, kt.umur, pm.umur) AS umur
    FROM master_wilayah d
    CROSS JOIN master_komoditas k

    -- Populasi bulan sebelumnya
    LEFT JOIN (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur, SUM(jumlah) AS pop_jumlah
        FROM v_populasi
        WHERE bulan = p_bulan_sebelum
          AND tahun = p_tahun_sebelum
        GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur
    ) p ON p.id_wilayah = d.id_wilayah AND p.id_komoditas = k.id_komoditas

    -- Kelahiran
    LEFT JOIN (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur, SUM(jumlah) AS kelahiran_jumlah
        FROM trx_kelahiran
        WHERE bulan = p_bulan AND tahun = p_tahun
        GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur
    ) kb ON kb.id_wilayah = d.id_wilayah
         AND kb.id_komoditas = k.id_komoditas
         AND (
             kb.jenis_kelamin = p.jenis_kelamin
             OR (kb.jenis_kelamin IS NULL AND p.jenis_kelamin IS NULL)
         )
         AND (
             kb.umur = p.umur
             OR (kb.umur IS NULL AND p.umur IS NULL)
         )

    -- Kematian
    LEFT JOIN (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur, SUM(jumlah) AS kematian_jumlah
        FROM trx_kematian
        WHERE bulan = p_bulan AND tahun = p_tahun
        GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur
    ) kt ON kt.id_wilayah = d.id_wilayah
         AND kt.id_komoditas = k.id_komoditas
         AND (
             kt.jenis_kelamin = p.jenis_kelamin
             OR (kt.jenis_kelamin IS NULL AND p.jenis_kelamin IS NULL)
         )
         AND (
             kt.umur = p.umur
             OR (kt.umur IS NULL AND p.umur IS NULL)
         )

    -- Keluar
    LEFT JOIN (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur, SUM(jumlah) AS keluar_jumlah
        FROM trx_keluar
        WHERE bulan = p_bulan AND tahun = p_tahun
        GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur
    ) kl ON kl.id_wilayah = d.id_wilayah
         AND kl.id_komoditas = k.id_komoditas
         AND (
             kl.jenis_kelamin = p.jenis_kelamin
             OR (kl.jenis_kelamin IS NULL AND p.jenis_kelamin IS NULL)
         )
         AND (
             kl.umur = p.umur
             OR (kl.umur IS NULL AND p.umur IS NULL)
         )

    -- Masuk
    LEFT JOIN (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur, SUM(jumlah) AS masuk_jumlah
        FROM trx_masuk
        WHERE bulan = p_bulan AND tahun = p_tahun
        GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur
    ) ms ON ms.id_wilayah = d.id_wilayah
         AND ms.id_komoditas = k.id_komoditas
         AND (
             ms.jenis_kelamin = p.jenis_kelamin
             OR (ms.jenis_kelamin IS NULL AND p.jenis_kelamin IS NULL)
         )
         AND (
             ms.umur = p.umur
             OR (ms.umur IS NULL AND p.umur IS NULL)
         )

    -- Pemotongan
    LEFT JOIN (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur, SUM(jumlah) AS pemotongan_jumlah
        FROM trx_pemotongan
        WHERE bulan = p_bulan AND tahun = p_tahun
        GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur
    ) pm ON pm.id_wilayah = d.id_wilayah
         AND pm.id_komoditas = k.id_komoditas
         AND (
             pm.jenis_kelamin = p.jenis_kelamin
             OR (pm.jenis_kelamin IS NULL AND p.jenis_kelamin IS NULL)
         )
         AND (
             pm.umur = p.umur
             OR (pm.umur IS NULL AND p.umur IS NULL)
         )

    WHERE k.urut IS NOT NULL
      AND d.urut < 100
    ORDER BY d.urut ASC, k.urut ASC, jenis_kelamin, umur;

    -- Tampilkan hasil rekap terbaru
    SELECT * FROM trx_populasi
    WHERE bulan = p_bulan AND tahun = p_tahun
    ORDER BY id_wilayah, id_komoditas, jenis_kelamin, umur;
END