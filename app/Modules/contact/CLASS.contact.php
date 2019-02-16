<?php

	class contact
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function contact_add($metin)
		{

			$error = 0;
			//çeşitli alanlar boş ise hatalı diyelim
			if($_REQUEST['contact_name'] == '' OR $_REQUEST['contact_message'] == '' OR $_REQUEST['contact_email'] == '' )
			{
				$error = 1;
			}

			//daha önceden mesaj göndermiş ise hatalı diyelim
			if($_SESSION[SES]['contact'] == 1)
			{
				$error = 1;
			}

			//hata almışsak, hiç kayıt etmeyelim
			if($error == 0)
			{

				if(RC_Akismet == 1)
				{
					$akismet = new Akismet(SITELINK, akismetKey);
					$akismet->setCommentAuthor($_REQUEST['contact_name']);
					$akismet->setCommentAuthorEmail($_REQUEST['contact_email']);
					$akismet->setCommentContent($_REQUEST['contact_message']);
					$akismet->setUserIP($_SESSION[SES]['ip']);
					$akismet->setPermalink(SITELINK);

					if($akismet->isCommentSpam())
					{
						//store the comment but mark it as spam (in case of a mis-diagnosis)
						$contact_status = 3;
					}
					else
					{	//store the comment normally
						$contact_status = 2;
					}
				}
				else
				{
					$contact_status = 2;
				}

				$record = array(
					'contact_email'		=> strtolower($_REQUEST['contact_email']),
					'contact_text'		=> $metin,
					'contact_status'	=> $contact_status,
					'contact_ip'		=> $_SESSION[SES]['ip'],
				);
				//print_pre($record);
				$rs = $this->conn->AutoExecute(T_CONTACT, $record, 'INSERT');
				if($rs === false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}

				//oturuma bu kişi mesaj göndermiş diye kayıt atalım
				$_SESSION[SES]['contact'] = 1;

				return true;
			}
		}

		public function contact_edit($_id)
		{
			$record = array(
				'contact_status'	=> $_REQUEST['contact_status'],
				'contact_aprover'	=> $_SESSION[SES]['user_id']
			);
			$rs = $this->conn->AutoExecute(T_CONTACT, $record, 'UPDATE', 'contact_id='.$_id);
			if($rs === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function contact_delete($_id)
		{
			$sql = 'DELETE FROM '.T_CONTACT.' WHERE contact_id= '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function contact_list($_id = 0)
		{
			$sql = 'SELECT
						*,
						DATE_FORMAT(create_time, "%d.%m.%Y %H:%i") AS create_time_f
					FROM
						'.T_CONTACT;
			if($_id <> 0)
			{
				$sql = $sql.' WHERE contact_id = '.$_id;
			}
			return $this->conn->GetAll($sql);
		}

		public function contact_list_small($user = 'none', $status = 'none', $limit = 30)
		{
			if($user <> 'none')
			{
				$sql_user = ' AND contact_aprover = '.$user;
			}

			if($status <> 'none')
			{
				$sql_status = ' AND contact_status IN ('.$status.')';
			}

			if($limit > 0) 	$sql_limit	= 'LIMIT 0,'.$limit;

			$sql = 'SELECT
						*,
						DATE_FORMAT(create_time, "%d.%m.%Y %H:%i") AS create_time_f
					FROM
						'.T_CONTACT.'
					WHERE
						contact_id > 0
						'.$sql_user.'
						'.$sql_status.'
					ORDER BY
						contact_id DESC
						'.$sql_limit;
			return $this->conn->GetAll($sql);
		}

		public function contact_truncate()
		{
			$sql = 'SELECT
						contact_id
					FROM
						'.T_CONTACT.'
					WHERE
						contact_status IN (0,3,4)';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->contact_delete($v['contact_id']);
			}
			return true;
		}

		public function get_contact_draft()
		{
			$sql = 'SELECT
						count(contact_id)
					FROM
						'.T_CONTACT.'
					WHERE
						contact_status = 2';
			return $this->conn->GetOne($sql);
		}
	}
