<?php if(!defined('APP')) die('...');

	//her halükarda sayfayı yüklüyoruz
	$icerik = $_content->content_detail($_id, $publish = 1, $json = 0);

	//yazı pasif ise ve yönetici değilse
	//ana sayfaya dönsün
	if($_SESSION[SES]['ADMIN'] <> 1 && $icerik[0]['content_status'] <> 1)
	{
		header("HTTP/1.1 404 Not Found");
		header('Location:'.LINK_404);
		exit();
	}

	//başlık yoksa, yazı da yoktur ;)
	//yazı yoksa, ana sayfaya dönsün
	if($icerik[0]['content_title'] == '')
	{
		header("HTTP/1.1 404 Not Found");
		header('Location:'.LINK_404);
		exit();
	}

	if($icerik[0]['content_template'] == 0 OR $icerik[0]['content_template'] == 3 OR $icerik[0]['content_template'] == 4)
	{
		header("HTTP/1.1 301 Moved Permanently");
		header('Location:'.$icerik[0]['content_url']);
		exit();
	}

	//içerik tipi yönlendirme ise
	if($icerik[0]['content_template'] == 2 )
	{
		//yönlendirilecek url boş değil ise yönlendir
		//boş ise ana sayfaya yönlendir
		if($icerik[0]['content_redirect'] <> '')
		{
            //yönlendirme okunma sayısını artır
            $_content->content_view($_id);

            //hedefe yönlendir
			header('Location:'.$icerik[0]['content_redirect']);
		}
		else
		{
			header('Location:'.LINK_INDEX);
		}
		exit();
	}

	//okunma sayısın artıralım
	$_content->content_view($_id);

	$site_title						= $icerik[0]['content_title_seo'].' - '.$array_cat_name[$icerik[0]['content_cat']];
	$content_metatitle 				= $icerik[0]['content_title_seo'];
	$content_metadesc 				= $icerik[0]['content_metadesc'];
	$content_metatags				= substr($icerik[0]['content_tags'], 0, 128);
	$site_canonical 				= $icerik[0]['content_url'];
	$link_edit 						= LINK_ACP.'&view=gallery&do=edit&id='.$_id;
	$content_metaimage				= $icerik[0]['content_image_url'];
	$gallery_data 					= $_gallery->get_gallery_images_list($_id, $icerik[0]['content_image_dir']);
	$benzer_list 					= $_content->content_list_benzer($cat = $icerik[0]['content_cat'], $_id, $limit = 10, $template = 1, $json = 0);
	$linkler 						= $_content->content_detay_links(
										$icerik[0]['content_id'],
										$icerik[0]['content_time'],
										$icerik[0]['content_cat'],
										$icerik[0]['content_template'],
										$icerik[0]['content_type']
									);

	$template = $twig->loadTemplate('page_detail_gallery.twig');
	$twig->addGlobal('color', 'color-page-gallery');
	$content = $template->render
	(
		array
		(
			'content' 				=> $icerik[0],
			'site_canonical' 		=> $site_canonical,
			'link_edit' 			=> $link_edit,
 			'content_benzer_list' 	=> $benzer_list,
			'gallery_data' 			=> $gallery_data,
			'gallery_data_adet' 	=> count($gallery_data),
			'linkler' 				=> $linkler,
		)
	);
