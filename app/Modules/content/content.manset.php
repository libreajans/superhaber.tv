<?php if(!defined('APP')) die('...');
 	//print_pre($_REQUEST);

	$type = myReq('type', 0);
	if($type == 1) $limit = $config['desktop_manset_main'];
	if($type == 2) $limit = $config['desktop_manset_sur'];
	if($type == 3) $limit = $config['desktop_manset_sondakika'];

	if($type == 4) $limit = $config['desktop_manset_alt_bir'];
	if($type == 5) $limit = $config['desktop_manset_alt_iki'];

	if($type > 0) $list = $_content->content_list_small_manset_order($type, ($limit+15));
	$adet = count($list);
?>

	<script src="<?=G_VENDOR_JQUERY?>jUi/jquery-ui.js"></script>

	<style>
		#sortable1
		{
			margin-top: 20px !important;
			margin-right: 10px;
			width: 100%;
			min-height: 20px;
			list-style-type: none;
			padding: 5px 0 0 0;
			float: left;
		}

		#sortable1 li
		{
			border:1px solid #eee;
			background-color:white;
			margin: 0 5px 5px 15px;
			padding: 10px;
			font-size: 1.2em;
			width: auto;
			margin-left:0px;
		}
	</style>

	<script>
		$(function() { $( "#sortable1" ).sortable({connectWith: ".connectedSortable"}).disableSelection(); });
	</script>

	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="manset"/>
		<input type="hidden" name="type" value="<?=$type?>"/>
		<a class="btn btn-primary" href="<?=LINK_ACP?>&amp;view=content&amp;do=manset&amp;type=1"><?=$array_content_type[1]?></a>
		<a class="btn btn-primary" href="<?=LINK_ACP?>&amp;view=content&amp;do=manset&amp;type=2"><?=$array_content_type[2]?></a>
		<a class="btn btn-primary" href="<?=LINK_ACP?>&amp;view=content&amp;do=manset&amp;type=3"><?=$array_content_type[3]?></a>
		&bull;
		<a class="btn btn-primary" href="<?=LINK_ACP?>&amp;view=content&amp;do=manset&amp;type=4"><?=$array_content_type[4]?></a>
		<a class="btn btn-primary" href="<?=LINK_ACP?>&amp;view=content&amp;do=manset&amp;type=5"><?=$array_content_type[5]?></a>

		<?php if($type > 0) : ?>
			<button style="float:right; margin:5px 10px 0px 0px;" name="order_content_manset" value="1" class="btn right btn-danger">Sıralamayı Kaydet</button>
		<?php endif ?>
	<div class="input-group col-md-12 clearMe"></div>
	<ul id="sortable1" class="connectedSortable">
		<?php for($i = 0; $i < $adet; $i++) { ?>
			<li class="">
				<input type="hidden" name="content_order[<?=$list[$i]['content_id']?>]"/>
				<?php if($type == 1) :?>
					<img width="250" src="<?=$list[$i]['content_image_manset_url']?>"/>
				<?php else : ?>
					<img width="250" src="<?=$list[$i]['content_image_url']?>"/>
				<?php endif?>
				<a target="_blank" rel="noopener noreferrer" href="<?=$list[$i]['content_url']?>"><?=$list[$i]['content_title']?></a>
			</li>
		<?php } ?>
	</ul>
	<div style="clear:both;"/>
	<div class="buttonArea">
		<?php if($type > 0) : ?>
			<button style="float:right; margin:5px 10px 0px 0px;" name="order_content_manset" value="1" class="btn right btn-danger">Sıralamayı Kaydet</button>
		<?php endif ?>
	</div>
	<div class="input-group col-md-12 clearMe"></div>
	</form>
