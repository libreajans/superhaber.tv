<?php if(!defined('APP')) die('...') ?>

	<div class="buttonArea">
		<?php if($do <> 'add') : ?>
		<div style="float:left; margin-top:10px; margin-left:10px;">
			<i class="ion ion-refresh" title="Ziyaret sayısı"></i> <b><?=$content_view?></b>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="ion ion-document" title="Okunma sayısı"></i> <?=$content_view_real?>
		</div>
		<?php endif ?>
		<?php if($do <> 'add' && $hata_delete <> '1') : ?>
			<a href="<?=LINK_ACP?>&amp;view=content&amp;action=delete&amp;id=<?=$_id?>">
				<p style="float:right; margin:5px 10px 0px 0px;" class="btn right btn-danger"> <?php if($content_status == '3') echo '<i class="ion ion-checkmark"></i>';?> Sil </p>
			</a>
		<?php endif ?>
		<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
		<?php if($content_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_draft?></button>
		<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
		<?php if($content_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_publish?></button>
	</div>
	<div class="input-group col-md-12 clearMe"></div>
