<?php
	require_once('../config/koneksi.php');
	require_once('RESTconfig.php');

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

	// get parameter
	$json_str = file_get_contents('php://input'); //https://davidwalsh.name/php-json
	$request = json_decode($json_str, TRUE); //http://php.net/manual/en/function.json-decode.php


	// parameter checking
	$login_type = array_key_exists('login_type', $request) ? $request['login_type'] : '';
	$email = array_key_exists('email', $request) ? $request['email'] : '';
	$password = array_key_exists('password', $request) ? $request['password'] : '';
	$longitude = array_key_exists('longitude', $request) ? $request['longitude'] : '';
	$latitude = array_key_exists('latitude', $request) ? $request['latitude'] : '';
	$status = "nok";
	$description = "";
	$result_sessionid = '';

	// validate session
	if($login_type == '' || $email == '' || $password == '' || $longitude == '' || $latitude == ''){
		$description = 'invalid parameter';
	} else if ($login_type == 'pelanggan') {
		$query = mysql_query(
			"SELECT count(*) as 'hasil' FROM pelanggan WHERE email = '". $email ."' and password = '". $password ."' and aktif = '1'"
		);
		$result_query = mysql_fetch_array($query);
		if($result_query['hasil'] == '1'){
			$sid = $email . $password . $secretKey . time(); // . (round(microtime(true) * 1000));
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
				$description = 'ok';
				$status = 'ok';
			} else{
				$description = 'error inserting user session';
				$result_sessionid = '';
			}
		}else{
			$description = 'invalid username/password, or user inactive';
		}
	} else if ($login_type == 'pemilik_kos') {
		$description = 'NOT AVAILABLE YET';
	} else{
		$description = 'invalid login type';
	}	

	//generate result
	$result['status'] = $status;
	$result['description'] = $description;
	$result['session_id'] = $result_sessionid;
	echo json_encode($result);
?>
