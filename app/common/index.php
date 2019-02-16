<?php if(!defined('APP')) die('...');

	use phpFastCache\CacheManager;

	//uzun çalışsın
	ini_set('max_execution_time', 90);

	//türkiyeye geçelim
	date_default_timezone_set('Europe/Istanbul');

	//sayfa saatini başlatıyoruz
	$starttime = microtime(true);

	//--- [+]--- Root Path Doğrulaması ---
	$_SERVER['DOCUMENT_ROOT'] = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT']);
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
	{
		$_SERVER['DOCUMENT_ROOT'] .= '/';
	}
	//--- [-]--- Root Path Doğrulaması sonu ---

	//öncel ayarlar ayrı dosyaya alındı, bu sayede
	//init dosyasındaki yapılan değişikliklerden
	//yayındaki site daha az etkileniyor
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.config.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.prefunctions.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.session.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.bots.php';

	//hata bastırma şeklini belirliyoruz
	if(ST_DEBUG <> 2) error_reporting(E_ERROR);
	if(ST_DEBUG == 2) error_reporting(E_ALL);

	$_id			= myReq('id', 0);
	$_pg			= myReq('pg', 0);
	$_key 			= myReq('key',2);
	$sayfaadi 		= myReq('page',2);

	//sayfalar varsayılan olarak cache e kapalıdır,
	//sadece izin verilen sayfaları cacheleyelim
	$cachable	= 0;

	//uygun model dosyasını çağıralım
	//arayüzü değişmeyen sayfalar için common klasörünü kullanalım
	//mobil ve  değişen sayfalar için
	switch($sayfaadi)
	{
		//COMMON içinden çalışanlar
		case 'pageview':
			$inc		= 'pageview';
			break;

		case 'crop':
			$inc		= 'crop';
			break;

		case 'giris':
			$inc		= 'giris';
			break;

		case 'service':
			$inc		= 'service';
			break;

		case 'acp':
			$inc		= 'acp';
			break;

		//checkmobile PASİF olanlar

        case 'sitene_ekle':
			$checmobile = 0;
            $inc		= 'sitene_ekle';
            break;

        case 'widget':
			$checmobile = 0;
            $inc		= 'widget';
            break;

		case 'feed':
			$checmobile = 0;
			$inc		= 'feed';
			break;

		case 'sitemap':
			$checmobile = 0;
			$inc		= 'sitemap';
			break;

		case 'sitemap_news':
			$checmobile = 0;
			$inc		= 'sitemap_news';
			break;

		case 'detail_amp':

			$inc		= 'detail_amp';
			$cachable	= 1;
			$checmobile = 0; //mobil kontrolü yapılmasın
			break;

		//checkmobile AKTİF ve cacheble AKTİF olanlar
		case 'index':
			$inc		= 'index';
			$cachable	= 1;
			break;

		case 'detail_content':
			$inc		= 'detail_content';
			$cachable	= 1;
			break;

		case 'detail_gallery':
			$inc		= 'detail_gallery';
			$cachable	= 1;
			break;

		case 'detail_article':
			$inc		= 'detail_article';
			$cachable	= 1;
			break;

		case 'detail_video':
			$inc		= 'detail_video';
			$cachable	= 1;
			break;

		case 'ajax':
			$inc		= 'ajax';
			$cachable	= 1;
			break;

		//checkmobile AKTİF ve cacheble PASİF olanlar
		case 'page':
			$inc		= 'page';
			break;

		case '404':
			$inc		= '404';
			break;

		case 'cat':
			$inc		= 'cat';
			break;

		case 'authors':
			$inc		= 'authors';
			break;

		case 'contact':
			$inc		= 'contact';
			break;

		case 'archive':
			$inc		= 'archive';
			break;

		case 'search':
			$inc		= 'search';
			break;

		case 'tags':
			$inc		= 'tags';
			break;

		case 'gallery':
			$inc		= 'gallery';
			break;
		case 'video':
			$inc		= 'video';
			break;
	}

	//phpfastcache aktif ediliyor
	if(RC_SuperCache == 1)
	{
		//memcached storage aktif edilmiş phpFastCache'e ihtiyacımız olacak
		include $_SERVER['DOCUMENT_ROOT'].'vendors/phpfastcache/src/autoload.php';
		$InstanceCache = CacheManager::getInstance('memcache');
	}

	//phpfastcache aktif ise devam ediyoruz
	if(RC_SuperCache == 1 && $_SESSION[SES]['ADMIN'] <> 1 && $cachable == 1)
	{
		if($sayfaadi == 'detail_amp')
		{
			$face = 'amp';
		}
		else
		{
			$face = 'desktop';
			if
			(
				(stristr($_SERVER['HTTP_USER_AGENT'], "iphone"))	||
				(stristr($_SERVER['HTTP_USER_AGENT'], "ipad"))		||
				(stristr($_SERVER['HTTP_USER_AGENT'], "android"))
			)
			{
				$face	= 'mobile';
			}
		}

		$dosya_gs 			= $_SERVER['HTTP_HOST'].'_google_status_'.$_id;
		$CachedString_gs 	= $InstanceCache->getItem($dosya_gs);

		$dosya 				= $_SERVER['HTTP_HOST'].'_'.$face.'_'.$sayfaadi.'_'.$_id.'.html';
		$CachedString 		= $InstanceCache->getItem(mds($dosya));

		//sayfa daha önce cachelenmiş ise ilgili sayfayı ekrana basıp işlemi sonlandıralım
		if(!is_null($CachedString->get()))
		{
			//burda hile yapıp veritabanına bağlanalım
			//güncelleme sorgusu gönderelim
			//
			//NOTE detail_amp yok, çünkü onun değerleri js ile ping servisine geliyor
			if
			(
				$sayfaadi == 'detail_content' OR
				$sayfaadi == 'detail_gallery' OR
				$sayfaadi == 'detail_article' OR
				$sayfaadi == 'detail_video' OR
				$sayfaadi == 'ajax'
			)
			{
				//bağlantı için gerekli dosyalarımız
				include $_SERVER['DOCUMENT_ROOT'].'vendors/adodb5/adodb.inc.php';
				include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.connection.php';

				//okunma sayısını artır
				content_view_direct($_id);

				//google ziyaret etmişse not alalım
				if($sayfaadi != 'ajax' && $CachedString_gs->get() == '0')
				{
					content_view_google($_id);
				}
			}
			echo $CachedString->get();
			exit();
		}
	}

	//cache de sayfa yoksa bu kısım işlemeye başlayacak
	//sayfayı iteleyelim
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.php';

	if
	(
		$sayfaadi == 'pageview' OR
		$sayfaadi == 'crop' OR
		$sayfaadi == 'giris' OR
		$sayfaadi == 'service' OR
		$sayfaadi == 'acp'
	)
	{
		if($sayfaadi == "service" OR $sayfaadi == "acp" )
		{
			include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.template.php';
		}
		include $_SERVER['DOCUMENT_ROOT'].'app/common/'.$inc.'.php';
	}
	else
	{
		//template iteleyelim
		if($inc <> '')
		{
			include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.template.php';
			include $_SERVER['DOCUMENT_ROOT'].'app/Template/'.$template_name.'/Model/'.$inc.'.php';
		}
	}

	if(RC_SuperCache == 1 && $_SESSION[SES]['ADMIN'] <> 1 && $cachable == 1 )
	{
		if (is_null($CachedString_gs->get()))
		{
			if($icerik[0]['content_google_status'] == 0)
			{
				$CachedString_gs->set($icerik[0]['content_google_status'])->expiresAfter(3600);
				$InstanceCache->save($CachedString_gs);
			}
		}

		$footnote = '<!-- Cache Time '.date('Y-m-d H:i:s').' -->';
		if (is_null($CachedString->get()))
		{
			$CachedString->set($header.$content.$footer.$footnote)->expiresAfter(3600);
			$InstanceCache->save($CachedString);
		}
	}
