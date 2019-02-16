<?php if(!defined('APP')) die('...') ?>

	<form id="form1" name="form1" method="post" action="">
		<input type="hidden" name="action" value="<?=$action_type?>"/>
		<input type="hidden" name="id" value="<?=$_id?>"/>
		<input type="hidden" name="contact_status" value="1"/>

		<div class="box box-primary">
			<div class="box-body">
				<div class="input-group col-md-10" style="float:left;">
					<h4 class="box-title"><?=$header_subtitle?></h4>
				</div>
				<div class="input-group col-md-2" style="float:right;">
					<p class="box-title" style="text-align:right; margin-right:15px; margin-top:10px;"><?=$create_time?></p>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group col-md-12 clearMe">
					<?=$contact_text?>
				</div>
				<div class="buttonArea">
					<button style="float:right; margin:5px 0px 0px 0px;" name="save_action" value="publish" class="btn right btn-success">
					<?php if($contact_status == '1') echo '<i class="ion ion-checkmark"></i>';?> <?=$array_contact_status[1]?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="draft" class="btn right btn-primary">
					<?php if($contact_status == '2') echo '<i class="ion ion-checkmark"></i>';?> <?=$array_contact_status[2]?></button>
					<button style="float:right; margin:5px 10px 0px 0px;" name="save_action" value="delete" class="btn right btn-danger">
					<?php if($contact_status == '4') echo '<i class="ion ion-checkmark"></i>';?> Sil</button>
				</div>
				<div class="input-group col-md-12 clearMe"></div>
			</div>
		</div>
	</form>
