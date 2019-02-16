<?php

	class page
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil, Listele
		*/

		public function page_edit($_id)
		{
			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim($v);

			$record = array(
				'page_title'	=> $_REQUEST['page_title'],
				'page_text' 	=> $_REQUEST['page_text'],
				'page_status'	=> $_REQUEST['page_status']
			);
			$rs = $this->conn->AutoExecute(T_PAGE, $record, 'UPDATE', 'page_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function page_delete($_id)
		{
			$sql = 'DELETE FROM '.T_PAGE.' WHERE page_id = '.$_id;
			if($this->conn->Execute($sql) == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function page_delete_soft($_id)
		{

			$record = array('page_status'	=> 3);
			$rs = $this->conn->AutoExecute(T_PAGE, $record, 'UPDATE', 'page_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function page_list($_id = 0, $json = 0)
		{
			$sql = 'SELECT
						*
					FROM
						'.T_PAGE.'
					WHERE
						page_id > 0 ';
			if($_id <> 0)
			{
				$sql = $sql.'AND page_id = '.$_id;
			}
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			if($json == 0) return $list;

			if($json == 1)
			{
				$adet = count($list);
				/**
				| Json datayı uygulamaya gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_title'] = $list[$i]['page_title'];
					$list[$i]['content_text'] = $list[$i]['page_text'];
					unset
					(
						$list[$i]['page_id'],
						$list[$i]['page_title'],
						$list[$i]['page_text'],
						$list[$i]['create_time'],
						$list[$i]['page_status']
					);
				}
				return $list;
			}
		}
	}
