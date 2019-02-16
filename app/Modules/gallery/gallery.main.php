<?php
	if(!defined('APP')) die('...');

	unset($array_content_status[0]);
	unset($array_content_status[4]);

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
		if($_auth['gallery_'.$action] <> '1') $hata_delete = '1';

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
		$content_image_dir		= $list[0]['content_image_dir'];


		//sonrasında standart işlemlerimizi yapalım
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do			= 'edit';
		$hata_edit	= '';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		if($_auth['gallery_'.$action] <> '1') $hata_edit = '1';

		if(RC_Authority == 1)
		{
			//sonrasında yetki denetimini gerçekleştiriyoruz
			if($_auth['content_type_edit_'.$content_type] <> '1')
			{
				$hata_edit = '1';
			}

			if($content_cat <> 0)
			{
				if(($_SESSION[SES]['user_id'] == $content_user) && ($_auth['content_cat_edit_'.$content_cat] <> '1'))			$hata_edit = '1';
				if(($_SESSION[SES]['user_id'] <> $content_user) && ($_auth['content_cat_others_edit_'.$content_cat] <> '1')) 	$hata_edit = '1';
			}
		}

		if($hata_edit <> '1')
		{
			//yetkilendirme denetimini geçtiysek
			//içerik durumunu kestiriyoruz
			if($_REQUEST['save_action'] == 'publish')	$_REQUEST['content_status'] = 1;
			if($_REQUEST['save_action'] == 'draft')		$_REQUEST['content_status'] = 2;

			include ACP_MODULE_PATH.'content/form.block/mainblock.gallery_save.php';

			include ACP_MODULE_PATH.'content/form.block/mainblock.content_save.php';
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

		$header_subtitle	= '<a href="'.$url.'">'.$list[0]['content_title'].'</a>';
	}

	if($do == 'add')
	{
		$header_subtitle	= 'Galeri Ekle';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Galeri Listesi';
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
			<li><a href="<?=LINK_ACP?>&amp;view=gallery&amp;do=list"><i class="ion ion-document"></i> <?=$page_name?></a></li>
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
						include ACP_MODULE_PATH.$modul_name[$view].'gallery.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'gallery.form.php';
					break;
					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'gallery.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
