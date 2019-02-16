<?php
	if(!defined('APP')) die('...');

	#----------------------------------------------------------------------------
	$total['comment']	= $_stats->get_count('comment_id', T_COMMENTS);

	#----------------------------------------------------------------------------
	$total_content['toplam']				= $_stats->get_count('content_id', T_CONTENT, 'content_status IN (1,2,3)');
	$total_content['aktif']					= $_stats->get_count('content_id', T_CONTENT, 'content_status = 1');
	$total_content['pasif']					= $_stats->get_count('content_id', T_CONTENT, 'content_status = 3');
	$total_content['taslak']				= $_stats->get_count('content_id', T_CONTENT, 'content_status = 2');

	foreach($array_cat_name as $k => $v)
	{
		$cat_content[$k]['toplam']			= $_stats->get_count('content_id', T_CONTENT, '(content_cat = '.$k.' OR content_cat2 = '.$k.') AND content_status IN (1,2,3)');
		$cat_content[$k]['aktif']			= $_stats->get_count('content_id', T_CONTENT, '(content_cat = '.$k.' OR content_cat2 = '.$k.') AND content_status = 1');
		$cat_content[$k]['pasif']			= $_stats->get_count('content_id', T_CONTENT, '(content_cat = '.$k.' OR content_cat2 = '.$k.') AND content_status = 3');
		$cat_content[$k]['taslak']			= $_stats->get_count('content_id', T_CONTENT, '(content_cat = '.$k.' OR content_cat2 = '.$k.') AND content_status = 2');
	}

	#----------------------------------------------------------------------------
	$read_total['view']						= $_stats->get_sum('t2.content_view', T_CONTENT.' as t1, '.T_VIEW.' as t2', 't1.content_id = t2.id AND t1.content_status = 1');
	$read_total['real_view']				= $_stats->get_sum('t2.content_view_real', T_CONTENT.' as t1, '.T_VIEW.' as t2', 't1.content_id = t2.id AND content_status = 1');

	foreach($array_cat_name as $k => $v)
	{
		$read_total_cat[$k]['view']			= $_stats->get_sum('t2.content_view', T_CONTENT.' as t1, '.T_VIEW.' as t2', 't1.content_id = t2.id AND (content_cat = '.$k.' OR content_cat2 = '.$k.') AND content_status = 1');
		$read_total_cat[$k]['real_view']	= $_stats->get_sum('t2.content_view_real', T_CONTENT.' as t1, '.T_VIEW.' as t2', 't1.content_id = t2.id AND (content_cat = '.$k.' OR content_cat2 = '.$k.') AND content_status = 1');
	}

	#----------------------------------------------------------------------------

	$ortalama['view'] 						= $read_total['view']/$total_content['aktif'];
	$ortalama['real_view'] 					= $read_total['real_view']/$total_content['aktif'];

	foreach($array_cat_name as $k => $v)
	{
		$ortalama_cat[$k]['view']			= $read_total_cat[$k]['view'] / $cat_content[$k]['aktif'];
		$ortalama_cat[$k]['real_view']		= $read_total_cat[$k]['real_view'] / $cat_content[$k]['aktif'];
	}

	//temaya basıyoruz
	$template = $twig->loadTemplate('page_stats.twig');
	$data = $template->render
	(
		array
		(
			'total_content'				=> $total_content,
			'total'						=> $total,
			'cat_content'				=> $cat_content,
			'read_total'				=> $read_total,
			'read_total_cat'			=> $read_total_cat,
			'ortalama'					=> $ortalama,
			'ortalama_cat'				=> $ortalama_cat,
			'data_time'					=> date('y-m-d H:i:s'),
			'array_content_status_bar'	=> $array_content_status_bar,
		)
	);
	echo $data;

	$data_time = date("Y-m-d H:i:s");
	$url_local = 'cache/stats/'.$data_time.'.html';

	$header_data = '
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>İstatistik Sonuçları | '.$data_time.'</title>

			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'Me/font-awesome-4.5.0/css/font-awesome.min.css">
			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'Me/ionicons-2.0.1/css/ionicons.min.css">
			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'dist/css/skins/skin-blue-light.min.css">
			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'dist/css/skins/skin-blue.min.css">
			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'dist/css/AdminLTE.min.css">
			<!-- Tüm CSSlerin sonunda, çünkü tüm csslere müdahale edebilir -->
			<link rel="stylesheet" href="'.G_VENDOR_ADMINLTE.'dist/css/AdminLTE.me.css">

			<script src="'.G_VENDOR_ADMINLTE.'plugins/jQuery/jQuery-2.1.4.min.js"></script>
			<script src="'.G_VENDOR_ADMINLTE.'bootstrap/js/bootstrap.min.js"></script>
			<script src="'.G_VENDOR_ADMINLTE.'plugins/slimScroll/jquery.slimscroll.min.js"></script>
			<script src="'.G_VENDOR_ADMINLTE.'plugins/fastclick/fastclick.min.js"></script>
			<script src="'.G_VENDOR_ADMINLTE.'dist/js/app.min.js"></script>

		</head>
	';

	//istatistik klasörünü oluştur ve yetki ver
	mkdir('cache/stats/', 0755);
	chmod('cache/stats/', 0755);

	//dosyayı istatistik klasörüne ekle
	file_put_contents($url_local, $header_data.$data);
