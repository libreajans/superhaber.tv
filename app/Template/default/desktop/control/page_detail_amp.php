<?php if(!defined('APP')) die('...');

	include $_SERVER['DOCUMENT_ROOT'].'vendor/autoload.php';

	use Lullabot\AMP\AMP;
	use Lullabot\AMP\Validate\Scope;

	//data değerini boşaltalım
	unset($data);

	//sonrasında, datayı servis edelim
	$data_detail = $_content->content_detail($_id, $publish = 1, $json = 0);

	//içerik türü haber ise
	if($data_detail[0]["content_template"] == 0)
	{
		$smilar_data = $_content->content_list_benzer(
			$cat = $data_detail[0]['content_cat'],
			$exc = $data_detail[0]['content_id'],
			$limit = 3,
			$template = 0,
			$json = 0
		);

		//yönetici değilse
		//yazı pasif ise
		//ana sayfaya dönsün
		if($_SESSION[SES]['ADMIN'] <> 1 && $data_detail[0]['content_status'] <> 1)
		{
			header("HTTP/1.1 404 Not Found");
			header('Location:'.LINK_404);
			exit();
		}
	}

	//içerik türü galeri ise
	if($data_detail[0]["content_template"] == 1)
	{

		$smilar_data = $_content->content_list_benzer(
			$cat = 'none',
			$exc = $data_detail[0]['content_id'],
			$limit = 3,
			$template = 1,
			$json = 0
		);

		$gallery_data	= $_gallery->get_gallery_images_list($_id, $data_detail[0]['content_image_dir']);

		//yönetici değilse
		//yazı pasif ise
		//ana sayfaya dönsün
		if($_SESSION[SES]['ADMIN'] <> 1 && $data_detail[0]['content_status'] <> 1)
		{
			header("HTTP/1.1 404 Not Found");
			header('Location:'.LINK_404);
			exit();
		}
	}

	//içerik türü makale ise
	if($data_detail[0]["content_template"] == 3)
	{
		$author_info 	= $_author->author_page($_key = "", $data_detail[0]['content_author']);
		$smilar_data 	= $_content->content_list_author($author_info['author_keyword'], $limit = 5, $json = 0);

		//yönetici değilse
		//yazı pasif ise veya yazar pasifse
		//ana sayfaya dönsün
		if($_SESSION[SES]['ADMIN'] <> 1 && ($data_detail[0]['content_status'] <> 1 OR $author_info['author_status'] <> 1))
		{
			header("HTTP/1.1 404 Not Found");
			header('Location:'.LINK_404);
			exit();
		}

	}

	//AMP sitedeki linkler de AMP içeriklere gidecek şekilde dönüştürülüyor
	$adet = count($smilar_data);
	for($i = 0; $i < $adet; $i++)
	{
		$smilar_data[$i]['content_url'] = $smilar_data[$i]['content_url'].'-amp';
	}

 	//amp uyumlu kutu haberler
 	$data_detail[0]['content_text'] = $_content->content_with_smilar_amp($data_detail[0]['content_text']);

	//üstüne videoları parse ediyoruz
	$data_detail[0]['content_text'] = $_content->content_with_smilar_video($data_detail[0]['content_text'], $mobile = 1);

	// WARNING SuperCache aktif değilse, amp sayfalar aşırı PHP ve Apache gücü tüketir

	$amp = new AMP();

	// Load up the HTML into the AMP object
	// Note that we only support UTF-8 or ASCII string input and output.
	$amp->loadHtml($data_detail[0]['content_text']);

	// Convert to AMP HTML and store output in a variable
	$data_detail[0]['content_text'] = @$amp->convertToAmpHtml();

	//post data gönderebilmek için https diye göstermemiz gerekiyor
	//yoksa data gitmiyor
	$url = SITELINK.'pageview/'.$_id;
    $data_detail[0]['content_url_https'] = str_replace('http','https',$url);

 	$data = array
	(
		'content'			=> $data_detail[0],
		'gallery'			=> $gallery_data,
		'author'			=> $author_info,
		'smilar'			=> $smilar_data,
		'gallery_data_adet' => count($gallery_data),
	);
 	//print_pre($data);

	$template = $twig->loadTemplate('amp/page_detail_amp.twig');
	$content = $template->render($data);
