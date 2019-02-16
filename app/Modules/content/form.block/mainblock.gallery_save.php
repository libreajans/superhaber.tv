<?php if(!defined('APP')) die('...');

	//önce resimlerin sıralamasını ve içeriklerini kaydedelim
	$_gallery->order_gallery_photo();

	//sonrasında silinmesi istenmiş resim varsa onları silelim
	$_gallery->delete_selected_images($_id, $gallery_type, $content_image_dir);

	//galeriye yeni resimler eklenmek isteniyorsa
	try
	{
		$adet = count($_FILES['filesToUpload']['name']);
		if($adet)
		{
			$gallery_id = $_id;

			for($i = 0; $i <= $adet; $i++)
			{
				if($_FILES['filesToUpload']['size'][$i])
				{
					if(!in_array($_FILES['filesToUpload']['type'][$i], $allowed_image_types))
					{
						$uyarilar.='Galeri resmi için yüklemek istediğiniz dosya tipi uygun değil: '.$_FILES['filesToUpload']['type'][$i].'<br/>';
					}
					else
					{
						$posNokta = strrpos(basename($_FILES['filesToUpload']['name'][$i]), '.');
						$fileName = substr(basename($_FILES['filesToUpload']['name'][$i]), 0, $posNokta);
						$fileExt = strtolower(substr(basename($_FILES['filesToUpload']['name'][$i]), $posNokta+1));
						$dosya_adi = 'gallery_'.$gallery_id.'_'.gen_key(15).'.'.$fileExt;

						$new_file_path	= IMAGE_DIRECTORY.'gallery/'.$content_image_dir.$dosya_adi;
						$new_file_loc	= IMAGE_DIRECTORY.'gallery/'.$content_image_dir;

						//gif değilse resmi boyutlandırıp kaydediyoruz
						if($fileExt <> 'gif')
						{
							$_image->load($_FILES['filesToUpload']['tmp_name'][$i]);
							if($_image->getWidth() > 630) $_image->resizeToWidth(630);
							$_image->save(
								$new_file_path,
								$new_file_loc
							);
						}
						else
						{
							$_image->save_new(
								$_FILES['filesToUpload']['tmp_name'][$i],
								$new_file_path,
								$new_file_loc
							);
						}

						//her ihtimale karşı nesneyi boşaltalım
						unset($image_sizes, $photo_width, $photo_height);

						$image_sizes	= getimagesize($new_file_path);
						$photo_width	= $image_sizes[0];
						$photo_height	= $image_sizes[1];

						try
						{
							$_gallery->record_gallery_photo($gallery_id, $dosya_adi, $photo_width, $photo_height);
							$message = $messages["info_gallery"];
						}
						catch(Exception $e)
						{
							$message = $messages["error_gallery"];
						}
					}

				}
			}
		}
	}
	catch(Exception $e)
	{
		$message['type'] = 'error';
		$message['text'] = 'Upload Hatası - '.$e->getMessage();
		$do = 'duzenle';
	}
