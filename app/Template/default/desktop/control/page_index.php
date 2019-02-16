<?php if(!defined('APP')) die('...');

	$site_title			= $L['pIndex_Company'];
	$content_metatitle 	= $L['pIndex_Company'];
	$content_metadesc 	= $L['pIndex_CompanyDesc'];
	$content_metatags	= $L['pIndex_Company'];
	$site_canonical 	= LINK_INDEX;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$author_list = $_author->authors_list_detailed($type = 'index', $json = 0);

	$index_manset_main = $_content->content_list_manset
	(
		$limit		= $config['desktop_manset_main'],
		$type		= '1',
		$template	= 'none',
		$cat		= 'none',
		$order		= 'order',
		$exclude 	= 'none',
		$json 		= 0
	);

	$index_manset_sur = $_content->content_list_manset
	(
		$limit		= $config['desktop_manset_sur'],
		$type		= '2',
		$template	= 'none',
		$cat		= 'none',
		$order		= 'order',
		$exclude 	= 'none',
		$json 		= 0
	);
	$index_manset_sondakika = $_content->content_list_manset
	(
		$limit		= $config['desktop_manset_sondakika'],
		$type		= '3',
		$template	= 'none',
		$cat		= 'none',
		$order		= 'order',
		$exclude 	= 'none',
		$json 		= 0
	);

	$index_block_galeri = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_galeri'],
		$type		= 'none',
		$template	= '1',
		$cat		= 'none',
		$order		= 'time',
		$exclude 	= 'none',
		$json 		= 0
	);

	if(RC_InternalVideo == 1)
	{
		$index_block_video = $_content->content_list_manset
		(
			$limit		= $config['desktop_block_video'],
			$type		= 'none',
			$template	= '4',
			$cat		= 'none',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 0
		);
	}

	$index_manset_alt_bir = $_content->content_list_manset
	(
		$limit		= $config['desktop_manset_alt_bir'],
		$type		= '4',
		$template	= 'none',
		$cat		= 'none',
		$order		= 'order',
		$exclude 	= 'none',
		$json 		= 0
	);
	$index_manset_alt_iki = $_content->content_list_manset
	(
		$limit		= $config['desktop_manset_alt_iki'],
		$type		= '5',
		$template	= 'none',
		$cat		= 'none',
		$order		= 'order',
		$exclude 	= 'none',
		$json 		= 0
	);

	$tmp_array = array_merge($index_manset_main, $index_manset_sur, $index_manset_sondakika, $index_manset_alt_bir, $index_manset_alt_iki, $index_block_galeri, $index_block_video);
	$exclude_list = '0';
	foreach($tmp_array as $k => $v) $exclude_list.=','.$v['content_id'];

	$index_block_roportaj = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_roportaj'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '114',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	$index_block_gundem = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_gundem'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '100',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	$index_block_spor = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_spor'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '101',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	$index_block_magazin = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_magazin'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '112',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	$index_block_dunya = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_dunya'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '104',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	$index_block_teknoloji = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_teknoloji'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '105',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	$index_block_yasam = $_content->content_list_manset
	(
		$limit		= $config['desktop_block_yasam'],
		$type		= 'none',
		$template	= '0,2',
		$cat		= '102',
		$order		= 'time',
		$exclude 	= $exclude_list,
		$json 		= 0
	);

	if(RC_ExternalVideo == 1)
	{
		$index_block_video = $_content->get_super_video_index($limit = 5);
	}

	$template = $twig->loadTemplate('page_index.twig');
	$content = $template->render
	(
		array
		(
			'author_list'				=> $author_list,

			'index_manset_sur'			=> $index_manset_sur,
			'index_manset_sondakika'	=> $index_manset_sondakika,
			'index_manset_main'			=> $index_manset_main,
			'index_manset_alt_bir'		=> $index_manset_alt_bir,
			'index_manset_alt_iki'		=> $index_manset_alt_iki,
			'index_block_roportaj'		=> $index_block_roportaj,

			'index_block_galeri'		=> $index_block_galeri,
			'index_block_video'			=> $index_block_video,

			'index_block_gundem'		=> $index_block_gundem,
			'index_block_spor'			=> $index_block_spor,
			'index_block_teknoloji'		=> $index_block_teknoloji,
			'index_block_magazin'		=> $index_block_magazin,
			'index_block_yasam'			=> $index_block_yasam,
			'index_block_dunya'			=> $index_block_dunya,
		)
	);
