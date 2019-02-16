<?php if(!defined('APP')) die('...');

	//sadece bu sayfada kullanıyoruz
	$array_month = array(
		'01' => 'Ocak',
		'02' => 'Şubat',
		'03' => 'Mart',
		'04' => 'Nisan',
		'05' => 'Mayıs',
		'06' => 'Haziran',
		'07' => 'Temmuz',
		'08' => 'Ağustos',
		'09' => 'Eylül',
		'10' => 'Ekim',
		'11' => 'Kasım',
		'12' => 'Aralık',
	);

	$template = $twig->loadTemplate('site_header.twig');
	$header = $template->render
	(
		array
		(
			//her sayfanın kendisinden gelecek olan değerler
			'site_title'				=> $site_title,
			'site_canonical'			=> $site_canonical,
			'content_metatitle' 		=> $content_metatitle,
			'content_metadesc' 			=> $content_metadesc,
			'content_metatags'			=> $content_metatags,
			'content_metaimage'			=> $content_metaimage,
			//global gelecek değerler
			'header_weather'			=> get_weather_istanbul(),
			'date_day' 					=> date('d'),
			'date_month' 				=> $array_month[date('m')],
			'date_year' 				=> date('Y'),
		)
	);
