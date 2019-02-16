<?php if(!defined('APP')) die('...');

	$site_title			= $L['pIndex_Company'];
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	// Widget için gelen parametrelerimiz.
	$cat   		= myReq('cat', 1);
	$limit  	= myReq('limit', 0);
	$limit_min	= 10;
	$limit_max	= 100;

	//dont be lazy man
	if($limit < $limit_min)	$limit = $limit_min;
	if($limit > $limit_max)	$limit = $limit_max;

	// Kullanıcı hiçbir parametre göndermemişse
	// çıkış yapıyoruz
	if($cat == "" or $limit == 0)
	{
		header('Location:'.LINK_INDEX);
		exit();
	}

	//1. set kodlar

	if($cat == 'mansetler')
	{
		$mansetler = $_content->content_list_manset(
			$limit		=  $limit,
			$type		= '1',
			$template	= 'none',
			$cat		= 'none',
			$order		= 'order',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Manşet Haberleri';
		$widget_title_url	= LINK_INDEX;
	}

	if($cat == 'gundem')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '100',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Gündem Haberleri';
		$widget_title_url	= $array_cat_url[100];
	}

	if($cat == 'spor')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '101',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Spor Haberleri';
		$widget_title_url	= $array_cat_url[101];
	}

	//2. set kodlar

	if($cat == 'magazin')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '112',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Magazin Haberleri';
		$widget_title_url	= $array_cat_url[112];
	}

	if($cat == 'ekonomi')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '103',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Ekonomi Haberleri';
		$widget_title_url	= $array_cat_url[103];
	}

	if($cat == 'seyahat')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '107',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Seyahat Haberleri';
		$widget_title_url	= $array_cat_url[107];
	}

	//3. set kodlar

	if($cat == 'tv')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '108',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv TV Haberleri';
		$widget_title_url	= $array_cat_url[108];
	}

	if($cat == 'teknoloji')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '105',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Teknoloji Haberleri';
		$widget_title_url	= $array_cat_url[105];
	}

	if($cat == 'roportaj')
	{
		$mansetler = $_content->content_list_manset(
			$limit		= $limit,
			$type		= 'none',
			$template	= '0,1',
			$cat		= '114',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
		$widget_title		= 'Superhaber.tv Röportajları';
		$widget_title_url	= $array_cat_url[114];
	}

	//basıp gidelim buralardan
	//eğer ki elimizde
	$adet = count($mansetler);
	if($adet < 0)
	{
		header('Location:'.LINK_INDEX);
		exit();
	}

	$site_canonical = SITELINK.$cat.'/'.$limit;

	$template = $twig->loadTemplate('page_widget.twig');
	$content = $template->render
	(
		array
		(
			'content'			=> $mansetler,
			'widget_title'		=> $widget_title,
			'widget_title_url'	=> $widget_title_url,
			'site_canonical'	=> $site_canonical,
		)
	);
