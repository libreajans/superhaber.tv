<?php
	if(!defined('APP')) die('...');

	$dir	= 'cache/stats/';
	$files	= array_diff(scandir($dir), array('..', '.'));
	rsort($files);

	foreach($files as $k => $v)
	{
		$sayfa_icerik.='<tr><td><a href="'.$dir.$v.'" target="_blank" rel="noopener noreferrer">'.$v.'</a></td></tr>';
	}
?>

	<?=$alert?>

	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title"><?=$header_subtitle?></h3>
		</div>
		<div class="box-body">
			<table id="recordList_Page" class="table table-bordered table-striped">
				<tbody>
					<?=$sayfa_icerik?>
				</tbody>
			</table>
		</div>
	</div>

