<?php
	if(!defined('APP')) die('...');

	$list = $_author->author_list($id = 0, $json = 0);
	$adet = count($list);
	if($adet > 0)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$author_id					= $list[$i]['author_id'];
			$author_name				= $list[$i]['author_name'];
			$author_show_index			= $list[$i]['author_show_index'];
			$author_show_page			= $list[$i]['author_show_page'];
			$author_status				= $list[$i]['author_status'];
			$author_order				= $list[$i]['author_order'];
			$create_time				= $list[$i]['create_time'];
			$create_time_f				= $list[$i]['create_time_f'];

			$author_name				= '<a href="'.url_author($author_name).'">'.$author_name.'</a>';
			$author_show_index_f		= $array_author_index_status_bar[$author_show_index];
			$author_show_page_f			= $array_author_index_status_bar[$author_show_page];
			$author_status_icon_f		= $array_content_status_bar[$author_status];
			$author_status_f			= $array_content_status[$author_status];
			$author_status_icon_f		= $array_content_status_bar[$author_status];

			$_id_f						= str_pad($author_id, 4, "0", STR_PAD_LEFT);

			if($author_show_index == 1)
			{
				$author_show_index_f = $author_show_index_f.' '.str_pad($author_order, 2, "0", STR_PAD_LEFT);
			}

			$link_edit					= LINK_ACP.'&amp;view=author&amp;do=edit&amp;id='.$author_id;
			$confirm_sorusu				= 'Sayfa kaydını silmek istediğinizden emin misiniz?';

			if($_auth['author_edit'] == 1)
			{
				$edit_link = '<a title="Düzenle" href="'.$link_edit.'"><i class="btn btn-success ion ion-edit"></i></a>';
			}

			$sayfa_icerik.='
				<tr>
					<td>'.$_id_f.'</td>
					<td>'.$author_name.'</td>
					<td><p class="hideMe">'.$author_show_index.'</p> '.$author_show_index_f.'</td>
					<td><p class="hideMe">'.$author_show_page.'</p> '.$author_show_page_f.'</td>
					<td>'.$author_status_f.' '.$author_status_icon_f.'</td>
					<td>
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

	<?php if($adet > 0) { ?>
		<script>
			$(document).ready(function()
			{
				$('#recordList_author').dataTable(
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

		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><?=$header_subtitle?></h3>
			</div>
			<div class="box-body">
				<table id="recordList_author" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="40">No</th>
							<th>Yazar Adı</th>
							<th width="110">Ana Sayfada</th>
							<th width="110">Yazar Sayfasında</th>
							<th width="110">Durum</th>
							<th width="90">Tarih</th>
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
