<div class="gallery">
    <h1>Daftar Kosan</h1>
    <?php
    require_once 'element/search_kost.php';
    $option = array();
    if (isset($_POST['nama']) && !empty($_POST['nama'])) {
        $option[] = 'kost.nama LIKE "%' . $_POST['nama'] . '%"';
    }

    if (isset($_POST['jenis'])) {
        if (count($_POST['jenis']) == 1) {
            $option[] = 'kamar.jenis = "' . $_POST['jenis'][0] . '"';
        } elseif (count($_POST['jenis']) == 2) {
            $option[] = '(SELECT COUNT(DISTINCT k.jenis) FROM kamar AS k WHERE k.kost_id = kost.id) = 2';
        }
    }

    if (isset($_POST['alamat']) && !empty($_POST['alamat'])) {
        $option[] = 'kost.alamat LIKE "%' . $_POST['alamat'] . '%"';
    }

    if (isset($_POST['fasilitas'])) {
        $in_fasilitas = implode(',', $_POST['fasilitas']);
        $option[] = 'fasilitas_kost.fasilitas_id IN (' . $in_fasilitas . ')';
    }
    
    if(isset($_POST['type_sewa']) && isset($_POST['harga_sewa']) && $_POST['type_sewa'] != '0' && $_POST['type_sewa'] != '0'){                                         
        
        $harga = explode('-', $_POST['harga_sewa']);
        if(isset($harga[1])){
            $option[] = 'harga_kamar.harga >='.$harga[0].' AND harga_kamar.harga <='.$harga[1]. ' AND type="'.$_POST['type_sewa'].'"';
        }else{
            $option[] = 'harga_kamar.harga >='.$harga[0]. ' AND type="'.$_POST['type_sewa'].'"';
        }
    }
    $conditions = '';
    if(count($option) > 0){
        $conditions = ' WHERE kost.aktif =1 AND '.  implode($option, ' AND ');
    }
    ?>
    <div>
        <ul>
            <?php
            $gambar = mysql_query("
                SELECT DISTINCT kost.*,
                    (SELECT lokasi
                        FROM gambar_kost
                        WHERE gambar_kost.kost_id = kost.id
                        AND type = 'cover'
                        LIMIT 1) AS gambar,
                    (SELECT harga_kamar.harga
                        FROM harga_kamar
                            LEFT JOIN kamar
                                ON kamar.id = harga_kamar.kamar_id
                            LEFT JOIN periode
                                ON periode.id = harga_kamar.periode_id
                        WHERE kamar.kost_id = kost.id
                            AND periode.aktif = 1
                        ORDER BY type DESC 
                        LIMIT 1) AS harga_max,
                    (SELECT harga_kamar.type
                        FROM harga_kamar
                            LEFT JOIN kamar
                                ON kamar.id = harga_kamar.kamar_id
                            LEFT JOIN periode
                                ON periode.id = harga_kamar.periode_id
                        WHERE kamar.kost_id = kost.id
                            AND periode.aktif = 1
                        ORDER BY type DESC 
                        LIMIT 1) AS type_sewa,
                    (SELECT COUNT(jenis)
                        FROM kamar
                        WHERE kamar.kost_id = kost.id
                            AND kamar.jenis = 'P') AS putri,
                    (SELECT COUNT(jenis)
                        FROM kamar
                        WHERE kamar.kost_id = kost.id
                            AND kamar.jenis = 'L') AS putra
                    FROM kost
                        LEFT JOIN kamar
                            ON kamar.kost_id = kost.id
                        LEFT JOIN harga_kamar
                            ON harga_kamar.kamar_id = kamar.id
                        LEFT JOIN fasilitas_kost
                            ON fasilitas_kost.kost_id = kost.id
                    ".$conditions);
            while ($r = mysql_fetch_array($gambar)) {
                ?>
                <li>
                    <a href="<?php echo '?pages=detail_kost&id=' . $r['id'] ?>">
                        <img src="
                        <?php
                        if (!empty($r['gambar'])) {
                            echo 'images/' . $r['gambar'];
                        } else {
                            echo 'images/na-135x135.jpg';
                        }
                        ?>
                             " alt="" /></a>
                    <span><a href="<?php echo '?pages=detail_kost&id=' . $r['id'] ?>"><?php echo $r['nama'] ?></a> <br>
                        <span>
                            <?php
                            if ($r['putra'] > 0 || $r['putri'] > 0) {
                                echo 'Kosan';
                                if ($r['putra'] > 0)
                                    echo ' Putra';
                                if ($r['putri'] > 0)
                                    echo ' Putri';
                            }
                            if (!empty($r['harga_max']))
                                echo '<br/>Harga Rp. ' . number_format($r['harga_max'], 2, ',', '.') . '/' . $r['type_sewa']
                                ?>
                        </span>
                    </span>
                    <?php
                }
                ?>
            </li>	
        </ul>	
    </div>
</div>
