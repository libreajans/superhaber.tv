<?php if(!defined('APP')) die('...');

	//işin bu kısmı aslında yönetim paneliyle alakalı
	//o yüzden oturum tablosundan buraya taşıdık
	//ip değişirse oturum da kapansın
	//ua imzası değişmişse oturum da kapansın
	if($_SESSION[SES]['fip'] <> get_real_ip()) kill_session();
	if($_SESSION[SES]['fagent'] <> $_SERVER['HTTP_USER_AGENT']) kill_session();

	$_id 				= myReq('id', 0);
	$do					= myReq('do', 1);
	$view				= myReq('view', 1);
	$action				= myReq('action', 1);

	if($do == '') 		$do 	= 'list';
	if($view == '') 	$view	= 'welcome';

	$message['text'] 	= '';
	$message['type'] 	= '';

	$_user->check_admin();
	$_auth = $_user->get_user_auth($_SESSION[SES]['user_id']);

	if(array_key_exists($view, $modul_name))
	{
		if($view == 'welcome')
		{
			$page_name			= $modul_title[$view];
			$include_file 		= ACP_MODULE_PATH.$modul_name[$view].$view.'.main.php';
		}
		elseif($view == 'password')
		{
			$page_name			= $modul_title[$view];
			$include_file 		= ACP_MODULE_PATH.$modul_name[$view].$view.'.main.php';
		}
		else
		{
			if($_auth[$view.'_list'] == 1)
			{
				$page_name		= $modul_title[$view];
				$include_file 	= ACP_MODULE_PATH.$modul_name[$view].$view.'.main.php';
			}
			else
			{
				$error_module = '<div class="box box-solid bg-red" style="margin:15px"><div class="box-body">Yetkiniz olmayan bir modüle erişmeye çalışıyorsunuz.</div></div>';
			}
		}
	}
	else
	{
		$error_module = '<div class="box box-solid bg-red" style="margin:15px"><div class="box-body">Varolmayan bir modüle erişmeye çalışıyorsunuz.</div></div>';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Yönetim Paneli</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>Me/ionicons-2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>dist/css/skins/skin-blue-light.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>dist/css/skins/skin-blue.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.min.css">
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>plugins/datatables/dataTables.bootstrap.css">
		<!-- Tüm CSSlerin sonunda, çünkü tüm csslere müdahale edebilir -->
		<link rel="stylesheet" href="<?=G_VENDOR_ADMINLTE?>dist/css/AdminLTE.me.css">

		<!--[if lt IE 9]>
			<script src="<?=G_VENDOR_ADMINLTE?>Me/html5shiv.min.js"></script>
			<script src="<?=G_VENDOR_ADMINLTE?>Me/respond.min.js"></script>
		<![endif]-->
		<script src="<?=G_VENDOR_ADMINLTE?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="<?=G_VENDOR_ADMINLTE?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?=G_VENDOR_ADMINLTE?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?=G_VENDOR_ADMINLTE?>plugins/fastclick/fastclick.min.js"></script>
		<script src="<?=G_VENDOR_ADMINLTE?>dist/js/app.min.js"></script>
		<script src="<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.min.js"></script>
	</head>
	<body class="hold-transition skin-blue-light skin-blue layout-boxed sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<?php include 'acp_header.php'; ?>
			<div class="content-wrapper">
				<?=$error_module?>
				<?php if($error_module == "") include $include_file; ?>
			</div>
			<?php include 'acp_menu.php'; ?>
		</div>
	</body>
</html>
