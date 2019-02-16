<?php

	class content
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		/**
		| Standart 4 İşlem; Ekle, Düzenle, Sil
		*/

		public function get_super_video_index($limit)
		{
			//datalarımızın yerelde tutulacağı klasör yolu
			$url_local = "cache/videoList.json";
			if(filesize($url_local) == 0) unlink($url_local);

			if(file_exists($url_local) && ((filemtime($url_local)+1800) > time()))
			{
				//yerel datayı aç
				$dt		= fopen($url_local, "r");
				//açılan datayı oku
				$data	= fread($dt, filesize($url_local));
				//dosyayı sonra kapat
				fclose($dt);
				//sonrasında else sonundaki kısımdan datayı parse etmeye devam et
			}
			else
			{
				//initalize
				$ch = curl_init();

				//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');

				//hargi url den veri çekeceğiz
				curl_setopt($ch, CURLOPT_URL , "http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=manset_superhaber");
				//dönen verileri kullanacak mıyız
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				//dönen header verilerini kaydedeyim mi
				curl_setopt($ch, CURLOPT_HEADER, false);

				curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
				//işlemi gerçekleştir
				$data = curl_exec($ch);
				//curl i kapat
				curl_close($ch);
				//dönen dataları, bir daha kullanmak üzere cache dizinine koy
				//bundan sonrasında if kısmına dönecek
				file_put_contents($url_local, $data);
			}
			$data = json_decode($data);

			for($i = 0; $i < $limit; $i++)
			{
				$list[$i] = array(
					'content_title'			=> $data->data[$i]->content_title,
					'content_url'			=> $data->data[$i]->content_url,
					'content_image_url'		=> $data->data[$i]->content_image_url,
					'content_thumb_url'		=> $data->data[$i]->content_thumb_url,
				);
			}

			return $list;
		}

		public function get_super_video_json($_id)
		{
			//datalarımızın yerelde tutulacağı klasör yolu
			$url_local = 'cache/video/cache_'.$_id.'.json';
			if(filesize($url_local) == 0) unlink($url_local);

			if(file_exists($url_local) && ((filemtime($url_local)+3600) > time()))
			{
				//yerel datayı aç
				$dt		= fopen($url_local, "r");
				//açılan datayı oku
				$data	= fread($dt, filesize($url_local));
				//dosyayı sonra kapat
				fclose($dt);
				//sonrasında else sonundaki kısımdan datayı parse etmeye devam et
			}
			else
			{
				//initalize
				$ch = curl_init();
				//hargi url den veri çekeceğiz
				curl_setopt($ch, CURLOPT_URL , "http://video.superhaber.tv/index.php?page=service&secure=MY_SECURITY_KEY&type=icerik&id=".$_id);
				//dönen verileri kullanacak mıyız
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				//dönen header verilerini kaydedeyim mi
				curl_setopt($ch, CURLOPT_HEADER, false);
				//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
				curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
				//işlemi gerçekleştir
				$data = curl_exec($ch);
				//curl i kapat
				curl_close($ch);
				//dönen dataları, bir daha kullanmak üzere cache dizinine koy
				//bundan sonrasında if kısmına dönecek
				file_put_contents($url_local, $data);
			}
			$data = json_decode($data);

			$list = array
			(
				'content_id'				=> $data->data[0]->content_id,
				'content_title'				=> $data->data[0]->content_title,
				'content_url'				=> $data->data[0]->content_url,
				'content_embed_url'			=> $data->data[0]->content_embed_url,
				'content_embed_mobile_url'	=> $data->data[0]->content_embed_mobile_url,
				'content_video_url'			=> $data->data[0]->content_video_url,
			);

			return $list;
		}

		public function clean_cache()
		{
			/**
			| Eski tarihli pasif içerikleri otomatik silelim
			*/
			$time = date('Y-m-d 00:00:00',strtotime("-2 day"));
			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 0
					AND
						create_time < "'.$time.'"';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->content_delete($v['content_id']);
			}
			return true;
		}

		public function content_add($user = '', $type = 'none')
		{
			//normalde daima reklam gösterilsin
			$content_ads_status = 1;

			//tarihi ve image dir otomatik biz atayalım
			$content_image_dir 	= date('Y/m/d/');
			$content_time 		= date("Y-m-d H:i:s");

			//metin şablonunu seçelim, farklı istenmişse farklı seçelim
												$content_template 	= 0;
			if($_REQUEST['redirect'] == 'true') $content_template = 2;
			if($_REQUEST['video'] == 'true')	$content_template = 4;
			if(RC_DetailedSeo == 1)				$content_metadesc = 'Haberin devamı ve tüm detayları SuperHaber.Tv\'de';

			//user zorlanması talep edilmişse bu seçenek işimize yarıyor
			//mesela botlardan gelen haberleri billi bir kullanıcı ile ilişkilendiriyoruz
			if($user == '') 					$user = $_SESSION[SES]['user_id'];

			if($type == 'sondakika')
			{
				$file_name = 'sondakika_'.gen_key(15).'.jpg';
				$url1 = IMAGE_DIRECTORY.'content/'.$content_image_dir.$file_name;
				$url2 = IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$file_name;
				$url3 = IMAGE_DIRECTORY.'manset/'.$content_image_dir.$file_name;

				copy(IMAGE_DIRECTORY_DEV.'sondakika_content.jpg', $url1);
				copy(IMAGE_DIRECTORY_DEV.'sondakika_thumbs.jpg', $url2);
				copy(IMAGE_DIRECTORY_DEV.'sondakika_manset.jpg', $url3);
			}

			if($type == 'flash')
			{
				$file_name = 'flash_'.gen_key(15).'.jpg';
				$url1 = IMAGE_DIRECTORY.'content/'.$content_image_dir.$file_name;
				$url2 = IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$file_name;
				$url3 = IMAGE_DIRECTORY.'manset/'.$content_image_dir.$file_name;

				copy(IMAGE_DIRECTORY_DEV.'flash_content.jpg', $url1);
				copy(IMAGE_DIRECTORY_DEV.'flash_thumbs.jpg', $url2);
				copy(IMAGE_DIRECTORY_DEV.'flash_manset.jpg', $url3);
			}

			if($type == 'sehit')
			{
				//şehit haberlerinde reklam gösterilMEsin
				$content_ads_status = 0;

				$file_name = 'sehit_'.gen_key(15).'.jpg';
				$url1 = IMAGE_DIRECTORY.'content/'.$content_image_dir.$file_name;
				$url2 = IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$file_name;
				$url3 = IMAGE_DIRECTORY.'manset/'.$content_image_dir.$file_name;

				copy(IMAGE_DIRECTORY_DEV.'sehit_content.jpg', $url1);
				copy(IMAGE_DIRECTORY_DEV.'sehit_thumbs.jpg', $url2);
				copy(IMAGE_DIRECTORY_DEV.'sehit_manset.jpg', $url3);
			}

			if($type == 'deprem')
			{
				//deprem haberlerinde reklam gösterilMEsin
				$content_ads_status = 0;

				$file_name = 'deprem_'.gen_key(15).'.jpg';
				$url1 = IMAGE_DIRECTORY.'content/'.$content_image_dir.$file_name;
				$url2 = IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$file_name;
				$url3 = IMAGE_DIRECTORY.'manset/'.$content_image_dir.$file_name;

				copy(IMAGE_DIRECTORY_DEV.'deprem_content.jpg', $url1);
				copy(IMAGE_DIRECTORY_DEV.'deprem_thumbs.jpg', $url2);
				copy(IMAGE_DIRECTORY_DEV.'deprem_manset.jpg', $url3);
			}

			//eklediğimiz içeriklere ait resim url'lerini de kaydedelim
			if($type <> 'none')
			{
				$content_image_url			= 'content/'.$content_image_dir.$file_name;
				$content_thumb_url			= 'thumbs/'.$content_image_dir.$file_name;
				$content_image_manset_url	= 'manset/'.$content_image_dir.$file_name;
			}

			//önce eski dataları silelim
			self::clean_cache();

			$record = array(
				'content_type'					=> 0,
				'content_cat'					=> 0,
				'content_cat_show_status'		=> 1,
				'content_user'					=> $user,
				'content_template'				=> $content_template,
				'content_time'					=> $content_time,
				'content_metadesc'				=> $content_metadesc,
				'content_ads_status'			=> $content_ads_status,
				//
				'content_image_dir'				=> $content_image_dir,
				'content_image'					=> $file_name,
				'content_image_manset'			=> $file_name,

				'content_image_url'				=> $content_image_url,
				'content_thumb_url'				=> $content_thumb_url,
				'content_image_manset_url'		=> $content_image_manset_url,
			);
			//print_pre($record);

			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'INSERT');
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
			$content_id = $this->conn->Insert_ID();

			//içerik view tablosuna ekleniyor
			$record = array(
				'id' => $content_id
			);
			$rs = $this->conn->AutoExecute(T_VIEW, $record, 'INSERT');

			return $content_id;
		}

		public function content_edit($_id)
		{
			//resim boyutları
			global $array_content_image_wh, $array_content_manset_wh;
			//resim manşet zorunlulukları
			global $array_content_type_required;

			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim($v);

			$detail_check = true;
			if($_REQUEST['content_template'] == 3) $detail_check = false;

			if($detail_check == true)
			{
				//içerik resmi yok ise taslak olarak kaydediyoruz
				if($_REQUEST['content_image'] == '')
				{
					$_REQUEST['content_status'] = 2;
				}

				//içerik türü manşet veya sağ manşet ise
				//ve manşet resmi boş ise taslak olarak kaydediyoruz
				if($_REQUEST['content_image_manset'] == '' && $array_content_type_required[$_REQUEST['content_type']] == true)
				{
					$_REQUEST['content_status'] = 2;
				}

				//resim varsa resim boyutlarını kontrol ediyoruz
				//Boyutlar hatalı ise taslak olarak kaydediyoruz
				if($_REQUEST['content_image'] <> '')
				{
					$image_sizes = getimagesize(IMAGE_DIRECTORY.'content/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image']);
					if($image_sizes[0] <> $array_content_image_wh['w']) $_REQUEST['content_status'] = 2;
					if($image_sizes[1] <> $array_content_image_wh['h']) $_REQUEST['content_status'] = 2;
				}

				//manşet resim varsa resim boyutlarını kontrol ediyoruz
				//Boyutlar hatalı ise taslak olarak kaydediyoruz
				if($_REQUEST['content_image_manset'] <> '' && $array_content_type_required[$_REQUEST['content_type']] == true)
				{
					$image_sizes = getimagesize(IMAGE_DIRECTORY.'manset/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image_manset']);
					if($image_sizes[0] <> $array_content_manset_wh[$_REQUEST['content_type']]['w']) $_REQUEST['content_status'] = 2;
					if($image_sizes[1] <> $array_content_manset_wh[$_REQUEST['content_type']]['h']) $_REQUEST['content_status'] = 2;
				}
			}
 			if($_REQUEST['content_manset_text_status'] == 'on') $_REQUEST['content_manset_text_status'] = 0;
 			if($_REQUEST['content_manset_text_status'] <> 'on') $_REQUEST['content_manset_text_status'] = 1;

 			if($_REQUEST['content_cat_show_status'] == 'on') 	$_REQUEST['content_cat_show_status'] = 0;
 			if($_REQUEST['content_cat_show_status'] <> 'on') 	$_REQUEST['content_cat_show_status'] = 1;

			if($_REQUEST['content_ads_status'] == 'on') 		$_REQUEST['content_ads_status'] = 0;
			if($_REQUEST['content_ads_status'] <> 'on') 		$_REQUEST['content_ads_status'] = 1;

			if(RC_DetailedSeo == 0)
			{
				$_REQUEST['content_title_seo'] = $_REQUEST['content_title_url'] = $_REQUEST['content_metadesc'] = $_REQUEST['content_title'];
			}
			else
			{
				if($_REQUEST['content_title_seo'] == '' ) 	$_REQUEST['content_title_seo']	= $_REQUEST['content_title'];
				if($_REQUEST['content_title_url'] == '' ) 	$_REQUEST['content_title_url']	= $_REQUEST['content_title'];
				if($_REQUEST['content_metadesc'] == '' ) 	$_REQUEST['content_metadesc']	= $_REQUEST['content_title'];
			}

			if($_REQUEST['content_image'] <> '')
			{
				$content_image_url			= 'content/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image'];
				$content_thumb_url			= 'thumbs/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image'];
			}
			if($_REQUEST['content_image_manset'] <> '')
			{
				$content_image_manset_url	= 'manset/'.$_REQUEST['content_image_dir'].$_REQUEST['content_image_manset'];
			}
			$content_url					= self::url_content_inline($_REQUEST['content_title_url'], $_id, $_REQUEST['content_template']);

			//bu kısımda, video url isteklerini embed kodlarına dönüştürüyoruz
			if($_REQUEST['content_template'] == 4)
			{
				$_REQUEST['content_redirect'] = self::convert_url_to_embed($_REQUEST['content_redirect']);
			}

			//etikette nokta, apostrof yer alamaz
			$_REQUEST['content_tags'] = str_replace(array('.','"','\''),'', $_REQUEST['content_tags']);

			$record = array(
				//text
				'content_desc'					=> strip_tags($_REQUEST['content_desc']),
				'content_text'					=> $this->cleanText($_REQUEST['content_text']),
				//'content_text'					=> $_REQUEST['content_text'],

				//varchar 256
				'content_title'					=> strip_tags($_REQUEST['content_title']),
				'content_title_outside'			=> strip_tags($_REQUEST['content_title_outside']),
				'content_image'					=> strip_tags($_REQUEST['content_image']),
				'content_image_dir'				=> strip_tags($_REQUEST['content_image_dir']),
				'content_image_manset'			=> strip_tags($_REQUEST['content_image_manset']),
				'content_redirect'				=> strip_tags($_REQUEST['content_redirect']),
				'content_tags'					=> tr_strtolower(strip_tags($_REQUEST['content_tags'])),
				//short int
				'content_status'				=> intval($_REQUEST['content_status']),
				'content_template'				=> intval($_REQUEST['content_template']),
				'content_comment_status'		=> intval($_REQUEST['content_comment_status']),
				'content_manset_text_status'	=> intval($_REQUEST['content_manset_text_status']),
				'content_cat_show_status'		=> intval($_REQUEST['content_cat_show_status']),
				'content_ads_status'			=> intval($_REQUEST['content_ads_status']),
				//long int
				'content_cat'					=> intval($_REQUEST['content_cat']),
				'content_cat2'					=> intval($_REQUEST['content_cat2']),
				'content_type'					=> intval($_REQUEST['content_type']),
				'content_author'				=> intval($_REQUEST['content_author']),

				//seo alanlar
				'content_title_seo'				=> strip_tags($_REQUEST['content_title_seo']),
				'content_title_url'				=> strip_tags($_REQUEST['content_title_url']),
				'content_metadesc'				=> strip_tags($_REQUEST['content_metadesc']),

				//simple alanlar
				'content_image_url'				=> $content_image_url,
				'content_thumb_url'				=> $content_thumb_url,
				'content_image_manset_url'		=> $content_image_manset_url,
				'content_url'					=> $content_url,

				//time stamp
				'content_time'					=> $_REQUEST['content_time'],
				'change_time'					=> time(),
			);

			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			if(RC_MansetOrder == 1)
			{
				//bu şekilde content_order sadece manşet sıralama fonksiyonu veya manşette göster butonu tarafından manipüle edilmiş oluyor
				if($_REQUEST['content_manset_reset'] == 'on')
				{
					self::content_manset_reset($_id);
				}
			}

			//site yayında ise ve içerik de aktif ise
			if(ST_ONLINE == 1 && $_REQUEST['content_status'] == 1)
			{
				//içeriği google ziyaret etmemiş ise
				if($list[0]['content_google_status'] == 0)
				{
					ping_it(SITELINK.$content_url);
				}

				if($_REQUEST['content_template'] == 0 or $_REQUEST['content_template'] == 3)
				{
					self::content_amp_cache_update_ping(SITELINK.$content_url);
				}
			}

			if(RC_SuperCache == 1)
			{
				//datalarımızın yerelde tutulduğu dosyayı siliyoruz
				self::clear_index_cache();
				self::clear_detail_cache($_id);
			}

			//içerik ampye uygun ise ve yayın amacıyla güncellenmişse
			//muhtemelen amp cache de bir örneği olabilir, güncellenmesini isteyelim
		}

		private function content_amp_cache_update_ping($url)
		{
			/**
			* amp olarak indexlenmiş içeriğimizin
			* değiştiğini ve yenilenmesi gerektiğini
			* google'a ping atıyoruz
			*/
			//en azından boş olmasın
			if($url == '') return false;

			$url = str_replace(array('http://','https://','www.'),'', $url.'-amp');

			$url = 'https://cdn.ampproject.org/update-ping/c/s/'.$url;
			$ch = curl_init();
			//hargi url den veri çekeceğiz
			curl_setopt($ch, CURLOPT_URL , $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
			//işlemi yürüt
			curl_exec($ch);
			//curl kapat
			curl_close($ch);
		}

		private function content_manset_reset($_id)
		{
			$record = array('content_order'	=> time());
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function clear_index_cache()
		{
			if(RC_SuperCache == 1)
			{
				global $InstanceCache;

				$dosya1 = mds($_SERVER['HTTP_HOST'].'_desktop_index_0.html');
				$dosya2 = mds($_SERVER['HTTP_HOST'].'_mobile_index_0.html');

				$InstanceCache->deleteItems(array($dosya1,$dosya2));
			}
		}

		public function clear_detail_cache($_id)
		{
			if(RC_SuperCache == 1)
			{
				global $InstanceCache;

				//muhtemel dosya yollarını tanımlayalım
				//desktop
				$dosya1 = mds($_SERVER['HTTP_HOST'].'_desktop_detail_content_'.$_id.'.html');
				$dosya2 = mds($_SERVER['HTTP_HOST'].'_desktop_detail_gallery_'.$_id.'.html');
				$dosya3 = mds($_SERVER['HTTP_HOST'].'_desktop_detail_article_'.$_id.'.html');
				$dosya4 = mds($_SERVER['HTTP_HOST'].'_desktop_detail_video_'.$_id.'.html');
				$dosya5 = mds($_SERVER['HTTP_HOST'].'_amp_detail_amp_'.$_id.'.html');
				//mobile
				$dosya6 = mds($_SERVER['HTTP_HOST'].'_mobile_detail_content_'.$_id.'.html');
				$dosya7 = mds($_SERVER['HTTP_HOST'].'_mobile_detail_gallery_'.$_id.'.html');
				$dosya8 = mds($_SERVER['HTTP_HOST'].'_mobile_detail_article_'.$_id.'.html');
				$dosya9 = mds($_SERVER['HTTP_HOST'].'_mobile_detail_video_'.$_id.'.html');
				//google status
				$dosya10 = $_SERVER['HTTP_HOST'].'_google_status_'.$_id;
				//tüm yollardaki dataları silelim
				$InstanceCache->deleteItems(array($dosya1,$dosya2,$dosya3,$dosya4,$dosya5,$dosya6,$dosya7,$dosya8,$dosya9,$dosya10));
			}
		}

		private function convert_url_to_embed($url)
		{
			$tdizi = parse_url($url);
			if($tdizi['host'] == 'www.youtube.com' or 'youtube.com')
			{
				parse_str($tdizi['query'], $tarr);

				if($tarr['v'] <> '')
				{
					return 'https://www.youtube.com/embed/'.$tarr['v'];
				}
				return $url;
			}
			return $url;
		}

		public function content_delete($_id)
		{
			//image_dir değerini bulalım
			$content_image_dir = $this->get_image_dir($_id);

			//içerik resmini siliyoruz
 			$this->content_delete_content_image($_id,$content_image_dir);

 			//manşet resmini siliyoruz
 			$this->content_delete_content_image_manset($_id,$content_image_dir);

			//galeriye ait resim ve dataları sil
			$this->content_delete_gallery($_id, $content_image_dir);

			//okunma sayılarını siliyoruz
			//İstatistik şaşmaması açısından, içerik silsek de okunma sayılarını silmiyoruz
			//$sql = 'DELETE FROM '.T_VIEW.' WHERE id= '.$_id;
			//$this->conn->Execute($sql);

 			//en son içeriği siliyoruz
			$sql = 'DELETE FROM '.T_CONTENT.' WHERE content_id= '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_delete_soft($_id)
		{
			/**
			| İçeriği silinmiş olarak işaretlemeye yarar
			| Silme işlemi daha sonra yetkili kişi tarafından garbage edilir
			*/
			$record = array('content_status' => 3);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_truncate()
		{
			/**
			| Haber ve Manşet resimlerinin de varolması sebebiyle direk delete fonksiyonu ile dataları silmek yerine
			| İlgili silme fonksiyonuna silinecek dataları işaret ediyoruz
			| o da, resimleriyle birlikte ilgili içerikten bizi kurtarıyor
			*/
			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_status IN (0,3)';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				$this->content_delete($v['content_id']);
			}
			return true;
		}

		/**
		| Haber Resim Fonksiyonları
		*/

		public function get_image_dir($_id)
		{
			//image_dir değerini döndürür
			$sql = 'SELECT
						content_image_dir
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			return $this->conn->GetOne($sql);
		}

		public function content_delete_content_image($_id,$content_image_dir)
		{
			$sql = 'SELECT content_image FROM '.T_CONTENT.' WHERE content_id = '.$_id;
			$file_name = $this->conn->GetOne($sql);

			if($file_name <> '')
			{
				@unlink(IMAGE_DIRECTORY.'content/'.$content_image_dir.$file_name);
				@unlink(IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$file_name);
			}

			$record = array(
				'content_image'		=> '',
				'content_image_url' => '',
				'content_thumb_url' => ''
			);

			$this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
		}

		public function content_delete_content_image_manset($_id,$content_image_dir)
		{
			$sql = 'SELECT content_image_manset FROM '.T_CONTENT.' WHERE content_id = '.$_id;
			$file_name = $this->conn->GetOne($sql);

			if($file_name <> '')
			{
				@unlink(IMAGE_DIRECTORY.'manset/'.$content_image_dir.$file_name);
			}

			$record = array(
				'content_image_manset'		=> '',
				'content_image_manset_url' 	=> ''
			);
			$this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
		}

		public function content_delete_gallery($_id, $content_image_dir)
		{
			//galeri içindeki resimleri belirliyoruz
			$sql = 'SELECT
						photo_image
					FROM
						'.T_GALLERY_IMAGES.'
					WHERE
						gallery_id = '.$_id;
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					@unlink(IMAGE_DIRECTORY.'gallery/'.$content_image_dir.$list[$i]['photo_image']);
				}
			}

			//galeri tablosundan galeriye ait değerleri kaldırıyoruz
			$sql = 'DELETE FROM '.T_GALLERY_IMAGES.' WHERE gallery_id = '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		/**
		| Çeşitli Listeleme Fonksiyonları
		*/

		public function article_list_small($author = '-1', $user = '-1', $cat = '-1', $status = '-1', $time = '', $keyword = '', $limit = 30)
		{
			/**
			| Daha basit bir içerik listesi çevirir
			| Yönetim panelinde Haberleri listelerken kullanıyoruz
			*/
			if($author	<> '-1')	$sql_author 	= ' AND content_author = '.$author;
			if($user	<> '-1')	$sql_user 		= ' AND content_user = '.$user;
			if($cat		<> '-1') 	$sql_cat 		= ' AND (content_cat = '.$cat.' OR content_cat2 = '.$cat.')';
			if($status	<> '-1')	$sql_status		= ' AND content_status = '.$status;
			if($time	<> '')		$sql_time		= ' AND content_time LIKE "'.$time.'%"';
			if($keyword	<> '')		$sql_keyword	= ' AND
			(
				content_title LIKE "%'.$keyword.'%"
				OR
				content_title_outside LIKE "%'.$keyword.'%"
				OR
				content_desc LIKE "%'.$keyword.'%"
				OR
				content_text LIKE "%'.$keyword.'%"
				OR
				content_tags LIKE "%'.$keyword.'%"
				OR
				content_image LIKE "%'.$keyword.'%"
				OR
				content_thumb_url LIKE "%'.$keyword.'%"
				OR
				content_url LIKE "%'.$keyword.'%"
				OR
				content_image_url LIKE "%'.$keyword.'%"
				OR
				content_image_manset_url LIKE "%'.$keyword.'%"
			)';

			if($limit > 0) 	$sql_limit	= 'LIMIT 0,'.$limit;

			$sql = '
				SELECT
					content_id,
					content_title_outside,
					content_title,
					content_title_seo,
					content_title_url,
					content_image,
					content_image_dir,
					content_image_manset,
					content_url,
					content_status,
					content_comment_status,
					content_tags,
					content_cat,
					content_cat2,
					content_type,
					content_user,
					content_author,
					content_time,
					content_cat_show_status,
					date_format(content_time, "%Y.%m.%d %H.%i") AS content_time_f,
					content_google_status,
					content_ads_status,
					content_view
				FROM
					'.T_CONTENT.','.T_VIEW.'
				WHERE
					'.T_CONTENT.'.content_id = '.T_VIEW.'.id
				AND
					content_status IN (1,2,3)
				AND
					content_template = 3
					'.$sql_author.'
					'.$sql_user.'
					'.$sql_cat.'
					'.$sql_status.'
					'.$sql_time.'
					'.$sql_keyword.'
				ORDER BY
					content_id DESC
					'.$sql_limit;
  			//print_pre($sql);
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				return $list;
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}

		public function gallery_list_small($user = '-1', $status = '-1', $keyword = '', $limit = 30)
		{
			/**
			| Daha basit bir içerik listesi çevirir
			| Yönetim panelinde Haberleri listelerken kullanıyoruz
			*/
			if($user	<> '-1')	$sql_user 		= ' AND content_user = '.$user;
			if($status	<> '-1')	$sql_status		= ' AND content_status = '.$status;
			if($keyword	<> '')		$sql_keyword	= ' AND
			(
				content_title LIKE "%'.$keyword.'%"
				OR
				content_title_outside LIKE "%'.$keyword.'%"
				OR
				content_desc LIKE "%'.$keyword.'%"
				OR
				content_text LIKE "%'.$keyword.'%"
				OR
				content_tags LIKE "%'.$keyword.'%"
			)';

			if($limit > 0) 	$sql_limit	= 'LIMIT 0,'.$limit;

			$sql = '
				SELECT
					content_id,
					content_title_outside,
					content_title,
					content_title_seo,
					content_title_url,
					content_image,
					content_image_dir,
					content_image_manset,
					content_url,
					content_image_url,
					content_thumb_url,
					content_status,
					content_comment_status,
					content_tags,
					content_cat,
					content_cat2,
					content_type,
					content_user,
					content_time,
					content_cat_show_status,
					date_format(content_time, "%Y.%m.%d %H.%i") AS content_time_f,
					content_google_status,
					content_view
				FROM
					'.T_CONTENT.','.T_VIEW.'
				WHERE
					'.T_CONTENT.'.content_id = '.T_VIEW.'.id
				AND
					content_status IN (1,2,3)
				AND
					content_template = 1
					'.$sql_type.'
					'.$sql_cat.'
					'.$sql_user.'
					'.$sql_status.'
					'.$sql_time.'
					'.$sql_keyword.'
				ORDER BY
					content_id DESC
					'.$sql_limit;
// 			print_pre($sql);
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				return $list;
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}

		public function content_list_small($template = '-1', $type = '-1', $cat = '-1', $user = '-1', $status = '-1', $time = '', $keyword = '', $limit = 30)
		{
			/**
			| Daha basit bir içerik listesi çevirir
			| Yönetim panelinde Haberleri listelerken kullanıyoruz
			*/
			if($template 	<> '-1') 	$sql_template	= ' AND content_template = '.$template;
			if($type 		<> '-1') 	$sql_type 		= ' AND content_type = '.$type;
			if($cat			<> '-1') 	$sql_cat 		= ' AND (content_cat = '.$cat.' OR content_cat2 = '.$cat.')';
			if($user		<> '-1')	$sql_user 		= ' AND content_user = '.$user;
			if($status		<> '-1')	$sql_status		= ' AND content_status = '.$status;
			if($time		<> '')		$sql_time		= ' AND content_time LIKE "'.$time.'%"';
			if($keyword		<> '')		$sql_keyword	= ' AND
			(
				content_title LIKE "%'.$keyword.'%"
				OR
				content_title_outside LIKE "%'.$keyword.'%"
				OR
				content_desc LIKE "%'.$keyword.'%"
				OR
				content_text LIKE "%'.$keyword.'%"
				OR
				content_tags LIKE "%'.$keyword.'%"
			)';

			if($limit > 0) 	$sql_limit	= 'LIMIT 0,'.$limit;

			$sql = '
				SELECT
					content_id,
					content_title_outside,
					content_title,
					content_title_seo,
					content_title_url,
					content_image,
					content_image_dir,
					content_image_manset,
					content_url,
					content_image_url,
					content_thumb_url,
					content_status,
					content_template,
					content_comment_status,
					content_tags,
					content_cat,
					content_cat2,
					content_type,
					content_user,
					content_time,
					content_cat_show_status,
					date_format(content_time, "%Y.%m.%d %H.%i") AS content_time_f,
					content_google_status,
					content_template,
					content_view
				FROM
					'.T_CONTENT.','.T_VIEW.'
				WHERE
					'.T_CONTENT.'.content_id = '.T_VIEW.'.id
				AND
					content_status IN (1,2,3)
				AND
					content_template IN (0,2,4)
					'.$sql_template.'
					'.$sql_type.'
					'.$sql_cat.'
					'.$sql_user.'
					'.$sql_status.'
					'.$sql_time.'
					'.$sql_keyword.'
				ORDER BY
					content_id DESC
					'.$sql_limit;
			//echo $sql;
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				return $list;
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}

		public function content_list_benzer($cat, $exc, $limit, $tmp, $json = 0)
		{
			/**
			| Haber detayındaki Benzer Yazılar linkini oluşturmak için kullanıyoruz
			*/

			if($tmp == 0) $limit_date = date('Y-m-d 00:00:00',strtotime("-15 day"));
			if($tmp == 1) $limit_date = date('Y-m-d 00:00:00',strtotime("-30 day"));

			if($cat <> 'none')
			{
				$sql_cat = ' AND ( content_cat = '.$cat.' OR content_cat2 = '.$cat.' )';
			}

			if($tmp <> 'none')
			{
				$sql_tmp = 'AND content_template = '.$tmp;
			}

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_cat,
						content_template,
						content_url,
						content_thumb_url,
						content_image_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_id <> '.$exc.'
					AND
						content_time > "'.$limit_date.'"
					AND
						content_template = '.$tmp.'
						'.$sql_cat.'
						'.$sql_admin.'
					ORDER BY
						rand()
					LIMIT
						0,'.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']				= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']			= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url']			= G_IMGLINK.$list[$i]['content_thumb_url'];
			}
			if($json == 0) return $list;

			if($json == 1)
			{
				/**
				| Json datayı uygulamaya gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_manset_text_status']	= null;
					$list[$i]['content_image_manset_url']	= null;
					unset
					(
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function content_list_most_view($limit, $type, $cat = 'none', $json = 0)
		{
			/**
			| Çok Okunanlar
			*/

			if($_SESSION[SES]['ADMIN'] <> 1) 	$sql_admin		= 'AND content_time < now()';

			if($type == 'galeri')
			{
				$limit_date = date('Y-m-d H:i:s',strtotime("-24 hours"));

				$sql = 'SELECT
							content_id,
							ABS
							(
								timestampdiff(HOUR, now(), content_time)
							) as ago,
							(
								content_view/ABS(timestampdiff(HOUR, now(), content_time))
							) as ortalama,
							content_id,
							content_cat,
							content_template,
							content_title_outside,
							content_title,
							content_url,
							content_thumb_url,
							content_image_url
						FROM
							'.T_CONTENT.','.T_VIEW.'
						WHERE
							'.T_CONTENT.'.content_id = '.T_VIEW.'.id
						AND
							content_time > "'.$limit_date.'"
						AND
							content_template = 1
						AND
							content_status = 1
							'.$sql_admin.'
						ORDER BY
							ortalama DESC
						LIMIT
							0,'.$limit.';';
				//echo $sql;
			}

			if($type == 'video')
			{
				$limit_date = date('Y-m-d H:i:s',strtotime("-24 hours"));

				$sql = 'SELECT
							content_id,
							ABS
							(
								timestampdiff(HOUR, now(), content_time)
							) as ago,
							(
								content_view/ABS(timestampdiff(HOUR, now(), content_time))
							) as ortalama,
							content_id,
							content_cat,
							content_template,
							content_title_outside,
							content_title,
							content_url,
							content_thumb_url,
							content_image_url
						FROM
							'.T_CONTENT.','.T_VIEW.'
						WHERE
							'.T_CONTENT.'.content_id = '.T_VIEW.'.id
						AND
							content_time > "'.$limit_date.'"
						AND
							content_template = 4
						AND
							content_status = 1
							'.$sql_admin.'
						ORDER BY
							ortalama DESC
						LIMIT
							0,'.$limit.';';
			}

			if($type == 'view')
			{
				$limit_date = date('Y-m-d H:i:s',strtotime("-8 hours"));

				$sql = 'SELECT
							content_id,
							ABS
							(
								timestampdiff(HOUR, now(), content_time)
							) as ago,
							(
								content_view/ABS(timestampdiff(HOUR, now(), content_time))
							) as ortalama,
							content_id,
							content_cat,
							content_template,
							content_title_outside,
							content_title,
							content_url,
							content_thumb_url,
							content_image_url
						FROM
							'.T_CONTENT.','.T_VIEW.'
						WHERE
							'.T_CONTENT.'.content_id = '.T_VIEW.'.id
						AND
							content_time > "'.$limit_date.'"
							'.$sql_admin.'
						AND
							content_template IN("0,2,3,5")
						ORDER BY
							ortalama DESC
						LIMIT
							0,'.$limit.';';
			}

			if($type == 'cat')
			{
				if($cat == 'none') return false;

				$limit_date = date('Y-m-d H:i:s',strtotime("-24 hours"));

				$sql = 'SELECT
							content_id,
							ABS
							(
								timestampdiff(HOUR, now(), content_time)
							) as ago,
							(
								content_view/ABS(timestampdiff(HOUR, now(), content_time))
							) as ortalama,
							content_id,
							content_cat,
							content_template,
							content_title_outside,
							content_title,
							content_url,
							content_thumb_url,
							content_image_url
						FROM
							'.T_CONTENT.','.T_VIEW.'
						WHERE
							'.T_CONTENT.'.content_id = '.T_VIEW.'.id
						AND
							content_time > "'.$limit_date.'"
						AND
							content_cat = "'.$cat.'"
							'.$sql_admin.'
						AND
							content_template IN("0,2,3,5")
						ORDER BY
							ortalama DESC
						LIMIT
							0,'.$limit.';';
			}

			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
				$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
			}

			if($json == 0) return $list;

			if($json == 1)
			{
				/**
				| Json datayı uygulamaya gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_manset_text_status']	= null;
					$list[$i]['content_image_manset_url']	= null;
					unset
					(
						$list[$i]['ago'],
						$list[$i]['ortalama'],
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function content_list_manset
		(
			$limit,
			$type = 'none',
			$template = 'none',
			$cat = 'none',
			$order = 'none',
			$exclude = 'none',
			$json = 0
		)
		{
			global $array_cat_name, $array_cat_url;

			$sql_order = 'content_time DESC';
			if(RC_MansetOrder == 1 && $order == 'order')
			{
				$sql_order = 'content_order DESC';
			}

			if($type		<> 'none')			$sql_type 		= 'AND content_type IN ('.$type.')';
			if($template	<> 'none')			$sql_template	= 'AND content_template IN ('.$template.')';
			if($cat			<> 'none')			$sql_cat 		= 'AND (content_cat IN('.$cat.') OR content_cat2 IN('.$cat.'))';
			if($exclude 	<> 'none') 			$sql_exclude	= 'AND content_id NOT IN('.$exclude.')';

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin		= 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_cat,
						content_template,
						content_title_outside,
						content_title,
						content_url,
						content_image_url,
						content_thumb_url,
						content_image_manset_url,
						content_manset_text_status
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_cat_show_status = 1
						'.$sql_admin.'
						'.$sql_template.'
						'.$sql_type.'
						'.$sql_cat.'
						'.$sql_exclude.'
					ORDER BY
						'.$sql_order.'
					LIMIT
						0,'.$limit.';';
 			//print_pre($sql);
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime_manset, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				if($list[$i]['content_url'] <> '') 				$list[$i]['content_url']				= SITELINK.$list[$i]['content_url'];
				if($list[$i]['content_image_url'] <> '') 		$list[$i]['content_image_url']			= G_IMGLINK.$list[$i]['content_image_url'];
				if($list[$i]['content_thumb_url'] <> '') 		$list[$i]['content_thumb_url']			= G_IMGLINK.$list[$i]['content_thumb_url'];
				if($list[$i]['content_image_manset_url'] <> '') $list[$i]['content_image_manset_url']	= G_IMGLINK.$list[$i]['content_image_manset_url'];
			}

			if($json == 0) return $list;

			//uygulama arayüzünde kullanıyoruz
			if($json == 1)
			{
				for($i = 0; $i < $adet; $i++)
				{
					unset
					(
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function content_list_seo($limit)
		{
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin		= 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title,
						content_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_cat_show_status = 0
						'.$sql_admin.'
					ORDER BY
						content_time DESC
					LIMIT
						0,'.$limit.';';
 			//print_pre($sql);
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime_manset, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				if($list[$i]['content_url'] <> '') $list[$i]['content_url'] = SITELINK.$list[$i]['content_url'];
			}

			return $list;
		}

		public function content_list_etiket($page = 1, $limit = 8, $_key)
		{
			$_key = htmlspecialchars_decode(strip_tags($_key));
			//iterasyonda kolaylık olsun diye
			//page değeri 0 gelenleri 1 ile değiştiriyoruz
			//sonrasında page değerinin hep bir eksiğini alıyoruz
			//ki böylece o sayfanın başlangıç numarasını bulmuş oluyoruz
			//
			//1 diye yazdığımızda
			//(1-1=0*8=0) limit 0,8 oluyor ki
			//sıfırdan başlayıp 8 içerik getir anlamına geliyor
			//ikinci sayfa için 2 yazdığımızda ise
			//(2-1=1*8=8) limit 8,8 oluyor ki
			//sekizden başlayıp 8 içerik getir anlamına geliyor

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			if($page == 0) $page = 1;

			$search_text = '%' . $_key . '%';

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_url,
						content_thumb_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_tags LIKE ?
						'.$sql_admin.'
					ORDER BY
						content_time DESC
					LIMIT
						'.(($page-1)*$limit).','.$limit;
			if(memcached == 0) $list = $this->conn->GetAll($sql, array($search_text));
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql, array($search_text));

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
			}
			return $list;
		}

		public function content_list_etiket_pages($limit = 8, $_key)
		{
			global $array_cat_url;
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$search_text = '%' . $_key . '%';
			$sql = 'SELECT
						count(content_id)
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_tags LIKE ?
						'.$sql_admin;
			if(memcached == 0) $adet = $this->conn->GetOne($sql, array($search_text));
			if(memcached == 1) $adet = $this->conn->CacheGetOne(cachetime, $sql, array($search_text));
			$madet = $adet/$limit;
			return intval($madet+1);
		}

		public function content_list_cat($page = 1, $limit = 8, $cat, $json = 0)
		{
			//iterasyonda kolaylık olsun diye
			//page değeri 0 gelenleri 1 ile değiştiriyoruz
			//sonrasında page değerinin hep bir eksiğini alıyoruz
			//ki böylece o sayfanın başlangıç numarasını bulmuş oluyoruz
			//
			//1 diye yazdığımızda
			//(1-1=0*8=0) limit 0,8 oluyor ki
			//sıfırdan başlayıp 8 içerik getir anlamına geliyor
			//ikinci sayfa için 2 yazdığımızda ise
			//(2-1=1*8=8) limit 8,8 oluyor ki
			//sekizden başlayıp 8 içerik getir anlamına geliyor

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			if($page == 0) $page = 1;
			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_url,
						content_cat,
						content_template,
						content_image_url,
						content_thumb_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_cat_show_status = 1
						'.$sql_admin.'
					AND
					(
						content_cat IN ('.$cat.')
						OR
						content_cat2 IN ('.$cat.')
					)
					ORDER BY
						content_time DESC
					LIMIT
						'.(($page-1)*$limit).','.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
			}

			if($json == 0) return $list;

			if($json == 1)
			{
				/**
				| Json datayı tablet ve phone uygulamasına gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				for($i = 0; $i < $adet; $i++)
				{
					unset
					(
						$list[$i]['content_title'],
						$list[$i]['content_title_url'],
						$list[$i]['content_image'],
						$list[$i]['content_image_dir'],
						$list[$i]['content_image_manset'],
						$list[$i]['content_image_manset_url'],
						$list[$i]['content_url'],
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function content_list_cat_pages($limit = 8, $cat)
		{
			global $array_cat_url;
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						count(content_id)
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_cat_show_status = 1
						'.$sql_admin.'
					AND
					(
						content_cat IN ('.$cat.')
						OR
						content_cat2 IN ('.$cat.')
					)
					';
			if(memcached == 0) $adet = $this->conn->GetOne($sql);
			if(memcached == 1) $adet = $this->conn->CacheGetOne(cachetime, $sql);
			$madet = $adet/$limit;
			return intval($madet+1);
		}

		public function content_list_gallery
		(
			$type 		= 'none',
			$template 	= 'none',
			$limit 		= 8,
			$page 		= 1,
			$json 		= 0
		)
		{

			//iterasyonda kolaylık olsun diye
			//page değeri 0 gelenleri 1 ile değiştiriyoruz
			//sonrasında page değerinin hep bir eksiğini alıyoruz
			//ki böylece o sayfanın başlangıç numarasını bulmuş oluyoruz
			//
			//1 diye yazdığımızda
			//(1-1=0*8=0) limit 0,8 oluyor ki
			//sıfırdan başlayıp 8 içerik getir anlamına geliyor
			//ikinci sayfa için 2 yazdığımızda ise
			//(2-1=1*8=8) limit 8,8 oluyor ki
			//sekizden başlayıp 8 içerik getir anlamına geliyor

			global $array_cat_name, $array_cat_url;


			if($type <> 'none')				$sql_tip 		= 'AND content_type IN ('.$type.')';
			if($template <> 'none')			$sql_template	= 'AND content_template IN ('.$template.')';

			if($_SESSION[SES]['ADMIN'] <> 0)
			{
				$sql_admin = 'AND content_time < now()';
			}

			if($page == 0) $page = 1;
			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_url,
						content_image_url,
						content_thumb_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						'.$sql_admin.'
						'.$sql_tip.'
						'.$sql_template.'
					ORDER BY
						content_time DESC
					LIMIT
						'.(($page-1)*$limit).','.$limit;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']				= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']			= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url']			= G_IMGLINK.$list[$i]['content_thumb_url'];
			}

			if($json == 0) return $list;

			if($json == 1)
			{
				/**
				| Json datayı uygulamaya gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				for($i = 0; $i < $adet; $i++)
				{
					//$list[$i]['content_title'] = null;
					unset
					(
						$list[$i]['content_title'],
						$list[$i]['content_title_url'],
						$list[$i]['content_image'],
						$list[$i]['content_image_manset'],
						//$list[$i]['content_cat'],
						//$list[$i]['content_cat_name'],
						$list[$i]['content_cat_url'],
						$list[$i]['xxx']
					);
				}
				return $list;
			}

		}

		public function content_list_gallery_pages
		(
			$type = 'none',
			$template = '1',
			$limit = 8
		)
		{

			if($type <> 'none')				$sql_tip 		= 'AND content_type IN ('.$type.')';
			if($template <> 'none')			$sql_template	= 'AND content_template IN ('.$template.')';

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						count(content_id)
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						'.$sql_admin.'
						'.$sql_tip.'
						'.$sql_template;
			if(memcached == 0) $adet = $this->conn->GetOne($sql);
			if(memcached == 1) $adet = $this->conn->CacheGetOne(cachetime, $sql);
			$madet = $adet/$limit;
			return intval($madet+1);
		}

		public function content_list_search($keyword = '', $limit, $json = 0)
		{
			global $array_cat_name, $array_cat_url;
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			if(strlen($keyword) < 3) return false;

			if($keyword	<> '')
			{
				$keyword = htmlspecialchars(html_entity_decode($keyword));
				$sql_keyword	= ' AND
				(
					content_title LIKE "%'.$keyword.'%"
					OR
					content_desc LIKE "%'.$keyword.'%"
					OR
					content_title_outside LIKE "%'.$keyword.'%"
					OR
					content_title_seo LIKE "%'.$keyword.'%"
					OR
					content_title_url LIKE "%'.$keyword.'%"
					OR
					content_tags LIKE "%'.$keyword.'%"
				)';
			}

			$sql = 'SELECT
						content_id,
						content_cat,
						content_template,
						content_title_outside,
						content_title,
						content_url,
						content_image_url,
						content_thumb_url,
						content_image_manset_url,
						content_manset_text_status
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						'.$sql_admin.'
						'.$sql_keyword.'
					ORDER BY
						content_time DESC
					LIMIT 0, '.$limit;
			//print_pre($sql);
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
					$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
					$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
				}

				if($json == 0) return $list;

				//mobil arayüzde ihtiyaç yok
				if($json == 1)
				{
					for($i = 0; $i < $adet; $i++)
					{
						unset
						(
							$list[$i]['xxx']
						);
					}
					return $list;
				}
			}
			else
			{
				return false;
			}
		}

		public function content_list_author($_key, $limit = 8, $json = 0)
		{
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_url,
						content_cat,
						content_template,
						content_time,
						date_format(content_time, "%d.%m.%Y") AS content_date
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						'.$sql_admin.'
					AND
						content_author = (SELECT author_id FROM '.T_AUTHOR.' WHERE author_keyword = "'.$_key.'")
					ORDER BY
						content_time DESC
					LIMIT
						0,'.$limit;
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
			}

			if($json == 0)
			{
				return $list;
			}
			if($json == 1)
			{
				/**
				| Json datayı uygulamaya gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				 */

				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
					$list[$i]['content_thumb_url']	= null;
					$list[$i]['content_image_url']	= null;
					$list[$i]['content_desc']		= null;

					unset
					(
						$list[$i]['xxx']
					);
				}
				return $list;
			}
		}

		public function content_list_archive($time, $type = 'arsiv')
		{
			global $array_cat_name, $array_cat_url;

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin 	= 'AND content_time < now()';
			}

			if($time <> '')
			{
				$sql_time 	= 'AND content_time LIKE "'.$time.'%"';
			}

			if($type == 'manset')
			{
				$sql_type 	= 'AND content_type <> 0';
			}

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_url,
						content_thumb_url,
						content_time,
						date_format(content_time, "%H.%i") AS content_hours
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
						'.$sql_admin.'
						'.$sql_time.'
						'.$sql_type.'
					ORDER BY
						content_time DESC';
