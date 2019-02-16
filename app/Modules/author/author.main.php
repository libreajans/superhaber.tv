<?php
	if(!defined('APP')) die('...');

	//delete işlemi istenmiş ise
	if($action == 'delete')
	{
		//do ile hangi duruma geri döneceğimizi belirliyoruz
		$do = 'list';

		//yetki denetimlerini kontrol ediyoruz
		//kişi yazar silme yetkisine sahip değilse yazar silemez
		if($_auth['author_'.$action] <> '1') $hata_delete = '1';

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_delete <> '1')
		{
			try
			{
				$_author->author_delete_soft($_id);
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
		//yazar düzenleme yetkisine sahip mi diye bakıyoruz
		//ve düzenlemek istediği yetkiyi ayışıp aşmadığına bakıyoruz
		if($_auth['author_'.$action] <> '1') $hata_edit = '1';

		//yetki denetimini geçtik, içeriğin yayın durumunu kestiriyoruz
		if($_REQUEST['save_action'] == 'publish')	$_REQUEST['author_status'] = 1;
		if($_REQUEST['save_action'] == 'draft')		$_REQUEST['author_status'] = 2;

		//herhangi bir yetkilendirme hatası almamış isek
		if($hata_edit <> '1')
		{
			try
			{

				//yeni bir resim yüklenmesi istenmişse
				//resmi kontrol ediyoruz
				//uygun ise yeniden isimlendiriyoruz
				//sonra eski resmi siliyoruz
				if($_FILES['author_image']['size'])
				{
					if(!in_array($_FILES['author_image']['type'], $allowed_image_types))
					{
						$uyarilar.= 'Yazar resim için yüklemek istediğiniz dosya tipi uygun değil: '.$_FILES['author_image']['type'].'<br/>';
					}
					else
					{
						$posNokta = strrpos(basename($_FILES['author_image']['name']), '.');
						$fileName = substr(basename($_FILES['author_image']['name']), 0, $posNokta);
						$fileExt = strtolower(substr(basename($_FILES['author_image']['name']), $posNokta+1));

						$dosyaAdi = 'author_'.format_url($_REQUEST['author_name']).'_'.gen_key(15).'.'.$fileExt;

						//resmin orjinal halini kayıt altına alalım
						$_image->save_new(
							$_FILES['author_image']['tmp_name'],
							IMAGE_DIRECTORY.'author/'.$dosyaAdi,
							IMAGE_DIRECTORY.'author/'
						);

						//dosya ismimiz belli oldu
						$_REQUEST['author_image'] = $dosyaAdi;
					}
				}
				else
				{
					//eski resim olduğu gibi kullanılması istenmiş ise
					$_REQUEST['author_image'] = $_REQUEST['org_author_image'];
				}

				//yazar eklenmek isteniyor ise
				//önce bir tane boş yazar ekliyoruz
				//sonra, dönen id değerine göre düzenleme modunda yazaryı açıyoruz
				$_author->author_edit($_id);

				$message			= $messages['info_edit'];
				if($uyarilar <> '') $uyarilar_text = showMessageBoxS($uyarilar, 'error');
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
		$list				= $_author->author_list($_id, $json = 0);
		$author_name		= $list[0]['author_name'];
		$author_url 		= url_author($author_name);
		$header_subtitle	= 'Düzenle &rarr; <a href="'.$author_url.'">'.$author_name.'</a>';
	}

	if($do == 'add')
	{
		$header_subtitle	= 'Yazar Ekle';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'Yazar Listesi';
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
			<li><a href="<?=LINK_ACP?>&amp;view=author&amp;do=list"><i class="ion ion-document"></i> <?=$page_name?></a></li>
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
						include ACP_MODULE_PATH.$modul_name[$view].'author.edit.php';
						include ACP_MODULE_PATH.$modul_name[$view].'author.form.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'author.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
