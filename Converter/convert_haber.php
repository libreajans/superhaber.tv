<?php
	/**
	| Root klasör altındaki php dosyaları SADECE çağrı dosyalarıdır
	| asıl dosyayı include etmekten başka görevleri yoktur
	| asıl dosyalarımız app/ klasörü altında bulunur
	| bu sayede yazdığımız gerçek kodlar tamamen app/ klasörü altında
	| depolanmış omaktadır
	*/
	die();
	define('APP', '1');

	//uzun çalışsın
	ini_set('max_execution_time', 0);

	set_time_limit(0);

	//türkiyeye geçelim
	date_default_timezone_set('Europe/Istanbul');

	//sayfa saatini başlatıyoruz
	$starttime = microtime(true);

	//--- [+]--- Root Path Doğrulaması ---
	$_SERVER['DOCUMENT_ROOT'] = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT']);
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
	{
		$_SERVER['DOCUMENT_ROOT'] .= '/';
	}
	//--- [-]--- Root Path Doğrulaması sonu ---

	//öncel ayarlar ayrı dosyaya alındı, bu sayede
	//init dosyasındaki yapılan değişikliklerden
	//yayındaki site daha az etkileniyor
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.config.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.prefunctions.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.session.php';

	//hata bastırma şeklini belirliyoruz
	error_reporting(E_ERROR);
	//error_reporting(E_ALL);

	//cache de sayfa yoksa bu kısım işlemeye başlayacak
	//sayfayı iteleyelim
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.php';

	//dom nesnesini yüklüyoruz
	include $_SERVER['DOCUMENT_ROOT'].'Converter/Dom/simple_html_dom.php';

	function url_content_inline($title, $id, $template)
	{
		global $array_content_ext;

		$url = format_url($title).'-'.$id.$array_content_ext[$template];

		return $url;
	}

	function create_local_image($url, $filename, $width = 700)
	{
		//uzak resmi indiriyoruz
		$sonuc = curl_grab_image($url, $filename);

		$sizes = getimagesize($filename);
		//print_pre($sizes);

		if($sizes['mime'] <> "")
		{
			try
			{
				//nesnemizi oluşturuyoruz
				$im = new imagick($filename);

				//resmin genişliğini hesaplıyoruz
				$w = $im->getImageWidth();

				//resim beklediğimizden geniş ise resmi küçültüyoruz
				//değilse hiç işlem yapmıyoruz
				if($w > $width)
				{
					//düz yöntemde direk resmi küçültüyoruz
					$im->thumbnailImage($width, null, false);

					//resmi tekrar yerine yazıyoruz
					$im->writeImage($filename);
				}

				//bellek boşaltmak için daima çalıştırıyoruz
				//çünkü resmin boyutlarını öğrenirken nesneyi zaten kullandık
				$im->destroy();

				//return $fileurl;
			}
			catch(Exception $e)
			{
				//echo $e->getMessage();
			}
		}
	}

	function curl_grab_image($url, $saveto)
	{
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);

		//url yi takip et
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0');

		$raw = curl_exec($ch);
		curl_close($ch);

		$data = curl_getinfo($ch);
		if($data['http_code'] == '301' or $data['http_code'] == '302' or $data['http_code'] == '404')
		{
			//hatalı hiç bulaşma
			return false;
		}
		else
		{
			if(file_exists($saveto))
			{
				unlink($saveto);
			}
			$fp = fopen($saveto,'x');
			fwrite($fp, $raw);
			fclose($fp);

			//doğru yoldasın
			return true;
		}
	}

	function convert_time($text)
	{
		if($text <> '')
		{
			$date = DateTime::createFromFormat('d.m.Y H:i:s', $text);
			if($date <> '')
			{
				return $date->format('Y-m-d H:i:s');
			}

			$date = DateTime::createFromFormat('d.m.Y', $text);
			if($date <> '')
			{
				return $date->format('Y-m-d 00:00:00');
			}

		}

	}

	function myCleaner($text)
	{
		$text = str_replace('&#039;','\'',$text);
		$text = str_replace('"','',$text);

		return $text;
	}

	function cleanText($content_text)
	{
		//inline haberlerdeki hatalı cdn linklemeleri düzeltiliyor
		$content_text = str_replace
		(
			'http://cdn.pivol.com/cdn/15388/imgs/',
			'http://pivolcdn.trafficmanager.net/15388/imgs/',
			$content_text
		);

		require_once 'HTMLPurifier/HTMLPurifier.auto.php';
		//iframe'e izin vermek için bulduğumuz ek class ekleniyor
		require_once 'HTMLPurifier/MyIframe.php';
		$config = HTMLPurifier_Config::createDefault();

		//önce iframe'e izin verelim
		$config->set('Filter.Custom', array( new HTMLPurifier_Filter_CustomIframesSupport() ));
		//sonra diğer ayarlar
		$config->set('Core.Encoding', 'UTF-8');  		// replace with your encoding
		$config->set('Core.RemoveInvalidImg', true); 	// resim olamayacak şeyler silinsin
		$config->set('CSS.AllowedProperties', array());
		$config->set('Attr.AllowedClasses', array());
		$config->set('HTML.TidyLevel', 'heavy');

		$purifier = new HTMLPurifier($config);
		$content_text	= $purifier->purify($content_text);

		//bellik azaltımı düşüncesiyle imha edelim
		unset($purifier);

		//bölünmez boşlukları düz boşluk yapalım
		$content_text = str_replace(array('&#160;', '&nbsp;'), ' ', $content_text);

		//h değerleri b yapalım
		$content_text = str_replace(array('<h1>', '<h2>'), '<p><b>', $content_text);
		$content_text = str_replace(array('</h1>', '</h2>'), '</b></p>', $content_text);

		//div değerleri p yapalım
		$content_text = str_replace('<div', '<p', $content_text);
		$content_text = str_replace('</div>', '</p>', $content_text);

		//boş elementleri temizleyelim
		$array = array(
		'src=""',
		'<p><img src="" /></p>', '<img src="" />', '<em></em>',
		"\n","\t", '<p> </p>', '<span> </span>', '<div> </div>',
		'<div style="clear:both;width:100%;">&nbsp;</div>',
		'align="left"', 'align="center"', 'align="right"',
		'alt=""', 'lang="tr"', 'dir="ltr"', 'xml:lang="tr"',
		'<a href="">', '<p><img src="" /></p>',
		'<strong></strong>', '<strong> </strong>',
		'<b></b>', '<b> </b>', '<p></p>', '<p> </p>'
		);
		$content_text = str_replace($array, '', $content_text);
		$content_text = str_replace($array, '', $content_text);
		$content_text = str_replace($array, '', $content_text);

		//raw gelen datayı entity edelim
		$content_text = htmlentities($content_text);
		// echo $content_text;

		$content_text = str_replace('&lt;p&gt;&#160;&lt;/p&gt;', '', $content_text);

		//DİV kabul ETMEYELİM
		$content_text = str_replace('&lt;div','<p',$content_text);
		$content_text = str_replace('&lt;/div&gt;','</p>',$content_text);

		//SPAN kabul ETMEYELİM
		$content_text = str_replace('&lt;span','<p',$content_text);
		$content_text = str_replace('&lt;span &gt;','<p>',$content_text);
		$content_text = str_replace('&lt;/span&gt;','</p>',$content_text);

		//bölünmez boşlukları kabul ETMEYELİM
		$content_text = str_replace('&nbsp;',' ',$content_text);
		$content_text = str_replace('&#160;',' ',$content_text);

		//çift br -> p olsun
		$content_text = str_replace('&lt;br /&gt;&lt;br /&gt;','</p><p>',$content_text);

		//daha beter olsun, tek br de p olsun
// 		$content_text = str_replace('&lt;br /&gt;','</p><p>',$content_text);

		//daha beter olsun, tek <br> de p olsun
// 		$content_text = str_replace('&lt;br&gt;','</p><p>',$content_text);

		//oh olsun, çift p de tek p olsun
		$content_text = str_replace('&lt;p&gt;&lt;p&gt;','<p>',$content_text);
		$content_text = str_replace('&lt;p/&gt;&lt;p/&gt;','</p>',$content_text);

		//hatalı paragraf
		$content_text = str_replace('&lt;p&gt;&lt;/p&gt;','',$content_text);
		$content_text = str_replace('&lt;p&gt; &lt;/p&gt;','',$content_text);

		//hatalı paragraf başına enter koymak
		$content_text = str_replace('&lt;p&gt;&lt;br /&gt;','<p>',$content_text);

		//hatalı paragraf sonuna enter koymak
		$content_text = str_replace('&lt;br /&gt;&lt;p&gt;','</p>',$content_text);

		//hatalı paragraf başı + boşluk
		$content_text = str_replace('&lt;p&gt; ','<p>',$content_text);
		$content_text = str_replace('&lt;p&gt;&nbsp;','<p>',$content_text);

		//hatalı br + boşluk
		$content_text = str_replace('&lt;br /&gt;&nbsp;','<br />',$content_text);
		$content_text = str_replace('&lt;br /&gt; ','<br />',$content_text);

		//hatalı etiket kapatma + boşluk
		$content_text = str_replace(' &gt;','>',$content_text);

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
		$content_text = str_replace(' ',' ',$content_text);
		$content_text = str_replace(' ',' ',$content_text);
		$content_text = str_replace(' ',' ',$content_text);

		//echo '<!--'.$content_text.'-->'."\n";

		//entitiy gelen datayı geri raw edelim
		$content_text = html_entity_decode($content_text);

		return $content_text;
	}

	function convert_cat($id)
	{
		$array_convert = array(
			13	=> 100,
			6	=> 101,
			11	=> 102,
			5	=> 103,
			36	=> 104,
			7	=> 105,
			10	=> 106,
			32	=> 107,
			12	=> 108,
			8	=> 109,
			14	=> 110,
			34	=> 111,
			9	=> 112,
			35	=> 113,
		);

		$id = intval($array_convert[$id]);
		if($id == 0)
		{
			return 100;
		}
		else
		{
			return $id;
		}

	}

	function convert_haber($_id)
	{
		/**
		Haber bilgilerini uzak sunucudan çeker
		*/

		global $conn;

		//initalize
		$ch = curl_init();
		//hargi url den veri çekeceğiz
		curl_setopt($ch, CURLOPT_URL , 'http://www.superhaber.tv/services/superh.asp?nid='.$_id);
		//dönen verileri kullanacak mıyız
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//dönen header verilerini kaydedeyim mi
		curl_setopt($ch, CURLOPT_HEADER, false);
		//işlemi gerçekleştir
		$data = curl_exec($ch);
		//curl i kapat
		curl_close($ch);

		//datayı decode edip alalım
		$data = json_decode($data);

		//data yoksa aşağıya doğru ilerleme, false dön
		if($data == "")
		{
			echo $_id.' !<br/>';
			return false;
		}
		else
		{
			//print_pre($data->data);
			$v = $data->data;

			//reset
			$content_template 		= 0;

			$counter = $categoryId = $writerId = 0;
			$title = $title2 = $sefurl = $imageUrl1 = $shortText = $fullText = $direckturl = '';

			if($v->title <> '0') 		$title 		= myCleaner($v->title);
			if($v->title2 <> '0') 		$title2 	= myCleaner($v->title2);
			if($v->sefurl <> '0') 		$sefurl 	= $v->sefurl;
			if($v->imageUrl1 <> '0') 	$imageUrl1	= $v->imageUrl1;
			if($v->shortText <> '0') 	$shortText	= $v->shortText;
			if($v->fullText <> '0') 	$fullText	= $v->fullText;
			if($v->direckturl <> '0') 	$direckturl	= $v->direckturl;
			if($v->tags <> '0') 		$tags		= $v->tags;
			if($v->counter <> '0') 		$counter	= intval($v->counter);
			if($v->categoryId <> '0') 	$categoryId	= intval($v->categoryId);
			if($v->writerId <> '0') 	$writerId	= intval($v->writerId);

			if($v->itemType == 'NEWS')
			{
				//haber metnini temizleyelim
				$fullText = cleanText(cleanText($fullText));

				try
				{
					//içerik tipi haber ise
					//Satır içindeki resimleri bulup download ediyoruz
					if($fullText <> "")
					{
						$html = str_get_html($fullText);

						foreach($html->find('img') as $e) $img[] = $e->src;

						//bellek boşaltıyoruz
						$html->clear();
						unset($html);

						foreach($img as $k => $url)
						{
							//resimlerin pivol kaynaklı olanlarını belirleyip indirelim
							if(strpos_array($url, 'http://pivolcdn.trafficmanager.net/15388/imgs/') == true)
							{

								$link 				= str_replace('http://pivolcdn.trafficmanager.net/15388/imgs/','',$url);
								$filename_manuel 	= IMAGE_DIRECTORY.'/manuel/2016/'.$link;

								$im = new Imagick();
								try
								{
									$im->pingImage($filename_manuel);
								}
								catch(ImagickException $e)
								{
									//echo "image doesn't exist";
									create_local_image($url, $filename_manuel, $width = 700);
								}

								//bellek boşaltmak için daima çalıştırıyoruz
								$im->destroy();


								//sonrasında orjinal metinden eski linki yeni link ile değiştirelim
								$fullText = str_replace($url, 'assets/uploads/images/manuel/2016/'.$link, $fullText);
							}
						}
					}
				}
				catch(Exception $e)
				{
					//print_pre($e);
				}

				//yönlendirme ise tespit ediyoruz
				if($direckturl <> '')
				{
					$content_template 	= 2;

					//yönlendirme ise bu alanlar aynı olsun
					$fullText = $shortText = $title;
				}

				//makale ise tespit ediyoruz
				if($categoryId == 1 )
				{
					$content_template 	= 3;

					//makale ise, spot ile başlık aynı olsun
					$shortText = $title;
				}

			}
			else if($v->itemType == 'PHOTO_GALLERY')
			{
				$content_template = 1;
				$fullText = $shortText = $title;

				//galeri datayı parse edelim
				$adet = count($v->itemList);
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['id'] 			= intval($v->itemList[$i]->itemId);
					$list[$i]['gallery_id'] 	= intval($_id);
					$list[$i]['photo_order'] 	= intval($adet-$i);
					$list[$i]['photo_image'] 	= $v->itemList[$i]->imageUrl;
					$list[$i]['photo_text'] 	= $v->itemList[$i]->title;
				}
				//print_pre($list);
			}

			//etiket yoksa, haber diye etiket atayalım
			if($tags == '') $tags = 'haber';

			if($imageUrl1 <> '')
			{
				//resetlerimiz
				unset($content_image, $content_image_url, $content_thumb_url, $tdizi);

				$tdizi = parse_url($imageUrl1);
				if($tdizi['host'] <> '')
				{
					$content_image		= '';
					$content_image_url	= '';
					$content_thumb_url	= '';
				}
				else
				{
					$content_image		= $imageUrl1;
					$content_image_url = 'content/2016/'.$imageUrl1;
					$content_thumb_url = 'thumbs/2016/'.$imageUrl1;
				}
			}

			if($sefurl <> '')
			{
				//resetlerimiz
				unset($content_title_url);

				$tdizi = parse_url($sefurl);
				if($tdizi['host'] <> '')
				{
					$content_title_url		= format_url(trim($title));
				}
				else
				{
					$content_title_url		= format_url(trim($sefurl));
				}
			}

			//başka işlem yoksa url ye dönüştürelim
			$content_url = url_content_inline($content_title_url, intval($v->itemId), intval($content_template));

			$record = array(
				'content_id'				=> intval($v->itemId),
				'content_user'				=> 1,
				'content_author'			=> intval($writerId),
				'content_text'				=> $fullText,		//birkaç defa temizlenmiş metin
				'content_desc'				=> strip_tags($shortText),
				'content_title_outside'		=> strip_tags($title2),
				'content_title'				=> strip_tags($title),
				'content_title_seo'			=> strip_tags($title),
				'content_metadesc'			=> strip_tags($title),
				'content_title_url'			=> strip_tags($content_title_url),
				'content_tags'				=> strip_tags($tags),
				'content_redirect'			=> strip_tags($direckturl),
				'content_image'				=> strip_tags($content_image),
				'content_image_dir'			=> '2016/',
				'content_status'			=> 1,
				'content_template'			=> $content_template,
				'content_comment_status'	=> 1,
				'content_manset_text_status'=> 1,
				'content_cat_show_status'	=> 1,
				'content_ads_status' 		=> 1,
				'content_type'				=> 0,
				'content_cat'				=> convert_cat($categoryId),
				'content_time'				=> convert_time($v->publishDate),
				'content_url' 				=> $content_url,
				'content_image_url' 		=> $content_image_url,
				'content_thumb_url' 		=> $content_thumb_url,
			);
			//print_pre($record);

			//önce sil
			$rs = $conn->Execute('DELETE FROM '.T_CONTENT.' WHERE content_id = '.$_id);

			//sonra yenisini ekle
			$rs = $conn->AutoExecute(T_CONTENT, $record, 'INSERT');

			//önce sil
			$rs = $conn->Execute('DELETE FROM '.T_VIEW.' WHERE id = '.$_id);

			//içerik view tablosuna ekleniyor
			$record = array(
				'id' 					=> $_id,
				'content_view' 			=> $counter,
				'content_view_real' 	=> $counter
			);
			$rs = $conn->AutoExecute(T_VIEW, $record, 'INSERT');

			//Haber Resmini alalım
			if($imageUrl1 <> '')
			{
				//dosyamızın adını ve konumunu belirliyoruz
				$url 				= 'http://pivolcdn.trafficmanager.net/15388/imgs/'.$imageUrl1;
				$filename_content 	= IMAGE_DIRECTORY.'/content/2016/'.$imageUrl1;
				$filename_thumbs 	= IMAGE_DIRECTORY.'/thumbs/2016/'.$imageUrl1;

				$im = new Imagick();
				try
				{
					$im->pingImage($filename_content);
				}
				catch(ImagickException $e)
				{
					//echo "image doesn't exist";
					create_local_image($url, $filename_content, $width = 700);
				}

				try
				{
					$im->pingImage($filename_thumbs);
				}
				catch(ImagickException $e)
				{
					//echo "image doesn't exist";
					create_local_image($url, $filename_thumbs, $width = 350);
				}

				//bellek boşaltmak için daima çalıştırıyoruz
				$im->destroy();
			}

			echo $_id.' .<br/>';

			//linki bulunmuş galeri resimlerini yeni klasörüne taşıyalım
			//Taşırken büyük resimleri daha küçük hale getirelim
			if($content_template == 1)
			{

				//adet yoksa hiç bulaşmayalım
				if($adet > 0)
				{
					//ilk aşamada galeri resimlerini veritabanına kaydediyoruz
					for($i = 0; $i < $adet; $i++)
					{
						//önce sil
						$rs = $conn->Execute('DELETE FROM '.T_GALLERY_IMAGES.' WHERE id = '.$list[$i]['id']);

						//içerik view tablosuna ekleniyor
						$record = array(
							'id' 					=> $list[$i]['id'],
							'gallery_id' 			=> $list[$i]['gallery_id'],
							'photo_order' 			=> $list[$i]['photo_order'],
							'photo_image' 			=> $list[$i]['photo_image'],
							'photo_text' 			=> html_entity_decode($list[$i]['photo_text'], ENT_QUOTES | ENT_HTML5, 'UTF-8')
						);
						//print_pre($record);
						$rs = $conn->AutoExecute(T_GALLERY_IMAGES, $record, 'INSERT');

					}


					//nesnemizi yükleyelim
					$im = new Imagick();

					//ikinci aşamada galeri resimlerini fiili olarak oluşturuyoruz
					for($i = 0; $i < $adet; $i++)
					{
						//dosyamızın adını ve konumunu belirliyoruz
						$url 		= 'http://pivolcdn.trafficmanager.net/15388/imgs/'.$list[$i]['photo_image'];
						$filename 	= IMAGE_DIRECTORY.'/gallery/2016/'.$list[$i]['photo_image'];

						try
						{
							$im->pingImage($filename);
						}
						catch(ImagickException $e)
						{
							//echo "image doesn't exist";
							create_local_image($url, $filename, $width = 700);
						}
					}

					//bellek boşaltmak için daima çalıştırıyoruz
					$im->destroy();
				}
			}

			//hafıza kullanımızı azaltalım
			gc_collect_cycles();
		}
	}

	//yönlendirme
	//convert_haber(33125);

	//haber
	//convert_haber(33243);

	//makale
 	//convert_haber(30082);

	//inline resim
	//convert_haber(33316);

	//galeri
	//convert_haber(33354);

	if($_REQUEST['submited'] == 1)
	{
		$start = intval($_REQUEST['start']);
		$limit = intval($_REQUEST['limit']);
		//echo $start;
		//echo $limit;

		for($i = $start; $i > ($start-$limit); $i--)
		{
			//echo $start;
			convert_haber($i);
		}
	}

	if($_REQUEST['convert_gallery'] == 'true')
	{
		$sql = 'SELECT content_id FROM '.T_CONTENT.' WHERE content_template = 1';
		$list = $conn->GetAll($sql);

		foreach($list as $k => $v)
		{
			convert_haber($v['content_id']);
			//echo $v['content_id'].'<br/>';
		}
	}


	?>
	<form name="form1" action="convert_haber.php" method="get">
		<input type="hidden" name="submited" value="1">
		<input required type="number" name="start" value="<?=$start-$limit?>" placeholder="Başlangıç Sayısı"/>
		<input required type="number" name="limit" value="<?=$limit?>" placeholder="Kaç Adet İçerik Dönüştürülsün"/>
		<input type="submit" value="işleme başla"/>
	</form>
	<?php
		echo '<hr>';
		echo debug_min();
	?>
