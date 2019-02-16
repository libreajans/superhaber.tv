<?php if(!defined('APP')) die('...') ?>

	<?=$uyarilar_text?>

	<?php if($do <> 'add' && $content_tags == "") :?>
		<div class="box box-solid bg-red">
			<div class="box-body-wp">
				<i class="ion ion-alert-circled"></i>&nbsp;&nbsp;&nbsp;&nbsp;Etiket alanını boş bırakmayınız.
			</div>
		</div>
	<?php endif ?>

	<?php if($aspect_error == 1) :?>
		<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_text?></div></div>
	<?php endif;?>

	<?php if($content_type <> 0) : ?>
		<?php if($aspect_manset_error == 1) :?>
			<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_manset_error_text?></div></div>
		<?php endif;?>
	<?php endif;?>

	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jMetro/jquery-ui.css">
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jTag/css/jquery.tagit.css"/>

	<script src="<?=G_VENDOR_TINYMCE?>tinymce.min.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor_plugin_haberekle.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor_plugin_videoekle.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor_plugin_case.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor_type_<?=$_SESSION[SES]['user_editor']?>.js"></script>
<!-- 	<script src="<?=G_VENDOR_TINYMCE?>editor_type_<?=$_SESSION[SES]['user_editor']?>.js?date=<?=date('Ymdhi')?>"></script> -->

	<script src="<?=G_VENDOR_JQUERY?>jUi/jquery-ui.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jTag/js/tag-it.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jAreYouSure/jquery.are-you-sure.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jAreYouSure/ays-beforeunload-shim.js"></script>

	<script>$(function() { $('#content_tags').tagit({allowSpaces: true});1});</script>
	<script>$(function() { $('#content_time').datetimepicker({lang:'tr', timepicker:true, format:'Y-m-d H:i:s'}); }); </script>
	<script>$(function() { $('#form1').areYouSure(); }); </script>

	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
		<input type="hidden" name="content_template" value="<?=$content_template?>"/>
		<input type="hidden" name="content_image_dir" value="<?=$content_image_dir?>"/>
		<!-- Aktardığımız içeriklerin orjinal url bilgilerini burada tutuyoruz -->

		<div class="box box-primary">

			<?php include ACP_MODULE_PATH.'content/form.block/block.header_submit.php'; ?>

			<div class="box-body">

				<?php include ACP_MODULE_PATH.'content/form.block/block.baslik.php'; ?>

				<div class="input-group col-md-5" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> İçerik Tipi</span>
					<select required class="form-control" id="content_type" name="content_type">
						<?=$option_type?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select required class="form-control" id="content_cat" name="content_cat">
						<?=$option_cat?>
					</select>
				</div>
				<div class="input-group col-md-2" style="float:left;">
					<select class="form-control" id="content_cat2" name="content_cat2">
						<?=$option_cat2?>
					</select>
				</div>
				<div class="input-group col-md-12" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Özet</span>
					<textarea style="height:90px;" class="form-control" id="content_desc" name="content_desc"><?=$content_desc?></textarea>
				</div>
				<div class="input-group col-md-12 clearMe">
					<span class="input-group-addon"><i class="ion ion-alert"></i> Embed URL</span>
					<input required class="form-control" type="url" id="content_redirect" name="content_redirect" value="<?=$content_redirect?>"/>
				</div>

				<?php include ACP_MODULE_PATH.'content/form.block/block.etiket.php'; ?>

				<?php include ACP_MODULE_PATH.'content/form.block/block.seo.php'; ?>

				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-document"></i> Alternatif Alanlar</h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Tarih</span>
					<input required placeholder="Tarih" class="form-control datepicker" type="text" id="content_time" name="content_time" value="<?=$content_time?>"/>
				</div>

				<div class="input-group col-md-6" style="float:left; height:33px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$content_ads_status_checked?> name="content_ads_status" id="content_ads_status"/> <label for="content_ads_status">REKLAM GÖSTERİLMESİN!</label>
					</span>
				</div>
				<div class="input-group col-md-12 clearMe"></div>

				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Yorum</span>
					<select class="form-control" id="content_comment_status" name="content_comment_status">
						<?=$option_comment_status?>
					</select>
				</div>
				<div class="input-group col-md-6" style="float:left; height:33px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$content_cat_show_status_checked?> name="content_cat_show_status" id="content_cat_show_status"/> <label for="content_cat_show_status">KATEGORI SAYFASINDA GİZLE</label>
					</span>
				</div>
				<div class="input-group col-md-12 clearMe"></div>

				<?php if($content_redirect <> '') : ?>
					<div class="input-group col-md-12">
						<iframe width="560" height="315" src="<?=$content_redirect?>"></iframe>
					</div>
				<div class="input-group col-md-12 clearMe"></div>
				<?php endif ?>
			</div>
		</div>

		<?php include ACP_MODULE_PATH.'content/form.block/block.image.php'; ?>

		<div class="box box-primary">
			<div class="box-body">
				<?php include ACP_MODULE_PATH.'content/form.block/block.footer_submit.php'; ?>
			</div>
		</div>
	</form>
