<?php if(!defined('APP')) die('...') ?>

	<?php if($content_template <> 3 or ($content_template == 3 and ($content_cat <> 0 or $content_cat2 <> 0 or $content_type <> 0))) :?>
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-image"></i> Haber Resmi - Manşet Resmi</h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<input accept="image/*" type="file" style="width:400px; padding: 0px;" id="content_image" name="content_image"/>
					<?php if($aspect_error_image == 1) :?>
						<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_image_text?></div></div>
					<?php endif;?>
					<?php if($action_type == 'edit' && $content_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<input style="width:400px;" class="form-control" type="text" id="org_content_image" name="org_content_image" value="<?=$content_image?>"/>
					<?php endif ?>
					<?php if($content_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<img style="max-width:400px !important; border:1px dotted #228866;" src="<?=$content_image_link?>"/>
					<?php endif ?>

					<?php if($content_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<input type="checkbox" name="delete_content_image" id="delete_content_image"/> <label for="delete_content_image">Haber Görseli Kaldır</label>
					<?php endif ?>

					<?php if($action_type == 'edit' && $content_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<a href="javascript:void(0);" onclick="window.open('<?=LINK_BASE?>crop&amp;id=0&amp;content_image_dir=<?=$content_image_dir?>&amp;type=content&amp;img=<?=$content_image?>','', 'width=1366, height=768, scrollbars=yes, left=100, top=50')"><b class="btn btn-primary">Resmi Kırp</b></a>
					<?php endif ?>
				</div>
 				<?php //if($array_content_type_required[$content_type] == true) : ?>
					<div class="input-group col-md-6" style="float:left;">
						<input accept="image/*" type="file" style="width:100%; padding: 0px;" id="content_image_manset" name="content_image_manset"/>
						<?php if($aspect_error_image_manset == 1) :?>
							<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_image_manset_text?></div></div>
						<?php endif;?>

						<?php if($action_type == 'edit' && $content_image_manset <> '') : ?>
							<div style="clearMe"></div><br/>
							<input style="width:100%;" class="form-control" type="text" id="org_content_image_manset" name="org_content_image_manset" value="<?=$content_image_manset?>"/>
						<?php endif ?>

						<?php if($content_image_manset <> '') : ?>
							<div style="clearMe"></div><br/>
							<img style="max-width:100% !important; border:1px dotted #228866;" src="<?=$content_image_manset_link?>"/>
						<?php endif ?>

						<?php if($content_image_manset <> '') : ?>
							<div style="clearMe"></div><br/>
							<input type="checkbox" name="delete_content_image_manset" id="delete_content_image_manset"/> <label for="delete_content_image_manset">Manşet Görseli Kaldır</label>
						<?php endif ?>

						<?php if($action_type == 'edit' && $content_image_manset <> '') : ?>
							<div style="clearMe"></div><br/>
							<a href="javascript:void(0);" onclick="window.open('<?=LINK_BASE?>crop&amp;content_type=<?=$content_type?>&amp;content_image_dir=<?=$content_image_dir?>&amp;type=manset&amp;img=<?=$content_image_manset?>','', 'width=1366, height=768, scrollbars=yes, left=100, top=50')"><b class="btn btn-primary">Resmi Kırp</b></a>
						<?php endif ?>
					</div>
				<?php //else : ?>
					<!-- Manşet Dönüşümlerinde manşet resmi kaybolmasın -->
					<!--<input type="hidden" name="org_content_image_manset" value="<?=$content_image_manset?>"/> -->
				<?php //endif ?>
			</div>
			<div class="input-group col-md-12 clearMe"></div>
		</div>
	<?php endif ?>
