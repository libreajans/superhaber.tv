<?php if(!defined('APP')) die('...') ?>

	<div class="input-group col-md-10" style="float:left;">
		<span class="input-group-addon"><i class="ion ion-alert" title="Ana başlıktan önce kullanılır"></i> Üst Başlık </span>
		<textarea class="force_input form-control" id="content_title_outside" name="content_title_outside"><?=$content_title_outside?></textarea>
	</div>
	<?php if(RC_MansetOrder == 1 && $content_type <> 0) : ?>
		<div class="input-group col-md-2" style="float:left;">
			&nbsp;<input type="checkbox" name="content_manset_reset" id="content_manset_reset"/> <label for="content_manset_reset">Manşette Göster</label>
		</div>
	<?php endif ?>
	<div class="input-group col-md-10" style="float:left;">
		<span class="input-group-addon"><i class="ion ion-document"></i> Başlık</span>
		<textarea
			<?php if(RC_ForceSeo == 1) : ?> maxlength="<?=$array_maxlength['h1']?>" <?php endif ?>
			required class="force_input form-control" id="content_title" name="content_title"><?=$content_title?></textarea>
	</div>
	<?php if($array_content_type_required[$content_type] == true) : ?>
		<div class="input-group col-md-2" style="float:left;">
			&nbsp;<input type="checkbox" <?=$content_manset_text_status_checked?> name="content_manset_text_status" id="content_manset_text_status"/> <label for="content_manset_text_status">Manşet Metni Kapalı</label>
		</div>
	<?php endif?>

