<?php
	if(!defined('APP')) die('...');

	//truncate işlemi istenmiş ise
	if($action == 'truncate')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'list';

		//yetki denetimi
		if($_auth['comment_'.$action] <> '1') $hata_truncate = '1';
		if($hata_truncate <> '1')
		{
			try
			{
				$_comment->comment_truncate();
				$message = $messages['info_truncate_passive_comment'];
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
		$do			= 'edit';
		$hata_edit	= '';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		//
		//kullanıcı düzenleme yetkisine sahip mi diye bakıyoruz
		//ve düzenlemek istediği yetkiyi ayışıp aşmadığına bakıyoruz
		if($_auth['comment_'.$action] <> '1') $hata_edit = '1';

		if($hata_edit <> '1')
		{
			if($_REQUEST['save_action'] == 'publish')	$_REQUEST['comment_status'] = 1;
			if($_REQUEST['save_action'] == 'draft')		$_REQUEST['comment_status'] = 2;
			if($_REQUEST['save_action'] == 'delete')	$_REQUEST['comment_status'] = 4;
			if($_REQUEST['save_action'] == 'spam')		$_REQUEST['comment_status'] = 3;

			if($_REQUEST['save_action'] == 'spam')
			{
				//spam olarak bildirmeye çalışalım
				$_comment->set_comment_spam($_id);
			}

			try
			{
				//kullanıcı eklenmek isteniyor ise
				//önce bir tane boş kullanıcı ekliyoruz
				//sonra, dönen id değerine göre düzenleme modunda kullanıcıyı açıyoruz
				$_comment->comment_edit($_id);
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
			$do = 'list';
		}
	}

	if($do == 'edit')
	{
		$list 				= $_comment->comment_list($_id);
		$header_subtitle	= 'Düzenle &rarr; Yorum Id : '.$_id;
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Yorum Listesi';
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
			<li><a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list"><i class="ion ion-android-hangout"></i> <?=$page_name?></a></li>
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
						include ACP_MODULE_PATH.$modul_name[$view].'comment.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'comment.form.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'comment.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
