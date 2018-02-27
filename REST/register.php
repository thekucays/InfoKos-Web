<?php
	/*
		this one using multipart/form data
		https://gist.github.com/no1k/8492575
		https://stackoverflow.com/questions/30654310/post-variables-not-working-with-files-and-multipart-form-data
	*/
	require_once('../config/koneksi.php');
	require_once('RESTconfig.php');
	require_once('../config/upload.php');

	// print_r($_FILES);
	// echo $_POST['nama'];

	// return values
	$description = 'invalid parameter';
	$status = 'nok';
	$userid_registered = '';

	// parameter checking
	if(!isset($_POST['nama']) || $_POST['nama']==''){
		$description = 'invalid parameter';
	}else if(!isset($_FILES['photo']) || $_FILES['photo']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['idno']) || $_POST['idno']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['email']) || $_POST['email']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['alamat']) || $_POST['alamat']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['gender']) || $_POST['gender']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['phone_number']) || $_POST['phone_number']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['college']) || $_POST['college']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['password']) || $_POST['password']==''){
		$description = 'invalid parameter';
	}else if(!isset($_POST['password_conf']) || $_POST['password_conf']==''){
		$description = 'invalid parameter';
	}else if($_POST['gender'] != 'm' && $_POST['gender'] != 'f'){
		$description = 'invalid parameter';
	}else if($_POST['password'] != $_POST['password_conf']){
		$description = 'invalid parameter';
	}else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$description = 'invalid parameter';
	}

	// processing data
	else{
		// check already registered
		$checkIsRegAlready = mysql_query("SELECT * FROM pelanggan WHERE email = '" . $_POST['email'] . "'");
		$isregData = mysql_fetch_array($checkIsRegAlready);
		$isReg = mysql_num_rows($checkIsRegAlready);
		if($isReg != 0){
			$description = 'Maaf User '. $isregData['nama'] .' ('. $isregData['email']. ') sudah terdaftar.';
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
	        	$status = 'ok';
	        	$description = 'Selamat User '. $_POST['nama'] .'('. $_POST['email'] .') berhasil didaftarkan.';
	        }else{
	        	$description = 'Maaf User '. $_POST['nama'] .'('. $_POST['email'] .' gagal didaftarkan.';
	        }
		}
	}

	// generate result
	$result['status'] = $status;
	$result['description'] = $description;
	echo json_encode($result);
?>