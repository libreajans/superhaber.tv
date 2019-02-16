<?php
	if(!defined('APP')) die('...');

	//truncate işlemi istenmiş ise
	if($action == 'truncate')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'list';

		//yetki denetimi
		if($_auth['contact_'.$action] <> '1') $hata_truncate = '1';
		if($hata_truncate <> '1')
		{
			try
			{
				$_contact->contact_truncate();
				$message = $messages['info_truncate_passive_contact'];
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_delete'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
	}

	//update işlemi istenmiş ise
	if($action == 'edit')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'list';
		if($_REQUEST['save_action'] == 'delete') $do = 'list';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		//
		//düzenleme yetkisine sahip mi diye bakıyoruz
		if($_auth['contact_'.$action] <> '1') $hata_edit = '1';

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_edit <> '1')
		{
			if($_REQUEST['save_action'] == 'publish')	$_REQUEST['contact_status'] = 1;
			if($_REQUEST['save_action'] == 'draft')		$_REQUEST['contact_status'] = 2;
			if($_REQUEST['save_action'] == 'delete')	$_REQUEST['contact_status'] = 4;

			try
			{
				//sayfa eklenmek isteniyor ise
				//önce bir tane boş sayfa ekliyoruz
				//sonra, dönen id değerine göre düzenleme modunda sayfayı açıyoruz
				$_contact->contact_edit($_id);
				$message = $messages['info_edit'];
			}
			catch(Exception $e)
			{
				//demek ki ekleme işlemi sırasında bir hata oluştur
									$message 			= $messages['error_edit'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	if($do == 'edit')
	{
		$list 				= $_contact->contact_list($_id);
		$ser_title			= $list[0]['contact_email'];
		$header_subtitle	= ' Düzenle &rarr; '.$ser_title;
	}

	if($do == 'list')
	{
		$header_subtitle	= 'İletişim Formu Mesajları';
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
			<li><a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list"><i class="ion ion-android-contact"></i> <?=$page_name?></a></li>
			<li class="active"><?=$header_subtitle?></li>
		</ol>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?=$alert?>
			<?php
				switch($do)
				{
					case 'edit':
						include ACP_MODULE_PATH.$modul_name[$view].'contact.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'contact.form.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'contact.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
