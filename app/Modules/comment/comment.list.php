<?php
	if(!defined('APP')) die('...');

	$array_users	= $_user->user_shortlist();

	//hariçten gazele hayır
	if($_REQUEST['filter'] == 1)
	{
		unset($list_users, $list_comment_status, $list_comment_limits);

		$keyword	= myReq('keyword', 	1);
		$time		= myReq('time', 	1);
		$user		= myReq('user', 	1);
		$status		= myReq('status', 	1);
		$limit		= myReq('limit', 	0);
		$filter		= myReq('filter',	0);

		$_SESSION[SES]['comment_list']['keyword']	= $keyword;
		$_SESSION[SES]['comment_list']['time']		= $time;
		$_SESSION[SES]['comment_list']['user']		= $user;
		$_SESSION[SES]['comment_list']['status']	= $status;
		$_SESSION[SES]['comment_list']['limit']		= $limit;
		$_SESSION[SES]['comment_list']['filter']	= 1;
	}

	foreach($array_users as $k => $v)
	{
		if($_SESSION[SES]['comment_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['comment_list']['user'] == $k) $selected = 'selected'; }
		$list_users.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_comment_status as $k => $v)
	{
		if($_SESSION[SES]['comment_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['comment_list']['status'] == $k) $selected = 'selected'; }
		$list_comment_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_limits as $k => $v)
	{
		if($_SESSION[SES]['comment_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['comment_list']['limit'] == $k) $selected = 'selected'; }
		$list_comment_limits.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	//filtreleme istenmişse, filtrelenmiş sonuçları gösteriyoruz
	if($_SESSION[SES]['comment_list']['filter'] == 1)
	{
		$list = $_comment->comment_list_small
		(
			$_SESSION[SES]['comment_list']['keyword'],
			$_SESSION[SES]['comment_list']['time'],
			$_SESSION[SES]['comment_list']['user'],
			$_SESSION[SES]['comment_list']['status'],
			$_SESSION[SES]['comment_list']['limit']
		);
	}
	else
	{
		//henüz bir oturuma sahip olmayan bir kullanıcı ise
		//50 tane içerik varsayılan olarak gösteriyoruz
		$list = $_comment->comment_list_small
		(
			$keyword 	= 'none',
			$time 		= 'none',
			$user 		= 'none',
			$status 	= 2,
			$limit 		= 100
		);
		$alert	= showMessageBoxS('Onay bekleyen yorum bulunamadı.', 'error');
	}


	$adet	= count($list);
	if($adet > 0)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$_id					= $list[$i]['comment_id'];
			$comment_content		= $list[$i]['comment_content'];
			$comment_author			= $list[$i]['comment_author'];
			$comment_ip				= $list[$i]['comment_ip'];
			$comment_text			= $list[$i]['comment_text'];
			$comment_status			= $list[$i]['comment_status'];
			$comment_aprover		= $list[$i]['comment_aprover'];
 			$create_time			= $list[$i]['create_time'];
 			$create_time_f			= $list[$i]['create_time_f'];

			$_id_f					= str_pad($_id, 4, "0", STR_PAD_LEFT);
			$comment_status_f		= $array_comment_status[$comment_status];
			$comment_status_icon	= $array_content_status_bar[$comment_status];
			$comment_aprover_f		= $array_users[$comment_aprover];

			$link_edit				= LINK_ACP.'&amp;view=comment&amp;do=edit&amp;id='.$_id;
			$confirm_sorusu			= 'Yorum kaydını silmek istediğinizden emin misiniz?';

			// ------------------ yetkilendirme düzenleme kısmı ------------------

			$edit_link = '';
			$hata_edit = '';

 			if($_auth['comment_edit'] <> '1') $hata_edit = '1';
			if($hata_edit <> '1')
			{
				$edit_link = '<a title="Düzenle" href="'.$link_edit.'"><i class="btn btn-primary ion ion-edit"></i></a>';
			}
			// ------------------ yetkilendirme kısmı sonu ------------------

			$sayfa_icerik.='
				<tr>
					<td class="left">'.$_id_f.'</td>
					<td>'.$comment_author.'</td>
					<td>'.$comment_aprover_f.'</td>
					<td class="right">'.$comment_status_f.' '.$comment_status_icon.'</td>
					<td class="right">
						<p class="hideMe">'.$create_time.'</p>
						'.$create_time_f.'
					</td>
					<td width="25" class="center">'.$edit_link.'</td>
				</tr>';
		}
	}
	else
	{
		$alert = showMessageBoxS('Herhangi bir kayıt bulunamadı.', 'error');
	}
?>
	<!--<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jMetro/jquery-ui.css">-->
	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.css"/>
	<script src="<?=G_VENDOR_JQUERY?>jUi/jquery-ui.js"></script>
	<script src="<?=G_VENDOR_JQUERY?>jDatePicker/jquery.datetimepicker.js"></script>
	<script>$(function() { $('#time').datetimepicker({lang:'tr', timepicker:false, format:'Y-m-d'}); }); </script>

	<div class="box box-primary">
		<form action="<?=LINK_ACP?>" name="form1" method="get">
			<input type="hidden" name="page" value="acp"/>
			<input type="hidden" name="view" value="<?=$view?>"/>
			<input type="hidden" name="do" value="<?=$do?>"/>
			<input type="hidden" name="filter" value="1"/>

			<div class="box-header">
				<h3 class="box-title">Yorum Yönetimi - Hızlı Filtrele</h3>
			</div>

			<div class="box-body" style="height: 60px;">
				<div class="input-group col-md-8" style="float:left;">
					<input placeholder="Aranacak Kelime" class="form-control" type="text" id="keyword" name="keyword" value="<?php if($keyword <> 'none') echo $keyword?>"/>
				</div>
				<div class="input-group col-md-4" style="float:left;">
					<input placeholder="Tarih" class="form-control" type="text" id="time" name="time" value="<?php if($time <> 'none') echo $time?>"/>
				</div>
				<div class="input-group col-md-4" style="float:left;">
					<select class="form-control" id="limit" name="limit">
						<?=$list_comment_limits?>
					</select>
				</div>
				<div class="input-group col-md-4" style="float:left;">
					<select class="form-control" id="user" name="user">
						<option value="none">Onaylayan</option>
						<?=$list_users?>
					</select>
				</div>
				<div class="input-group col-md-4" style="float:left;">
					<select class="form-control" id="status" name="status">
						<option value="none">Yayın Durumu</option>
						<?=$list_comment_status?>
					</select>
				</div>
				<div class="input-group col-md-12" style="float:right; text-align:right;">
					<button class="btn btn-success">Filtrele</button>
				</div>
			</div>
		</form>
<!-- 		<hr class="hr_ince_min"/> -->
		<div class="input-group col-md-12 clearMe"></div>
		<hr class="hr_ince"/>
		<div class="input-group col-md-12 clearMe"></div>

		<?php if($adet > 0) : ?>
			<script>
				$(document).ready(function()
				{
					$('#recordList_Comment').dataTable(
					{
						"aaSorting"				: [[0, "desc", 1]],
						"bPaginate" 			: true,		//sayfalama özelliğini açıp kapatır
						"bLengthChange"			: true, 	//sayı seçimini açıp kapatır
						"bFilter"				: true, 	//arama alanını açıp kapatır
						"bStateSave" 			: true,		//kalınan noktayı kaydetmeye yarar
						"bSort" 				: true, 	//sıralama özelliğini açıp kapatır
						"bInfo"					: true,		//bilgi alanını açıp kapatır
						"oLanguage"				:
						{
							"sProcessing"		: "Lütfen Bekleyin...",
							"sLengthMenu"		: "_MENU_ kayıt göster, her sayfada",
							"sZeroRecords"		: "Herhangi bir sonuç bulunamadı",
							"sInfo"				: "_TOTAL_ kayıttan _START_ ile _END_ arası kayıt görüntüleniyor",
							"sInfoEmpty"		: "Gösterilecek bir kayıt bulunamadı",
							"sInfoFiltered"		: "(Toplam _MAX_ kayıt arasında)",
							"sInfoPostFix"		: "",
							"sSearch"			: "Filtrelenmiş sonuçlar içinde ARA",
							"oPaginate"			:
							{
								"sFirst"		: "İlk",
								"sPrevious"		: "Önceki Sayfa",
								"sNext"			: "Sonraki Sayfa",
								"sLast"			: "Son"
							}
						}
					}
					);
				});
			</script>

				<div class="box-body">
					<table id="recordList_Comment" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="50">No</th>
								<th>Yorum Gönderen</th>
								<th width="120">Son İşlem</th>
								<th width="90">Yayın Durumu</th>
								<th width="90">Kayıt Tarihi</th>
								<th class="hideMe"></th>
							</tr>
						</thead>
						<tbody>
							<?=$sayfa_icerik?>
						</tbody>
					</table>
				</div>
			</div>
		<?php else : ?>
			<div class="box-body"><?=$alert?></div>
		<?php endif ?>
