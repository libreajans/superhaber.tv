<?php
	if(!defined('APP')) die('...');

	//edit işlemi istenmiş ise
	if($action == 'edit')
	{
		//hangi moda döneceğimizi belirtiyoruz
		$do = 'edit';

		try
		{
			$_user->user_edit_self();
			$message = $messages['info_edit'];
		}
		catch(Exception $e)
		{
			//ekleme işlemi sırasında bir hata oluştu
								$message 			= $messages['error_edit'];
			if(ST_DEBUG == 1) 	$message["text"] 	= $e->getMessage();
		}
	}

	if(!empty($message['type']))
	{
		$alert = showMessageBoxS($message['text'], $message['type']);
	}

	$list 				= $_user->user_list($_SESSION[SES]['user_id']);
	$user_name			= $list[0]['user_name'];
	$user_realname		= $list[0]['user_realname'];
	$user_email			= $list[0]['user_email'];
	$user_avatar		= $list[0]['user_avatar'];
	$user_editor		= $list[0]['user_editor'];

	$header_subtitle	= 'Güncelle &rarr; '.$user_name;

	//select alanı optionları
	foreach($array_editor as $k => $v)
	{
		//selected etiketi
		$selected = ''; if($user_editor <> '' && $user_editor == $k) $selected = 'selected';
		$option_user_editor.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	$liste_avatar = $_user->get_avatar_list($user_avatar);

?>

	<section class="content">
		<div>
			<ol class="breadcrumb">
				<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
				<li><i class="ion ion-android-person"></i> <?=$page_name?></li>
				<li><?=$header_subtitle?></li>
			</ol>
		</div>

		<?=$alert?>

		<form id="form1" name="form1" method="post" action="">
			<input type="hidden" name="action" value="edit"/>

			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Kullanıcı Bilgileriniz</h3>
				</div>
				<div class="box-body" style="margin-top: -15px;">
					<div class="input-group col-md-6" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-person"></i> Gerçek Adı</span>
						<input required class="form-control" type="text" id="user_name" name="user_name" value="<?=$user_name?>"/>
					</div>
					<div class="input-group col-md-6" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-person"></i> Görünecek Adı</span>
						<input required class="form-control" type="text" id="user_realname" name="user_realname" value="<?=$user_realname?>"/>
					</div>
					<div class="input-group col-md-6" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-android-mail"></i> E-posta Adresi</span>
						<input required class="form-control" type="email" id="user_email" name="user_email" value="<?=$user_email?>"/>
					</div>
					<div class="input-group col-md-6" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-document"></i> Editör</span>
						<select class="form-control" id="user_editor" name="user_editor">
							<?=$option_user_editor?>
						</select>
					</div>
					<div class="input-group col-md-6" style="float:left;">
						<span class="input-group-addon"><i class="ion ion-key"></i> Parola Bilgileri</span>
						<input class="form-control" autocomplete="off" type="password" id="user_pass" name="user_pass" value=""/>
					</div>
					<div class="input-group col-md-6" style="float:left;">
						&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="user_pass_renew" name="user_pass_renew"/> <label for="user_pass_renew">Parolayı Yenile</label>
					</div>
					<hr/>
					<div class="input-group col-md-12" style="float: left; margin-top: 10px; border-top: 1px solid #f1f1f1; padding-top: 10px;">
						<h3 style="margin-top: 0px;">Avatar Seçiniz</h3>
						<?=$liste_avatar?>
					</div>
					<div class="buttonArea">
						<button class="btn right btn-success">Güncelle</button>
					</div>
				</div>
			</div>
		</form>
	</section>
