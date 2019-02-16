<?php
	if(!defined('APP')) die('...');

	include 'lib.bots.array.php';

	if(RC_BadBotBlock == 1 && $_SESSION[SES]['allowed'] <> 1)
	{
		//önce izin verelim
		$allowed	= '';
		$banned		= false;
		$logit 		= false;

		//izin verilen ip adreslerinden gelen istekleri belirleyelim
		$testip = get_real_ip();
		if(in_array($testip, $allowed_ips))
		{
			$allowed 	= $testip;
			$banned 	= false;
			$logit 		= false;
		}

		//izin verilmiş botları da işaretleyelim
		$test0 = strpos_array($_SERVER['HTTP_USER_AGENT'], $bot_allowed);
		if($test0 <> '')
		{
			$allowed 	= $test0;
			$banned 	= false;
			$logit 		= false;
		}

		//allowed imzası kullanan sahte araçlara dikkat edelim
		$fakers = strpos_array($_SERVER['HTTP_USER_AGENT'], $ua_fakes);
		if($fakers <> '')
		{
			$allowed 	= '';
			$banned 	= true;
			$logit 		= true;
			$ban_reason = 'banned_ua'.$_SERVER['HTTP_USER_AGENT'];
		}

		//tüm sonuçlar olumlu ise
		//allowed diye oturumunu işaretleyelim
		if($allowed <> '')
		{
			$_SESSION[SES]['allowed'] = 1;
		}

		//izin verildiğine dair elimizde kanıt yok ise işleme devam edelim
		if($allowed == '')
		{
			if(RC_BadBotBlock_level >= 0)
			{
				if($ban_reason == '')
				{
					if(in_array($_SERVER['HTTP_USER_AGENT'], $ua_banned))
					{
						$banned 	= true;
						$logit 		= false;
						$ban_reason = 'banned_ua'.$_SERVER['HTTP_USER_AGENT'];
					}
				}

				if($ban_reason == '')
				{
					//Kesin yasaklıları kontrol edelim ve siteden uzaklaştıralım
					$str_ua = strpos_array($_SERVER['HTTP_USER_AGENT'], $bot_tested_banned);
					if($str_ua <> '')
					{
						$banned 	= true;
						$logit 		= false;
						$ban_reason = $str_ua;
					}
				}
			}

			if(RC_BadBotBlock_level > 0)
			{
				if($ban_reason == '')
				{
					//yasaklamayı düşündüklerimizi kontrol edelim ve loglayalım
					$test1 = strpos_array($_SERVER['HTTP_USER_AGENT'], $bot_mylist);
					if($test1 <> '')
					{
						$banned 	= false;
						$logit 		= true;
						$ban_reason = $test1;
					}
				}
			}

			if(RC_BadBotBlock_level > 1)
			{
				if($ban_reason == '')
				{
					//yasaklamayı düşündüklerimizi kontrol edelim ve loglayalım
					$test2 = strpos_array($_SERVER['HTTP_USER_AGENT'], $bot_limited);
					if($test2 <> '')
					{
						$banned 	= false;
						$logit 		= true;
						$ban_reason = $test2;
					}
				}
			}

			if(RC_BadBotBlock_level > 2)
			{
				if($ban_reason == '')
				{
					//yasaklamayı düşündüklerimizi kontrol edelim ve loglayalım
					$test3 = strpos_array($_SERVER['HTTP_USER_AGENT'], $bot_stopbadbots);
					if($test3 <> '')
					{
						$banned 	= false;
						$logit 		= true;
						$ban_reason = $test3;
					}
				}

				if($ban_reason == '')
				{
					//bu son liste banlamak amaçlı değil
					//tamamen botları tespit edebilmek amaçlıdır
					//o yüzden bot olabilecek her türlü imzayı loglamaya çalışır
					$test4 = strpos_array($_SERVER['HTTP_USER_AGENT'], $spider_dedector);
					if($test4 <> '')
					{
						$banned 	= false;
						$logit 		= true;
						$ban_reason = $test4;
					}
				}

			}

			if($logit == true)
			{
				include $_SERVER['DOCUMENT_ROOT'].'trap.php';
			}

			if($banned == true)
			{
				header("HTTP/1.1 404 Not Found");
				header('Location:http://www.google.com');
				exit();
			}

			//loglanmadıysa ve banlanmadıysa allowed işaretleyelim
			if($logit <> true && $banned <> true)
			{
				$_SESSION[SES]['allowed'] = 1;
			}
		}
	}

