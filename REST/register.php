<?php
	/*
		this one using multipart/form data
		https://gist.github.com/no1k/8492575
		https://stackoverflow.com/questions/30654310/post-variables-not-working-with-files-and-multipart-form-data
	*/
	require_once('../config/koneksi.php');
	require_once('../config/upload.php');

	// print_r($_FILES);
	// echo $_POST['nama'];

	// parse config file
	$config = parse_ini_file('RESTconfig.ini', false, INI_SCANNER_RAW);

	// return values
	$description = $config['error_invalidparameter'];
	$status = $config['status_notok'];
	$userid_registered = '';

	// parameter checking
	if(!isset($_POST['nama']) || $_POST['nama']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_FILES['photo']) || $_FILES['photo']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['idno']) || $_POST['idno']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['email']) || $_POST['email']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['alamat']) || $_POST['alamat']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['gender']) || $_POST['gender']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['phone_number']) || $_POST['phone_number']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['college']) || $_POST['college']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['password']) || $_POST['password']==''){
		$description = $config['error_invalidparameter'];
	}else if(!isset($_POST['password_conf']) || $_POST['password_conf']==''){
		$description = $config['error_invalidparameter'];
	}else if($_POST['gender'] != 'm' && $_POST['gender'] != 'f'){
		$description = $config['error_invalidparameter'];
	}else if($_POST['password'] != $_POST['password_conf']){
		$description = $config['error_invalidparameter'];
	}else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$description = $config['error_invalidparameter'];
	}

	// processing data
	else{
		// check already registered
		$checkIsRegAlready = mysql_query("SELECT * FROM pelanggan WHERE email = '" . $_POST['email'] . "'");
		$isregData = mysql_fetch_array($checkIsRegAlready);
		$isReg = mysql_num_rows($checkIsRegAlready);
		if($isReg != 0){
			$description = $config['error_alreadyregistered'];
			$description = str_replace('$p1$', $isregData['nama'], $description);
			$description = str_replace('$p2$', $isregData['email'], $description);
		}else{
			// upload photo to server
			$nama_file = $_FILES['photo']['name'];
			$nama_file = UploadPhoto($nama_file);

			// insert pelanggan data to DB
			$insert = mysql_query("INSERT INTO pelanggan(nama,
                                        email,
                                        ktp,
                                        password,
                                        alamat,
                                        jenis_kelamin,
                                        no_hp,
                                        kampus,
                                        photo,
                                        aktif)
                                   VALUE('$_POST[nama]',
                                        '$_POST[email]',
                                        '$_POST[idno]',
                                        '$_POST[password]',
                                        '$_POST[alamat]',
                                        '$_POST[gender]',
                                        '$_POST[phone_number]',
                                        '$_POST[college]',
                                        '$nama_file',
                                        '1')");
	        if($insert){
	        	$status = $config['status_ok'];
	        	$description = $config['message_successregister'];
	        	$description = str_replace('$p1$', $_POST['nama'], $description);
	        	$description = str_replace('$p2$', $_POST['email'], $description);
	        }else{
	        	$description = $config['message_failedregister'];
	        	$description = str_replace('$p1$', $_POST['nama'], $description);
	        	$description = str_replace('$p2$', $_POST['email'], $description);
	        }
		}
	}

	// generate result
	$result['status'] = $status;
	$result['description'] = $description;
	echo json_encode($result);
?>