<?php
	if(!defined('APP')) die('...');

	/*
	//işe yaramadı
	//istediğimizden fazla hata topluyor

	set_error_handler('myErrorHandler');
	register_shutdown_function('fatalErrorShutdownHandler');

	function myErrorHandler($code, $message, $file, $line)
	{
		//hatalı urlyi kaydedelim
		$uri.= 'URL			: '.$_SERVER['REQUEST_URI']."\n";
		$uri.= 'Code		: '.$code."\n";
		$uri.= 'Message		: '.$message."\n";
		$uri.= 'File		: '.$file."\n";
		$uri.= 'Line		: '.$line."\n\n";

		//tüm hata mesajları
		$uri.= file_get_contents('cache/fatal.errors');
		file_put_contents('cache/fatal.errors', $uri);

		//hata veren urller
		$uri = $_SERVER['REQUEST_URI']."\n";
		$uri.= file_get_contents('cache/url.errors');
		file_put_contents('cache/url.errors', $uri);
	}

	function fatalErrorShutdownHandler()
	{
		$last_error = error_get_last();
		if($last_error['type'] === E_PARSE)
		{
			myErrorHandler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}
	*/

	//TÜM SAYFA BAĞLANTILARI
	define('SITELINK',					'http://'.$_SERVER['HTTP_HOST'].'/');

	define('LINK_INDEX',				SITELINK);
	define('LINK_BASE',					SITELINK.'index.php?page=');
	define('LINK_ACP',					LINK_BASE.'acp');
	define('LINK_404',					LINK_BASE.'404');
	define('LINK_CIKIS',				LINK_BASE.'giris&amp;logout=1');
	define('LINK_GIRIS',				LINK_BASE.'giris');
	define('LINK_AJAX',					LINK_BASE.'ajax');

	//torpilli direk linkler
	define('LINK_SEARCH',				SITELINK.'arama');
	define('LINK_FEED',					SITELINK.'feed');
	define('LINK_TAGS',					SITELINK.'etiket/');
 	define('LINK_AUTHOR',				SITELINK.'yazarlar');
 	define('LINK_ARSIV',				SITELINK.'arsiv');
	define('LINK_GALLERY',				SITELINK.'galeri');
	define('LINK_VIDEO',				SITELINK.'video');
	define('LINK_CONTACT',				SITELINK.'iletisim');
	define('LINK_SITENE_EKLE',			SITELINK.'sitene-ekle');
	define('LINK_WIDGET',				SITELINK.'widget/');

	define('SITEHOST',					'http://'.$_SERVER['HTTP_HOST']);
	if(ST_CDN == 0)
	{
		define('G_ASSETSLINK',			SITELINK.'assets/');
		define('G_VENDORLINK',			SITELINK.'vendors/');
		define('G_IMGLINK_DEV',			G_ASSETSLINK.'img/');
		define('G_IMGLINK',				G_ASSETSLINK.'uploads/images/');
		define('G_BANNER',				G_ASSETSLINK.'uploads/banner/');
		define('G_VENDOR_ADMINLTE',		G_VENDORLINK.'AdminLTE_230/');
		define('G_VENDOR_JQUERY',		G_VENDORLINK.'scripts/jQuery/');
		define('G_VENDOR_TINYMCE',		G_VENDORLINK.'tinymce/');
	}

	if(ST_CDN == 1)
	{
		define('G_ASSETSLINK',			'http://cdn.superhaber.tv/assets/');
		define('G_VENDORLINK',			'http://cdn.superhaber.tv/vendors/');
		define('G_IMGLINK_DEV',			G_ASSETSLINK.'img/');
		define('G_IMGLINK',				G_ASSETSLINK.'uploads/images/');
		define('G_BANNER',				G_ASSETSLINK.'uploads/banner/');
		define('G_VENDOR_ADMINLTE',		G_VENDORLINK.'AdminLTE_230/');
		define('G_VENDOR_JQUERY',		G_VENDORLINK.'scripts/jQuery/');
		define('G_VENDOR_TINYMCE',		G_VENDORLINK.'tinymce/');
	}
	//path tanımlamaları sonu

	//uygulama kaynakların include yollarını
	//PATH değerleri olarak burada tanımlıyoruz
	//dikkat
	//bu alanın dışında bir yerlerde PATH tanımlamayınız
	define('ACP_MODULE_PATH',			$_SERVER['DOCUMENT_ROOT'].'app/Modules/');
	define('G_TEMPLATE_PATH',			$_SERVER['DOCUMENT_ROOT'].'app/Template/');
	define('IMAGE_DIRECTORY',			$_SERVER['DOCUMENT_ROOT'].'assets/uploads/images/');
	define('IMAGE_DIRECTORY_DEV',		$_SERVER['DOCUMENT_ROOT'].'assets/img/');
	//dizide url yolları tanımladığımız için,
	//sitelink değerini burada tanımlıyoruz

	//tablolar tanımlıyoruz
	define('T_USER',					'app_user');
	define('T_USER_LOG',				'app_user_log');
	define('T_PAGE',					'app_page');
	define('T_CONTENT',					'app_content');
	define('T_VIEW',					'app_view');
	define('T_COMMENTS',				'app_comment');
	define('T_GALLERY_IMAGES', 			'app_gallery_images');
	define('T_CONTACT',					'app_contact');
	define('T_AUTHOR',					'app_author');

	//recaptha keyleri
	define('capthaPublicKey',			'6LeNWA8UAAAAAFqg8DYW_c_OzY_d6SQc5G9m2lN_');
	define('capthaPublicKey',			'6LeNWA8UAAAAAFqg8DYW_c_OzY_d6SQc5G9m2lN_');
	define('capthaPrivateKey',			'6LeNWA8UAAAAAHPZ-nuwgdHz4tL0TI8ePz-gH-TP');

	//akismet key
	define('akismetKey',				'042ad8b8d511');


	//yönetici ise sql sorguları cachlenmesin
	if($_SESSION[SES]['ADMIN'] == 1)
	{
		//yönetici ise reklam daima kapalı olsun
		//lakin reklam alanları ekleyeceğimiz için geçici olarak reklamları açalım
		//yönetici ise memcache daima kapalı olsun
		//$ads_status					= 1;
		$ads_status					= 0;
		$memcache_status 			= 0;
		define('memcached',			$memcache_status);
		define('cachetime',			0);
		define('cachetime_manset',	0);
	}
	else
	{
		//yönetici değilse reklam gösterilsin
		$ads_status					= 1;
		//cachetime, saniye cinsinden hesaplanıyor ve config değerden geliyor
		define('memcached',			$memcache_status);
		define('cachetime',			300);
		define('cachetime_manset',	60);
	}

	//vendors dosyalar
	//Veritabanı class, oturum ve bağlantı dosyası, captha, mailer, Twig, Simple Resize
	include $_SERVER['DOCUMENT_ROOT'].'vendors/adodb5/adodb.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'vendors/adodb5/adodb-memcache.lib.inc.php';
	//include $_SERVER['DOCUMENT_ROOT'].'vendors/captha/captha.php'; //eski class, şimdilik yerinde kalsın
	include $_SERVER['DOCUMENT_ROOT'].'vendors/recaptcha/src/autoload.php';

	include $_SERVER['DOCUMENT_ROOT'].'vendors/Twig/Autoloader.php';
	include $_SERVER['DOCUMENT_ROOT'].'vendors/classes/class.simpleresize.php';
	include $_SERVER['DOCUMENT_ROOT'].'vendors/PHPAkismet/Akismet.class.php';

	//kendi dosyalarımız
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.connection.php';

	//bu dosya dil değerleri için gerekli
	//oturum tablosundan sonra çalışmak zorunda
	//sonraki dosya ise, dizi değerlerimiz
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.array.php';

	//modüllerimizi çağırıyoruz
 	include ACP_MODULE_PATH.'user/CLASS.user.php';
 	include ACP_MODULE_PATH.'page/CLASS.page.php';
 	include ACP_MODULE_PATH.'content/CLASS.content.php';
 	include ACP_MODULE_PATH.'comment/CLASS.comment.php';
 	include ACP_MODULE_PATH.'stats/CLASS.stats.php';
 	include ACP_MODULE_PATH.'contact/CLASS.contact.php';
 	include ACP_MODULE_PATH.'gallery/CLASS.gallery.php';
 	include ACP_MODULE_PATH.'author/CLASS.author.php';
 	include ACP_MODULE_PATH.'article/CLASS.article.php';

	//nesneler oluşturuluyor
	$_user			= new user();
	$_page			= new page();
	$_content		= new content();
	$_comment		= new comment();
	$_stats			= new stats();
	$_gallery		= new gallery();
	$_contact		= new contact();
	$_author		= new author();
	$_article		= new article();
	$_image 		= new SimpleImage();

	//içerik detayda içeriğe özel olarak reklam gösterimi kapatılabilsin
	if($ads_status <> 0 && ($sayfaadi == 'detail_content'))
	{
		$ads_status = $_content->content_ads_status($_id);
	}
