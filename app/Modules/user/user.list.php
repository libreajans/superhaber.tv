<?php
	if(!defined('APP')) die('...');

	$list = $_user->user_list();

	$adet = count($list);
	if($adet > 0)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$_id				= $list[$i]['user_id'];
			$user_name			= $list[$i]['user_name'];
			$user_realname		= $list[$i]['user_realname'];
			$user_email			= $list[$i]['user_email'];
			$user_status		= $list[$i]['user_status'];
			$create_time		= $list[$i]['create_time'];
			$create_time_f		= $list[$i]['create_time_f'];

			$_id_f				= str_pad($_id, 4, "0", STR_PAD_LEFT);
			$user_status_f		= $array_user_status[$user_status];

			$link_edit			= LINK_ACP.'&amp;view=user&amp;do=edit&amp;id='.$_id;
			$link_delete		= LINK_ACP.'&amp;view=user&amp;action=delete&amp;id='.$_id;
			$confirm_sorusu		= 'Kullanıcı kaydını silmek istediğinizden emin misiniz?';

			$delete_link		= '';
			$edit_link			= '';

			if($_auth['user_delete'] == 1)
			{
				$delete_link = '<a title="Sil" href="'.$link_delete.'" onclick="javascript:return confirm(\''.$confirm_sorusu.'\')"><i class="btn btn-danger ion ion-android-delete"></i></a>';
			}

			if($_auth['user_edit'] == 1)
			{
				$edit_link = '<a title="Düzenle" href="'.$link_edit.'"><i class="btn btn-primary ion ion-edit"></i></a>';
			}

			$user_icerik.='
				<tr>
					<td>'.$_id_f.'</td>
					<td>'.$user_name.'</td>
					<td>'.$user_email.'</td>
					<td>'.$user_status_f.'</td>
					<td>
						<p class="hideMe">'.$create_time.'</p>
						'.$create_time_f.'
					</td>
					<td width="25" class="center">'.$delete_link.'</td>
					<td width="25" class="center">'.$edit_link.'</td>
				</tr>';
		}
	}
	else
	{
		$alert = showMessageBoxS('Herhangi bir kayıt bulunamadı.', 'error');
	}

?>

	<?php if($adet > 0) { ?>
		<script>
			$(document).ready(function()
			{
				$('#recordList_User').dataTable(
				{
					"aaSorting"		: [[0, "desc", 1]],
					"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
					"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
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

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><?=$header_subtitle?></h3>
			</div>
			<div class="box-body">
				<table id="recordList_User" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="50">No</th>
							<th>Kullanıcı Adı</th>
							<th>Eposta Adresi</th>
							<th width="90">Yetki Seviyesi</th>
							<th width="90">Kayıt Tarihi</th>
							<th class="hideMe"></th>
							<th class="hideMe"></th>
						</tr>
					</thead>
					<tbody>
						<?=$user_icerik?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>

