<?php
	if(!defined('APP')) die('...');

	//delete işlemi istenmiş ise
	if($action == 'delete')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'list';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		//
		//sayfa silme yetkisine sahip mi diye bakıyoruz
		if($_auth['page_'.$action] <> '1') $hata_delete = '1';

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_delete <> '1')
		{
			try
			{
				$_page->page_delete_soft($_id);
				//$_page->page_delete($_id);
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
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'edit';

		//sonrasında yetki denetimini gerçekleştiriyoruz
		//
		//sayfa düzenleme yetkisine sahip mi diye bakıyoruz
		//ve düzenlemek istediği yetkiyi ayışıp aşmadığına bakıyoruz
		if($_auth['page_'.$action] <> '1') $hata_edit = '1';

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_edit <> '1')
		{
			try
			{
				//sayfa eklenmek isteniyor ise
				//önce bir tane boş sayfa ekliyoruz
				//sonra, dönen id değerine göre düzenleme modunda sayfayı açıyoruz
				$_page->page_edit($_id);
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
		$list				= $_page->page_list($_id);
		$page_title			= $list[0]['page_title'];
		$page_url 			= $array_page_url[$_id];
		$header_subtitle	= 'Düzenle &rarr; <a href="'.$page_url.'">'.$page_title.'</a>';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Sayfa Listesi';
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
			<li><a href="<?=LINK_ACP?>&amp;view=page&amp;do=list"><i class="ion ion-document"></i> <?=$page_name?></a></li>
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
						include ACP_MODULE_PATH.$modul_name[$view].'page.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'page.form.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'page.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
