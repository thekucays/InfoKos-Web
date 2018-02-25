<?php
	require_once('../config/koneksi.php');
	require_once('RESTconfig.php');


	/*
		session_id generated with the following format: md5(email + password + <secret key> + timestamp)
	*/

	// get parameter
	$json_str = file_get_contents('php://input'); //https://davidwalsh.name/php-json
	$request = json_decode($json_str, TRUE); //http://php.net/manual/en/function.json-decode.php

	//parameter checking
	$session_status = 'false';
	$session_id = array_key_exists('session_id', $request) ? $request['session_id'] : ''; //http://php.net/manual/en/function.array-key-exists.php
	$login_type = array_key_exists('login_type', $request) ? $request['login_type'] : '';
	$result = array();
	$description = '';
	$result_sessionid = '';

	// validate session
	if($session_id == '' || $login_type == ''){
		$description = 'invalid parameter';
	} else{
		if($login_type == 'pelanggan'){
			$query = mysql_query(
				"SELECT * FROM session_android WHERE session_id = '". $session_id ."' and flagactive = 't'"
			);
			$result_query = mysql_fetch_array($query);
			$result_sessionid = strlen($result_query['session_id'])>0 ? $result_query['session_id'] : '';
			$session_status = strlen($result_sessionid)>0 ? 'true' : 'false';
			$description = strlen($result_sessionid)>0 ? 'ok' : 'nok';
		} elseif ($login_type == 'admin') {
			$description = 'NOT AVAILABLE YET';
		} else{
			$description = 'invalid login type';
		}
	}

	// extend the session length if the session checking is correct
	if($session_status == 'true'){

	}

	// generate result
	$result['session_status'] = $session_status;
	$result['description'] = $description;
	$result['session_id'] = $result_sessionid;
	echo json_encode($result);

?>