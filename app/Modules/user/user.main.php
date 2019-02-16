<?php
	if(!defined('APP')) die('...');

	//truncate işlemi istenmiş ise
	if($action == 'truncate')
	{
		//hangi moda döneceğimizi belirtiyoruz
		$do = 'none';

		//yetki denetimi
		if($_auth['user_'.$action] <> '1') $hata_truncate = '1';
		if($hata_truncate  <> '1')
		{
			try
			{
				$_user->user_log_truncate();
				$message = $messages['info_truncate_acces_logs'];
			}
			catch(Exception $e)
			{
				//ekleme işlemi sırasında bir hata oluştu
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
		//hangi moda döneceğimizi belirtiyoruz
		$do = 'none';

		//kullanıcı silme yetki kontrolü yapıyoruz
		if($_auth['user_'.$action] <> '1') $hata_delete = '1';

		//yetkilendirme hatası yoksa
		if($hata_delete <> '1')
		{
			try
			{
				if($_id <> '1')
				{
					$_user->user_delete_soft($_id);
					$message = $messages['info_delete'];
				}
				else
				{
					//kurucu üye silinemez
					$message = $messages['error_kurucuUyeSilinemez'];
				}
			}
			catch(Exception $e)
			{
				//ekleme işlemi sırasında bir hata oluştu
									$message 			= $messages['error_delete'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	//add işlemi istenmiş ise
	if($action == 'add')
	{
		//hangi moda döneceğimizi belirtiyoruz
		$do = 'list';

		//kullanıcı ekleme yetki kontrolü yapıyoruz
		if($_auth['user_'.$action] <> '1') $hata_add = '1';

		//yetkilendirme hatası yoksa
		if($hata_add <> '1')
		{
			try
			{
				//kullanıcı eklenmek isteniyor ise
				//önce bir tane boş kullanıcı ekliyoruz
				//düzenleme modunda kullanıcıyı açıyoruz
				$_id = $_user->user_add();
				$_user->user_edit($_id);
				$message = $messages['info_add'];
			}
			catch(Exception $e)
			{
				//ekleme işlemi sırasında bir hata oluştu
									$message 			= $messages['error_add'];
				if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
			}
		}
		else
		{
			$message = $messages['error_yetki'];
		}
	}

	//edit işlemi istenmiş ise
	if($action == 'edit')
	{
		//hangi moda döneceğimizi belirtiyoruz
		$do = 'edit';

		//kullanıcı düzenleme yetki kontrolü yapıyoruz
		if($_auth['user_'.$action] <> '1') $hata_edit = '1';

		//yetkilendirme hatası yoksa
		if($hata_edit <> '1')
		{
			try
			{
				//kullanıcı eklenmek isteniyor ise
				//önce bir tane boş kullanıcı ekliyoruz
				//düzenleme modunda kullanıcıyı açıyoruz
				$_user->user_edit($_id);
				$message = $messages['info_edit'];
			}
			catch(Exception $e)
			{
				//ekleme işlemi sırasında bir hata oluştu
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
		$list 				= $_user->user_list($_id);
		$user_name 			= $list[0]['user_name'];
		$header_subtitle	= 'Düzenle &rarr; '.$user_name;
	}

	if($do == 'add')
	{
		$header_subtitle	= 'Yeni Kullanıcı Ekle';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Kullanıcı Listesi';
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
			<li><a href="<?=LINK_ACP?>&amp;view=user&amp;do=list"><i class="ion ion-android-person"></i> <?=$page_name?></a></li>
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
						include ACP_MODULE_PATH.$modul_name[$view].'user.add.php';
						include ACP_MODULE_PATH.$modul_name[$view].'user.form.php';
					break;

					case 'edit':
						include ACP_MODULE_PATH.$modul_name[$view].'user.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'user.form.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'user.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
