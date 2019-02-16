<?php if(!defined('APP')) die('...');

	try
	{
		//varolan imajlar kaldırılmak istenmişse
		//işlemin bu kısmı gerçekleşiyor
		//lakin bu işlem için ilave bir yetkilendirme denetimi yapılmıyor
		//düzenleme yetkisinin varolması yeterli bulunuyor
		if($_REQUEST['delete_content_image'] == 'on')
		{
			$_content->content_delete_content_image($_id, $content_image_dir);
			$_REQUEST['org_content_image'] = '';
		}

		if($_REQUEST['delete_content_image_manset'] == 'on')
		{
			$_content->content_delete_content_image_manset($_id, $content_image_dir);
			$_REQUEST['org_content_image_manset'] = '';
		}

		if($_FILES['content_image']['size'])
		{
			if(!in_array($_FILES['content_image']['type'], $allowed_image_types))
			{
				$uyarilar.='Haber resmi için yüklemek istediğiniz dosya tipi uygun değil: '.$_FILES['content_image']['type'].'<br/>';
			}
			else
			{
				$posNokta = strrpos(basename($_FILES['content_image']['name']), '.');
				$fileName = substr(basename($_FILES['content_image']['name']), 0, $posNokta);
				$fileExt = strtolower(substr(basename($_FILES['content_image']['name']), $posNokta+1));

				$dosyaAdi = 'content_'.format_url($_REQUEST['content_title']).'_'.gen_key(15).'.'.$fileExt;
				$_image->load($_FILES['content_image']['tmp_name']);

				//resmin orjinal halini kayıt altına alalım
				$_image->save_new(
					$_FILES['content_image']['tmp_name'],
					IMAGE_DIRECTORY.'content/'.$content_image_dir.$dosyaAdi,
					IMAGE_DIRECTORY.'content/'.$content_image_dir
				);

				//resmin thumbs versiyonunu oluşturuyoruz
				if($_image->getWidth() > 350) $_image->resizeToWidth(350);
				$_image->save(
					IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$dosyaAdi,
					IMAGE_DIRECTORY.'thumbs/'.$content_image_dir
				);

				//sonrasındaki eski imajı silelim
				$_content->content_delete_content_image($_id, $content_image_dir);

				//dosya ismimiz belli oldu
				$_REQUEST['content_image'] = $dosyaAdi;
			}
		}
		else
		{
			//eski resim olduğu gibi kullanılması istenmiş ise
			$_REQUEST['content_image'] = $_REQUEST['org_content_image'];
		}

		//aynı işlemi manşet resmi için de yapıyoruz
		if($_FILES['content_image_manset']['size'])
		{
			if(!in_array($_FILES['content_image_manset']['type'], $allowed_image_types))
			{
				$uyarilar.='Haber resmi için yüklemek istediğiniz dosya tipi uygun değil: '.$_FILES['content_image_manset']['type'].'<br/>';
			}
			else
			{
				$posNokta = strrpos(basename($_FILES['content_image_manset']['name']), '.');
				$fileName = substr(basename($_FILES['content_image_manset']['name']), 0, $posNokta);
				$fileExt = strtolower(substr(basename($_FILES['content_image_manset']['name']), $posNokta+1));

				$dosyaAdi = 'manset_'.format_url($_REQUEST['content_title']).'_'.gen_key(15).'.'.$fileExt;

				//resmin orjinal halini kayıt altına alalım
				$_image->save_new(
					$_FILES['content_image_manset']['tmp_name'],
					IMAGE_DIRECTORY.'manset/'.$content_image_dir.$dosyaAdi,
					IMAGE_DIRECTORY.'manset/'.$content_image_dir
				);

				//sonrasındaki eski imajı silelim
				$_content->content_delete_content_image_manset($_id, $content_image_dir);

				//dosya ismimiz belli oldu
				$_REQUEST['content_image_manset'] = $dosyaAdi;
			}
		}
		else
		{
			//eski resim olduğu gibi kullanılması istenmiş ise
			$_REQUEST['content_image_manset'] = $_REQUEST['org_content_image_manset'];
		}

		//kullanıcı eklenmek isteniyor ise
		//önce bir tane boş kullanıcı ekliyoruz
		//sonra, dönen id değerine göre düzenleme modunda kullanıcıyı açıyoruz
		$_content->content_edit($_id);

		$message			= $messages['info_edit'];
		if($uyarilar <> '') $uyarilar_text = showMessageBoxS($uyarilar, 'error');
	}
	catch(Exception $e)
	{
		//demek ki ekleme işlemi sırasında bir hata oluştur
							$message 			= $messages['error_edit'];
		if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
	}
