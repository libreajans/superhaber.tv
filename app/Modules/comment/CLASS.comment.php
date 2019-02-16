<?php

	class comment
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil
		*/

		public function comment_save()
		{
			//yorum kayıt için gerekli olanlar
			$comment_to			= intval($_REQUEST["comment_to"]);
			$comment_to_hash	= htmlspecialchars(strip_tags($_REQUEST["comment_to_hash"]));
			$comment_to_date	= htmlspecialchars(strip_tags($_REQUEST["comment_to_date"]));

			$regular_date 		= date("Y-m-d H:i:s");

			//içerik yoksa bir şey eklemeye gerek yok
			if($comment_to == 0) return false;

			$start_time			= strtotime($regular_date);
			$end_time			= strtotime("-4 hours", $start_time);
			$comment_time 		= strtotime($comment_to_date);

			//gönderilen hash ile id ve hash uyumlu olmalı
			if( $comment_to_hash <> mds($comment_to.'/'.$comment_to_date)) return false;

			//bir yorum şimdiden daha sonraki bir tarihe olamaz
			if($comment_time > $start_time) return false;

			//bir yorum izin verilen zaman aralığını da geçmiş olamaz
			if($comment_time < $end_time) return false;

			//en son yorumu kayıt fonksiyonuna gönderelim
			//tamam orada da bir hayli işlem yapacağız
			$res = self::comment_add($comment_to);
			return $res;
		}

		public function comment_add($_id)
		{
			//id yoksa uğraşmaya gerek yok, hata dönelim
			if($_id == 0) return false;

			if($_REQUEST['isim'] == '' OR $_REQUEST['yorum'] == '' ) return false;

			//daha önceden bu başlığa yorum yazmışsa float yapamasın
			if($_SESSION[SES]['comment'][$_id] == 1) return false;

			//akismet aktif ise spam sorgulaması yapalım
			if(RC_Akismet == 1)
			{
				//önce akismet üstünden spamcı olup olmadığını sorgulayalım
				//spamcı ise içeriği kayıt altına bile almayalım
				$akismet = new Akismet(SITELINK, akismetKey);
				$akismet->setCommentAuthor($_REQUEST['isim']);
				$akismet->setCommentContent($_REQUEST['yorum']);
				$akismet->setUserIP($_SESSION[SES]['ip']);
				$akismet->setPermalink(SITELINK);

				if($akismet->isCommentSpam())
				{
					//store the comment but mark it as spam (in case of a mis-diagnosis)
					$comment_status = 3;
					//yorumu spam yakaladık, hiç uğraşmadan hata dönelim
					return false;
				}
				else
				{	//store the comment normally
					$comment_status = 2;
				}
			}
			else
			{
				//spam sorgulaması yapmayacaksak
				//onay bekleyen yorum olarak kayıt edelim
				$comment_status = 2;
			}

			//yönetici ise yorumları otomatik onaylayalım
			if($_SESSION[SES]['ADMIN'] == 1)
			{
				$comment_status					= 1;
				//yöneticiler için commented oluşturmayalım
				//varolan commented varsa onu da yok edelim
				unset($_SESSION[SES]['comment'][$_id]);
			}
			else
			{
				//commented status için bu değeri oturuma alıyoruz
				//böylece tekrar tekrar yorum yaptırmıyoruz
				//tabi yöneticiler istisna oluyor
				$_SESSION[SES]['comment'][$_id] = 1;
			}

			//veritabanına kaydedeceğimiz için artık tüm değerleri temizleyelim
			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim(htmlspecialchars(strip_tags($v)));

			$record = array(
				'comment_content'		=> $_id,
				'comment_author'		=> $_REQUEST['isim'],
				'comment_text'			=> n2br($_REQUEST['yorum']),
				'comment_ip'			=> $_SESSION[SES]['ip'],
				'comment_status'		=> $comment_status,
				'create_time'			=> date("Y-m-d H:i:s")
			);

			$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			return true;
		}

		public function comment_add_app($_id, $author, $comment)
		{
			//id yoksa uğraşmaya gerek yok, hata dönelim
			if($_id == 0) return false;

			if($author == '' OR $comment == '' ) return false;

			//daha önceden bu başlığa yorum yazmışsa float yapamasın
			if($_SESSION[SES]['comment'][$_id] == 1) return false;

			//kendi ip mizi kendimiz bulalım
			$ip = get_real_ip();

			if(RC_Akismet == 1)
			{
				$akismet = new Akismet(SITELINK, akismetKey);
				$akismet->setCommentAuthor($author);
				$akismet->setCommentContent($comment);
				$akismet->setUserIP($ip);
				$akismet->setPermalink(SITELINK);

				if($akismet->isCommentSpam())
				{
					//store the comment but mark it as spam (in case of a mis-diagnosis)
					$comment_status = 3;
					//yorumu spam yakaladık, hiç uğraşmadan hata dönelim
					return false;
				}
				else
				{	//store the comment normally
					$comment_status = 2;
				}
			}
			else
			{
				$comment_status = 2;
			}

			$author		= trim(htmlspecialchars(strip_tags(($author))));
			$comment	= trim(htmlspecialchars(strip_tags(($comment))));

			$record = array(
				'comment_content'		=> $_id,
				'comment_author'		=> $author,
				'comment_ip' 			=> $ip,
				'comment_text'			=> n2br($comment),
				'comment_status'		=> 2,
				'create_time'			=> date("Y-m-d H:i:s")
			);

			$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			return true;
		}

		public function comment_edit($_id)
		{
			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim($v);

			if($_REQUEST['please_turkish'] == on)
			{
				//tarzan dilinden Türkçe kurallarına transfer ediyoruz
				$_REQUEST['comment_text'] = tr_strtolower($_REQUEST['comment_text']);
			}
			$_REQUEST['comment_text'] = $this->cleanTextComment($_REQUEST['comment_text']);

			$record = array(
				'comment_content'		=> $_REQUEST['comment_content'],
				'comment_author'		=> $_REQUEST['comment_author'],
				'comment_text'			=> $_REQUEST['comment_text'],
				'comment_status'		=> $_REQUEST['comment_status'],
				'comment_aprover'		=> $_SESSION[SES]['user_id'],
				'create_time'			=> $_REQUEST['create_time'],
			);

			$rs = $this->conn->AutoExecute(T_COMMENTS, $record, 'UPDATE', 'comment_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function comment_delete($_id)
		{
			$sql = 'DELETE FROM '.T_COMMENTS.' WHERE comment_id= '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function comment_truncate()
		{
			$sql = 'SELECT
						comment_id
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status IN (0,3,4)';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->comment_delete($v['comment_id']);
			}
			return true;
		}

		/**
		| Listeleme Fonksiyonları
		*/

		public function comment_list_small($keyword = 'none', $time = 'none', $user = 'none', $status = 'none', $limit = 30)
		{

			if($keyword	== '') $keyword	= 'none';
			if($time	== '') $time	= 'none';

			if($keyword	<> 'none')	$sql_keyword	= ' AND (comment_author LIKE "%'.$keyword.'%" OR comment_text LIKE "%'.$keyword.'%" OR comment_ip LIKE "%'.$keyword.'%")';
			if($time	<> 'none')	$sql_time		= ' AND create_time LIKE "'.$time.'%"';
			if($user 	<> 'none')	$sql_user 		= ' AND comment_aprover = '.$user;
			if($status 	<> 'none')	$sql_status 	= ' AND comment_status IN ('.$status.')';

			$sql = 'SELECT
						*,
						DATE_FORMAT(create_time, "%d.%m.%Y %H:%i") AS create_time_f
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_id > 0
						'.$sql_keyword.'
						'.$sql_time.'
						'.$sql_user.'
						'.$sql_status.'
					ORDER BY
						comment_id DESC
						LIMIT 0,'.$limit;
			return $this->conn->GetAll($sql);
		}

		public function comment_list($_id = 0)
		{
			$sql = 'SELECT
						*,
						DATE_FORMAT(create_time, "%d.%m.%Y") AS create_time_f
					FROM
						'.T_COMMENTS;

			if($_id <> 0)
			{
				$sql = $sql.' WHERE comment_id = '.$_id;
			}
			return $this->conn->GetAll($sql);
		}


		public function comment_list_content($_id, $json = 0)
		{
			$sql = 'SELECT
						comment_id,
						comment_author,
						comment_text,
						create_time,
						DATE_FORMAT(create_time, "%Y.%m.%d %H:%i") AS publish_time,
						DATE_FORMAT(create_time, "%d.%m.%Y") AS create_time_f
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status = 1
					AND
						comment_content = '.$_id.'
					ORDER BY
						create_time ASC';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);

			if($json == 0)
			{
				if($adet > 0)
				{
					return $list;
				}
				else
				{
					return false;
				}
			}

			if($json == 1)
			{
				if($adet > 0)
				{
					for($i = 0; $i < $adet; $i++)
					{
						unset
						(
							$list[$i]['create_time'],
							$list[$i]['create_time_f'],
							$list[$i]['xxx']
						);
					}
					return $list;
				}
				else
				{
					return array();
				}
				/**
				| Json datayı tablet ve phone uygulamasına gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

			}


		}

		public function get_comment_draft()
		{
			$sql = 'SELECT
						count(comment_id)
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status = 2';
			return $this->conn->GetOne($sql);
		}

		public function get_comment_count($_id)
		{
			$sql = 'SELECT
						count(comment_id)
					FROM
						'.T_COMMENTS.'
					WHERE
						comment_status = 1
					AND
						comment_content = '.$_id;
			return $this->conn->GetOne($sql);
		}

		public function set_comment_spam($_id)
		{
			$icerik = $this->comment_list($_id);

			$akismet = new Akismet(SITELINK, akismetKey);
			$akismet->setCommentAuthor($icerik[0]['comment_author']);
			$akismet->setCommentContent(strip_tags($icerik[0]['comment_text']));
			$akismet->setUserIP($icerik[0]['comment_author']);
			$akismet->setPermalink(SITELINK);
			$akismet->submitSpam();
		}


		private function cleanTextComment($content_text)
		{
			//raw gelen datayı entity edelim
			$content_text = htmlentities($content_text);

			//bölünmez boşlukları kabul ETMEYELİM
			$content_text = str_replace('&nbsp;',' ',$content_text);

			//] işareti sonrasında boşluk olmasın
			$content_text = str_replace('] ',']',$content_text);

			//] işareti sonrasında boşluk olmasın
			$content_text = str_replace(']&nbsp;',']',$content_text);

			//] işareti öncesinde p olmasın
			$content_text = str_replace('&lt;p&gt;[','[',$content_text);

			//] işareti sonrasında p olmasın
			$content_text = str_replace(']&lt;/p&gt;',']',$content_text);

			//çift br -> p olsun
			$content_text = str_replace('&lt;br /&gt;&lt;br /&gt;','</p><p>',$content_text);

			//daha beter olsun, tek br de p olsun
			//$content_text = str_replace('&lt;br /&gt;','</p><p>',$content_text);

			//daha beter olsun, tek <br> de p olsun
			//$content_text = str_replace('&lt;br&gt;','</p><p>',$content_text);

			//hatalı paragraf
			$content_text = str_replace('&lt;p&gt;&lt;/p&gt;','',$content_text);
			$content_text = str_replace('&lt;p&gt; &lt;/p&gt;','',$content_text);

			//hatalı paragraf başına enter koymak
			$content_text = str_replace('&lt;p&gt;&lt;br /&gt;','<p>',$content_text);

			//hatalı paragraf sonuna enter koymak
			$content_text = str_replace('&lt;br /&gt;&lt;p&gt;','</p>',$content_text);

			//hatalı paragraf başı + boşluk
			$content_text = str_replace('&lt;p&gt; ','&lt;p&gt;',$content_text);
			$content_text = str_replace('&lt;p&gt;&nbsp;','&lt;p&gt;',$content_text);

			//hatalı br + boşluk
			$content_text = str_replace('&lt;br /&gt;&nbsp;','&lt;br /&gt;',$content_text);
			$content_text = str_replace('&lt;br /&gt; ','&lt;br /&gt;',$content_text);

			//hatalı h3 başı + boşluk
			$content_text = str_replace('&lt;h3&gt; ','&lt;h3&gt;',$content_text);
			$content_text = str_replace('&lt;h3&gt;&nbsp;','&lt;h3&gt;',$content_text);

			//hatalı h3 başı + strong + boşluk
			$content_text = str_replace('&lt;h3&gt;&lt;strong&gt;&nbsp;','&lt;h3&gt;&lt;strong&gt;',$content_text);
			$content_text = str_replace('&lt;h3&gt;&lt;strong&gt; ','&lt;h3&gt;&lt;strong&gt;',$content_text);

			//bu değişimlerin sonuna
			//boşluk eklemek sorun yaratıyor
			//noktalı virgüller
			$content_text = str_replace(' ;',';',$content_text);

			//bu değişimler sorunsuz

			//nokta
			$content_text = str_replace(' .','.',$content_text);
			//hatalı üç nokta
			$content_text = str_replace('. . .','...',$content_text);
			//malum üç nokta
			$content_text = str_replace(' ...','...',$content_text);

			//virgüller
			$content_text = str_replace(' ,',',',$content_text);

			//çift nokta
			$content_text = str_replace(' :',':',$content_text);
			//soru işareti
			$content_text = str_replace(' ?','?',$content_text);

			//ünlem işareti
			$content_text = str_replace(' !','!',$content_text);

			//boşlukları 3 defa temizleyelim
			$content_text = str_replace('  ',' ',$content_text);
			$content_text = str_replace('  ',' ',$content_text);
			$content_text = str_replace('  ',' ',$content_text);

			//echo '<!--'.$content_text.'-->'."\n";

			//entitiy gelen datayı geri raw edelim
			$content_text = html_entity_decode($content_text);

			return $content_text;
		}
	}
