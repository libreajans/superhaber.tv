<?php
	if(!defined('APP')) die('...');

	class user
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function get_avatar_list($user_avatar)
		{
			$dir	= 'assets/img/Avatar/';
			$files	= array_diff(scandir($dir), array('..', '.'));
			asort($files);

			foreach($files as $k => $v)
			{
				$selected = ''; if($v <> '' && $v == $user_avatar) $selected = 'checked="checked"';
				$liste.='
					<div style="width:100px; float:left; margin-right:10px; ">
						<label for="user_avatar_'.$v.'"><img width="100" height="100" src="'.$dir.$v.'"/></label>
						<span style="margin-left: 42px;"><input '.$selected.' id="user_avatar_'.$v.'" type="radio" name="user_avatar" value="'.$v.'"/></span>
					</div>';
			}
			return $liste;
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil, Listele
		*/
		public function user_add()
		{
			$record = array('user_name' => 'New User');
			$rs = $this->conn->AutoExecute(T_USER, $record, 'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			return $this->conn->Insert_ID();
		 }

		public function user_edit_self()
		{
			// değiştirilmek istenen eposta adresi
			// daha önce başka bir kişi tarafından kullanılmış olamaz

			$sql = 'SELECT
						count(user_id)
					FROM
						'.T_USER.'
					WHERE
						user_email = "'.$_REQUEST['user_email'].'"
					AND
						user_id <> '.$_SESSION[SES]['user_id'];
			$adet = $this->conn->GetOne($sql);
			if($adet <> 0)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			else
			{
				$record = array(
					'user_name' 		=> $_REQUEST['user_name'],
					'user_realname'		=> $_REQUEST['user_realname'],
					'user_email'		=> $_REQUEST['user_email'],
					'user_avatar'		=> $_REQUEST['user_avatar'],
					'user_editor'		=> $_REQUEST['user_editor'],
				);
				$rs = $this->conn->AutoExecute(T_USER, $record, 'UPDATE', 'user_id='.$_SESSION[SES]['user_id']);
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
				else
				{
					//oturumdaki değerlerimizi de güncelleyelim
					$_SESSION[SES]['user_name']			= $_REQUEST['user_name'];
					$_SESSION[SES]['user_realname']		= $_REQUEST['user_realname'];
					$_SESSION[SES]['user_email']		= $_REQUEST['user_email'];
					$_SESSION[SES]['user_avatar']		= $_REQUEST['user_avatar'];
					$_SESSION[SES]['user_editor']		= $_REQUEST['user_editor'];
				}

				//parola değiştirelim
				if($_REQUEST['user_pass_renew'] == 'on')
				{
					$pass = trim($_REQUEST['user_pass']);
					if($pass <> '')
					{
						$pass = mds($pass);
						$record = array('user_pass' => $pass);
						$rs = $this->conn->AutoExecute(T_USER, $record, 'UPDATE', 'user_id='.$_SESSION[SES]['user_id']);
						if($rs == false)
						{
							throw new Exception($this->conn->ErrorMsg());
						}
					}
				}
			}
		}


		public function user_edit($_id)
		{
			$record = array(
				'user_email'		=> $_REQUEST['user_email'],
				'user_name' 		=> $_REQUEST['user_name'],
				'user_realname'		=> $_REQUEST['user_realname'],
				'user_avatar'		=> $_REQUEST['user_avatar'],
				'user_status'		=> $_REQUEST['user_status'],
				'user_editor'		=> $_REQUEST['user_editor']
			);
			$rs = $this->conn->AutoExecute(T_USER, $record, 'UPDATE', 'user_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//parola değiştirelim
			if($_REQUEST['user_pass_renew'] == 'on')
			{
				$pass = trim($_REQUEST['user_pass']);
				if($pass <> '')
				{
					$pass = mds($pass);
					$record = array('user_pass' => $pass);
					$rs = $this->conn->AutoExecute(T_USER, $record, 'UPDATE', 'user_id='.$_id);
					if($rs == false)
					{
						throw new Exception($this->conn->ErrorMsg());
					}
				}
			}

			//yetkileri değiştirelim
			//lakin önce şöyle bir denetim yapalım ve
			//kullanıcı yönetici seviyesine sahip değilse
			//gönderilmiş auth değerlerini parse etmeyelim
			// ;)
			if($_REQUEST['user_status'] == 9)
			{
				foreach($_REQUEST['auth'] as $k) $auth[$k] = '1';

				$record = array('user_auth' => serialize($auth));
				$rs = $this->conn->AutoExecute(T_USER, $record, 'UPDATE', 'user_id='.$_id);
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}

		}

		public function user_delete($_id)
		{
			//kurucu üye silinemez
 			$sql = 'DELETE FROM
						'.T_USER.'
					WHERE
						user_id <> 1
					AND
						user_id = '.$_id;
			if($this->conn->Execute($sql) == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function user_delete_soft($_id)
		{
            //kurucu üye silinemez
            if($_id == 1) return false;

			//kullanıcıya ait tüm iletileri root = 1 kullanıcısına atayalım
			//çünkü root kullanıcısı silinemez!
			$record = array('content_user'	=> 1);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_user='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//kullanıcının onayladığı tüm yorumları root = 1 kullanıcısına atayalım
			//çünkü root kullanıcısı silinemez!
			$record = array('comment_aprover'	=> 1);
			$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'UPDATE', 'comment_aprover='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//sonrasında kullanıcının durumunu pasif yapalım
			$record = array('user_status'	=> 0);
			$rs = $this->conn->AutoExecute(T_USER, $record, 'UPDATE', 'user_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//en son olarak kullanıcıyı silelim
			$this->user_delete($_id);
		}

		public function user_list($_id = 0)
		{
			$sql = 'SELECT
						*,
						date_format(create_time, "%d.%m.%Y") AS create_time_f
					FROM
						'.T_USER;
			if($_id <> 0)
			{
				$sql = $sql.' WHERE user_id = '.$_id;
			}

			return $this->conn->GetAll($sql);
		}

		public function user_shortlist()
		{
			$sql = 'SELECT
						user_id,
						user_name,
						user_realname
					FROM
						'.T_USER.'
					ORDER BY
						user_name ASC';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$dizi[$v['user_id']] = $v['user_realname'];
			}
			return $dizi;
		}

		/**
		| Log İşlemleri; Ekle, Listele
		| epostadan user id getirmek ise
		| loglama sırasında işimize yarıyor
		| o sebeple class dışında kullanılmayan
		| private bir fonksiyon olarak atıyoruz
		*/

		private function get_user_id_from_email($email)
		{
			$sql = 'SELECT
						user_id
					FROM
						'.T_USER.'
					WHERE
						user_email = "'.$email.'"';
			$rs = $this->conn->GetOne($sql);
			return $rs;
		}

		public function user_log_add($action)
		{
			$id = $_SESSION[SES]['user_id'];
			if($id == 0)
			{
				//user_id 0 ise, kullanılan eposta adresinden kullanıcı id değerini bulalım
				//sonra kayıt girişi denemesini, ilgili kullanıcıya atayalım
				//böylece anonim loglar yerine
				//kişinin denemelerinde görünsün
				$ids = $this->get_user_id_from_email(myReq("email",1));
				if($ids > 0) $id = $ids;
			}
			$record = array(
				'user_id' 		=> $id,
				'user_ip'		=> $_SESSION[SES]['ip'],
				'user_action'	=> $action,
			);
			$rs = $this->conn->AutoExecute(T_USER_LOG,$record,'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function user_log_truncate()
		{
			$sql = 'TRUNCATE TABLE '.T_USER_LOG;
			$rs = $this->conn->Execute($sql);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			else
			{
				return true;
			}
		}

		public function get_user_log($_id, $type)
		{
			if($type == 'self')
			{
				$sql = 'SELECT
							user_ip,
							user_action,
							date_format(create_time, "%d.%m.%Y, %h.%i") AS create_time_f
						FROM
							'.T_USER_LOG.'
						WHERE
							user_id = '.$_id.'
						ORDER BY
							create_time DESC
						LIMIT 0,10';
			}

			if($type == 'others')
			{
				$sql = 'SELECT
							user_ip,
							user_action,
							date_format(create_time, "%d.%m.%Y, %h.%i") AS create_time_f
						FROM
							'.T_USER_LOG.'
						WHERE
							user_id > 0
						AND
							user_id <> '.$_id.'
						ORDER BY
							create_time DESC
						LIMIT 0,10';
			}

			if($type == 'anonim')
			{
				$sql = 'SELECT
							user_ip,
							user_action,
							date_format(create_time, "%d.%m.%Y, %h.%i") AS create_time_f
						FROM
							'.T_USER_LOG.'
						WHERE
							user_id = 0
						ORDER BY
							create_time DESC
						LIMIT 0,10';
			}

			$list = $this->conn->GetAll($sql);

			$adet = count($list);

			for($i = 0; $i < $adet; $i++)
			{
				$ip		= $list[$i]['user_ip'];
				$log	= $list[$i]['user_action'];
				$time	= $list[$i]['create_time_f'];

				($i%2 ? $trcolor = 'even' : $trcolor = 'odd');

				$content.='
					<tr class="'.$trcolor.'">
						<td>'.$time.'</td>
						<td>'.$log.'</td>
						<td>'.$ip.'</td>
					</tr>
				';
			}
			return $content;
		}

		/**
		| Kullanıcı izinlerini getir
		*/
		public function get_user_auth($_id)
		{
			$sql = 'SELECT user_auth FROM '.T_USER.' WHERE user_id = '.$_id;
			return unserialize($this->conn->GetOne($sql));
		}

		/**
		| iki aşamalı olarak,
		| yönetici olup olmadıklarını
		| ve giriş yapıp yapmadıklarını kontrol ediyoruz
		| admin_login o sebeple private bir fonksiyon
		*/

		private function admin_login()
		{
			//user/pass boş olamaz
			if($_REQUEST['pass'] == '' || $_REQUEST['pass'] == '')
			{
				header('Location: '.LINK_GIRIS.'&hata=1');
				$this->user_log_add('Giriş Denemesi: Parola Boş Olamaz');
				exit();
			}

			if(RC_Captcha == 1)
			{
				if(!$_REQUEST['g-recaptcha-response'])
				{
					header('Location: '.LINK_GIRIS.'&hata=2');
					$this->user_log_add('Giriş Denemesi: Güvenlik Sorusu Boş {'.$_REQUEST['email'].'}');
					exit();
				}

				if($_REQUEST['g-recaptcha-response'])
				{
					$recaptcha = new \ReCaptcha\ReCaptcha(capthaPrivateKey, new \ReCaptcha\RequestMethod\SocketPost());
					$resp = $recaptcha->verify($_POST['g-recaptcha-response'], get_real_ip());
					if ($resp->isSuccess())
					{
						//giriş başarılı
					}
					else
					{
						//foreach ($resp->getErrorCodes() as $code) echo '<tt>' , $code , '</tt> ';
						header('Location: '.LINK_GIRIS.'&hata=3');
						$this->user_log_add('Giriş Denemesi: Güvenlik Sorusu Hatalı {'.$_REQUEST['email'].'}');
						exit();
					}
				}
			}

			$sql = 'SELECT
						user_id,
						user_name,
						user_realname,
						user_email,
						user_avatar,
						user_status,
						user_editor
					FROM
						'.T_USER.'
					WHERE
						user_email = ?
					AND
						user_pass = ?
					';
			$rs = $this->conn->GetAll($sql, array($_REQUEST['email'], mds(trim($_REQUEST['pass']))));
			//uygulamadaki tek binding örneği

			$adet = count($rs);
			if($adet <> 1)
			{
				$this->user_log_add('Giriş Denemesi: E-posta Adresi veya Parola Hatası {'.$_REQUEST['email'].'}');
				header('Location: '.LINK_GIRIS.'&hata=0');
				exit();
				return false;
			}
			else
			{
				/**
				* Kullanicinin durumu 0 ise sisteme giris izni yok
				*/

				$user_status = $rs[0]['user_status'];

				if($user_status == 0)
				{
					$this->user_log_add('Giriş Denemesi: Pasif Kullanıcı {'.$_REQUEST['email'].'}');
					header('Location: '.LINK_GIRIS.'&hata=4');
					exit();
					return false;
				}

				if($user_status == 1)
				{
					$this->user_log_add('Giriş Denemesi: Aktif Kullanıcı{'.$_REQUEST['email'].'}');
					header('Location: '.SITELINK.'?login=true');
					exit();
					return false;
				}

				if($user_status == 9)
				{
					//yetki
					$_SESSION[SES]['ADMIN']				= 1;
					$_SESSION[SES]['login']				= 1;
					//kullanıcı bilgileri
					$_SESSION[SES]['user_id']			= $rs[0]['user_id'];
					$_SESSION[SES]['user_name']			= $rs[0]['user_name'];
					$_SESSION[SES]['user_realname']		= $rs[0]['user_realname'];
					$_SESSION[SES]['user_email']		= $rs[0]['user_email'];
					$_SESSION[SES]['user_avatar']		= $rs[0]['user_avatar'];
					$_SESSION[SES]['user_status']		= $rs[0]['user_status'];
					$_SESSION[SES]['user_editor']		= $rs[0]['user_editor'];

					$this->user_log_add('Başarılı Giriş: {'.$_REQUEST['email'].'}');
					return true;
				}
			}
		}

		public function check_admin()
		{
			/**
			| Kullanici oturumu kontrol fonksiyonu.
			*/

			if($_SESSION[SES]['ADMIN'] == 1)
			{
				return true;
			}
			else
			{
				if(isset($_REQUEST['email']))
				{
					if(!$this->admin_login())
					{
						header('Location:'.LINK_GIRIS);
						exit();
					}
				}
				else
				{
					header('Location:'.LINK_GIRIS);
					exit();
				}
			}
		}
	}
