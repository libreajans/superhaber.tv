<?php if(!defined('APP')) die('...');

	if($sayfaadi == 'index')
	{
		$footer_block_seo = $_content->content_list_seo($config['footer_block_seo']);
	}

	$template = $twig->loadTemplate('site_footer.twig');
	$footer = $template->render
	(
		array
		(
			'footer_block_seo' 	=> $footer_block_seo,
			'site_canonical'	=> $site_canonical,
			'year'				=> date('Y'),
		)
	);
	//bağlantıyı kapatalım
	$conn->Close();
