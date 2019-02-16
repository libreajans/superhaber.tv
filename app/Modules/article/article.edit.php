<?php
	if(!defined('APP')) die('...');

	$action_type			= 'edit';
	$action_submit_draft	= 'Taslak';
	$action_submit_publish	= 'Yayınla';
	$array_authors			= $_author->author_list_array();

	if($do == 'add')
	{
		$_id = $_content->content_add();

		//tarihi ve image dir otomatik biz atayalım
		$content_time 		= date("Y-m-d H:i:s");
		$content_image_dir	= date("Y/m/d/");
		$content_user 		= $_SESSION[SES]['user_id'];

		//MAKALE şablonunu seçelim
		$content_template 	= 3;
	}
	else
	{

		//text
		$content_text					= $list[0]['content_text'];
		$content_desc					= $list[0]['content_desc'];

		//varchar
		$content_title					= $list[0]['content_title'];
		$content_title_outside			= $list[0]['content_title_outside'];
		$content_title_seo				= $list[0]['content_title_seo'];
		$content_title_url				= $list[0]['content_title_url'];
		$content_image					= $list[0]['content_image'];
		$content_image_dir				= $list[0]['content_image_dir'];
		$content_image_manset			= $list[0]['content_image_manset'];
		$content_redirect				= $list[0]['content_redirect'];
		$content_tags					= $list[0]['content_tags'];
		$content_metadesc				= $list[0]['content_metadesc'];

		//short int
		$content_status					= $list[0]['content_status'];
		$content_comment_status			= $list[0]['content_comment_status'];
		$content_manset_text_status		= $list[0]['content_manset_text_status'];
		$content_template				= $list[0]['content_template'];
		$content_cat_show_status		= $list[0]['content_cat_show_status'];
		$content_ads_status				= $list[0]['content_ads_status'];

		//int
		$content_cat					= $list[0]['content_cat'];
		$content_cat2					= $list[0]['content_cat2'];
		$content_type					= $list[0]['content_type'];
		$content_view					= $list[0]['content_view'];
		$content_view_real				= $list[0]['content_view_real'];

		//timestamp
		$content_time					= $list[0]['content_time'];
		$create_time					= $list[0]['create_time'];

		//biçimlendirmeler
		$content_image_link				= G_IMGLINK.'content/'.$content_image_dir.$content_image;
		$content_image_manset_link		= G_IMGLINK.'manset/'.$content_image_dir.$content_image_manset;

		//resmin boyutlarını kontrol etmekte kullanacağız
		$content_image_path				= IMAGE_DIRECTORY.'content/'.$content_image_dir.$content_image;
		$content_image_manset_path		= IMAGE_DIRECTORY.'manset/'.$content_image_dir.$content_image_manset;

		//yazar modülüne özel
		$content_author					= $list[0]['content_author'];
	}

	//yazar
	$option_author = '<option value="">Yazar Seçiniz</option>'. "\n";
	foreach($array_authors as $k => $v)
	{
		$selected = ''; if($content_author <> '' && $content_author == $k) $selected = 'selected';
		if(RC_Authority == 1)
		{
			if($_auth['article_edit_'.$k] == 1)
			{
				$option_author.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
			}
		}
		else
		{
			$option_author.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
		}
	}

	$option_cat = '<option value="">Kategori Seçiniz</option>'. "\n";
	foreach($array_cat_name as $k => $v)
	{
		$selected = ''; if($content_cat <> '' && $content_cat == $k) $selected = 'selected';
		if(RC_Authority == 1)
		{
			if($_auth['content_cat_edit_'.$k] == 1)
			{
				$option_cat.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
			}
		}
		else
		{
			$option_cat.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
		}
	}

	$option_cat2 = '<option value="">Ek Kategori</option>'. "\n";
	foreach($array_cat_name as $k => $v)
	{
		$selected = ''; if($content_cat2 <> '' && $content_cat2 == $k) $selected = 'selected';
		if(RC_Authority == 1)
		{
			if($_auth['content_cat_edit_'.$k] == 1)
			{
				$option_cat2.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
			}
		}
		else
		{
			$option_cat2.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
		}
	}

	//içerik tipleri
	foreach($array_content_type as $k => $v)
	{
		if(RC_Authority == 1)
		{
			if($_auth['content_type_edit_'.$k] == 1)
			{
				$selected = ''; if($content_type <> '' && $content_type == $k) $selected = 'selected';
				$option_type.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
			}
		}
		else
		{
			$selected = ''; if($content_type <> '' && $content_type == $k) $selected = 'selected';
			$option_type.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
		}
	}

	foreach($array_content_comment_status as $k => $v)
	{
		$selected = ''; if($content_comment_status <> '' && $content_comment_status == $k) $selected = 'selected';
		$option_comment_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

 	//checkbox checked oluşturuluyor
 	//ekleme modu dışındaysa checkboxları da işaretleyelim
 	if($do <> 'add' && $content_manset_text_status == 0)	$content_manset_text_status_checked = ' checked';
 	if($do <> 'add' && $content_cat_show_status == 0)		$content_cat_show_status_checked = ' checked';
 	if($do <> 'add' && $content_ads_status == 0)			$content_ads_status_checked = ' checked';


 	//---[+]--- Resim Alanı Uyarıları ----------------------------------------------

	if($content_image == '' && $do <> "add")
	{
		$aspect_error_image					= 1;
		$aspect_error_image_text			= 'Haber resim alanını boş bırakmayınız.';
	}

 	//içerik türü manşet ise, manşet resmi zorunlu olsun
	if($content_image_manset == '' && $array_content_type_required[$content_type] == true)
	{
		$aspect_error_image_manset			= 1;
		$aspect_error_image_manset_text		= 'Manşet resim alanını boş bırakmayınız';
	}

	//resim varsa resim boyutlarını kontrol ediyoruz
	if($content_image <> '')
	{
		$image_sizes = getimagesize($content_image_path);
		if($image_sizes[0] <> $array_content_image_wh['w']) $aspect_error_image = 1;
		if($image_sizes[1] <> $array_content_image_wh['h']) $aspect_error_image = 1;
		$aspect_error_image_text 			= 'Haber resim boyutları hatalı, lütfen resmi kırpınız';
	}

	//manşet resim varsa resim boyutlarını kontrol ediyoruz
	if($content_image_manset <> '' && $array_content_type_required[$content_type] == true)
	{
		$image_sizes = getimagesize($content_image_manset_path);
		if($image_sizes[0] <> $array_content_manset_wh[$content_type]['w']) $aspect_error_image_manset = 1;
		if($image_sizes[1] <> $array_content_manset_wh[$content_type]['h']) $aspect_error_image_manset = 1;
		$aspect_error_image_manset_text = ' Manşet resim boyutları hatalı, lütfen resmi kırpınız';
	}

// 	if(RC_DetailedSeo == 1)
// 	{
// 		if($_content->get_content_id_from_title($content_title, $_id) > 0)
// 		{
// 			$text_error_manset_title_dublicate 			= 1;
// 			$text_error_manset_title_dublicate_text		= 'Bu başlık daha önce kullanılmış; lütfen farklı bir iç başlık kullanın!';
// 		}
// 	}

	//---[-]--- Resim Alanı Uyarıları ----------------------------------------------

	//silme butonu gösterilecek mi, onu burdan denetliyoruz
	if($_auth['article_delete'] <> '1') $hata_delete = '1';

	if(RC_Authority == 1)
	{
		if($_auth['article_delete_'.$content_author] <> '1')	$hata_delete = '1';
	}
