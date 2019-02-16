<?php
	if(!defined('APP')) die('...');

	if($do == 'phpinfo')	$header_subtitle	= 'PHP Info';
	if($do == 'opcache')	$header_subtitle	= 'OpCache Info';
	if($do == 'memcache')	$header_subtitle	= 'MemCache Info';

	if(!empty($message['type']))
	{
		$alert = showMessageBoxS($message['text'], $message['type']);
	}

	if($do == 'opcache')
	{
		$reset_it = '<li><a href="index.php?page=acp&view=developer&do=opcache&reset=true">OpCache Resetle</a></li>';
		if($_REQUEST['reset'] == true) opcache_reset();
	}
?>

<section class="content">
	<div>
		<ol class="breadcrumb">
			<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
			<li><a href="<?=LINK_ACP?>&amp;view=developer"><i class="ion ion-document"></i> <?=$page_name?></a></li>
			<li class="active"><?=$header_subtitle?></li>
			<?=$reset_it?>
		</ol>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?=$alert?>

			<?php
				switch($do)
				{
					case 'phpinfo':
						include ACP_MODULE_PATH.$modul_name[$view].'developer.phpinfo.php';
					break;

					case 'opcache':
						include ACP_MODULE_PATH.$modul_name[$view].'developer.opcache.php';
					break;

					case 'memcache':
						include ACP_MODULE_PATH.$modul_name[$view].'developer.memcache.php';
					break;
				}
			?>
		</div>
	</div>
</section>
