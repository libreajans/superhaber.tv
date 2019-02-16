<?php if(!defined('APP')) die('...');

	if($_REQUEST['checklogin'] == 1)
	{
		$_user->check_admin();
		if($_SESSION[SES]['ADMIN'] == 1)
		{
			header('Location:'.LINK_ACP);
			exit();
		}
	}

	if($_REQUEST['logout'] == 1)
	{
		if($_SESSION[SES]['user_email'] <> '')
		{
			//sadece eposta adresi varsa, loglama yapalım
			$_user->user_log_add('Başarılı Çıkış: {'.$_SESSION[SES]['user_email'].'}');
		}
		$alert = '<div class="box box-solid bg-green"><div class="box-body">Çıkış İşleminiz başarıyla gerçekleştirilmiştir.</div></div>';
		kill_session();
	}

	if(!$alert)
	{
		$alert = '<div class="box box-solid bg-yellow"><div class="box-body">E-posta adresiniz ve parolanız.</div></div>';
	}

	if($_REQUEST['hata'] == '0') $alert = '<div class="box box-solid bg-red"><div class="box-body">E-Posta Adresi veya Parola hatalı.</div></div>';
	if($_REQUEST['hata'] == '1') $alert = '<div class="box box-solid bg-red"><div class="box-body">E-Posta Adresi ve Parola alanları boş olamaz.</div></div>';
	if($_REQUEST['hata'] == '2') $alert = '<div class="box box-solid bg-red"><div class="box-body">Güvenlik sorusu boş bırakılamaz.</div></div>';
	if($_REQUEST['hata'] == '3') $alert = '<div class="box box-solid bg-red"><div class="box-body">Güvenlik sorusuna yanlış cevap verdiniz.</div></div>';
	if($_REQUEST['hata'] == '4') $alert = '<div class="box box-solid bg-red"><div class="box-body">Pasif bir hesaba erişmeye çalışıyorsunuz.</div></div>';

	//burda daha detaylı bir yönlendirme yapacağız
	//yetki seviyesi ve durumuna göre davranacağız
	//echo mds('Elluga12345**');

	//kişi giriş yapmış ve çıkış da istemiyorsa, direk yönetim paneline dönelim
	if($_SESSION[SES]['ADMIN'] == 1)
	{
 		header('Location:'.LINK_ACP);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="robots" content="noindex, nofollow"/>
		<title>Giriş &rarr; <?=$L['pIndex_Company']?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>Me/ionicons-2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>dist/css/AdminLTE.min.css">
		<script src="<?=G_VENDOR_ADMINLTE?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="<?=G_VENDOR_ADMINLTE?>bootstrap/js/bootstrap.min.js"></script>
		<!--[if lt IE 9]>
			<script src="<?=G_VENDOR_ADMINLTE?>Me/html5shiv.min.js"></script>
			<script src="<?=G_VENDOR_ADMINLTE?>Me/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-box-body">
				<form action="<?=LINK_GIRIS?>" method="post">
					<input type="hidden" name="checklogin" value="1"/>
					<div class="row">
						<div class="col-xs-12">
							<a title="&larr; Ana Sayfaya Dön" href="<?=LINK_INDEX?>">
								<img style="margin-bottom:15px;" alt="<?=$L['pIndex_Company']?>" width="320" src="<?=SITELINK?>assets/default/desktop/img/logo_giris.png"/>
							</a>
						</div>
					</div>

					<?=$alert?>

					<div class="form-group has-feedback">
						<input type="email" name="email" class="form-control" placeholder="E-posta Adresi">
							<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" name="pass" class="form-control" placeholder="Parola">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>

					<?php if(RC_Captcha == 1) : ?>
						<div class="row" style="margin:10px">
							<?php $recaptcha = new \ReCaptcha\ReCaptcha(capthaPublicKey, new \ReCaptcha\RequestMethod\CurlPost()); ?>
								<div class="g-recaptcha" data-sitekey="<?=capthaPublicKey?>"></div>
								<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=TR_tr"></script>
						</div>
					<?php endif; ?>

					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Giriş</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</body>
</html>
