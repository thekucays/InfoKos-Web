<?php
	require_once('../config/koneksi.php');
	/*
		example request:
		{
			"login_type": "pelanggan",
			"email": "fredi24@yahoo.com",
			"password": "19c539cba356953948355f0256eba161",
			"longitude": "2222222",
			"latitude": "3333333"
		}
	*/

	// parse config file
	$config = parse_ini_file('RESTconfig.ini', false, INI_SCANNER_RAW);

	// get parameter
	$json_str = file_get_contents('php://input'); //https://davidwalsh.name/php-json
	$request = json_decode($json_str, TRUE); //http://php.net/manual/en/function.json-decode.php


	// parameter checking
	$login_type = array_key_exists('login_type', $request) ? $request['login_type'] : '';
	$email = array_key_exists('email', $request) ? $request['email'] : '';
	$password = array_key_exists('password', $request) ? $request['password'] : '';
	$longitude = array_key_exists('longitude', $request) ? $request['longitude'] : '';
	$latitude = array_key_exists('latitude', $request) ? $request['latitude'] : '';
	$status = $config['status_notok'];
	$description = "";
	$result_sessionid = '';

	// validate session
	if($login_type == '' || $email == '' || $password == '' || $longitude == '' || $latitude == ''){
		$description = $config['error_invalidparameter'];
	} else if ($login_type == 'pelanggan') {
		$query = mysql_query(
			"SELECT count(*) as 'hasil' FROM pelanggan WHERE email = '". $email ."' and password = '". $password ."' and aktif = '1'"
		);
		$result_query = mysql_fetch_array($query);
		if($result_query['hasil'] == '1'){
			// $sid = $email . $password . $secretKey . time(); // . (round(microtime(true) * 1000));
			$sid = $email . $password . $config['secret_key'] . time();
			$result_sessionid = md5($sid);
			$micro_expire = time() + (5*3600); //add 5 minutes

			// insert new login session
			$query_session = mysql_query(
				"insert into session_android (session_id, expire, longitude, latitude, flagactive) values (
					'". $result_sessionid ."',
				    '". $micro_expire ."',
				    '". $longitude ."',
				    '". $latitude ."',
				    't'
				)"
			);
			if($query_session){
				$description = $config['message_ok'];
				$status = $config['status_ok'];
			} else{
				$description = $config['error_internal'];
				$result_sessionid = '';
			}
		}else{
			$description = $config['error_invalidlogincredential'];
		}
	} else if ($login_type == 'pemilik_kos') {
		$description = $config['error_notavailable'];		
	} else{
		$description = $config['error_invalidlogintype'];
	}	

	//generate result
	$result['status'] = $status;
	$result['description'] = $description;
	$result['session_id'] = $result_sessionid;
	echo json_encode($result);
?>
