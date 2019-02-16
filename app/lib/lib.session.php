<?php
	if(!defined('APP')) die('...');

	#oturum tutma süresini artıralım
	ini_set('session.cache_expire', 86500);
	ini_set('session.gc_maxlifetime', 86500);
	ini_set('session.cookie_lifetime', 86500);
	session_start();

	//oturumu başlatmak için çok mu geç kaldık?
	//bu hatayı aşmak için iki yöntemimiz var, ilki oturumu her istek yapılmadan önce otomatik başlatmak
	//bunun için .htaccess dosyamızda şöyle bir çağrı bulunuyor
	// # php_value	session.auto_start 1
	// ikinci yöntem ise, init dosyamızda en başta bulunan
	// session_start(); çağrımız

	//platform bağımsız açıklar için session güvencesi...
	//önce RANDOM bir anahtar belirliyoruz
	//sonra random anahtarı SES sabitine kaydediyoruz
	//sonrasında oturumla ilgili tüm işlemleri SES sabiti dizisinden çağırıyoruz
	if(!$_SESSION['key'])
	{
		$_SESSION['key'] = sesId(33);
	}
	define('SES', md5($_SESSION['key']));

	//oturum başlatılıyor
	if(!$_SESSION[SES])
	{
		$_SESSION[SES]['fagent'] 	= $_SERVER['HTTP_USER_AGENT']; 	// bağlantı yapan tarayıcı
		$_SESSION[SES]['agent'] 	= $_SERVER['HTTP_USER_AGENT']; 	// bağlantı yapan tarayıcı
		$_SESSION[SES]['fip'] 		= get_real_ip(); 				// ilk bağlanılan ip
		$_SESSION[SES]['ip'] 		= get_real_ip();				// son bağlananan ip
		$_SESSION[SES]['allowed']	= 0;							// botları test ederken, userlara hızlı geçiş veriyoruz
		$_SESSION[SES]['user_id']	= 0;
		$_SESSION[SES]['login']		= 0;
	}
	else
	{
		$_SESSION[SES]['ip'] 		= get_real_ip();				// son bağlananan ip
		$_SESSION[SES]['agent'] 	= $_SERVER['HTTP_USER_AGENT']; 	// bağlantı yapan tarayıcı
	}
