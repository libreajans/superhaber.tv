<?php if(!defined('APP')) die('...') ?>

	<?php if(RC_DetailedSeo == 1) : ?>
		<div class="input-group col-md-12" style="float:left;">
			<span class="input-group-addon"><i class="ion ion-help" title="Haber Detay sayfasının Title alanında kullanılır"></i> Meta Title </span>
			<textarea class="force_input form-control" id="content_title_seo" name="content_title_seo"><?=$content_title_seo?></textarea>
		</div>
		<div class="input-group col-md-12" style="float:left;">
			<span class="input-group-addon"><i class="ion ion-help" title="Haber linki oluşturulurken kullanılır"></i> Meta URL </span>
			<textarea maxlength="128" class="force_input form-control" id="content_title_url" name="content_title_url"><?=$content_title_url?></textarea>
		</div>
		<div class="input-group col-md-12" style="float:left;">
			<span class="input-group-addon"><i class="ion ion-help" title="Haber Detay sayfasının Meta Desc alanında kullanılır"></i> Meta Açıklama</span>
			<textarea maxlength="128" class="force_input form-control" id="content_metadesc" name="content_metadesc"><?=$content_metadesc?></textarea>
		</div>
	<?php endif ?>
