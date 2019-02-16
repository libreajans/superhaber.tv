<?php
	if(!defined('APP')) die('...');

	if($do == 'add')
	{
		$header_subtitle	= 'Güncel İstatistik';
	}

	if($do == 'list')
	{
		$header_subtitle	= 'İstatistik Arşivi';
	}

	if(!empty($message['type']))
	{
		$alert = showMessageBoxS($message['text'], $message['type']);
	}
?>

<section class="content">
	<div>
		<ol class="breadcrumb">
			<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
			<li><a href="<?=LINK_ACP?>&amp;view=stats&amp;do=list"><i class="ion ion-stats-bars"></i> <?=$page_name?></a></li>
			<li class="active"><?=$header_subtitle?></li>
		</ol>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<?=$alert?>
			<?php
				switch($do)
				{
					case 'add':
						include ACP_MODULE_PATH.$modul_name[$view].'stats.add.php';
					break;

					case 'list':
						include ACP_MODULE_PATH.$modul_name[$view].'stats.list.php';
					break;
				}
			?>
		</div>
	</div>
</section>
