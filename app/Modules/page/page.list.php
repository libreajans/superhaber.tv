<?php
	if(!defined('APP')) die('...');

	$list = $_page->page_list();

	$adet = count($list);
	if($adet > 0)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$_id				= $list[$i]['page_id'];
			$page_id			= $list[$i]['page_id'];
			$page_title			= $list[$i]['page_title'];
			$page_status		= $list[$i]['page_status'];

			$page_title			= '<a href="'.$array_page_url[$_id].'">'.$page_title.'</a>';

			$_id_f				= str_pad($_id, 4, "0", STR_PAD_LEFT);
			$page_status_f		= $array_content_status[$page_status];
			$page_status_icon	= $array_content_status_bar[$page_status];

			$link_edit			= LINK_ACP.'&amp;view=page&amp;do=edit&amp;id='.$_id;
			$link_delete		= LINK_ACP.'&amp;view=page&amp;action=delete&amp;id='.$_id;
			$confirm_sorusu		= 'Sayfa kaydını silmek istediğinizden emin misiniz?';

			if($_auth['page_delete'] == 1)
			{
				$delete_link = '<a title="Sil" href="'.$link_delete.'" onclick="javascript:return confirm(\''.$confirm_sorusu.'\')"><i class="btn btn-danger ion ion-android-delete"></i></a>';
			}

			if($_auth['page_edit'] == 1)
			{
				$edit_link = '<a title="Düzenle" href="'.$link_edit.'"><i class="btn btn-primary ion ion-edit"></i></a>';
			}

			$sayfa_icerik.='
				<tr>
					<td class="left">'.$_id_f.'</td>
					<td>'.$page_title.'</td>
					<td>'.$page_status_f.' '.$page_status_icon.'</td>
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
				$('#recordList_Page').dataTable(
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
				<table id="recordList_Page" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="40">ID</th>
							<th>Sayfa Adı</th>
							<th width="90">Durum</th>
							<th class="hideMe"></th>
							<th class="hideMe"></th>
						</tr>
					</thead>
					<tbody>
						<?=$sayfa_icerik?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>
