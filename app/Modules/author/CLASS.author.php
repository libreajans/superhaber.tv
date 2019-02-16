<?php

	class author
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil, Listele
		*/

		private function clean_cache()
		{
			/**
			| Eski tarihli pasif içerikleri otomatik silelim
			*/
			$time = date('Y-m-d 00:00:00',strtotime("-2 day"));
			$sql = 'DELETE
					FROM
						'.T_AUTHOR.'
					WHERE
						author_status = 0
					AND
						create_time < "'.$time.'"';
			$rs = $this->conn->Execute($sql);
			return true;
		}

		public function author_add()
		{

			//önce eski dataları silelim
			self::clean_cache();

			$record = array('author_status' => 0);
			$rs = $this->conn->AutoExecute(T_AUTHOR, $record, 'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			return $this->conn->Insert_ID();
		}

		public function author_edit($_id)
		{
			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim($v);

 			if($_REQUEST['author_show_index'] == 'on') 		$_REQUEST['author_show_index'] = 0;
 			if($_REQUEST['author_show_index'] <> 'on') 		$_REQUEST['author_show_index'] = 1;

			if($_REQUEST['author_show_page'] == 'on') 		$_REQUEST['author_show_page'] = 0;
			if($_REQUEST['author_show_page'] <> 'on') 		$_REQUEST['author_show_page'] = 1;

			$record = array(
				'author_name'		=> $_REQUEST['author_name'],
				'author_keyword'	=> format_url($_REQUEST['author_name']),
				'author_image' 		=> $_REQUEST['author_image'],
				'author_twitter'	=> $_REQUEST['author_twitter'],
				'author_facebook'	=> $_REQUEST['author_facebook'],
				'author_instagram'	=> $_REQUEST['author_instagram'],
				'author_email'		=> $_REQUEST['author_email'],
// 				'author_text' 		=> $_REQUEST['author_text'],
// 				'author_contact' 	=> $_REQUEST['author_contact'],
// 				'author_notes' 		=> $_REQUEST['author_notes'],
 				'author_show_index' => intval($_REQUEST['author_show_index']),
 				'author_show_page' 	=> intval($_REQUEST['author_show_page']),
				'author_status'		=> intval($_REQUEST['author_status']),
				'author_order'		=> intval($_REQUEST['author_order'])
			);
			$rs = $this->conn->AutoExecute(T_AUTHOR, $record, 'UPDATE', 'author_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function author_delete($_id)
		{
			$sql = 'DELETE FROM '.T_AUTHOR.' WHERE author_id = '.$_id;
			if($this->conn->Execute($sql) == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function author_delete_soft($_id)
		{

			$record = array('author_status'	=> 3);
			$rs = $this->conn->AutoExecute(T_AUTHOR, $record, 'UPDATE', 'author_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			//self::author_delete($_id);
		}

		public function author_list($_id = 0, $json = 0)
		{
			$sql = 'SELECT
						*,
						date_format(create_time, "%d.%m.%Y") AS create_time_f
					FROM
						'.T_AUTHOR.'
					WHERE
						author_status <> 0
						';
			if($_id <> 0)
			{
				$sql = $sql.' AND author_id = '.$_id;
			}
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['author_url']				= url_author($list[$i]['author_name']);
				$list[$i]['author_image_url']		= G_IMGLINK.'author/'.$list[$i]['author_image'];
			}
			if($json == 0) return $list;

			//uygulama arayüzünde kullanıyoruz
			if($json == 1)
			{
				for($i = 0; $i < $adet; $i++)
				{
					unset
					(
						$list[$i]['author_image'],
						$list[$i]['author_text'],
						$list[$i]['author_contact'],
						$list[$i]['author_notes'],
						$list[$i]['author_status'],
						$list[$i]['author_show_page'],
						$list[$i]['author_show_index'],
						$list[$i]['author_order'],
						$list[$i]['author_url'],
						$list[$i]['create_time'],
						$list[$i]['create_time_f'],
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function authors_list_detailed($type = 'none', $json = 0)
		{
			global $_article;
			/**
			* Yazarların listesini çeker
			* Sonra bu bilgiyi yazarların son yazıları ile birleştirir
			*/

			if($type == 'index')	$sql_type = 'AND author_show_index = 1 ORDER BY author_order ASC';
			if($type == 'page')		$sql_type = 'AND author_show_page = 1 ORDER BY author_name ASC';

			$sql = 'SELECT
						*
					FROM
						'.T_AUTHOR.'
					WHERE
						author_status = 1
						'.$sql_type.';';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime*5, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				//yazarın son yazısını çekiyoruz
				$author_info = $_article->author_articles($list[$i]['author_id'], 1);

				//yazarın bilgileri ile birleştiriyoruz
				$list[$i]['author_image_url']		= G_IMGLINK.'author/'.$list[$i]['author_image'];
				$list[$i]['author_url']				= url_author($list[$i]['author_name']);
				$list[$i]['content_id']				= $author_info[0]['content_id'];
				$list[$i]['content_url']			= $author_info[0]['content_url'];
				$list[$i]['content_title']			= $author_info[0]['content_title'];
			}
			if($json == 0) return $list;

			//uygulama arayüzünde kullanıyoruz
			if($json == 1)
			{
				for($i = 0; $i < $adet; $i++)
				{
					unset
					(
						$list[$i]['author_status'],
						$list[$i]['author_keyword'],
						$list[$i]['author_image'],
						$list[$i]['author_keyword'],
						$list[$i]['author_contact'],
						$list[$i]['author_notes'],
						$list[$i]['author_show_index'],
						$list[$i]['author_show_page'],
						$list[$i]['author_order'],
						$list[$i]['author_text'],
						$list[$i]['author_url'],
						$list[$i]['create_time'],
						$list[$i]['content_url'],
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function author_page($_key = "", $_id = 0)
		{
			if($_key <> "") $sql_key = 'AND author_keyword = "'.$_key.'"';
			if($_id <> 0)	$sql_key = 'AND author_id = '.$_id;

			$sql = 'SELECT
						*,
						(
							SELECT
								count(content_id)
							FROM
								'.T_CONTENT.'
							WHERE
								content_template = 3
							AND
								content_author = author_id
						) as author_content_count
					FROM
						'.T_AUTHOR.'
					WHERE
						author_status = 1
						'.$sql_key.'
					';
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['author_url']			= url_author($list[$i]['author_name']);
					$list[$i]['author_image_url']	= G_IMGLINK.'author/'.$list[$i]['author_image'];
				}
				return $list[0];
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}

		public function author_list_array()
		{
			$sql = 'SELECT
						author_id,
						author_name
					FROM
						'.T_AUTHOR.'
					WHERE
						author_status = 1
					ORDER BY
						author_name ASC';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			foreach($list as $k => $v)
			{
				$dizi[$v['author_id']] = $v['author_name'];
			}
			return $dizi;
		}
	}
