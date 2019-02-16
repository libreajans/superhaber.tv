<?php if(!defined('APP')) die('...');

	if($sayfaadi == 'index')
	{
		//$footer_block_seo = $_content->content_list_seo($config['footer_block_seo']);
		$index_footer_main = $_content->content_list_manset
		(
				$limit		= $config['desktop_block_seo'],
				$type		= '7',
				$template	= 'none',
				$cat		= 'none',
				$order		= 'order',
				$exclude 	= 'none',
				$json 		= 0
		);
	}

	$template = $twig->loadTemplate('site_footer.twig');
	$footer = $template->render
	(
		array
		(
			'footer_block_seo' 	=> $index_footer_main,
			'site_canonical'	=> $site_canonical,
			'year'				=> date('Y'),
		)
	);
	//bağlantıyı kapatalım
	$conn->Close();
