<?php if(!defined('APP')) die('...');

	$site_title			= $array_page_name[107] .' | '. $L['pIndex_Company'];
	$content_metatitle 	= $array_page_name[107] .' | '. $L['pIndex_Company'];
	$content_metadesc 	= $array_page_name[107] .' | '. $L['pIndex_Company'];
	$content_metatags	= $array_page_name[107] .' | '. $L['pIndex_Company'];
	$site_canonical 	= $array_page_url[107];
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	//1. set
	$widget_manset	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'mansetler/30"></iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';
	$widget_gundem	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'gundem/30"></iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';
	$widget_spor		= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'spor/30"></iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';

	//2. set
	$widget_magazin	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'magazin/30"> </iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';
	$widget_ekonomi	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'ekonomi/30"> </iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';
	$widget_seyahat	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'seyahat/30"> </iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';

	//3. set
	$widget_tv	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'tv/30"></iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';
	$widget_teknoloji	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"><h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'teknoloji/30"></iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';
	$widget_roportaj	= '<!-- BAŞLA: Superhaber.tv Sitene Ekle --><div style="overflow: hidden; float: left; width: 300px; border: solid 1px #ccc; background: #fff; border-radius: 3px; box-shadow: 1px 1px 3px #ccc;"> <h1 style="padding: 8px; text-align: center; background: #b40000; height: 35px; border-bottom: solid 1px #ccc;"> <a href="'.LINK_INDEX.'" target="_blank" rel="noopener noreferrer"> <img src="'.G_IMGLINK_APP.'logo.png" width="108" height="20" alt="Superhaber.tv"> </a></h1> <iframe frameborder="0" width="300" height="380" src="'.LINK_WIDGET.'roportaj/30"></iframe> <div style="float: left; width:100%;text-align: center; height: 20px; font-size: 11px; border-top: solid 1px #ccc; color: #666; padding:10px 0 0 0;"> <a href="'.LINK_INDEX.'">En Son Haberler</a> &nbsp;|&nbsp; <a href="'.LINK_INDEX.'gundem">Gündem Haberleri</a> &nbsp;|&nbsp; <a href="'.LINK_SITENE_EKLE.'">Sitene Ekle</a></div></div><!-- BİTİŞ: Superhaber.tv Sitene Ekle -->';

	$template = $twig->loadTemplate('page_sitene_ekle.twig');
	$content = $template->render
	(
		array
		(
			'site_canonical' 			=> $site_canonical,
			//1. set
			'code_manset'				=> $widget_manset,
			'code_gundem'				=> $widget_gundem,
			'code_spor'					=> $widget_spor,
			//2. set
			'code_magazin'				=> $widget_magazin,
			'code_ekonomi'				=> $widget_ekonomi,
			'code_seyahat'				=> $widget_seyahat,
			//3. set
			'code_tv'					=> $widget_tv,
			'code_teknoloji'			=> $widget_teknoloji,
			'code_roportaj'				=> $widget_roportaj,
		)
	);