//  			print_pre($sql);
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
			}

			return $list;
		}

		public function content_list_sitemap($limit, $type)
		{
			/**
			* TODO : Galeriler için AMP aktif olduğunda amp değeri fonksiyona geçirilecek
			*/

			//klasik sitemap
			if($type == 0)
			{
				$ct 	= 'content_template <> 2';
				$order	= 'content_time';
				$amp 	= false;
			}

			//güncellenen içerik sitemap
			if($type == 1)
			{
				$ct 	= 'content_template <> 2';
				$order	= 'change_time';
				$amp 	= false;
			}

			//metin sitemap
			if($type == 20)
			{
				$ct 	= 'content_template = 0';
				$order	= 'change_time';
				$amp 	= false;
			}

			//galeri sitemap
			if($type == 21)
			{
				$ct 	= 'content_template = 1';
				$order	= 'change_time';
				$amp 	= false;
			}

			//yönlendirmeliri listelemiyoruz

			//makale sitemap
			if($type == 23)
			{
				$ct 	= 'content_template = 3';
				$order	= 'change_time';
				$amp 	= false;
			}

			//videoları da göstermiyoruz
			//çünkü harici video sitesi aktif

			//klasik sitemap + AMP
			if($type == 30)
			{

				//galeri amp olmadığı için şimdilik sadece haber ve makaleleri gönderiyoruz
				//$ct 	= 'content_template <> 2';
				$ct 	= 'content_template IN(0,3)';
				$order	= 'content_time';
				$amp 	= true;
			}

			//güncellenen içerik sitemap + AMP
			if($type == 31)
			{

				//galeri amp olmadığı için şimdilik sadece haber ve makaleleri gönderiyoruz
				//$ct 	= 'content_template <> 2';
				$ct 	= 'content_template IN(0,3)';
				$order	= 'content_time';
				$amp 	= true;
			}

			//metin sitemap  + AMP
			if($type == 40)
			{
				$ct 	= 'content_template = 0';
				$order	= 'change_time';
				$amp 	= true;
			}

			//galeri sitemap
			if($type == 41)
			{
				$ct 	= 'content_template = 1';
				$order	= 'change_time';
				$amp 	= true;
			}

			//yönlendirmeliri listelemiyoruz

			//makale sitemap + AMP
			if($type == 43)
			{
				$ct 	= 'content_template = 3';
				$order	= 'change_time';
				$amp 	= true;
			}

			$sql = 'SELECT
						content_title,
						content_url,
						content_image_url,
						DATE_FORMAT(change_time,"%Y-%m-%d") AS content_date,
						DATE_FORMAT(change_time,"%H:%i:%s") AS content_hours
					FROM
						'.T_CONTENT.'
					WHERE
						content_time < now()
					AND
						content_status = 1
					AND
						'.$ct.'
					ORDER BY
						'.$order.' DESC
					LIMIT 0,'.$limit;

			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				if($amp == false )	$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				if($amp == true )	$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'].'-amp';
									$list[$i]['content_image_url']	= SITELINK.'assets/uploads/images/'.$list[$i]['content_image_url'];
									$list[$i]['content_thumb_url']	= SITELINK.'assets/uploads/images/'.$list[$i]['content_thumb_url'];
			}

			return $list;
		}

		public function content_list_feed($limit = 25)
		{
			global $array_cat_name;

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_title_url,
						content_desc,
						content_image,
						content_image_dir,
						content_url,
						content_image_url,
						content_thumb_url,
						content_cat,
						content_time,
						content_template
					FROM
						'.T_CONTENT.'
					WHERE
						content_time < now()
					AND
						content_status = 1
					AND
						content_template <> 2
					ORDER BY
						content_time DESC
					LIMIT 0,'.$limit;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_cat_name']	= $array_cat_name[$list[$i]['content_cat']];
				$list[$i]['changetar']			= pco_format_date(strtotime($list[$i]['content_time']));
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_image_url']	= G_IMGLINK.$list[$i]['content_image_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
			}
			return $list;
		}

		public function content_list_sitemap_news()
		{
			$time = date('Y-m-d 00:00:00',strtotime("-1 day"));

			$sql = 'SELECT
						content_id,
						content_url,
						DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date,
						DATE_FORMAT(content_time,"%H:%i:%s") AS content_hours,
						content_title_outside,
						content_title,
						content_tags
					FROM
						'.T_CONTENT.'
					WHERE
						content_time < now()
					AND
						content_status = 1
					AND
						content_template = 0
					AND
						content_time >= "'.$time.'"
					AND
						content_template <> 2
					ORDER BY
						content_time DESC';
			//echo $sql;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
			}
			return $list;
		}

		/**
		| Çeşitli Diğer SQL içeren fonksiyonlar
		*/

		public function content_inline_benzer($_key)
		{
			/**
			| Metin içinde gösterilecek olan Benzer Haberler Kutusu
			| Inline gösterim olmakta ve Haber Alanında girilen
			| ID değerlerine göre sonuçları göstermektedir
			*/

			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_desc,
						content_url,
						content_thumb_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_id IN ('.$_key.')
						'.$sql_admin.'
					ORDER BY
						content_time DESC';
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{
				$list[$i]['content_url']		= SITELINK.$list[$i]['content_url'];
				$list[$i]['content_thumb_url']	= G_IMGLINK.$list[$i]['content_thumb_url'];
			}
			return $list;
		}

		public function content_ads_status($_id)
		{
			/**
			| İlgili içeriğe ait tüm dataları getirir
			*/
			$sql = 'SELECT
						content_ads_status
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			if(memcached == 0) $rs = $this->conn->GetOne($sql);
			if(memcached == 1) $rs = $this->conn->CacheGetOne(cachetime, $sql);

			//sonucu dönelim
			return $rs;
		}

		public function content_detail($_id, $publish = 0, $json = 0)
		{
			/**
			| İlgili içeriğe ait tüm dataları getirir
			*/
			$sql = '
				SELECT
					*
				FROM
					'.T_CONTENT.','.T_VIEW.'
				WHERE
					'.T_CONTENT.'.content_id = '.T_VIEW.'.id
				AND
					content_id = '.$_id;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$list[0]['content_url']			= SITELINK.$list[0]['content_url'];
			$list[0]['content_image_url']	= G_IMGLINK.$list[0]['content_image_url'];
			$list[0]['content_thumb_url']	= G_IMGLINK.$list[0]['content_thumb_url'];

			if($publish == 1) $list[0]['content_text'] = str_replace('assets/',G_ASSETSLINK,$list[0]['content_text']);

			//eğer içerik google'a eklenmemiş ise
			//google arama motoru ziyaret ettimi diye kontrol ediyoruz
			if($list[0]['content_google_status'] == 0)
			{
				self::is_google($_id);
			}

			if($json == 0) return $list;

			if($json == 1)
			{
				/**
				| Json datayı tablet ve phone uygulamasına gönderirken kullanıyoruz
				| bu sebeple kullanılmayacak kimi değerleri hiç göndermiyoruz
				*/

				if($list[0]['content_template'] == 1 or $list[0]['content_template'] == 4)
				{
					$list[0]['content_text'] = null;
				}
				else
				{
					$list[0]['content_inline'] = self::content_with_smilar_ids($list[0]['content_text']);

					//uygulamaya gidecek datada smilar tagına gerek yok
					$list[0]['content_text'] = self::content_with_smilar_clean($list[0]['content_text']);

					//video taglarını da dönüştürelim
					$list[0]['content_text'] = self::content_with_smilar_video($list[0]['content_text'], $mobile = 2);

					//uygulamaya detaylarına işaret koyuyoruz
					$list[0]['content_text'] = '
					<!DOCTYPE HTML>
					<html lang="tr">
						<head>
							<meta charset="UTF-8">
							<link href="'.G_ASSETSLINK.'default/desktop/css/app.css" rel="stylesheet" media="screen"/>
						</head>
						<body>
							<div class="contentDetail">
								'.$list[0]['content_text'].'
							</div>
						</body>
					</html>';
				}
				unset
				(
					$list[0]['content_user'],
					$list[0]['content_title_seo'],
					$list[0]['content_title_url'],
					$list[0]['content_image'],
					$list[0]['content_image_dir'],
					$list[0]['content_image_manset'],
					$list[0]['content_image_manset_url'],
					$list[0]['content_manset_text_status'],
					$list[0]['content_order'],
					$list[0]['content_ads_status'],
					$list[0]['content_google_status'],
					$list[0]['content_tags'],
					$list[0]['content_metadesc'],
					$list[0]['content_status'],
					$list[0]['content_comment_status'],
					$list[0]['content_type'],
					$list[0]['content_view'],
					$list[0]['content_view_real'],
					$list[0]['create_time'],
					$list[0]['change_time'],
					$list[0]['content_cat2'],
					$list[0]['content_cat_show_status'],
					$list[0]['id'],
					$list[0]['xxx']
				);
				return $list;
			}
		}

		public function content_view($_id)
		{
			//her halükarda okunma sayısını artıralım
			$sql = 'UPDATE
						'.T_VIEW.'
					SET
						content_view = (content_view + 1)
					WHERE
						id = '.$_id;
			$rs = $this->conn->Execute($sql);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}

			//bot değilse gerçek okunma sayısını da artıralım
			if(!is_bot())
			{
				if($_SESSION[SES]["content_view"][$_id] <> 1)
				{
					$sql = 'UPDATE
								'.T_VIEW.'
							SET
								content_view_real = (content_view_real + 1)
							WHERE
								id = '.$_id;
					$rs = $this->conn->Execute($sql);
					if($rs == false)
					{
						throw new Exception($this->conn->ErrorMsg());
					}
					else
					{
						$_SESSION[SES]["content_view"][$_id] = 1;
					}
				}
			}
		}

		public function get_content_id_from_title($title, $id)
		{
			/**
			* Başlıkların dublicate olması durumuna karşı
			* ajax ile dublicate kontrolü için kullanılır
			*/
			if($title == "") return false;

			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_id <> '.$id.'
					AND
						content_title = "'.$title.'"';
			return $this->conn->GetOne($sql);
		}

		public function content_detay_links($_id, $time, $cat, $template, $type, $json = 0)
		{
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			if($type == 7)
			{
				$sql_type = 'AND content_type = 7';
			}
			else
			{
				$sql_type = 'AND content_type <> 7';
			}

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_cat,
						content_url,
						content_image_url,
						content_thumb_url,
						content_image_manset_url,
						content_manset_text_status,
						content_template
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_cat IN ('.$cat.')
					AND
						content_template = '.$template.'
					AND
						content_id <> '.$_id.'
					AND
						content_time < "'.$time.'"
						'.$sql_admin.'
						'.$sql_type.'
					ORDER BY
						content_time DESC
					LIMIT 0,1';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			if($adet > 0)
			{
				$dizi['pre_id']				= $list[0]['content_id'];
				$dizi['pre_title']			= $list[0]['content_title'];
				$dizi['pre_link']			= SITELINK.$list[0]['content_url'];

				//uygulamada kullanıyoruz, web arayüzde gerek yok
				if($json == 1)
				{
					unset($dizi['pre_id'], $dizi['pre_title'], $dizi['pre_link']);
					$dizi['pre']['content_id']						= $list[0]['content_id'];
					$dizi['pre']['content_cat']						= $list[0]['content_cat'];
					$dizi['pre']['content_template']				= $list[0]['content_template'];
					$dizi['pre']['content_title_outside']			= $list[0]['content_title_outside'];
					$dizi['pre']['content_title']					= $list[0]['content_title'];
					$dizi['pre']['content_manset_text_status']		= $list[0]['content_manset_text_status'];
					$dizi['pre']['content_url']						= SITELINK.$list[0]['content_url'];
					$dizi['pre']['content_image_url']				= G_IMGLINK.$list[0]['content_image_url'];
					$dizi['pre']['content_thumb_url']				= G_IMGLINK.$list[0]['content_thumb_url'];
					$dizi['pre']['content_image_manset_url']		= null;
				}
			}

			//next linkine ihtiyaç varsa aktif ediyoruz
			/*
			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_cat,
						content_url,
						content_image_url,
						content_thumb_url,
						content_image_manset_url,
						content_manset_text_status,
						content_template
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_cat IN ('.$cat.')
					AND
						content_template = '.$template.'
					AND
						content_id <> '.$_id.'
					AND
						content_time > "'.$time.'"
						'.$sql_admin.'
					ORDER BY
						content_time ASC
					LIMIT 0,1';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			$adet = count($list);
			if($adet > 0)
			{
				$dizi['next_id']			= $list[0]['content_id'];
				$dizi['next_title']			= $list[0]['content_title'];
				$dizi['next_link']			= SITELINK.$list[0]['content_url'];

				//uygulamada kullanıyoruz, web arayüzde gerek yok
				if($json == 1)
				{
					unset($dizi['next_id'], $dizi['next_title'], $dizi['next_link']);
					$dizi['next']['content_id']						  = $list[0]['content_id'];
					$dizi['next']['content_cat']						 = $list[0]['content_cat'];
					$dizi['next']['content_template']					= $list[0]['content_template'];
					$dizi['next']['content_title_outside']				= $list[0]['content_title_outside'];
					$dizi['next']['content_title']						= $list[0]['content_title'];
					$dizi['next']['content_manset_text_status']		  = $list[0]['content_manset_text_status'];
					$dizi['next']['content_url']						 = SITELINK.$list[0]['content_url'];
					$dizi['next']['content_image_url']					= G_IMGLINK.$list[0]['content_image_url'];
					$dizi['next']['content_thumb_url']					= G_IMGLINK.$list[0]['content_thumb_url'];
					$dizi['next']['content_image_manset_url']			= null;
				}
			}
			*/
			//sonucu döndürüyoruz
			return $dizi;
		}

		public function article_pre_link($_id, $time, $author, $json = 0)
		{
			if($_SESSION[SES]['ADMIN'] <> 1)
			{
				$sql_admin = 'AND content_time < now()';
			}

			$sql = 'SELECT
						content_id,
						content_title_outside,
						content_title,
						content_url,
						content_thumb_url
					FROM
						'.T_CONTENT.'
					WHERE
						content_status = 1
					AND
						content_author = '.$author.'
					AND
						content_id <> '.$_id.'
					AND
						content_time < "'.$time.'"
						'.$sql_admin.'
					ORDER BY
						content_time DESC
					LIMIT 0,1';
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);

			$adet = count($list);
			if($adet > 0)
			{
				$dizi['pre_id']				= $list[0]['content_id'];
				$dizi['pre_title']			= $list[0]['content_title'];
				$dizi['pre_link']			= SITELINK.$list[0]['content_url'];

				//uygulamada kullanıyoruz, web arayüzde gerek yok
				if($json == 1)
				{
					unset($dizi['pre_id'], $dizi['pre_title'], $dizi['pre_link']);
					$dizi['pre']['content_id'] 					= $list[0]['content_id'];
					$dizi['pre']['content_cat'] 				= $list[0]['content_cat'];
					$dizi['pre']['content_template']			= $list[0]['content_template'];
					$dizi['pre']['content_title_outside']		= $list[0]['content_title_outside'];
					$dizi['pre']['content_title'] 				= $list[0]['content_title'];
					$dizi['pre']['content_manset_text_status'] 	= $list[0]['content_manset_text_status'];
					$dizi['pre']['content_url'] 				= SITELINK.$list[0]['content_url'];
					$dizi['pre']['content_thumb_url'] 			= G_IMGLINK.$list[0]['content_thumb_url'];
					$dizi['pre']['content_image_url']			= null;
					$dizi['pre']['content_image_manset_url'] 	= null;
				}
			}
			return $dizi;
		}

		/**
		| Çeşitli Diğer SQL içermeyen fonksiyonlar
		*/

		public function cleanText($content_text)
		{

			//raw gelen datayı entity edelim
			$content_text = htmlentities($content_text);
	// 		echo $content_text;

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

		public function url_content_inline($title, $id, $template)
		{
			global $array_content_ext;

			$url = format_url($title).'-'.$id.$array_content_ext[$template];

			return $url;
		}

		/**
		| BBCode Fonksiyonları
		*/

		public function content_with_smilar($rawtext)
		{
			// BBCode Parse function
			global $twig;

			//raw gelen datayı entity edelim
			$text = htmlentities($rawtext);

			// BBcode nasıl parse edeceğimizi belirtelim
			$find = "/\[haber=(.*?)\]/i";
			preg_match_all($find, $text, $saveMe, PREG_PATTERN_ORDER);

			//benzer listesi varmı bakalım
			$benzer_list = intval($saveMe[1][0]);
			if($benzer_list > 0)
			{
				$adet = count($saveMe[1]);
				//echo $adet;
				for($i = 0; $i < $adet; $i++)
				{
					$ti = intval($saveMe[1][$i]);
					$content_inline_benzer = self::content_inline_benzer($ti);
 					//print_pre($content_inline_benzer);

 					//verilen id ile gelen id uyuşuyor ise
 					//bunun anlamı elimizde bir data olduğudur ;)
 					if($content_inline_benzer[0]['content_id'] == $ti)
 					{
						$template = $twig->loadTemplate('page_content_block_benzer.twig');
						$content = $template->render
						(
							array
							(
								'content_inline_benzer' 	=> $content_inline_benzer,
							)
						);
						$saveMe[2][$i] = $content;
					}
					else
					{
						$saveMe[2][$i] = '';
					}
				}
				//print_pre($saveMe);
				//şimdi bu değerleri diziye yazalım
				//return preg_replace($find,$replace,$text);
				$newstr = str_replace($saveMe[0], $saveMe[2], $text);
				$newstr = html_entity_decode($newstr);
				return $newstr;
			}
			else
			{
				return $rawtext;
			}
		}

		public function content_with_smilar_amp($rawtext)
		{

			// BBCode Parse function
			global $twig;

			//raw gelen datayı entity edelim
			$text = htmlentities($rawtext);

			// BBcode nasıl parse edeceğimizi belirtelim
			$find = "/\[haber=(.*?)\]/i";
			preg_match_all($find, $text, $saveMe, PREG_PATTERN_ORDER);

			//benzer listesi varmı bakalım
			$benzer_list = intval($saveMe[1][0]);
			if($benzer_list > 0)
			{
				$adet = count($saveMe[1]);
				//echo $adet;
				for($i = 0; $i < $adet; $i++)
				{
					$ti = intval($saveMe[1][$i]);
					$content_inline_benzer = self::content_inline_benzer($ti);
 					//print_pre($content_inline_benzer);

 					//verilen id ile gelen id uyuşuyor ise
 					//bunun anlamı elimizde bir data olduğudur ;)
 					if($content_inline_benzer[0]['content_id'] == $ti)
 					{
						$template = $twig->loadTemplate('amp/page_content_block_benzer.twig');
						$content = $template->render
						(
							array
							(
								'content_inline_benzer' 	=> $content_inline_benzer,
							)
						);
						$saveMe[2][$i] = $content;
					}
					else
					{
						$saveMe[2][$i] = '';
					}
				}
				//print_pre($saveMe);
				//şimdi bu değerleri diziye yazalım
				//return preg_replace($find,$replace,$text);
				$newstr = str_replace($saveMe[0], $saveMe[2], $text);
				$newstr = html_entity_decode($newstr);
				return $newstr;
			}
			else
			{
				return $rawtext;
			}
		}

		//BBCode Parser function
		public function content_with_smilar_video($rawtext, $mobile = 0)
		{
			global $twig;

			//raw gelen datayı entity edelim
			$text = htmlentities($rawtext);

			// BBcode nasıl parse edeceğimizi belirtelim
			$find = "/\[video=(.*?)\]/i";
			preg_match_all($find, $text, $saveMe, PREG_PATTERN_ORDER);

			//benzer listesi varmı bakalım
			$benzer_list = intval($saveMe[1][0]);
			if($benzer_list > 0)
			{
				$adet = count($saveMe[1]);
				//echo $adet;
				for($i = 0; $i < $adet; $i++)
				{
					$ti = intval($saveMe[1][$i]);
					$content_video_data = self::get_super_video_json($ti);
 					//print_pre($content_video_data);

 					//verilen id ile gelen id uyuşuyor ise
 					//bunun anlamı elimizde bir data olduğudur ;)
 					if($content_video_data['content_id'] == $ti)
 					{
 						if($content_video_data)
						if($mobile == 0) $template = $twig->loadTemplate('block/block_content_video.twig');
						if($mobile == 1) $template = $twig->loadTemplate('block/block_content_video_mobile.twig');
						if($mobile == 2) $template = $twig->loadTemplate('block/block_content_video_app.twig');
						$content = $template->render
						(
							array
							(
								'content_video_data' => $content_video_data,
							)
						);
						$saveMe[2][$i] = $content;
					}
					else
					{
						$saveMe[2][$i] = '';
					}
				}
				//print_pre($saveMe);
				//şimdi bu değerleri diziye yazalım
				//return preg_replace($find,$replace,$text);
				$newstr = str_replace($saveMe[0], $saveMe[2], $text);
				$newstr = html_entity_decode($newstr);
				return $newstr;
			}
			else
			{
				return $rawtext;
			}
		}

		public function content_with_smilar_clean($rawtext)
		{
			// BBCode Kaldırma function

			//raw gelen datayı entity edelim
			$text = htmlentities($rawtext);

			// BBcode nasıl parse edeceğimizi belirtelim
			$find = "/\[haber=(.*?)\]/i";
			preg_match_all($find, $text, $saveMe, PREG_PATTERN_ORDER);

			//benzer listesi varmı bakalım
			$benzer_list = $saveMe[1][0];
			if($benzer_list <> '')
			{
				$adet = count($saveMe[1]);
				//echo $adet;
				for($i = 0; $i < $adet; $i++) $saveMe[2][$i] = '';

				$newstr = str_replace($saveMe[0], $saveMe[2], $text);
				$newstr = html_entity_decode($newstr);
				return $newstr;
			}
			else
			{
				return $rawtext;
			}
		}

		public function content_with_smilar_ids($rawtext)
		{
			// BBCode Kaldırma function

			//raw gelen datayı entity edelim
			$text = htmlentities($rawtext);

			// BBcode nasıl parse edeceğimizi belirtelim
			$find = "/\[haber=(.*?)\]/i";
			preg_match_all($find, $text, $saveMe, PREG_PATTERN_ORDER);

			//benzer listesi varmı bakalım
			$benzer_list = $saveMe[1][0];
			return $saveMe[1];
		}

		/**
		| Manşet Sıralama ile ilgili fonksiyonlar
		*/

		public function order_content_manset($type)
		{
			$type = intval($type);
			$i = time();
			$adet = count($_REQUEST['content_order']);

			foreach($_REQUEST['content_order'] as $k => $v)
			{
				$k = intval($k);
				$t = $i--;

				//form boş ise işlem yapmayalım
				if($k == 0) return false;

				$record = array('content_order' => $t);
				$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$k);
				if($rs === false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}
		}

		public function content_list_small_manset_order($type = '-1', $limit = 30)
		{
			global $array_url_sablon;
			/**
			| Daha basit bir içerik listesi çevirir
			| Yönetim panelinde Haberleri listelerken kullanıyoruz
			*/
			if($type	<> '-1')	$sql_type		= ' AND content_type = '.$type;
			if($limit > 0)	$sql_limit	= 'LIMIT 0,'.$limit;

			$sql = '
				SELECT
					content_id,
					content_title_outside,
					content_title,
					content_url,
					content_image_url,
					content_image_manset_url,
					DATE_FORMAT(content_time, "%Y.%m.%d %H.%i") AS content_time_f,
					DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date
				FROM
					'.T_CONTENT.'
				WHERE
					content_status IN (1,2,3)
					'.$sql_type.'
					'.$sql_cat.'
					'.$sql_user.'
					'.$sql_status.'
					'.$sql_time.'
					'.$sql_keyword.'
				ORDER BY
					content_order DESC
					'.$sql_limit;
//			print_pre($sql);
			$list = $this->conn->GetAll($sql);
			$adet = count($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['content_url'] 				= SITELINK.$list[$i]['content_url'];
					$list[$i]['content_image_url'] 			= G_IMGLINK.$list[$i]['content_image_url'];
					$list[$i]['content_image_manset_url'] 	= G_IMGLINK.$list[$i]['content_image_manset_url'];
				}
				return $list;
			}
			else
			{
				//hiçbir sonuç yoksa false dönelim, yönetilmesi daha kolay oluyor
				return false;
			}
		}
		/**
		| Arama Motorları
		*/

		public function is_google($_id)
		{
			$x = is_google_bot();

			if($x == true )
			{
				$sql = 'UPDATE
							'.T_CONTENT.'
						SET
							content_google_status = 1
						WHERE
							content_id = '.$_id;
				$rs = $this->conn->Execute($sql);
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}
		}

		public function content_haber_ortalama($user = 'none', $day = 7)
		{

			$time = date('Y-m-d 00:00:00',strtotime("-".$day." day"));

			if($user <> 'none') $sql_user = 'AND content_user = '.$user;

			$sql = 'SELECT
						count(content_id) as gunluk_haber,
						sum(content_view) as gunluk_okunma,
						DATE_FORMAT(content_time,"%Y-%m-%d") AS content_date_order,
						DATE_FORMAT(content_time,"%d-%m-%Y") AS content_date
					FROM
						'.T_CONTENT.','.T_VIEW.'
					WHERE
						'.T_CONTENT.'.content_id = '.T_VIEW.'.id
					AND
						content_status = 1
					AND
						content_time > "'.$time.'"
						'.$sql_user.'
					GROUP BY
						content_date_order';
			//echo $sql;
			$list = $this->conn->CacheGetAll(7200, $sql);

			return $list;
		}
	}
