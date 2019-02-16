<?php
	if(!defined('APP')) die('...');

	$template_name	= ST_TEMPLATE.'/desktop';
	if($checmobile <> 0)
	{
		if
		(
			(stristr($_SERVER['HTTP_USER_AGENT'], "iphone"))	||
			(stristr($_SERVER['HTTP_USER_AGENT'], "ipad"))		||
			(stristr($_SERVER['HTTP_USER_AGENT'], "android"))
		)
		{
			$template_name	= ST_TEMPLATE.'/mobile';
		}
	}

	if(ST_CDN == 0)
	{
		define('G_CSSLINK',				G_ASSETSLINK.$template_name.'/css/');
		define('G_JSLINK',				G_ASSETSLINK.$template_name.'/js/');
		define('G_IMGLINK_APP',			G_ASSETSLINK.$template_name.'/img/');
	}

	if(ST_CDN == 1)
	{
		define('G_CSSLINK',				G_ASSETSLINK.$template_name.'/css/');
		define('G_JSLINK',				G_ASSETSLINK.$template_name.'/js/');
		define('G_IMGLINK_APP',			G_ASSETSLINK.$template_name.'/img/');
	}

	Twig_Autoloader::register();

	//$loader = new Twig_Loader_Filesystem($templateDir);
	$loader	= new Twig_Loader_Filesystem(G_TEMPLATE_PATH.$template_name.'/view/');

	//debug kapalı ise bellekleme işlemi aktif
	if(ST_DEBUG == 0)	$twig = new Twig_Environment($loader, array('cache' => $_SERVER['DOCUMENT_ROOT'].'cache/tmp/'));

	//debug açık ise bellekleme işlemi pasif
	if(ST_DEBUG <> 0)	$twig	= new Twig_Environment($loader);

	//Rakam gösterim ayarı ile oynayarak Türkçe uyumlu sayı formatına geçiyoruz
	$twig->getExtension('Twig_Extension_Core')->setNumberFormat(0, ',', '.');

	//temada kullanacağımız global değişkenleri bu şekilde geçiriyoruz
	//yoksa tek tek tema dosyalarında geçirmek zorunda kalıyoruz
	//Desktop Dayfalar
	$twig->addGlobal('SITELINK',			SITELINK);
	$twig->addGlobal('LINK_INDEX',			LINK_INDEX);

	$twig->addGlobal('LINK_SEARCH',			LINK_SEARCH);
	$twig->addGlobal('LINK_ACP',			LINK_ACP);
	$twig->addGlobal('LINK_AJAX',			LINK_AJAX);

	$twig->addGlobal('LINK_FEED',			LINK_FEED);
	$twig->addGlobal('LINK_TAGS',			LINK_TAGS);
	$twig->addGlobal('LINK_AUTHOR',			LINK_AUTHOR);
	$twig->addGlobal('LINK_ARSIV',			LINK_ARSIV);
 	$twig->addGlobal('LINK_GALLERY',		LINK_GALLERY);
 	$twig->addGlobal('LINK_VIDEO',			LINK_VIDEO);
 	$twig->addGlobal('LINK_CONTACT',		LINK_CONTACT);
 	$twig->addGlobal('LINK_SITENE_EKLE',	LINK_SITENE_EKLE);
 	$twig->addGlobal('LINK_WIDGET',			LINK_WIDGET);

	//kaynaklar
	$twig->addGlobal('G_CSSLINK',			G_CSSLINK);
	$twig->addGlobal('G_JSLINK',			G_JSLINK);
	$twig->addGlobal('G_IMGLINK_APP',		G_IMGLINK_APP);
	$twig->addGlobal('G_VENDOR_JQUERY',		G_VENDOR_JQUERY);
	$twig->addGlobal('G_BANNER',			G_BANNER);
	//diziler
	$twig->addGlobal('cat_name',			$array_cat_name);
	$twig->addGlobal('cat_url',				$array_cat_url);
	$twig->addGlobal('cat_url_slug',		$array_cat_url_slug);
	$twig->addGlobal('page_name',			$array_page_name);
	$twig->addGlobal('page_url',			$array_page_url);
	$twig->addGlobal('social_media',		$array_social_media);
	$twig->addGlobal('L',					$L);
	$twig->addGlobal('config',				$config);

	//değerler
	$twig->addGlobal('admin',				$_SESSION[SES]['ADMIN']);
	$twig->addGlobal('site_keywords',		$config['meta_tags']);
	$twig->addGlobal('sayfaadi',			$sayfaadi);
	$twig->addGlobal('id',					$_id);
	$twig->addGlobal('pg',					$_pg);
	$twig->addGlobal('etiket',				$_key);
	//reklam gösterim durumunu ayarlar
	//her reklam gösterilmeden önce denetlenir
	$twig->addGlobal('ads_status',			$ads_status);
	$twig->addGlobal('ST_ONLINE',			ST_ONLINE);
	//bir kaç config daha
	$twig->addGlobal('RC_ExternalVideo',	RC_ExternalVideo);
	$twig->addGlobal('RC_InternalVideo',	RC_InternalVideo);

