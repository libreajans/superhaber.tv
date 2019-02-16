<?php if(!defined('APP')) die('...');

	//id burada author_id anlamına gelmektedir, content_id ile karıştırılmamalıdır
	if($_key <> '')
	{
		$content_list		= $_content->content_list_author($_key, $limit = 100, $json = 0);
		$author_info		= $_author->author_page($_key, $id = 0);
		$site_title			= $author_info['author_name'].' - '.$array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metatitle 	= $author_info['author_name'].' - '.$array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metadesc 	= $author_info['author_name'].' - '.$array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metatags	= $author_info['author_name'].' - '.$array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';
		$site_canonical		= url_author($author_info['author_name']);
	}
	else
	{
		$author_content		= $_author->authors_list_detailed($type = 'page', $json = 0);
		$site_title			= $array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metatitle 	= $array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metadesc 	= $array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metatags	= $array_page_name[203].' - '.$L['pIndex_Company'];
		$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';
		$site_canonical		= LINK_AUTHOR;
	}

	$template = $twig->loadTemplate('page_authors.twig');
	$twig->addGlobal('sayfa_title', $array_page_name[203]);
	$twig->addGlobal('color', 'cat-110');

	$content = $template->render
	(
		array
		(
			'key'				=> $_key,
			'content_list'		=> $content_list,
			'author_info' 		=> $author_info,
			'author_content' 	=> $author_content,
		)
	);
