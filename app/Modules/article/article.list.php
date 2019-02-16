<?php
	if(!defined('APP')) die('...');

	$array_authors	= $_author->author_list_array();
	$array_users	= $_user->user_shortlist();

// 	print_pre($array_authors);
// 	print_pre($array_users);

	//hariçten gazele hayır
	if($_REQUEST['filter'] == 1)
	{
		unset($list_authors, $list_users, $list_cat_name, $list_content_status, $list_content_limits);

		$author		= myReq('author', 	0);
		$user		= myReq('user', 	0);
		$cat		= myReq('cat', 		0);
		$status		= myReq('status', 	0);
		$limit		= myReq('limit', 	0);
		$filter		= myReq('filter',	0);
		$time		= myReq('time', 	2);
		$keyword	= myReq('keyword', 	2);

		$_SESSION[SES]['article_list']['author']	= $author;
		$_SESSION[SES]['article_list']['user']		= $user;
		$_SESSION[SES]['article_list']['cat']		= $cat;
		$_SESSION[SES]['article_list']['status']	= $status;
		$_SESSION[SES]['article_list']['limit']		= $limit;
		$_SESSION[SES]['article_list']['time']		= $time;
		$_SESSION[SES]['article_list']['keyword']	= $keyword;
		$_SESSION[SES]['article_list']['filter']	= 1;
	}


	foreach($array_authors as $k => $v)
	{
		if($_SESSION[SES]['article_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['article_list']['author'] == $k) $selected = 'selected'; }
		$list_authors.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}
	foreach($array_users as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['user'] == $k) $selected = 'selected'; }
		$list_users.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_cat_name as $k => $v)
	{
		if($_SESSION[SES]['content_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['content_list']['cat'] == $k) $selected = 'selected'; }
		$list_cat_name.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";

	}

	foreach($array_content_status as $k => $v)
	{
		if($_SESSION[SES]['article_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['article_list']['status'] == $k) $selected = 'selected'; }
		$list_content_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_limits as $k => $v)
	{
		if($_SESSION[SES]['article_list']['filter'] == 1) { $selected = ''; if($_SESSION[SES]['article_list']['limit'] == $k) $selected = 'selected'; }
		$list_content_limits.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	//filtreleme istenmişse, filtrelenmiş sonuçları gösteriyoruz
	if($_SESSION[SES]['article_list']['filter'] == 1)
	{
		$list = $_content->article_list_small
		(
			$_SESSION[SES]['article_list']['author'],
			$_SESSION[SES]['article_list']['user'],
			$_SESSION[SES]['article_list']['cat'],
			$_SESSION[SES]['article_list']['status'],
			$_SESSION[SES]['article_list']['time'],
			$_SESSION[SES]['article_list']['keyword'],
			$_SESSION[SES]['article_list']['limit']
		);
	}
	else
	{
		//henüz bir oturuma sahip olmayan bir kullanıcı ise
		//50 tane içerik varsayılan olarak gösteriyoruz
		$list = $_content->article_list_small('-1', '-1', '-1', '-1', '', '', 100);
	}

	$adet	= count($list);
	if($list <> false)
	{
		for($i = 0; $i < $adet; $i++)
		{
			$content_id					= $list[$i]['content_id'];
			$content_title				= $list[$i]['content_title'];
			$content_title_outside		= $list[$i]['content_title_outside'];

			$content_image				= $list[$i]['content_image'];
			$content_image_dir			= $list[$i]['content_image_dir'];
			$content_image_manset		= $list[$i]['content_image_manset'];

			$content_status				= $list[$i]['content_status'];
			$content_template			= $list[$i]['content_template'];
			$content_comment_status		= $list[$i]['content_comment_status'];
			$content_tags				= $list[$i]['content_tags'];
			$content_type				= $list[$i]['content_type'];
			$content_author				= $list[$i]['content_author'];
			$content_user				= $list[$i]['content_user'];
			$content_time				= $list[$i]['content_time'];
			$content_time_f				= $list[$i]['content_time_f'];
			$content_title_seo			= $list[$i]['content_title_seo'];
			$content_title_url			= $list[$i]['content_title_url'];
			$content_view				= $list[$i]['content_view'];
			$content_google_status		= $list[$i]['content_google_status'];

			//bu değeri biz oluşturduk
			$content_url				= $list[$i]['content_url'];

			$content_id_f				= str_pad($content_id, 4, "0", STR_PAD_LEFT);
			$content_status_f			= $array_content_status[$content_status];
			$content_status_icon		= $array_content_status_bar[$content_status];

			$content_author_f			= $array_authors[$content_author];
			$content_user_f				= $array_users[$content_user];

			//resimlerin yolları
			$content_image_path			= IMAGE_DIRECTORY.'content/'.$content_image_dir.$content_image;
			$content_image_manset_path	= IMAGE_DIRECTORY.'manset/'.$content_image_dir.$content_image_manset;

			$link_edit_url				= LINK_ACP.'&amp;view=article&amp;do=edit&amp;id='.$content_id;
			$link_delete_url			= LINK_ACP.'&amp;view=article&amp;action=delete&amp;id='.$content_id;
			$confirm_sorusu				= 'Makale kaydını silmek istediğinizden emin misiniz?';

			//---[+]--- Yetkilendirme ----------------------------------------------
			$hata_delete 					= '';
			$hata_edit 						= '';

			$link_delete 					= '';
			$link_edit 						= '';
			$content_image_f				= '';
			$content_tags_f					= '';
			$content_cat_show_f				= '';
			$content_image_health_f 		= '';
			$error_aspect 					= '';
			$aspect_error_image 			= '';
			$aspect_error_image_manset		= '';
			$aspect_error_image_text		= '';
			$aspect_error_image_manset_text	= '';

			if($_auth['article_delete'] <> '1') 						$hata_delete = '1';
			if($_auth['article_edit'] <> '1') 							$hata_edit = '1';

			if(RC_Authority == 1)
			{
				if($_auth['article_delete_'.$content_author] <> '1')	$hata_delete = '1';
				if($_auth['article_edit_'.$content_author] <> '1')		$hata_edit = '1';
			}


			if($hata_delete <> '1')
			{
				if($_SESSION[SES]['user_id'] == 1)
				{
					$link_delete = '<a target="_blank" rel="noopener noreferrer" href="'.$link_delete_url.'"><i class="btn btn-danger ion ion-android-delete"></i></a>';
				}
				else
				{
					$link_delete = '<a href="'.$link_delete_url.'" onclick="javascript:return confirm(\''.$confirm_sorusu.'\')"><i class="btn btn-danger ion ion-android-delete"></i></a>';
				}
			}

			if($hata_edit <> '1')
			{
				$link_edit = '<a title="Düzenle" href="'.$link_edit_url.'"><i class="btn btn-primary ion ion-edit"></i></a>';
			}

			//---[-]--- Yetkilendirme SONU ----------------------------------------------

			//---[+]--- Resim Alanı Uyarıları -------------------------------------------
			if($content_cat <> 0 or $content_cat2 <> 0 or $content_type <> 0)
			{
				if($content_image <> '')
				{
					$image_sizes = getimagesize($content_image_path);
					if($image_sizes[0] <> $array_content_image_wh['w']) $aspect_error_image = 1;
					if($image_sizes[1] <> $array_content_image_wh['h']) $aspect_error_image = 1;
					if($aspect_error_image == 1)
					{
						$aspect_error_image_text	= 'Resim boyutları hatalı'."\n";
						$error_aspect = 1;
					}
				}

				//manşet resim varsa resim boyutlarını kontrol ediyoruz
				if($content_image_manset <> '' && $array_content_type_required[$content_type] == true)
				{
					$image_sizes = getimagesize($content_image_manset_path);
					if($image_sizes[0] <> $array_content_manset_wh[$content_type]['w']) $aspect_error_image_manset = 1;
					if($image_sizes[1] <> $array_content_manset_wh[$content_type]['h']) $aspect_error_image_manset = 1;
					if($aspect_error_image_manset == 1)
					{
						$aspect_error_image_manset_text	= 'Manşet Resim boyutları hatalı'."\n";
						$error_aspect = 1;
					}
				}

				if($content_image == '') 		$content_image_f 				= '<i title="Resim Eksik" class="btn btn-danger ion ion-alert"></i>';
				if($error_aspect == 1)			$content_image_health_f 		= '<i title="'.$aspect_error_image_text.$aspect_error_image_manset_text.'" class="btn btn-warning ion ion-alert"></i>';
			}
			//if($content_tags == '')				$content_tags_f 				= '<i title="Etiketler Eksik" class="btn btn-warning ion ion-alert"></i>';
			if($content_cat_show_status == 0)	$content_cat_show_f 			= '<i title="Kategori Sayfasında Gizlenmiş" class="btn btn-warning  ion ion-information"></i>';;
			if($content_google_status == 1)		$content_google_f				= '<i title="Google Ziyaret Etti" class="btn btn-success ion ion-social-google"></i>';;

			$sayfa_icerik.='
				<tr>
					<td class="left">'.$content_id_f.'</td>
					<td><a href="'.$content_url.'">'.$content_title.'</a></td>
					<td width="50">'.$content_status_f.' '.$content_status_icon.'</td>
					<td>'.$content_author_f.'</td>
					<td class="right">'.$content_view.'</td>
					<td>
						<p class="hideMe">'.$content_time_f.'</p>
						'.$content_time_f.'
					</td>
					<td class="hideMe">
						'.$content_title_outside.'
						'.$content_title_seo.'
						'.$content_title_url.'
					</td>
					<td width="30" class="center">
						'.$content_google_f.'
						'.$content_image_health_f.'
						'.$content_image_f.'
						'.$content_tags_f.'
					</td>
					<td width="30" class="center">'.$link_delete.'</td>
					<td width="30" class="center">'.$link_edit.'</td>
				</tr>';
		}
	}
	else
	{
		$alert = showMessageBoxS('Herhangi bir kayıt bulunamadı.', 'error');
	}

?>

<!-- 	<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jMetro/jquery-ui.css"> -->
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
				<h3 class="box-title">Makale Yönetimi - Hızlı Filtrele</h3>
			</div>
			<div class="box-body" style="height: 60px;">
				<div class="input-group col-md-6" style="float:left;">
					<input placeholder="Aranacak Kelime" class="form-control" type="text" id="keyword" name="keyword" value="<?=$keyword?>"/>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<input placeholder="Tarih" class="form-control" type="text" id="time" name="time" value="<?=$time?>"/>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="limit" name="limit">
						<?=$list_content_limits?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="author" name="author">
						<option value="-1">Yazar</option>
						<?=$list_authors?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="cat" name="cat">
						<option value="-1">Kategori</option>
						<?=$list_cat_name?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="user" name="user">
						<option value="-1">Ekleyen</option>
						<?=$list_users?>
					</select>
				</div>
				<div class="input-group col-md-3" style="float:left;">
					<select class="form-control" id="status" name="status">
						<option value="-1">Yayın Durumu</option>
						<?=$list_content_status?>
					</select>
				</div>
				<div class="input-group col-md-12" style="float:right; text-align:right;">
					<button class="btn btn-success">Filtrele</button>
				</div>
			</div>
		</form>
		<div class="input-group col-md-12 clearMe"></div>
		<hr class="hr_ince"/>
		<div class="input-group col-md-12 clearMe"></div>


		<?php if($list <> false) : ?>

			<script>
				function toggle(source, type)
				{
					checkboxes = document.getElementsByClassName(type);
					for(var i=0, n=checkboxes.length;i<n;i++) { checkboxes[i].checked = source.checked; }
				}
			</script>

			<script>
				$(document).ready(function()
				{
					$('#recordList_Article').dataTable(
					{
						"aaSorting"		: [[0, "desc", 1]],
						"bPaginate" 	: true,		//sayfalama özelliğini açıp kapatır
						"bLengthChange"	: true, 	//sayı seçimini açıp kapatır
						"bFilter"		: true, 	//arama alanını açıp kapatır
						"bStateSave" 	: true,		//kalınan noktayı kaydetmeye yarar
						"bSort" 		: true, 	//sıralama özelliğini açıp kapatır
						"bInfo"			: true,		//bilgi alanını açıp kapatır
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
				<div class="input-group col-md-12">
					<table id="recordList_Article" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">ID</th>
								<th>Başlık</th>
								<th width="80">Durum</th>
								<th width="120"><i class="ion ion-person"></i> Yazar</th>
								<th width="30"><i title="Ziyaret Sayısı" class="ion ion-refresh"></i></th>
								<th width="100">Tarih</th>
								<th class="hideMe"></th>
								<th class="hideMe"></th>
								<th class="hideMe"></th>
								<th class="hideMe"></th>
							</tr>
						</thead>
						<tbody>
							<?=$sayfa_icerik?>
						</tbody>
					</table>
				</div>
				<div class="input-group col-md12 clearMe"></div>
			</div>
		<?php endif ?>
	</div>
