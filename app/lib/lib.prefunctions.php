<?php
 	if(!defined('APP')) die('...');

	function mds($key,$level = 0)
	{
		/**
		| http://randomkeygen.com sitesi ile random key oluşturuyorum :)
		|
		| * Şifre Hatırlat özelliği bulunan bir sistem için şunu önerebilirim
		|
		| Her yıl değişiminde yeniden şifrelemeye zorlamak için
		| $salt2 	= date("Y"); olarak ayarlanması yeterlidir.
		|
		| Her ay yeniden şifrelemeye zorlamak için
		| $salt2 	= date("Ym"); olarak ayarlanması yeterlidir.
		|
		| $salt0 değeri en basit şifreyi sıfır/Zero bile olsa güçlendirecek
		| sayı + harf + şekil + karakter
		| gibi zorlu bir kombinasyondan oluşmalıdır
		|
		| en son aşamada md5 kullanılmasının amacı çıktıyı normal bir
		| md5 çıktısıymış gibi göstermektir
		|
		| $Level değeri
		| md5 yapılmış şifreleri
		| mds şekline dönüştürmek için kullanılır
		|
		| Tek farkı, ilk aşamadaki key değerini md5 yapmak yerine, yapılmış haliyle kullanmasıdır
		|
		| Tayland, Songkhala'da yazılmıştır.
		*/

		$salt0 	= '9x3lM6Ll"z>wo7Y??-A{OweX-:X6IX';
		$salt1 	= 'k-8v#5nw8=s0de>bYW563r25*`l%0M'; //bir hash
		$salt2 	= 'Ce97jR-K0jOi&6dKm9G59oM/FR(;6q'; //bir başka hash
		$s0 	= md5($salt0);
		$s1 	= md5($salt1);
		$s2 	= md5($salt2);
		if($level == 1 )
		{
			$f0 	= $key.md5($s0);
		}
		else
		{
			$f0 	= md5($key).md5($s0);
		}
		$f0 	= md5($f0);

		//aşama 1
		$f1 = hash('sha1', 		$s0.$f0);
		//aşama 2
		$f2 = hash('sha256', 	$s1.$f1);
		//aşama 3
		$f3 = hash('sha512', 	$s2.$f2);
		//aşama 2 ters
		$f2 = hash('sha256', 	$s1.$f3);
		//aşama 1 ters
		$f1 = hash('sha1', 		$s0.$f2);
		//sadece maskeleme
		$f0 = hash('md5', 		$s2.$f1);

		return $f0;
	}


	function check_email_address($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function myReq($key, $level = 1, $slash = 0)
	{
		/**
		| DEĞİŞKEN GETİRME FONKSİYONU
		*/

		/**
		| Keyleri request ile varsayılan olarak aldığımızı unutmamak lazım
		| Zaten fonksiyonun amacı keyleri hızlıca alıp değişkene dönüştürmek
		| istenirse bir seçenek daha eklenip, get post reguest veya metodu devre dışı bırakmak
		| imkanı da eklenebilir.
		|
		| $level, hangi seviyede işlem göreceğini
		| $slash, işlemin sonunda slash eklenip eklenmeyeceğini gösterir
		| Örnek Kullanım, doğru yöntem
		| $key = myReq($key,1,1)
		|
		| kullanımı kolaylaştırmak için şu şekillerde kullanma imkanı da var
		| lakin log dosyalarını şişirme ihtimali olduğunu unutmamak lazım
		|
		| $key = myReq($key,1);
		| $key = myReq($key);

		| level : 0 => intval olarak sayısal değer döndürür
		| level : 1 => metin olarak trimlenmiş değer döndürür; textarea ve editörden gelen değerleri almak için
		| level : 2 => varchar değer text alanlarından gelen değerleri döndürür, etiketleri temizlenmiş saf metindir
		| level : 3 => checkbox ve radio alanlarından gelecek değerleri sayısal veriye dönüştürür
		*/

		$key = $_REQUEST[$key];
		if($level == 0) $key = intval(trim($key));
		if($level == 1) $key = trim($key);
		if($level == 2) $key = htmlspecialchars(trim(strip_tags($key)));
		if($level == 3)
		{
			if($key == "on")
			{
				$key = 1;
			}
			else
			{
				$key = 0;
			}
		}
		if($slash == 1) $key = addslashes($key);
		return $key;
	}

	function cleanKey($key,$level = 0)
	{
		/**
		| $level, hangi seviyede işlem göreceğini
		*/
		if($level == 0) $key = trim($key);
		if($level == 1) $key = trim(stripslashes($key));
		return $key;
	}

	function keygen($length)
	{
		$key = '';
		list($usec, $sec) = explode(' ', microtime());
		mt_srand((float) $sec + ((float) $usec * 100000));

		$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

		for($i=0; $i<$length; $i++)
		{
			$key .= $inputs{mt_rand(0,61)};
		}
		return $key;
	}

	function gen_key($length)
	{
		$options 	= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890098765432100258369741";
		$code 		= "";
		for($i = 0; $i < $length; $i++)
		{
			$key = rand(0, strlen($options) - 1);
			$code .= $options[$key];
		}
		return $code;
	}

	function gen_id($length)
	{
		$options 	= "1234567890098765432100258369741";
		$code 		= "";
		for($i = 0; $i < $length; $i++)
		{
			$key = rand(0, strlen($options) - 1);
			$code .= $options[$key];
		}
		return $code;
	}

	function format_url($text)
	{
		#-------------------------------------------------
		# phpBB Turkiye ekibi Alexis tarafından 2007 yılında yazılmıştır
		#-------------------------------------------------

		$text = strip_tags(trim(html_entity_decode($text, ENT_QUOTES)));
		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		$find = array(' ', '&quot;', '&amp;', '&', '\r\n', '\n', '/', '\\', '+', '<', '>');
		$text = str_replace ($find, '-', $text);

		$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
		$text = str_replace ($find, 'e', $text);

		$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
		$text = str_replace ($find, 'i', $text);

		$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
		$text = str_replace ($find, 'o', $text);

		$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
		$text = str_replace ($find, 'a', $text);

		$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
		$text = str_replace ($find, 'u', $text);

		$find = array('ç', 'Ç');
		$text = str_replace ($find, 'c', $text);

		$find = array('ş', 'Ş');
		$text = str_replace ($find, 's', $text);

		$find = array('ğ', 'Ğ');
		$text = str_replace ($find, 'g', $text);

		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

		$repl = array('', '-', '');

		$text = preg_replace ($find, $repl, $text);
		$text = str_replace ('--', '-', $text);

		//$text = $text;

		return $text;
	}

	function format_int($text)
	{
		#-------------------------------------------------
		# phpBB Turkiye ekibi Alexis tarafından 2007 yılında yazılmıştır
		#-------------------------------------------------

		$text = trim($text);
		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
		$text = str_replace ($find, 'e', $text);

		$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
		$text = str_replace ($find, 'i', $text);

		$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
		$text = str_replace ($find, 'o', $text);

		$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
		$text = str_replace ($find, 'a', $text);

		$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
		$text = str_replace ($find, 'u', $text);

		$find = array('ç', 'Ç');
		$text = str_replace ($find, 'c', $text);

		$find = array('ş', 'Ş');
		$text = str_replace ($find, 's', $text);

		$find = array('ğ', 'Ğ');
		$text = str_replace ($find, 'g', $text);

		return $text;
	}

	function tr_strtolower($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.strtoupper.php#97667
		#-------------------------------------------------
		return mb_convert_case(str_replace('I','ı',$text), MB_CASE_LOWER, "UTF-8");
	}

	function tr_strtoupper($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.strtoupper.php#97667
		#-------------------------------------------------
		return mb_convert_case(str_replace('i','İ',$text), MB_CASE_UPPER, "UTF-8");
	}

	function tr_ucfirst($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.ucfirst.php#105435
		#-------------------------------------------------

		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		if($text[0] == "i")
			$tr_text = "İ".substr($text, 1);
		else
			$tr_text = mb_convert_case($text, MB_CASE_TITLE, "UTF-8");

		return trim($tr_text);
	}

	function tr_ucwords($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.ucfirst.php#105435
		#-------------------------------------------------
		$p = explode(" ",$text);
		if(is_array($p))
		{
			$tr_text = "";
			foreach($p AS $item)
				$tr_text .= " ".tr_ucfirst($item);

			return trim($tr_text);
		}
		else
			return tr_ucfirst($text);
	}


	function showMessageBoxS($msgText, $msgType)
	{
		if($msgType == 'error')
		{
			$a = '<div class="box box-solid bg-red"><div class="box-body-wp">'.$msgText.'</div></div>';
		}

		if($msgType == 'info')
		{
			$a = '<div class="box box-solid bg-green"><div class="box-body-wp">'.$msgText.'</div></div>';
		}
		return $a;

	}

	function print_pre($s)
	{
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}

	function url_author($title)
	{
		$url = SITELINK.'yazar/'.format_url($title);
		return $url;
	}

	function n2br($metin)
	{
		$metin = str_replace(array("\r\n","\r","\n"), "\n<br />", $metin); // cross-platform newlines
		$metin = str_replace(array("<br /><br /><br />","<br /><br />","<br /><br /><br /><br />"), "\n<br />", $metin); // cross-platform newlines
		$metin = trim($metin);
		return $metin;
	}

	function debug_sql($ilave = '')
	{
		$uri = serialize($_REQUEST)."\n";
		$uri.= $ilave.file_get_contents('cache/sql.errors');
		file_put_contents('cache/sql.errors', $uri);
	}

	function debug_min()
	{
		global $starttime;

		$endtime = microtime(true);
		$endtime = substr(($endtime - $starttime),0,6);

		$kullanim = memory_get_peak_usage(true);
		$kullanim = number_format($kullanim / 1024);

		$content = 'SÜS : '.$endtime.' | MEM : '.$kullanim.'<br/>';
		return $content;
	}

	function debug_min_hide()
	{
		global $starttime;

		$endtime = microtime(true);
		$endtime = substr(($endtime - $starttime),0,6);

		$kullanim = memory_get_peak_usage(true);
		$kullanim = number_format($kullanim / 1024);

		$content = '<!-- SÜS : '.$endtime.' | MEM : '.$kullanim.'-->';
		return $content;
	}

	//istanbula ait hava durumu derecesini ve durum ikonunu dizi olarak döndürür
	function get_weather_istanbul()
	{
		//datalarımızın yerelde tutulacağı klasör yolu
		$url_local = "cache/weather.xml";

		if(filesize($url_local) == 0) unlink($url_local);

		if(file_exists($url_local) && ((filemtime($url_local)+3600) > time()))
		{
			//yerel datayı aç
			$dt		= fopen($url_local, "r");
			//açılan datayı oku
			$air	= fread($dt, filesize($url_local));
			//dosyayı sonra kapat
			fclose($dt);
			//sonrasında else sonundaki kısımdan datayı parse etmeye devam et
		}
		else
		{
			//initalize
			$ch = curl_init();

			//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');

			//hargi url den veri çekeceğiz
			curl_setopt($ch, CURLOPT_URL , "http://api.openweathermap.org/data/2.5/weather?q=istanbul&appid=f12e22d5c19865e1f946264b22cc3b83");
			//dönen verileri kullanacak mıyız
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//dönen header verilerini kaydedeyim mi
			curl_setopt($ch, CURLOPT_HEADER, false);
			//işlemi gerçekleştir
			$data = curl_exec($ch);
			//curl i kapat
			curl_close($ch);
			//dönen dataları, bir daha kullanmak üzere cache dizinine koy
			//bundan sonrasında if kısmına dönecek

			//datayı decode edip alalım
			$data = json_decode($data);

			//doğru yerlere yerleştirelim
			$air['id']		= $data->weather[0]->id;
			//dönen değer KELVIN cinsinden olduğundan, cellcius a dönüştürüyoruz
			$air['derece'] 	= round(($data->main->temp - 273.15));
			$air = json_encode($air);
			file_put_contents($url_local, $air);

		}
		$data = json_decode($air);
		$airData['id']		= $data->id;
		$airData['derece']	= $data->derece;
		return $airData;
	}

	function pco_format_date($tarih)
	{
		return date('D, d M Y H:i:s O', $tarih);
	}

	function xml_rpc_ping($service_url, $content_url)
	{
		global $L;
		$client = new IXR_Client( $service_url );
		$client->timeout = 3;
		$client->useragent .= ' -- PingTool/1.0.0';
		$client->debug = false;

		if( $client->query('weblogUpdates.extendedPing', $L['pIndex_Company'], SITELINK, $content_url, LINK_FEED))
		{
			return $client->getResponse();
		}

		if( $client->query('weblogUpdates.ping', $myBlogName, SITELINK ))
		{
			return $client->getResponse();
		}

		return false;
	}

	function ping_it($content_url)
	{
		require_once($_SERVER['DOCUMENT_ROOT'].'vendors/classes/class.ping.php' );

		$dizi_ping = array(
			'http://blogsearch.google.com.tr/ping/RPC2',
			'http://blogsearch.google.com/ping/RPC2',
			'http://blogsearch.google.us/ping/RPC2',
			'https://ping.blogs.yandex.ru/RPC2',
			'http://rpc.pingomatic.com',
		);

		foreach( $dizi_ping as $v )
		{
			xml_rpc_ping($v, $content_url);
		}
	}

	function sesId($length)
	{
		$key = '';
		list($usec, $sec) = explode(' ', microtime());
		mt_srand((float) $sec + ((float) $usec * 103020));

		$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

		for($i=0; $i<$length; $i++)
		{
			$key .= $inputs{mt_rand(0,61)};
		}
		return $key;
	}

	function get_real_ip()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	function content_view_direct($_id)
	{
		global $conn;

		//her halükarda okunma sayısını artıralım
		$sql = 'UPDATE
					app_view
				SET
					content_view = (content_view + 1)
				WHERE
					id = '.$_id;
		$conn->Execute($sql);

		//bot değilse gerçek okunma sayısını da artıralım
		if(!is_bot())
		{
			if($_SESSION[SES]["content_view"][$_id] <> 1)
			{
				$sql = 'UPDATE
							app_view
						SET
							content_view_real = (content_view_real + 1)
						WHERE
							id = '.$_id;
				$rs = $conn->Execute($sql);
				if($rs <> false)
				{
					$_SESSION[SES]["content_view"][$_id] = 1;
				}
			}
		}

		//bağlantıyı kapatalım
		$conn->Close();
	}

	function content_view_google($_id)
	{
		global $conn, $dosya_gs, $InstanceCache;

		//bot değilse gerçek okunma sayısını da artıralım
		$x = is_google_bot();

		if($x == true )
		{
			$sql = 'UPDATE
						app_content
					SET
						content_google_status = 1
					WHERE
						content_id = '.$_id;
			$rs = $conn->Execute($sql);
		}

		//bağlantıyı kapatalım
		$conn->Close();

		//memcache kaydını da silelim
		$InstanceCache->deleteItems(array($dosya_gs));
	}

	function strpos_array($samanlik, $igne)
	{
		/**
		* strpos un dizi ile çalışanı
		* ilk değeri gördüğünde direk değeri dönüş yapar
		* yoksa boş döner
		*/

		if(!is_array($igne))
		{
			$igne = array($igne);
		}

		foreach($igne as $k => $v)
		{
			$konum = stripos($samanlik, trim($v));
			if($konum !== false)
			{
				return $v;
			}
		}
	}

	function kill_session()
	{
		session_start();
		session_unset();
		session_destroy();

		//çıkış yaparken çerezleri de imha etmek, akıllıca olur
		setcookie("PHPSESSID", "", time()-86500);
	}

	function is_google_bot()
	{
		//google bot gelmiş ise
		//geldi diye işaretleyelim
		//ilerde ping atarken kullanacağız ve
		//google bot gelmiş içerikleri tekrar ping atmayacağız

		$spiders = array(
			'googlebot',
			'googlebot-news',
			'googlebot-video',
			'googlebot-mobile',
			'googlebot-image',
			'google-amphtml',
			'mediapartners-google',
			'mediapartners (googlebot)',
			'adsbot-google-mobile-apps',
			'feedfetcher-google',
		);

		foreach($spiders as $spider)
		{
			//If the spider text is found in the current user agent, then return true
			if (stripos(strtolower($_SERVER['HTTP_USER_AGENT']), $spider) !== false ) $x = true;
		}

		return $x;
	}

	function is_bot()
	{
		$spiders = array(
			//google
			'googlebot',
			'googlebot-news',
			'googlebot-video',
			'googlebot-mobile',
			'googlebot-image',
			'google-amphtml',
			'mediapartners-google',
			'mediapartners (googlebot)',
			'adsbot-google-mobile-apps',
			'feedfetcher-google',
			//bing
			'bingbot',
			//yandex
			'yandexbot',
			'yandexaccessibilitybot',
			'yandexvideoparser',
			'yandexmetrika',
			'yandexmobilebot',
			'yandexblogs',
			'yandexmedia',
			'yandexsomething',
			//sosyal medya
			'twitterbot',
			'facebookexternalhit/',
			'whatsapp',
			//diğerleri
			'duckduckbot',
			'google-http-java-client/',
			'abot',
			'dbot',
			'ebot',
			'hbot',
			'kbot',
			'lbot',
			'mbot',
			'nbot',
			'obot',
			'pbot',
			'rbot',
			'sbot',
			'tbot',
			'vbot',
			'ybot',
			'zbot',
			'bot.',
			'bot/',
			'_bot',
			'.bot',
			'/bot',
			'-bot',
			':bot',
			'(bot',
			'crawl',
			'slurp',
			'spider',
			'seek',
			'accoona',
			'acoon',
			'adressendeutschland',
			'ah-ha.com',
			'ahoy',
			'altavista',
			'ananzi',
			'anthill',
			'appie',
			'arachnophilia',
			'arale',
			'araneo',
			'aranha',
			'architext',
			'aretha',
			'arks',
			'asterias',
			'atlocal',
			'atn',
			'atomz',
			'augurfind',
			'backrub',
			'bannana_bot',
			'baypup',
			'bdfetch',
			'big brother',
			'biglotron',
			'bjaaland',
			'blackwidow',
			'blaiz',
			'blog',
			'blo.',
			'bloodhound',
			'boitho',
			'booch',
			'bradley',
			'butterfly',
			'calif',
			'cassandra',
			'ccubee',
			'cfetch',
			'charlotte',
			'churl',
			'cienciaficcion',
			'cmc',
			'collective',
			'comagent',
			'combine',
			'computingsite',
			'csci',
			'curl',
			'cusco',
			'daumoa',
			'deepindex',
			'delorie',
			'depspid',
			'deweb',
			'die blinde kuh',
			'digger',
			'ditto',
			'dmoz',
			'docomo',
			'download express',
			'dtaagent',
			'dwcp',
			'ebiness',
			'ebingbong',
			'e-collector',
			'ejupiter',
			'emacs-w3 search engine',
			'esther',
			'evliya celebi',
			'ezresult',
			'facebook',
			'falcon',
			'felix ide',
			'ferret',
			'fetchrover',
			'fido',
			'findlinks',
			'fireball',
			'fish search',
			'fouineur',
			'funnelweb',
			'gazz',
			'gcreep',
			'genieknows',
			'getterroboplus',
			'geturl',
			'glx',
			'goforit',
			'golem',
			'grabber',
			'grapnel',
			'gralon',
			'griffon',
			'gromit',
			'grub',
			'gulliver',
			'hamahakki',
			'harvest',
			'havindex',
			'helix',
			'heritrix',
			'hku www octopus',
			'homerweb',
			'htdig',
			'html index',
			'html_analyzer',
			'htmlgobble',
			'hubater',
			'hyper-decontextualizer',
			'ibm_planetwide',
			'ichiro',
			'iconsurf',
			'iltrovatore',
			'image.kapsi.net',
			'imagelock',
			'incywincy',
			'indexer',
			'infobee',
			'informant',
			'ingrid',
			'inktomisearch.com',
			'inspector web',
			'intelliagent',
			'internet shinchakubin',
			'ip3000',
			'iron33',
			'israeli-search',
			'ivia',
			'jack',
			'jakarta',
			'javabee',
			'jetbot',
			'jumpstation',
			'katipo',
			'kdd-explorer',
			'kilroy',
			'knowledge',
			'kototoi',
			'kretrieve',
			'labelgrabber',
			'lachesis',
			'larbin',
			'legs',
			'libwww',
			'linkalarm',
			'link validator',
			'linkscan',
			'lockon',
			'lwp',
			'lycos',
			'magpie',
			'mantraagent',
			'mapoftheinternet',
			'marvin/',
			'mattie',
			'mediafox',
			'mediapartners',
			'mercator',
			'merzscope',
			'microsoft url control',
			'minirank',
			'miva',
			'mj12',
			'mnogosearch',
			'moget',
			'monster',
			'moose',
			'motor',
			'multitext',
			'muncher',
			'muscatferret',
			'mwd.search',
			'myweb',
			'najdi',
			'nameprotect',
			'nationaldirectory',
			'nazilla',
			'ncsa beta',
			'nec-meshexplorer',
			'nederland.zoek',
			'netcarta webmap engine',
			'netmechanic',
			'netresearchserver',
			'netscoop',
			'newscan-online',
			'nhse',
			'nokia6682/',
			'nomad',
			'noyona',
			'nutch',
			'nzexplorer',
			'objectssearch',
			'occam',
			'omni',
			'open text',
			'openfind',
			'openintelligencedata',
			'orb search',
			'osis-project',
			'pack rat',
			'pageboy',
			'pagebull',
			'page_verifier',
			'panscient',
			'parasite',
			'partnersite',
			'patric',
			'pear.',
			'pegasus',
			'peregrinator',
			'pgp key agent',
			'phantom',
			'phpdig',
			'picosearch',
			'piltdownman',
			'pimptrain',
			'pinpoint',
			'pioneer',
			'piranha',
			'plumtreewebaccessor',
			'pogodak',
			'poirot',
			'pompos',
			'poppelsdorf',
			'poppi',
			'popular iconoclast',
			'psycheclone',
			'publisher',
			'python',
			'rambler',
			'raven search',
			'roach',
			'road runner',
			'roadhouse',
			'robbie',
			'robofox',
			'robot',
			'robozilla',
			'rules',
			'salty',
			'sbider',
			'scooter',
			'scoutjet',
			'scrubby',
			'search.',
			'searchprocess',
			'semanticdiscovery',
			'senrigan',
			'sg-scout',
			'shai\'hulud',
			'shark',
			'shopwiki',
			'sidewinder',
			'sift',
			'silk',
			'simmany',
			'site searcher',
			'site valet',
			'sitetech-rover',
			'skymob.com',
			'sleek',
			'smartwit',
			'sna-',
			'snappy',
			'snooper',
			'sohu',
			'speedfind',
			'sphere',
			'sphider',
			'spinner',
			'spyder',
			'steeler/',
			'suke',
			'suntek',
			'supersnooper',
			'surfnomore',
			'sven',
			'sygol',
			'szukacz',
			'tach black widow',
			'tarantula',
			'templeton',
			'/teoma',
			't-h-u-n-d-e-r-s-t-o-n-e',
			'theophrastus',
			'titan',
			'titin',
			'tkwww',
			'toutatis',
			't-rex',
			'tutorgig',
			'twiceler',
			'twisted',
			'ucsd',
			'udmsearch',
			'url check',
			'updated',
			'vagabondo',
			'valkyrie',
			'verticrawl',
			'victoria',
			'vision-search',
			'volcano',
			'voyager/',
			'voyager-hc',
			'w3c_validator',
			'w3m2',
			'w3mir',
			'walker',
			'wallpaper',
			'wanderer',
			'wauuu',
			'wavefire',
			'web core',
			'web hopper',
			'web wombat',
			'webbandit',
			'webcatcher',
			'webcopy',
			'webfoot',
			'weblayers',
			'weblinker',
			'weblog monitor',
			'webmirror',
			'webmonkey',
			'webquest',
			'webreaper',
			'websitepulse',
			'websnarf',
			'webstolperer',
			'webvac',
			'webwalk',
			'webwatch',
			'webwombat',
			'webzinger',
			'wget',
			'whizbang',
			'whowhere',
			'wild ferret',
			'worldlight',
			'wwwc',
			'wwwster',
			'xenu',
			'xget',
			'xift',
			'xirq',
			'yandex',
			'yanga',
			'yodao',
			'zao/',
			'zippp',
			'zyborg',
			//kendi eklediklerim
			'applebot',
			'showyoubot',
			'rssowl',
			'docomo',
			'ichiro',
			'moget',
			'naverbot',
			'baiduspider',
			'baiduspider-video',
			'baiduspider-image',
			'sogou-test-spider',
			'sogou',
			'youdaobot',
			'youdaobot-image',
			'majestic-seo',
			'deusu',
			'paperlibot',
			'plukkie',
			'link_thumbnailer',
			'picofeed',
			'aria2',
			'hybridbot',
			'newspaper',
			'haberlerdenevar',
			'flipboardproxy',
			'shortlinktranslate',
			'alexabot',
			'proximic',
			'dotbot',
			'google-apps-script',
			'adbeat_bot',
			'java/1',
			'feedly',
			'special_archiver',
			'php/',
			'yak/',
			'something',
			'apple-pubsub',
			'archive.org_bot',
			'gnowitnewsbot',
			'traackr',
			'ltx71',
			'pocketparser',
			'anderspinkbot',
			'mail.ru_bot',
			'photon',
			'um-ln',
			'ahrefs',
			'getintent',
			//zararlı diye
			//firewall üstünden engellediklerim
			'mozilla/5.0 jorgee',
			'megaindex',
			'scalaj-http',
			'ia_archiver',
			'metauri',
			'mediatoolkitbot',
			'go-http-client',
			'qoshe',
			'simplepie',
			'ntentbot',
			'ahrefsbot',
			'grapeshotcrawler',
			'trendictionbot',
			'nuzzel',
			'istellabot',
			'aa-crawler',
			'apache-httpclient',
			'semrushbot',
			'getintent crawler',
			'uptimerobot',
			'moreover',
			'python-urllib',
			'grouphigh',
			'node-superagent',
			'guzzlehttp',
			'livelapbot',
			'buzztalk',
			'lknsuite monitoring bot',
			'admantx-adform',
			'okhttp',
			'python-requests',
			'ucrawler',
			'feedlybot',
			'yeti/',
			'needle/',
			'omgili',
			'alphabot',
			'mj12bot',
			'lwp-request',
			'mtm bot',
			'nutch-',
			'socialrankiobot',
			'um-fc/',
		);

		foreach($spiders as $spider)
		{
			//If the spider text is found in the current user agent, then return true
			if ( stripos(strtolower($_SERVER['HTTP_USER_AGENT']), $spider) !== false ) return true;
		}
		//If it gets this far then no bot was found!
		return false;
	}

