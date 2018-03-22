<?php
	require_once('../config/koneksi.php');

	/*
		Notes:
		- fasilitas are in comma separated id format ex: 1,2,3
		- jenis kamar is L/P/All
		- type sewa are 0, bulan, 6 bulan, tahun
	*/

	// parse config file
	$config = parse_ini_file('RESTconfig.ini', false, INI_SCANNER_RAW);

	// get parameter
	$json_str = file_get_contents('php://input'); //https://davidwalsh.name/php-json
	$request = json_decode($json_str, TRUE); //http://php.net/manual/en/function.json-decode.php

	// return values
	$description = $config['error_invalidparameter'];
	$status = $config['status_notok'];

	// result parameter
	$results = array();
	$results['records'] = array();

	// option and comditions for searching query 
	$option = array();
	$conditions = '';
	$tipe_sewa_arr = array('0', 'bulan', '6 bulan', 'tahun');

	// parameter checking
	$nama_kosan = array_key_exists('nama_kosan', $request) ? $request['nama_kosan'] : '';
	$alamat = array_key_exists('alamat', $request) ? $request['alamat'] : '';
	$jenis_kamar = array_key_exists('jenis_kamar', $request) ? $request['jenis_kamar'] : '';
	$fasilitas = array_key_exists('fasilitas', $request) ? $request['fasilitas'] : '';
	$tipe_sewa = array_key_exists('tipe_sewa', $request) ? $request['tipe_sewa'] : '';
	$harga_sewa_min = array_key_exists('harga_sewa_min', $request) ? $request['harga_sewa_min'] : '';
	$harga_sewa_max = array_key_exists('harga_sewa_max', $request) ? $request['harga_sewa_max'] : '';
	$session_id = array_key_exists('session_id', $request) ? $request['session_id'] : '';
	/*
	if($nama_kosan==''){
		$description = $config['error_invalidparameter'];
	} else if($alamat==''){
		$description = $config['error_invalidparameter'];
	} else if($jenis_kamar==''){
		$description = $config['error_invalidparameter'];
	} else if($fasilitas==''){
		$description = $config['error_invalidparameter'];
	} else if($tipe_sewa==''){
		$description = $config['error_invalidparameter'];
	} else if($harga_sewa_max==''){
		$description = $config['error_invalidparameter'];
	} else if($harga_sewa_min){
		$description = $config['error_invalidparameter'];
	} 
	*/


	// processing data 
	// first of all, hit SESSION_ANDROID_CHECK to validate session
	if($session_id == ''){
		$description = $config['error_invalidsessionid'];
	} if(!is_numeric($harga_sewa_min) || !is_numeric($harga_sewa_max)){
		$description = $config['error_notanumber'];
	} if($harga_sewa_min > $harga_sewa_max){
		$description = $config['error_search_minmaxvalueinverted'];	
	} if($jenis_kamar!='L'  || $jenis_kamar!='P'){
		$description = $config['error_search_invalidjeniskamar'];
	} if($tipe_sewa!=''){
		if(!in_array($tipe_sewa, $tipe_sewa_arr)){
			$description = $config['error_search_invalidrenttype'];
		}
	} else{
		// populate search criteria
		if($nama_kosan!=''){
			$option[] = 'kost.nama LIKE "%' . $nama_kosan . '%"';
		} else if($alamat!=''){
			$option[] = 'kost.nama LIKE "%' . $alamat . '%"';
		} else if($jenis_kamar!=''){
			if(strtolower($jenis_kamar) != 'all'){
				$option[] = 'kamar.jenis = "' . $jenis_kamar . '"';
			}
		} else if($fasilitas!=''){
			$option[] = 'fasilitas_kost.fasilitas_id IN (' . $fasilitas . ')';
		} else if($tipe_sewa!=''){
			if($tipe_sewa!='0'){
				if($harga_sewa_max > 0 || $harga_sewa_max != ''){
					$option[] = 'harga_kamar.harga >='. $harga_sewa_min .' AND harga_kamar.harga <='. $harga_sewa_max . ' AND type="'. $tipe_sewa .'"';
				} else{
					$option[] = 'harga_kamar.harga >='. $harga_sewa_min . ' AND type="'. $tipe_sewa .'"';
				}
			}
		} 

		// append all search criteria
		if(count($option) > 0){
	        $conditions = ' WHERE kost.aktif =1 AND '.  implode($option, ' AND ');
	    }

	    // execute query
	    $querySearch = mysql_query("
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


	    // generate search result, if any
	    while ($r = mysql_fetch_array($querySearch)) {
	    	$jenis_kosan = 'All';
	    	if ($r['putra'] > 0 || $r['putri'] > 0) {
                if ($r['putra'] > 0)
                    $jenis_kosan = 'Putra';
                if ($r['putri'] > 0)
                    $jenis_kosan = 'Putri';
            }

	    	$kost = array(
	    		'id' => $r['id'],
	    		'gambar' => $r['gambar'],
	    		'nama' => $r['nama'],
	    		'jenis_kosan' => $jenis_kosan,
	    		'tipe_sewa' => $r['type_sewa'],
	    		'harga' => number_format($r['harga_max'], 2, ',', '.')
	    	);

	    	array_push($results['records'], $kost);
	    }

	    $status = $config['status_ok'];
	}

	// generate json result
	$result['status'] = $status;
	$result['description'] = $description;
	$result['session_id'] = $session_id;
	$result['records']	= $results['records'];
	echo json_encode($result);
?>