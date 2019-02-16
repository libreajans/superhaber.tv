<?php if(!defined('APP')) die('...') ?>

	<div class="input-group col-md-10" style="float:left;">
		<span class="input-group-addon"><i class="ion ion-document"></i> Özet</span>
		<textarea
			<?php if(RC_ForceSeo == 1) : ?> maxlength="<?=$array_maxlength['h2']?>" <?php endif ?>
			required style="height:90px;" class="form-control" id="content_desc" name="content_desc"><?=$content_desc?></textarea>
	</div>
	<div class="input-group col-md-2" style="float:left;">
		<span style="float: left; margin-top: 70px; margin-left: 5px;"><b id="counter_content_desc"></b> karakter kaldı</span>
	</div>

