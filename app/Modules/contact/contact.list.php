<?php
	if(!defined('APP')) die('...');

	$array_users	= $_user->user_shortlist();

	//hariçten gazele hayır
	if($_REQUEST['filter'] == 1)
	{
		unset($list_users, $list_contact_status, $list_contact_limits);

		$user		= myReq('user', 	1);
		$status		= myReq('status', 	1);
		$limit		= myReq('limit', 	0);
		$filter		= myReq('filter',	0);

		$_SESSION[SES]['contact_list']['user']		= $user;
		$_SESSION[SES]['contact_list']['status']	= $status;
		$_SESSION[SES]['contact_list']['limit']		= $limit;
		$_SESSION[SES]['contact_list']['filter']	= 1;
	}

	foreach($array_users as $k => $v)
	{
		if($_SESSION[SES]['contact_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['contact_list']['user'] == $k) $selected = 'selected'; }
		$list_users.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_contact_status as $k => $v)
	{
		if($_SESSION[SES]['contact_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['contact_list']['status'] == $k) $selected = 'selected'; }
		$list_contact_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}
	foreach($array_limits as $k => $v)
	{
		if($_SESSION[SES]['contact_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['contact_list']['limit'] == $k) $selected = 'selected'; }
		$list_contact_limits.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	//filtreleme istenmişse, filtrelenmiş sonuçları gösteriyoruz
	if($_SESSION[SES]['contact_list']['filter'] == 1)
	{
		$list = $_contact->contact_list_small
		(
			$_SESSION[SES]['contact_list']['user'],
			$_SESSION[SES]['contact_list']['status'],
			$_SESSION[SES]['contact_list']['limit']
		);
	}
	else
	{
		//henüz bir oturuma sahip olmayan bir kullanıcı ise
		//50 tane içerik varsayılan olarak gösteriyoruz
		$list	= $_contact->contact_list_small('none', 2, 100);
		$alert	= showMessageBoxS('Onay bekleyen yorum bulunamadı.', 'error');
	}

	$adet = count($list);
	if($adet > 0)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$contact_id				= $list[$i]['contact_id'];
			$contact_email			= $list[$i]['contact_email'];
			$contact_status			= $list[$i]['contact_status'];
			$contact_aprover		= $list[$i]['contact_aprover'];
 			$create_time			= $list[$i]['create_time'];
 			$create_time_f			= $list[$i]['create_time_f'];

			$_id_f					= str_pad($contact_id, 4, "0", STR_PAD_LEFT);
			$contact_status_f		= $array_contact_status[$contact_status];
			$contact_status_icon	= $array_content_status_bar[$contact_status];
			$contact_aprover_f		= $array_users[$contact_aprover];

			$link_edit			= LINK_ACP.'&amp;view=contact&amp;do=edit&amp;id='.$contact_id;

			if($_auth['contact_edit'] == 1)
			{
				$edit_link = '<a title="Düzenle" href="'.$link_edit.'"><i class="btn btn-primary ion ion-edit"></i></a>';
			}

			$sayfa_icerik.='
				<tr>
					<td class="left">'.$_id_f.'</td>
					<td>'.$contact_email.'</td>
					<td>'.$contact_aprover_f.'</td>
					<td class="right">'.$contact_status_f.' '.$contact_status_icon.'</td>
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

	<div class="box box-primary">
		<form action="<?=LINK_ACP?>" name="form1" method="get">
			<input type="hidden" name="page" value="acp"/>
			<input type="hidden" name="view" value="<?=$view?>"/>
			<input type="hidden" name="do" value="<?=$do?>"/>
			<input type="hidden" name="filter" value="1"/>

			<div class="box-header">
				<h3 class="box-title">İletişim Formu Yönetimi - Hızlı Filtrele</h3>
			</div>

			<div class="box-body" style="height: 60px;">
				<div class="input-group col-md-4" style="float:left;">
					<select class="form-control" id="status" name="status">
						<option value="none">Yayın Durumu</option>
						<?=$list_contact_status?>
					</select>
				</div>
				<div class="input-group col-md-4" style="float:left;">
					<select class="form-control" id="user" name="user">
						<option value="none">Onaylayan</option>
						<?=$list_users?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="limit" name="limit">
						<?=$list_contact_limits?>
					</select>
				</div>
				<div class="input-group col-md-1" style="float:right; text-align:right;">
					<button class="btn btn-success button_fill">Filtrele</button>
				</div>
			</div>
		</form>
		<hr class="hr_ince_min"/>


		<?php if($adet > 0) : ?>
			<script>
				$(document).ready(function()
				{
					$('#recordlist_Contact').dataTable(
					{
						"aaSorting"		: [[0, "desc", 1]],
						"bPaginate" 	: true,	//sayfalama özelliğini açıp kapatır
						"bLengthChange"	: true, 	//sayı seçimini açıp kapatır
						"bFilter"		: true, 	//arama alanını açıp kapatır
						"bStateSave" 	: true,		//kalınan noktayı kaydetmeye yarar
						"bSort" 		: true, 	//sıralama özelliğini açıp kapatır
						"bInfo"			: true,		//bilgi alanını açıp kapatır
						//dil dosyalarını yükler
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
					});
				});
			</script>

			<div class="box-body">
				<table id="recordlist_Contact" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="40">No</th>
							<th>Gönderen</th>
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
