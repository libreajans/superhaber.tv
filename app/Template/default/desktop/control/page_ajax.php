<?php
	if(!defined('APP')) die('...');

	$type = myReq('type', 1);

	if($type == 'cat')
	{
		//başka içerik devralmasın
		unset($content);

		$template = $twig->loadTemplate('ajax/ajax_page_content.twig');
		//içerikleri listeliyoruz
		$content = $template->render
		(
			array
			(
				'content_list' => $_content->content_list_cat
				(
					$_pg,
					$config['desktop_limit_cat'],
					$_id
				),
			)
		);
		echo $content;
	}

	if($type == 'etiket')
	{
		//başka içerik devralmasın
		unset($content);

		$template = $twig->loadTemplate('ajax/ajax_page_content.twig');
		//içerikleri listeliyoruz
		$content = $template->render
		(
			array
			(
				'content_list' => $_content->content_list_etiket(
					$_pg,
					$config['desktop_limit_cat'],
					$_key
				),
			)
		);
		echo $content;
	}

	if($type == 'gallery')
	{
		//başka içerik devralmasın
		unset($content);

		$template = $twig->loadTemplate('ajax/ajax_page_gallery.twig');
		//içerikleri listeliyoruz
		$content = $template->render
		(
			array
			(
				'content_list' => $_content->content_list_gallery(
					$type		= 'none',
					$template	= '1',
					$limit		= $config['desktop_limit_gallery'],
					$page		= $_pg
				)
			)
		);
		echo $content;
	}

	if($type == 'video')
	{
		//başka içerik devralmasın
		unset($content);

		$template = $twig->loadTemplate('ajax/ajax_page_video.twig');
		//içerikleri listeliyoruz
		$content = $template->render
		(
			array
			(
				'content_list' => $_content->content_list_gallery(
					$type		= 'none',
					$template	= '4',
					$limit		= $config['desktop_limit_gallery'],
					$page		= $_pg
				)
			)
		);
		echo $content;
	}

	if($type == 'infinity_content')
	{
		//başka içerik devralmasın
		unset($content);

		//her halükarda sayfayı yüklüyoruz
		$icerik = $_content->content_detail($_id, $publish = 1, $json = 0);

		//yazı pasif ise ve yönetici değilse
		if($_SESSION[SES]['ADMIN'] <> 1 && $icerik[0]['content_status'] <> 1) return false;

		//başlık yoksa, yazı da yoktur ;)
		if($icerik[0]['content_title'] == '') return false;
		if($icerik[0]['content_template'] <> 0 ) return false;

		//her halükarda okunma sayılarını artır
		$_content->content_view($_id);

		$site_canonical 				= $icerik[0]['content_url'];
		$link_edit 						= LINK_ACP.'&view=content&do=edit&id='.$_id;
		$link_comment_edit 				= LINK_ACP.'&view=comment&do=edit&id=';
		$array_tags						= explode(',', $icerik[0]['content_tags']);
		$content_with_smilar 			= $_content->content_with_smilar($icerik[0]['content_text']);
		$benzer_list 					= $_content->content_list_benzer($cat = $icerik[0]['content_cat'], $_id, $limit = 3 , $template = 0, $json = 0);
		$linkler 						= $_content->content_detay_links(
											$icerik[0]['content_id'],
											$icerik[0]['content_time'],
											$icerik[0]['content_cat'],
											$icerik[0]['content_template'],
											$icerik[0]['content_type']
										);

		//üstüne videoları parse ediyoruz
		$content_with_smilar 			= $_content->content_with_smilar_video($content_with_smilar, $mobile = 0);

		$template = $twig->loadTemplate('ajax/ajax_page_detail_content.twig');
		$content = $template->render
		(
			array
			(
				'content_benzer_list'	=> $benzer_list,
				'content' 				=> $icerik[0],
				'site_canonical' 		=> $site_canonical,
				'link_edit' 			=> $link_edit,
				'array_tags' 			=> $array_tags,
				'content_with_smilar' 	=> $content_with_smilar,
				'comment_list'			=> $_comment->comment_list_content($_id),
				'link_comment_edit' 	=> $link_comment_edit,
				'linkler' 				=> $linkler,
				'comment_to_hash' 		=> mds($_id.'/'.date('Y-m-d H:i:s')),
				'comment_to_date' 		=> date('Y-m-d H:i:s'),
			)
		);
		echo $content;
	}


	if($type == 'infinity_article')
	{
		//başka içerik devralmasın
		unset($content);

		//her halükarda sayfayı yüklüyoruz
		$icerik = $_content->content_detail($_id, $publish = 1, $json = 0);

		//yazar sayfasındayız, yazarımızın bilgisini alalım
		$author_info = $_author->author_page($_key = "", $icerik[0]['content_author']);

		//yazı pasif ise ve yönetici değilse
		if($_SESSION[SES]['ADMIN'] <> 1 && $icerik[0]['content_status'] <> 1) return false;

		//başlık yoksa, yazı da yoktur ;)
		if($icerik[0]['content_title'] == '') return false;
		if($icerik[0]['content_template'] <> 3 ) return false;

		//her halükarda okunma sayılarını artır
		$_content->content_view($_id);

		$site_canonical 				= $icerik[0]['content_url'];
		$link_edit 						= LINK_ACP.'&view=article&do=edit&id='.$_id;
		$link_comment_edit 				= LINK_ACP.'&view=comment&do=edit&id=';
		$array_tags						= explode(',', $icerik[0]['content_tags']);
		$content_with_smilar 			= $_content->content_with_smilar($icerik[0]['content_text']);
		$benzer_list 					= $_article->author_articles($icerik[0]['content_author'], 10);
		$linkler 						= $_content->article_pre_link( $icerik[0]['content_id'], $icerik[0]['content_time'], $icerik[0]['content_author'], $json = 0);

		//üstüne videoları parse ediyoruz
		$content_with_smilar 			= $_content->content_with_smilar_video($content_with_smilar, $mobile = 0);

		$template = $twig->loadTemplate('ajax/ajax_page_detail_article.twig');
		$content = $template->render
		(
			array
			(
				'content' 				=> $icerik[0],
				'site_canonical' 		=> $site_canonical,
				'link_edit' 			=> $link_edit,
				'array_tags' 			=> $array_tags,
				'content_with_smilar' 	=> $content_with_smilar,
				'content_benzer_list'	=> $benzer_list,
				'comment_list'			=> $_comment->comment_list_content($_id),
				'link_comment_edit' 	=> $link_comment_edit,
				'author_info'			=> $author_info,
				'linkler' 				=> $linkler,
				'comment_to_hash' 		=> mds($_id.'/'.date('Y-m-d H:i:s')),
				'comment_to_date' 		=> date('Y-m-d H:i:s'),
			)
		);
		echo $content;
	}

	if($type == 'infinity_gallery')
	{
		//başka içerik devralmasın
		unset($content);

		//her halükarda sayfayı yüklüyoruz
		$icerik = $_content->content_detail($_id, $publish = 1, $json = 0);
		//yazı pasif ise ve yönetici değilse
		if($_SESSION[SES]['ADMIN'] <> 1 && $icerik[0]['content_status'] <> 1) return false;

		//başlık yoksa, yazı da yoktur ;)
		if($icerik[0]['content_title'] == '') return false;
		if($icerik[0]['content_template'] <> 1 ) return false;

		//her halükarda okunma sayılarını artır
		$_content->content_view($_id);

		$site_canonical 				= $icerik[0]['content_url'];
		$link_edit 						= LINK_ACP.'&view=gallery&do=edit&id='.$_id;
		$gallery_data 					= $_gallery->get_gallery_images_list($_id, $icerik[0]['content_image_dir']);
		$benzer_list 					= $_content->content_list_benzer($cat = $icerik[0]['content_cat'], $_id, $limit = 10, $template = 1, $json = 0);
		$linkler 						= $_content->content_detay_links(
											$icerik[0]['content_id'],
											$icerik[0]['content_time'],
											$icerik[0]['content_cat'],
											$icerik[0]['content_template'],
											$icerik[0]['content_type']
										);

		$template = $twig->loadTemplate('ajax/ajax_page_detail_gallery.twig');
		$content = $template->render
		(
			array
			(
				'content_onecikanlar'	=> $benzer_list,
				'content' 				=> $icerik[0],
				'site_canonical' 		=> $site_canonical,
				'link_edit' 			=> $link_edit,
				'content_benzer_list' 	=> $benzer_list,
				'gallery_data' 			=> $gallery_data,
				'gallery_data_adet' 	=> count($gallery_data),
				'linkler' 				=> $linkler,
			)
		);
		echo $content;
	}

	if($type == 'submit_comment')
	{
		$ress = $_comment->comment_save();
		echo $ress;
	}
