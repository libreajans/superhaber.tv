<?php if(!defined('APP')) die('...');

	/**
	|--------------------------------------------------------------
	|
	| Geçerli talep tipleri ve talep şekilleri
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=manset
	|
	| Uygulama için ayarlanmış miktardaki manşet ve diğer dataları döndürür
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=kategori&id={NUMBER}&pg=1
	|
	| Belirtilen kategori türüne ait içerikleri döndürür,
	| Kabul edilen kategoriler şunlardır
	| (dizi değerlerimizdeki $array_cat_name ile aynıdır)
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=galeri&pg=1
	|
	| Galeri içerik türüne ait dataları döndürür
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=video&pg=1
	|
	| Video içerik türüne ait dataları döndürür

	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=icerik&id={NUMBER}
	|
	| Belirtilen içerike ait bilgileri döndürür
	| Data yoksa boş döner
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=arama&search={VARCHAR}
	|
	| Tüm data içinde belirtilen kelimeyi arar ve bulunan en fazla 100 sonucu görüntüler
	| Dönen sonuçların başlık alanlarında aranan kelime olmaması canınızı sıkmasın, çünkü
	| Arama yapılan alanlar şunlardır
	| - İç Başlık, Dış Başlık, Spot, Etiket, UrL, Seo Değerler
	|
	| Dikkat
	| Arama fonksiyonunun verimli çalışabilmesi için haber metin alanlarında arama yapılmaz
	| Aynı şekilde galerilerin metin alanlarında da arama yapılmaz
	|
	| Aranan kelime yoksa veya aranan kelime 3 harften az karakter içeriyorsa
	| false döner
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=yorumEkle&id={CONTENT_ID/Number}&author={AUTHOR/Varchar}&comment={COMMENT/Varchar}
	|
	| Belirtilen içerike yorum eklemeye yarar; zorunlu alanlar şunlardır
	|	id
	|	author
	|	email
	|	comment
	|
	| Kayıt kabul edildiyse True, hatalıysa false döner
	| Dikkat: True dönmesi ilgili yorumun sitede yayınlanacağı anlamına gelmez
	| Yorumlar, editörler onayladıktan sonra sitede görünür olacaktır
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=yorumListe&id={CONTENT_ID/Number}
	|
	| İçeriğe ait yorumları listeler
	|
	|--------------------------------------------------------------
	|
	| index.php?page=service&secure=MY_SECURITY_KEY&type=yazarListe
	|
	| Yazar sayfası gibi çalışıp pazarları listeler
	|
	|--------------------------------------------------------------
	*/

	define('serviceApiKey', 'MY_SECURITY_KEY');

	if(RC_Application == 0) die('Service Error');

	//init çağrısından önce kod olmaması tercihimdir
	$type		= htmlspecialchars($_REQUEST["type"]);
	$secure		= htmlspecialchars($_REQUEST["secure"]);
	//arama
	$keyword	= myReq('search', 1);

	//yorum kayıt için gerekli olanlar
	$author		= htmlspecialchars($_REQUEST["author"]);
	$email		= htmlspecialchars($_REQUEST["email"]);
	$comment	= htmlspecialchars($_REQUEST["comment"]);

	if($secure <> serviceApiKey)
	{
		die("Anahtar Hatali");
	}

	//index veya tüm manşetler
	if($type == "manset")
	{
		unset($data);

		$index_manset_main = $_content->content_list_manset
		(
			$limit		= $config['app_manset_main'],
			$type		= '1',
			$template	= 'none',
			$cat		= 'none',
			$order		= 'order',
			$exclude 	= 'none',
			$json 		= 1
		);
		$index_manset_sur = $_content->content_list_manset
		(
			$limit		= $config['app_manset_sur'],
			$type		= '2',
			$template	= 'none',
			$cat		= 'none',
			$order		= 'order',
			$exclude 	= 'none',
			$json 		= 1
		);
		$index_manset_sondakika = $_content->content_list_manset
		(
			$limit		= $config['app_manset_sondakika'],
			$type		= '3',
			$template	= 'none',
			$cat		= 'none',
			$order		= 'order',
			$exclude 	= 'none',
			$json 		= 1
		);

		$index_block_galeri = $_content->content_list_manset
		(
			$limit		= $config['app_block_galeri'],
			$type		= 'none',
			$template	= '1',
			$cat		= 'none',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 1
		);

		$index_block_video = $_content->content_list_manset
		(
			$limit		= $config['app_block_video'],
			$type		= 'none',
			$template	= '4',
			$cat		= 'none',
			$order		= 'time',
			$exclude 	= 'none',
			$json 		= 1
		);

		$author_list = $_author->authors_list_detailed($type = 'index', $json = 1);

		$index_manset_alt_bir = $_content->content_list_manset
		(
			$limit		= $config['app_manset_alt_bir'],
			$type		= '4',
			$template	= 'none',
			$cat		= 'none',
			$order		= 'order',
			$exclude 	= 'none',
			$json 		= 1
		);
		$index_manset_alt_iki = $_content->content_list_manset
		(
			$limit		= $config['app_manset_alt_iki'],
			$type		= '5',
			$template	= 'none',
			$cat		= 'none',
			$order		= 'order',
			$exclude 	= 'none',
			$json 		= 1
		);

		$tmp_array = array_merge($index_manset_main, $index_manset_sur, $index_manset_sondakika, $index_manset_alt_bir, $index_manset_alt_iki, $index_block_galeri, $index_block_video);
		$exclude_list = '0';
		foreach($tmp_array as $k => $v) $exclude_list.=','.$v['content_id'];

		$index_block_roportaj = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '114',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);

		$index_block_gundem = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '100',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);

		$index_block_spor = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '101',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);

		$index_block_magazin = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '112',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);

		$index_block_dunya = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '104',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);

		$index_block_teknoloji = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '105',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);

		$index_block_yasam = $_content->content_list_manset
		(
			$limit		= $config['app_block_kategori'],
			$type		= 'none',
			$template	= '0',
			$cat		= '102',
			$order		= 'time',
			$exclude 	= $exclude_list,
			$json 		= 1
		);


		$data["data"] = array
		(
 			'index_block_authors' 		=> $author_list,
 			'index_manset_main' 		=> $index_manset_main,
 			'index_manset_sur' 			=> $index_manset_sur,
 			'index_manset_sondakika' 	=> $index_manset_sondakika,
 			'index_manset_alt_bir'		=> $index_manset_alt_bir,
 			'index_manset_alt_iki'		=> $index_manset_alt_iki,
 			'index_block_galeri' 		=> $index_block_galeri,
 			'index_block_video' 		=> $index_block_video,
 			'index_block_kategori'		=> array_merge($index_block_roportaj,
												$index_block_gundem,$index_block_spor,$index_block_magazin,
												$index_block_dunya,$index_block_teknoloji,$index_block_yasam
										),
		);
	}

	//belirtilen kategoriye ait içerikleri döndürür
	if($type == "kategori" && $_id > 99)
	{
		unset($data);

		if($_pg == 0) $_pg = 1;

		$list	= $_content->content_list_cat($_pg, $config['app_limit_cat'], $_id);
		$total	= $_content->content_list_cat_pages($config['app_limit_cat'], $_id);
		$data = array
		(
			'page_current' 		=> $_pg,
			'page_remaining' 	=> $total-$_pg,
			'page_total' 		=> $total,
			'content_list' 		=> $list,
		);
	}

	//galeri içerik türüne ait dataları döndürür
	if($type == "galeri")
	{
		unset($data);

		if($_pg == 0) $_pg = 1;

		$list	= $_content->content_list_gallery
		(
			$xtype		= 'none',
			$xtemplate	= '1',
			$xlimit		= $config['app_limit_gallery'],
			$xpage		= $_pg
		);
		$total	= $_content->content_list_gallery_pages
		(
			$xtype		= 'none',
			$xtemplate	= '1',
			$xlimit		= $config['app_limit_gallery']
		);
		$data = array
		(
			'page_current' 		=> $_pg,
			'page_remaining' 	=> $total-$_pg,
			'page_total' 		=> $total,
			'content_list' 		=> $list,
		);
	}

	//video içerik türüne ait dataları döndürür
	if($type == "video")
	{
		unset($data);

		if($_pg == 0) $_pg = 1;

		$list	= $_content->content_list_gallery
		(
			$xtype		= 'none',
			$xtemplate	= '4',
			$xlimit		= $config['app_limit_gallery'],
			$xpage		= $_pg
		);
		$total	= $_content->content_list_gallery_pages
		(
			$xtype		= 'none',
			$xtemplate	= '4',
			$xlimit		= $config['app_limit_gallery']
		);
		$data = array
		(
			'page_current' 		=> $_pg,
			'page_remaining' 	=> $total-$_pg,
			'page_total' 		=> $total,
			'content_list' 		=> $list,
		);
	}

	if($type == "arama")
	{
		unset($data);
		$data = $_content->content_list_search($keyword, $limit = $config['limit_100'], $json = 1);
	}

	if($type == "yorumEkle")
	{
		unset($data);
		$data = $_comment->comment_add_app($_id, $author, $comment);
	}

	if($type == "yorumListe" && $_id > 0)
	{
		unset($data);
		$data = $_comment->comment_list_content($_id, $json = 1);
	}

	if($type == "yazarListe")
	{
		unset($data);
		$data = $_author->authors_list_detailed($type = 'page', $json = 1);
	}


	//içerik ile ilgili detay bilgileri getiriyoruz
	if($type == "icerik" && $_id > 0)
	{
		unset($data);
		//okunma sayısın artıralım
		$_content->content_view($_id);

		//sonrasında, datayı servis edelim
		$data_detail = $_content->content_detail($_id, $publish = 1, $json = 1);

		$link = $_content->content_detay_links(
			$_id,
			$data_detail[0]['content_time'],
			$data_detail[0]['content_cat'],
			$data_detail[0]['content_template'],
			$data_detail[0]['content_type'],
			$json = 1
		);

		$gallery_data = null;
		if($data_detail[0]["content_template"] == 1)
		{
			$gallery_data = $_gallery->get_gallery_images_list($_id, $_content->get_image_dir($_id), $json = 1);
		}

		$author_data = null;
		if($data_detail[0]["content_template"] == 3)
		{
			$author_data 	= $_author->author_list($data_detail[0]['content_author'], $json = 1);
			$content_data 	= $_content->content_list_author($author_data[0]['author_keyword'], $limit = 100, $json = 1);
			$link 			= $_content->article_pre_link($data_detail[0]['content_id'], $data_detail[0]['content_time'], $data_detail[0]['content_author'], $json = 1);
		}

		$smilar_data = null;
		if($data_detail[0]["content_template"] == 0)
		{
			$smilar_data = $_content->content_list_benzer(
				$cat = $data_detail[0]['content_cat'],
				$exc = $data_detail[0]['content_id'],
				$limit = 4,
				$template = 0,
				$json = 1
			);
		}

		if($data_detail[0]["content_template"] == 1)
		{
			$smilar_data = $_content->content_list_most_view($limit = 4, $type = 'galeri', $cat = 'none', $json = 1);
		}

		if($data_detail[0]["content_template"] == 4)
		{
			$smilar_data = $_content->content_list_most_view($limit = 4, $type = 'video', $cat = 'none', $json = 1);
		}

		$data = array
		(
			'data'				=> $data_detail[0],
			'link'				=> $link,
			'gallery'			=> $gallery_data,
			'author'			=> $author_data[0],
			'author_content'	=> $content_data,
			'smilar'			=> $smilar_data,
		);
	}

	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Content-type: application/json');
	$data = json_encode($data) ;
	echo $data;
