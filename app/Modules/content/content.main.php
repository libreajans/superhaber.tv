<?php if(!defined('APP')) die('...');

	if(isset($_REQUEST['order_content_manset']))
	{
		$do = 'manset';

		//yetki denetimi
		if($_auth['content_'.$action] <> '1') $hata_yetki = '1';
		if($hata_yetki <> '1')
		{
			try
			{
				$_content->order_content_manset($_REQUEST['type']);
				$_content->clear_index_cache();
				$message = $messages["info_manset_order"];
			}
			catch(Exception $e)
			{
				$message = $messages["error_manset_order"];
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	//truncate işlemi istenmiş ise
	if($action == 'truncate')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'none';

		//yetki denetimi
		if($_auth['content_'.$action] <> '1') $hata_truncate = '1';
		if($hata_truncate <> '1')
		{
			try
			{
				$_content->content_truncate();
				$message = $messages['info_truncate_passive_content'];
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_delete'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	//delete işlemi istenmiş ise
	if($action == 'delete')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'none';

		//içerik durumu için ek sorgu gönderiyoruz
		$list					= $_content->content_detail($_id);
		$content_type			= $list[0]['content_type'];
		$content_user			= $list[0]['content_user'];
		$content_cat			= $list[0]['content_cat'];

		//yetki denetimlerini kontrol ediyoruz
		//kişi içerik silme yetkisine sahip değilse içerik silemez
		if($_auth['content_'.$action] <> '1') $hata_delete = '1';

		if(RC_Authority == 1)
		{
			if($_auth['content_type_delete_'.$content_type] <> '1') $hata_delete = '1';
			if(($_SESSION[SES]['user_id'] == $content_user) && ($_auth['content_cat_delete_'.$content_cat] <> '1'))			$hata_delete = '1';
			if(($_SESSION[SES]['user_id'] <> $content_user) && ($_auth['content_cat_others_delete_'.$content_cat] <> '1'))	$hata_delete = '1';
		}

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_delete <> '1')
		{
			try
			{
				$_content->content_delete_soft($_id);
				$message = $messages['info_delete'];
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_delete'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}


	//update işlemi istenmiş ise
	if($action == 'edit')
	{
		$list					= $_content->content_detail($_id);
		$content_type			= $list[0]['content_type'];
		$content_user			= $list[0]['content_user'];
		$content_cat			= $list[0]['content_cat'];
		$content_status			= $list[0]['content_status'];
		$content_image_dir		= $list[0]['content_image_dir'];
		$content_template		= $list[0]['content_template'];


		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do			= 'edit';
		$hata_edit	= '';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		if($_auth['content_'.$action] <> '1') $hata_edit = '1';

		if(RC_Authority == 1)
		{
			if($_auth['content_type_edit_'.$content_type] <> '1')
			{
				$hata_edit = '1';
			}
			//içeriğin kayıtlı olduğu kategori SİLİNMİŞSE
			//yetki denetiminde kategori denetimini baypas edelim
			if(!array_key_exists($content_cat, $array_cat_name))
			{
				$content_cat = 0;
			}

			if($content_cat <> 0)
			{
				if(($_SESSION[SES]['user_id'] == $content_user) && ($_auth['content_cat_edit_'.$content_cat] <> '1'))			$hata_edit = '1';
				if(($_SESSION[SES]['user_id'] <> $content_user) && ($_auth['content_cat_others_edit_'.$content_cat] <> '1')) 	$hata_edit = '1';
			}
		}

		if($hata_edit <> '1')
		{

			//yetki denetimini geçtik, içeriğin yayın durumunu kestiriyoruz
			if($_REQUEST['save_action'] == 'publish')	$_REQUEST['content_status'] = 1;
			if($_REQUEST['save_action'] == 'draft')		$_REQUEST['content_status'] = 2;

			include ACP_MODULE_PATH.'content/form.block/mainblock.content_save.php';

			if($_REQUEST['content_manset_reset'] == 'on')
			{
				$do 				= 'manset';
				$_REQUEST['type']	= $_REQUEST['content_type'];
			}

		}
		else
		{
			$message = $messages['error_yetki'];
			$do = 'list';
		}
	}


	if($do == 'edit')
	{
		$list 				= $_content->content_detail($_id);
		$url 				= $list[0]['content_url'];

		$header_subtitle	= 'Düzenle &rarr; <a href="'.$url.'">'.$list[0]['content_title'].'</a>';
	}

	if($do == 'add')
	{
		$header_subtitle	= 'Yeni Haber Ekle';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Haber Listesi';
	}

	if(!empty($message['type']))
	{
		$alert = showMessageBoxS($message['text'], $message['type']);
	}

?>

<section class="content">
	<div>
		<ol class="breadcrumb">
			<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
			<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list"><i class="ion ion-document"></i> <?=$page_name?></a></li>
			<li class="active"><?=$header_subtitle?></li>
		</ol>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?=$alert?>
			<?php
				switch($do)
				{
					case 'add':
					case 'edit':
						include ACP_MODULE_PATH.$modul_name[$view].'content.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'content.form_'.$content_template.'.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'content.list.php';
					break;

					case 'manset':
						include ACP_MODULE_PATH.$modul_name[$view].'content.manset.php';
					break;

				}
			?>
		</div>
	</div>
</section>
