<?php
	class gallery
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function delete_selected_images($gallery_id, $content_image_dir)
		{
			foreach($_REQUEST['imageList'] as $photo_id => $v)
			{
				$gallery_id = intval($gallery_id);
				$this->fizikiTekDosyaSil($photo_id, $gallery_id, $content_image_dir);
			}
		}

		public function order_gallery_photo()
		{
			$v = time();
			foreach($_REQUEST['photo_order'] as $k => $c)
			{
				$k = intval($k);
				$v = $v-1;

				$record = array(
					'photo_order'		=> $v,
					'photo_text'		=> $_REQUEST['photo_text'][$k]
				);
				$rs = $this->conn->AutoExecute(T_GALLERY_IMAGES, $record, 'UPDATE', 'id='.$k);
				if($rs === false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}
		}

		public function record_gallery_photo($gallery_id, $photo_image, $photo_width, $photo_height)
		{
			$record = array(
				'gallery_id'	=> $gallery_id,
				'photo_image'	=> $photo_image,
				'photo_width'	=> $photo_width,
				'photo_height'	=> $photo_height,
			);

			$rs = $this->conn->AutoExecute(T_GALLERY_IMAGES,$record,'INSERT');
			if($rs === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function get_gallery_images_list($_id, $content_image_dir, $json = 0)
		{
			$sql = 'SELECT
						id,
						photo_width,
						photo_height,
						photo_order,
						photo_image,
						photo_text
					FROM
						'.T_GALLERY_IMAGES.'
					WHERE
						gallery_id = '.$_id.'
					ORDER BY
						photo_order DESC,
						id ASC';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['photo_image_url'] = G_IMGLINK.'gallery/'.$content_image_dir.$list[$i]['photo_image'];
			}
			if($json == 0)
			{
				return $list;
			}

			if($json == 1)
			{
				for($i = 0; $i < $adet; $i++)
				{
					unset(
						$list[$i]['photo_order'],
						$list[$i]['photo_image']
					);
				}
				return $list;
			}
		}

		public function fizikiTekDosyaSil($photo_id, $gallery_id, $content_image_dir)
		{
			$sql = 'SELECT
						id,
						photo_image
					FROM
						'.T_GALLERY_IMAGES.'
					WHERE
						gallery_id = '.$gallery_id.'
					AND
						id = '.$photo_id;
			$rs = $this->conn->GetRow($sql);

			if($rs)
			{
				$dosya_adi = $rs['photo_image'];

				if($dosya_adi <> '')
				{
					@unlink(IMAGE_DIRECTORY.'gallery/'.$content_image_dir.$dosya_adi);
				}

				$sql = 'DELETE FROM '.T_GALLERY_IMAGES.' WHERE id='.$photo_id;
				$this->conn->Execute($sql);
			}
		}
	}
