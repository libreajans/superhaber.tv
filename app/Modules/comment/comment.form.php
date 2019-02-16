<?php if(!defined('APP')) die('...') ?>

	<!-- date time picker -->
	<link href="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.css" rel="stylesheet"/>

	<script src="<?=G_VENDOR_TINYMCE?>tinymce.min.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor.minimal.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.js"></script>
	<script>$(function() { $(".datepicker" ).datetimepicker({lang:'tr', timepicker:true, format:'Y-m-d H:i:s'}); }); </script>

	<form id="form1" name="form1" method="post" action="">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
		<input type="hidden" name="comment_content" value="<?=$comment_content?>"/>

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><?=$header_subtitle?>&nbsp;&nbsp;&nbsp;<a title="İlgili Başlığı Görüntüle" target="_blank" rel="noopener noreferrer" href="yorum-kontrol-<?=$comment_content?>-haber#write-comment-<?=$comment_content?>"><i class="ion ion-link"></i></a></h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-5" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-person"></i> Yorum Yapan</span>
					<input required class="form-control" type="text" id="comment_author" name="comment_author" value="<?=$comment_author?>"/>
				</div>
				<div class="input-group col-md-4" style="float:left;">
					<input required class="datepicker form-control" type="text" id="create_time" name="create_time" value="<?=$create_time?>"/>
				</div>
				<div class="input-group col-md-2" style="float:left;">
					<input readonly class="form-control" type="text" id="comment_ip" name="comment_ip" value="<?=$comment_ip?>"/>
				</div>
				<div class="input-group col-md-1" style="float:left;">
					&nbsp; <a target="_blank" rel="noopener noreferrer" href="http://www.ipsorgu.com/ip_numarasindan_adres_bulma.php?ip=<?=$comment_ip?>"><b class="btn btn-primary"><i class="ion ion-help"></i> IP Sor</b></a>
				</div>
				<div class="input-group col-md-12 clearMe">
					<span class="input-group-addon"><i class="ion ion-document"></i> Yorum Metni</span>
					<textarea class="mceEditorSimple" id="comment_text" name="comment_text"><?=$comment_text?></textarea>
				</div>
				<div class="buttonArea">
					<span style="float:left;" class="btn right btn-warning">
						<input type="checkbox" id="please_turkish" name="please_turkish"/>
						<label for="please_turkish">Küçük harfe dönüştür</label>
					</span>
					<button style="float:right; margin:5px 0px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
					<?php if($comment_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$array_comment_status[1]?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
					<?php if($comment_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$array_comment_status[2]?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="delete" class="btn right btn-danger">
					<?php if($comment_status == '4') echo '<i class="ion ion-checkmark"></i>';?> Sil</button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="spam" class="btn right btn-danger">
					<?php if($comment_status == '3') echo '<i class="ion ion-checkmark"></i>';?> Spam</button>
				</div>
				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>
		<br/>
	</form>

