<?php
	if(!defined('APP')) die('...');

	$action_type			= 'edit';
	$action_submit_draft	= 'Taslak';
	$action_submit_publish	= 'Yayınla';

	if($do == 'add')
	{
		$_id = $_author->author_add();
	}
	else
	{
		$author_id				= $list[0]['author_id'];
		$author_name			= $list[0]['author_name'];
		$author_image			= $list[0]['author_image'];
		$author_twitter			= $list[0]['author_twitter'];
		$author_facebook		= $list[0]['author_facebook'];
		$author_instagram		= $list[0]['author_instagram'];
		$author_email			= $list[0]['author_email'];
// 		$author_text			= $list[0]['author_text'];
// 		$author_contact			= $list[0]['author_contact'];
// 		$author_notes			= $list[0]['author_notes'];
		$author_status			= $list[0]['author_status'];
		$author_show_index		= $list[0]['author_show_index'];
		$author_show_page		= $list[0]['author_show_page'];
		$author_order			= $list[0]['author_order'];

		$author_title 			= $author_title;
		$author_image_link		= G_IMGLINK.'author/'.$author_image;

		//resmin boyutlarını kontrol etmekte kullanacağız
		$author_image_path		= IMAGE_DIRECTORY.'author/'.$author_image;
	}

 	//checkbox checked oluşturuluyor
 	//ekleme modu dışındaysa checkboxları da işaretleyelim
 	if($do <> 'add' && $author_show_index == 0)		$author_show_index_checked = ' checked';
 	if($do <> 'add' && $author_show_page  == 0)		$author_show_page_checked = ' checked';

	//---[-]--- Resim Alanı Uyarıları ----------------------------------------------

	//resim varsa resim boyutlarını kontrol ediyoruz
	if($author_image <> '')
	{
		$image_sizes = getimagesize($author_image_path);
		if($image_sizes[0] <> $array_author_image_wh['w']) $aspect_error = 1;
		if($image_sizes[1] <> $array_author_image_wh['h']) $aspect_error = 1;
		$aspect_error_text = 'Resim boyutları hatalı, lütfen resmi kırpınız';
	}

	//---[-]--- Resim Alanı Uyarıları ----------------------------------------------

	//silme butonu gösterilecek mi, onu burdan denetliyoruz
	if($_auth['author_delete'] <> '1') 								$hata_delete = '1';

