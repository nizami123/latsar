CREATE PROCEDURE `sp_hitung_populasi_bulan`(
    IN p_bulan CHAR(2),
    IN p_tahun CHAR(4)
)
BEGIN
	
	DECLARE p_bulan_prev CHAR(2);
    DECLARE p_tahun_prev CHAR(4);
	
    IF p_bulan = '01' THEN
        SET p_bulan_prev = '12';
        SET p_tahun_prev = CAST(p_tahun - 1 AS CHAR(4));
    ELSE
        SET p_bulan_prev = LPAD(p_bulan - 1, 2, '0');
        SET p_tahun_prev = p_tahun;
    END IF;

    /* ===============================
       PRE-AGGREGATE POPULASI AWAL
    =============================== */
    DROP TEMPORARY TABLE IF EXISTS tmp_pop;
    CREATE TEMPORARY TABLE tmp_pop AS
    SELECT
        id_wilayah,
        kode_desa,
        id_komoditas,
        jenis_kelamin,
        umur,
        SUM(jumlah) AS pop_jumlah
    FROM v_populasi
    WHERE bulan = p_bulan_prev
      AND tahun = p_tahun_prev
    GROUP BY id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur;

    CREATE INDEX idx_tmp_pop ON tmp_pop
    (id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur);

    /* ===============================
       PRE-AGGREGATE TRANSAKSI
    =============================== */
    DROP TEMPORARY TABLE IF EXISTS tmp_trx;
    CREATE TEMPORARY TABLE tmp_trx AS
    SELECT
        id_wilayah,
        kode_desa,
        id_komoditas,
        jenis_kelamin,
        umur,
        SUM(kelahiran) AS kelahiran,
        SUM(masuk)     AS masuk,
        SUM(keluar)    AS keluar,
        SUM(kematian)  AS kematian,
        SUM(pemotongan) AS pemotongan
    FROM (
        SELECT id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur,
               jumlah AS kelahiran, 0 masuk, 0 keluar, 0 kematian, 0 pemotongan
        FROM trx_kelahiran WHERE bulan=p_bulan AND tahun=p_tahun

        UNION ALL
        SELECT id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur,
               0, jumlah, 0, 0, 0
        FROM trx_masuk WHERE bulan=p_bulan AND tahun=p_tahun

        UNION ALL
        SELECT id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur,
               0, 0, jumlah, 0, 0
        FROM trx_keluar WHERE bulan=p_bulan AND tahun=p_tahun

        UNION ALL
        SELECT id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur,
               0, 0, 0, jumlah, 0
        FROM trx_kematian WHERE bulan=p_bulan AND tahun=p_tahun

        UNION ALL
        SELECT id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur,
               0, 0, 0, 0, jumlah
        FROM trx_pemotongan WHERE bulan=p_bulan AND tahun=p_tahun
    ) x
    GROUP BY id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur;

    CREATE INDEX idx_tmp_trx ON tmp_trx
    (id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur);
    
    
    DROP TEMPORARY TABLE IF EXISTS tmp_trx_wil;
    CREATE TEMPORARY TABLE tmp_trx_wil AS
     SELECT
        id_wilayah,
        NULL as kode_desa,
        id_komoditas,
        jenis_kelamin,
        umur,
        SUM(kelahiran) AS kelahiran,
        SUM(masuk)     AS masuk,
        SUM(keluar)    AS keluar,
        SUM(kematian)  AS kematian,
        SUM(pemotongan) AS pemotongan
    FROM (
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur,
               jumlah AS kelahiran, 0 masuk, 0 keluar, 0 kematian, 0 pemotongan
        FROM trx_kelahiran WHERE bulan=p_bulan AND tahun=p_tahun
        UNION ALL
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur,
               0, jumlah, 0, 0, 0
        FROM trx_masuk WHERE bulan=p_bulan AND tahun=p_tahun
        UNION ALL
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur,
               0, 0, jumlah, 0, 0
        FROM trx_keluar WHERE bulan=p_bulan AND tahun=p_tahun
        UNION ALL
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur,
               0, 0, 0, jumlah, 0
        FROM trx_kematian WHERE bulan=p_bulan AND tahun=p_tahun
        UNION ALL
        SELECT id_wilayah, id_komoditas, jenis_kelamin, umur,
               0, 0, 0, 0, jumlah
        FROM trx_pemotongan WHERE bulan=p_bulan AND tahun=p_tahun
    ) x
    GROUP BY id_wilayah, id_komoditas, jenis_kelamin, umur;
    
    CREATE INDEX idx_tmp_trx_wil ON tmp_trx_wil
    (id_wilayah, kode_desa, id_komoditas, jenis_kelamin, umur);

    /* ===============================
       RESULT SET
    =============================== */
--     
    DELETE FROM trx_populasi WHERE bulan = p_bulan AND tahun = p_tahun;
    
    INSERT INTO trx_populasi (
        bulan,
        tahun,
        id_wilayah,
        kode_desa,
        id_komoditas,
        jenis_kelamin,
        umur,
        jumlah        
    )

--     select * from tmp_pop;
    /* ===== MODE DESA ===== */
    SELECT
        p_bulan AS bulan,
        p_tahun AS tahun,
        d.id_wilayah,
        p.kode_desa,
        k.id_komoditas,
        p.jenis_kelamin,
        p.umur,
        (
            p.pop_jumlah
            + COALESCE(t.kelahiran,0)
            + COALESCE(t.masuk,0)
            - COALESCE(t.keluar,0)
            - COALESCE(t.kematian,0)
            - CASE WHEN k.id_komoditas=18 THEN 0 ELSE COALESCE(t.pemotongan,0) END
        ) AS jumlah
    FROM tmp_pop p
    JOIN master_komoditas k ON k.id_komoditas=p.id_komoditas
    JOIN master_wilayah d ON d.id_wilayah=p.id_wilayah
    LEFT JOIN tmp_trx t
        ON t.id_wilayah=p.id_wilayah
       AND t.kode_desa=p.kode_desa
       AND t.id_komoditas=p.id_komoditas
       AND t.jenis_kelamin<=>p.jenis_kelamin
       AND t.umur<=>p.umur
    WHERE p.kode_desa IS NOT NULL

    UNION ALL
-- 
--     /* ===== MODE WILAYAH ===== */
    SELECT
        p_bulan,
        p_tahun,
        p.id_wilayah,
        NULL AS kode_desa,
        p.id_komoditas,
        p.jenis_kelamin,
        p.umur,
        (
            p.pop_jumlah
            + COALESCE(t.kelahiran,0)
            + COALESCE(t.masuk,0)
            - COALESCE(t.keluar,0)
            - COALESCE(t.kematian,0)
            - CASE WHEN p.id_komoditas=18 THEN 0 ELSE COALESCE(t.pemotongan,0) END
        ) AS jumlah
    FROM tmp_pop p
    LEFT JOIN tmp_trx_wil t
        ON t.id_wilayah=p.id_wilayah
       AND t.id_komoditas=p.id_komoditas
       AND t.jenis_kelamin <=> p.jenis_kelamin
       AND t.umur <=> p.umur
    WHERE p.kode_desa IS NULL;
--     
    SELECT * FROM trx_populasi
    WHERE bulan = p_bulan AND tahun = p_tahun
    ORDER BY id_wilayah, id_komoditas, jenis_kelamin, umur;

END