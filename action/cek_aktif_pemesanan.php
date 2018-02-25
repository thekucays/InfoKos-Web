<?php
//Update data pemesanan yang kadaluarsa
mysql_query("UPDATE pemesanan 
    SET aktif = 0 
    WHERE id IN (
        SELECT id 
        FROM pemesanan 
        WHERE aktif = 1 
            AND batas_akhir < '" . date('Y-m-d H:i:s') . "' 
            AND (SELECT SUM(jumlah) 
                FROM pembayaran 
                WHERE pembayaran.pemesanan_id = pemesanan.id) IS NULL)");

//Buat pemesanan ketika masa kontrak kamar habis dengan jangka waktu pemesanan 1 minggu
$data = mysql_query('
    SELECT kamar.id,
        pemesanan.tgl_keluar,
        pemesanan.pelanggan_id,
        harga_kamar.type,
        harga_kamar.jumlah_orang
    FROM kamar
        LEFT JOIN harga_kamar
            ON harga_kamar.kamar_id = kamar.id
        LEFT JOIN pemesanan 
            ON pemesanan.harga_kamar_id = harga_kamar.id
    WHERE kamar.status = "isi"
        AND pemesanan.aktif = 1
        AND pemesanan.tgl_keluar < "'.date('Y-m-d').'"');

while($r = mysql_fetch_array($data)){
    mysql_query('INSERT INTO pemesanan(tgl_masuk,
                                        tgl_keluar,
                                        harga_kamar_id,
                                        tanggal,
                                        batas_akhir,
                                        aktif,
                                        pelanggan_id)
                                VALUES("'.date('Y-m-d', strtotime('+1 day', strtotime($r['tgl_keluar']))).'",
                                        "'.date('Y-m-d', strtotime('+1 day 1 year', strtotime($r['tgl_keluar']))).'",
                                        (SELECT harga_kamar.id
                                        FROM harga_kamar
                                            LEFT JOIN periode
                                                ON periode.id = harga_kamar.periode_id
                                        WHERE periode.aktif = 1
                                            AND harga_kamar.type = "'.$r['type'].'"
                                            AND harga_kamar.jumlah_orang = '.$r['jumlah_orang'].'
                                            AND harga_kamar.kamar_id = '.$r['id'].'
                                        LIMIT 1),
                                        "'.date('Y-m-d H:i:s').'",
                                        "'.date('Y-m-d H:i:s', strtotime('+1 week')).'",
                                        1,
                                        '.$r['pelanggan_id'].'
                                        )');
}

//Update data kamar dari status isi ke status dipesan (berhubungan dengan query diatas)
mysql_query('
    UPDATE kamar 
    SET status = "dipesan" 
    WHERE id IN (
        SELECT kamar.id
        FROM kamar
            LEFT JOIN harga_kamar
                ON harga_kamar.kamar_id = kamar.id
            LEFT JOIN pemesanan 
                ON pemesanan.harga_kamar_id = harga_kamar.id
        WHERE kamar.status = "isi"
            AND pemesanan.aktif = 1
            AND pemesanan.tgl_keluar < "'.date('Y-m-d').'")');
?>
