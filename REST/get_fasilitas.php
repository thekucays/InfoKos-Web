 <?php
	require_once('../config/koneksi.php');

	// parse config file
	$config = parse_ini_file('RESTconfig.ini', false, INI_SCANNER_RAW);

	// get parameter
	$json_str = file_get_contents('php://input');
	$request = json_decode($json_str, TRUE);

	// return values
	$description = $config['error_invalidparameter'];
	$status = $config['status_notok'];
	$countResult = 0;

	// result parameter
	$results = array();
	$results['records'] = array();

	// parameter checking
	$session_id = array_key_exists('session_id', $request) ? $request['session_id'] : '';

	if($session_id == ''){
		$description = $config['error_invalidsessionid'];
	} else{
		$querySearch = mysql_query('SELECT * FROM fasilitas');

		// generate search result, if any
	    while ($r_fasilitas = mysql_fetch_array($querySearch)) {
	    	$fasilitas = array(
	    		'id' => $r_fasilitas['id'],
	    		'name' => $r_fasilitas['nama']
	    	);

	    	array_push($results['records'], $fasilitas);
	    }
	    $countResult = mysql_num_rows($querySearch);
	    $status = $config['status_ok'];
	    $description = $config['message_ok'];


	}

	// generate json result
	$result['status'] = $status;
	$result['description'] = $description;
	$result['count'] = $countResult;
	$result['session_id'] = $session_id;
	$result['records']	= $results['records'];
	echo json_encode($result);
?>	