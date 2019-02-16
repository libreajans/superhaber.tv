<?php if(!defined('APP')) die('...') ?>

	<?=$uyarilar_text?>

<!--	<script src="<?=G_VENDOR_TINYMCE?>tinymce.min.js"></script>
	<script src="<?=G_VENDOR_TINYMCE?>editor.minimal.js"></script>-->

	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>

		<div class="box box-primary">
			<div class="box-header">
				<div style="float:left; width:75%">
					<h3 class="box-title contentTitleSize"><?=$header_subtitle?></h3>
				</div>
				<div style="float:right; width:25%">
					<?php if($do <> 'add' && $hata_delete <> '1') : ?>
						<a href="<?=LINK_ACP?>&amp;view=author&amp;action=delete&amp;id=<?=$_id?>">
							<p style="float:right; margin:5px 10px 0px 0px;" class="btn right btn-danger"> <?php if($author_status == '3') echo '<i class="ion ion-checkmark"></i>';?> Sil </p>
						</a>
					<?php endif ?>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
					<?php if($author_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_draft?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
					<?php if($author_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$action_submit_publish?></button>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Adı Soyadı</span>
					<input class="form-control" type="text" id="author_name" name="author_name" value="<?=$author_name?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-document"></i> Index Sıra No</span>
					<input class="form-control" type="number" id="author_order" name="author_order" value="<?=$author_order?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-social-twitter"></i> Twitter</span>
					<input class="form-control" type="url" id="author_twitter" name="author_twitter" value="<?=$author_twitter?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-social-facebook"></i> Facebook</span>
					<input class="form-control" type="url" id="author_facebook" name="author_facebook" value="<?=$author_facebook?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-social-instagram"></i> İnstagram</span>
					<input class="form-control" type="url" id="author_instagram" name="author_instagram" value="<?=$author_instagram?>"/>
				</div>
				<div class="input-group col-md-6" style="float:left;">
					<span class="input-group-addon"><i class="ion ion-email"></i> E-posta</span>
					<input class="form-control" type="mail" id="author_email" name="author_email" value="<?=$author_email?>"/>
				</div>
			</div>
		</div>

		<div class="box box-danger box-n collapsed-box">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-document"></i> Alternatif Alanlar</h3>
				<div class="box-tools pull-right">
					<i class="btn btn-default btn-sm" data-widget="collapse"><i class="ion ion-minus"></i></i>
				</div>
			</div>
			<div style="display: none;" class="box-body">
				<div class="input-group col-md-6" style="float:left; height:33px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$author_show_index_checked?> name="author_show_index" id="author_show_index_checked"/> <label for="author_show_index_checked">Ana Sayfada GİZLE</label>
					</span>
				</div>

				<div class="input-group col-md-6" style="float:left; height:33px; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
					<span class="input-group-addon">
						&nbsp;<input type="checkbox" <?=$author_show_page_checked?> name="author_show_page" id="author_show_page_checked"/> <label for="author_show_page_checked">Yazar Sayfasında GİZLE</label>
					</span>
				</div>
				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><i class="ion ion-image"></i> Yazar Resmi</h3>
			</div>
			<div class="box-body">
				<div class="input-group col-md-4" style="float:left;">
					<input accept="image/*" type="file" style="width:100%; padding: 0px;" id="author_image" name="author_image"/>
					<?php if($aspect_error == 1) :?>
						<div class="box box-solid bg-red"><div class="box-body-wp"><?=$aspect_error_text?></div></div>
					<?php endif;?>

					<?php if($action_type == 'edit' && $author_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<input style="width:100%;" class="form-control" type="text" id="org_author_image" name="org_author_image" value="<?=$author_image?>"/>
					<?php endif ?>

					<?php if($author_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<img style="max-width:100% !important; border:1px dotted #228866;" src="<?=$author_image_link?>"/>
					<?php endif ?>

					<?php if($author_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<input type="checkbox" name="delete_author_image" id="delete_author_image"/> <label for="delete_author_image">Görseli Kaldır</label>
					<?php endif ?>

					<?php if($action_type == 'edit' && $author_image <> '') : ?>
						<div style="clearMe"></div><br/>
						<a href="javascript:void(0);" onclick="window.open('<?=LINK_BASE?>crop&amp;id=3&amp;type=author&amp;img=<?=$author_image?>','', 'width=1366, height=768, scrollbars=yes, left=0, top=0')"><b class="btn btn-primary">Resmi Kırp</b></a>
					<?php endif ?>
				</div>
				<!--
					<div class="input-group col-md-12 clearMe"></div>
					<div class="input-group col-md-12 clearMe">
						<span class="input-group-addon"><i class="ion ion-document"></i> Biyografi</span>
						<textarea class="mceEditorSimple" style="height:70px;" class="form-control" id="author_text" name="author_text"><?=$author_text?></textarea>
					</div>
					<hr class="hr_ince"/>
					<div class="input-group col-md-12">
						<span class="input-group-addon"><i class="ion ion-coffee"></i> Özel Adres</span>
						<textarea style="height:70px;" class="form-control" id="author_contact" name="author_contact"><?=$author_contact?></textarea>
					</div>
					<div class="input-group col-md-12">
						<span class="input-group-addon"><i class="ion ion-coffee"></i> Özel Notlar</span>
						<textarea style="height:70px;" class="form-control" id="author_notes" name="author_notes"><?=$author_notes?></textarea>
					</div>
				-->
			</div>
		</div>
	</form>
