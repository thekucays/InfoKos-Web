<?php
	require_once('../config/koneksi.php');
	require_once('RESTconfig.php');


	// get parameter
	$json_str = file_get_contents('php://input'); //https://davidwalsh.name/php-json
	$request = json_decode($json_str, TRUE); //http://php.net/manual/en/function.json-decode.php

	// parameter checking
	$nama = array_key_exists('nama', $request) ? $request['nama'] : '';
	$idno = array_key_exists('idno', $request) ? $request['idno'] : '';  // nomor ktp
	$email = array_key_exists('email', $request) ? $request['email'] : '';
	$alamat = array_key_exists('alamat', $request) ? $request['alamat'] : '';
	$gender = array_key_exists('gender', $request) ? $request['gender'] : '';
	$phone_number = array_key_exists('phone_number', $request) ? $request['phone_number'] : '';
	$college = array_key_exists('college', $request) ? $request['college'] : '';
	$password = array_key_exists('password', $request) ? $request['password'] : '';
	$password_conf = array_key_exists('password_conf', $request) ? $request['password_conf'] : '';

	// return values
	$description = 'invalid parameter';
	$status = 'nok';
	$userid_registered = '';

	// validate session
	if($nama=='' || $idno=='' || $email=='' || $alamat=='' || $gender=='' || $phone_number=='' || $college=='' || $password=='' || $password_conf==''){
		$description = 'invalid parameter';
	} else{
		if($password != $password_conf){
			$description = 'password and password confirmation did not match';
		}else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    		$description = 'invalid email address';
		}else if($gender != 'm' && $gender != 'f'){
			$description = 'invalid gender';
		}else{
			// check user already registered
			$file_foto = 'logo.JPEG';
			$file_type = pathinfo($file_foto, PATHINFO_EXTENSION);
			$file_data = file_get_contents($file_foto);
			$file_base64 = 'data:image/' . $file_type . ';base64,' . base64_encode($file_data);
			$file_obj = json_encode(array("image"=>$file_base64));

			// all OK
			$status = "ok";
			$description = 'Registrasi Berhasil! Silahkan cek email anda untuk melakukan konfirmasi';
		}
	}

	// generate result
	$result['status'] = $status;
	$result['description'] = $description;
	$result['status'] = $status;
	echo json_encode($result);

	// if($gender == 'm'){
	// 	echo 'yes';
	// }else{
	// 	echo 'no';
	// }
?>