<?php if(!defined('APP')) die('...') ?>

	<script src="<?=G_VENDOR_TINYMCE?>tinymce.min.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor.page.js"></script>

	<form id="form1" name="form1" method="post" action="">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><?=$header_subtitle?></h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-10" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Başlık</span>
					<input required class="form-control" type="text" id="page_title" name="page_title" value="<?=$page_title?>"/>
				</div>
				<div class="input-group col-md-2" style="float:left;">
					<select class="form-control" id="page_status" name="page_status">
						<?=$option_page_status?>
					</select>
				</div>
				<div class="input-group col-md-12 clearMe">
					<span class="input-group-addon"><i class="ion ion-document"></i> Sayfa Metni</span>
					<textarea class="mceEditor" id="page_text" name="page_text"><?=$page_text?></textarea>
				</div>
				<div class="buttonArea">
					<button class="btn right btn-success"><?=$action_submit?></button>
				</div>
			</div>
		</div>
		<br/>
	</form>
