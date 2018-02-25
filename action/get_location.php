<?php

require_once '../config/koneksi.php';
if($_GET[id] != 'false'){
    $condition = " WHERE (kost.aktif = 1 OR kost.aktif IS NULL) AND type_tempat.id =".$_GET[id];
}else{
    $condition = 'WHERE (kost.aktif = 1 OR kost.aktif IS NULL)';
}
if(isset($_GET[id_tempat])){
    $condition = 'WHERE tempat.id ='.$_GET[id_tempat];
}

$lokasi = mysql_query("SELECT tempat.id,
                        tempat.nama AS nama_tempat,
                        tempat.alamat,
                        tempat.bujur,
                        tempat.lintang,
                        type_tempat.nama AS nama_type_tempat,
                        type_tempat.gambar,
                        kost.id AS id_kost,
                        kost.nama AS nama_kost,
                        (SELECT harga_kamar.harga
                            FROM harga_kamar
                                LEFT JOIN kamar
                                    ON kamar.id = harga_kamar.kamar_id
                                LEFT JOIN periode
                                    ON periode.id = harga_kamar.periode_id
                            WHERE kamar.kost_id = tempat.kost_id
                                AND periode.aktif = 1
                            ORDER BY type DESC 
                            LIMIT 1) AS harga_max,
                        (SELECT harga_kamar.type
                            FROM harga_kamar
                                LEFT JOIN kamar
                                    ON kamar.id = harga_kamar.kamar_id
                                LEFT JOIN periode
                                    ON periode.id = harga_kamar.periode_id
                        WHERE kamar.kost_id = tempat.kost_id
                            AND periode.aktif = 1
                        ORDER BY type DESC 
                        LIMIT 1) AS type_sewa
                        FROM tempat
                            LEFT JOIN type_tempat
                                ON type_tempat.id = tempat.type_tempat_id 
                            LEFT JOIN kost
                                ON kost.id = tempat.kost_id
                        ".$condition);
$data = array();
while ($r = mysql_fetch_array($lokasi)) {
    if(empty($r['nama_kost'])){
        $info = "$r[nama_tempat] (<b>$r[nama_type_tempat]</b>)<br/>$r[alamat]";
    }else{
        $info = "<a href='index.php?pages=detail_kost&id=$r[id_kost]'>$r[nama_kost]</a> (<b>$r[nama_type_tempat]</b>)<br/>$r[alamat]<br/>Harga Rp. ".number_format($r[harga_max],2,',','.')."/".$r[type_sewa];
    }
    $data[] = array(
        'id' => $r['id'],
        'bujur' => $r['bujur'],
        'lintang' => $r['lintang'],
        'info' => $info,
        'icon' => $r['gambar'],
        'nama' => $r['nama_tempat'],
        'id_kost' => $r['id_kost']
    );
}

echo json_encode($data);
die;
?>
