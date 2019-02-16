<?php
	if(!defined('APP')) die('...');

	if($_auth['log_self'] 	== 1) 			$logSelf 			= $_user->get_user_log($_SESSION[SES]['user_id'], 'self');
	if($_auth['log_others'] == 1)			$logOthers 			= $_user->get_user_log($_SESSION[SES]['user_id'], 'others');
	if($_auth['log_anonim'] == 1)			$logAnonim 			= $_user->get_user_log($_SESSION[SES]['user_id'], 'anonim');
	if($_auth['comment_edit'] == 1)			$commentDraft 		= $_comment->get_comment_draft();
	if($_auth['contact_edit'] == 1)			$contactDraft 		= $_contact->get_contact_draft();
	if($_auth['stat_common'] == 1)			$ortalama_genel 	= $_content->content_haber_ortalama($user = 'none', $day = 30);
	if($_auth['stat_self'] == 1)			$ortalama_user 		= $_content->content_haber_ortalama($_SESSION[SES]['user_id'], $day = 30);

	if($_auth['stat_common'] == 1)
	{
		foreach($ortalama_genel as $k => $v)
		{
			$genel_content_date	.= '"'.$v['content_date'].'",';
			$genel_gunluk_okunma .= $v['gunluk_okunma'].',';
			$genel_gunluk_haber .= $v['gunluk_haber'].',';
		}
	}
	if($_auth['stat_self'] == 1)
	{
		foreach($ortalama_user as $k => $v)
		{
			$user_content_date	.= '"'.$v['content_date'].'",';
			$user_gunluk_okunma .= $v['gunluk_okunma'].',';
			$user_gunluk_haber .= $v['gunluk_haber'].',';
		}
	}
?>

	<?php if($_auth['stat_common'] == 1 or $_auth['stat_self'] == 1) : ?>

		<script src="<?=G_VENDOR_ADMINLTE?>plugins/chartjs/Chart.min.js"></script>
		<script>
			$(function ()
			{
				'use strict';

				var ChartOptions =
				{
					//Boolean - If we should show the scale at all
					showScale: true,
					//Boolean - Whether grid lines are shown across the chart
					scaleShowGridLines: true,
					//String - Colour of the grid lines
					scaleGridLineColor: "rgba(0,0,0,.05)",
					//Number - Width of the grid lines
					scaleGridLineWidth: 1,
					//Boolean - Whether to show horizontal lines (except X axis)
					scaleShowHorizontalLines: true,
					//Boolean - Whether to show vertical lines (except Y axis)
					scaleShowVerticalLines: true,
					//Boolean - Whether the line is curved between points
					bezierCurve: true,
					//Number - Tension of the bezier curve between points
					bezierCurveTension: 0.3,
					//Boolean - Whether to show a dot for each point
					pointDot: false,
					//Number - Radius of each point dot in pixels
					pointDotRadius: 4,
					//Number - Pixel width of point dot stroke
					pointDotStrokeWidth: 1,
					//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
					pointHitDetectionRadius: 20,
					//Boolean - Whether to show a stroke for datasets
					datasetStroke: true,
					//Number - Pixel width of dataset stroke
					datasetStrokeWidth: 2,
					//Boolean - Whether to fill the dataset with a color
					datasetFill: true,
					//String - A legend template
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
					//Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
					maintainAspectRatio: true,
					//Boolean - whether to make the chart responsive to window resizing
					responsive: false
				};

				var Canvas_user_haber = $("#Chart_user_haber").get(0).getContext("2d");
				var Canvas_user_view = $("#Chart_user_view").get(0).getContext("2d");
				var Canvas_genel_haber = $("#Chart_genel_haber").get(0).getContext("2d");
				var Canvas_genel_view = $("#Chart_genel_view").get(0).getContext("2d");

				var Chart_user_haber = new Chart(Canvas_user_haber);
				var Chart_user_view = new Chart(Canvas_user_view);
				var Chart_genel_haber = new Chart(Canvas_genel_haber);
				var Chart_genel_view = new Chart(Canvas_genel_view);

				var ChartData_user_haber =
				{
					labels: [ <?=$user_content_date?>],
					datasets: [
						{
							label: "Haber Sayısı",
							fillColor: "rgb(210, 214, 222)",
							strokeColor: "rgb(210, 214, 222)",
							pointColor: "rgb(210, 214, 222)",
							pointStrokeColor: "#c1c7d1",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgb(220,220,220)",
							data: [<?=$user_gunluk_haber?>]
						}
					]
				};

				var ChartData_user_view =
				{
					labels: [ <?=$user_content_date?>],
					datasets: [
						{
							label: "Okunma Sayısı",
							fillColor: "rgb(210, 214, 222)",
							strokeColor: "rgb(210, 214, 222)",
							pointColor: "rgb(210, 214, 222)",
							pointStrokeColor: "#c1c7d1",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgb(220,220,220)",
							data: [<?=$user_gunluk_okunma?>]
						}
					]
				};

				var ChartData_genel_haber =
				{
					labels: [ <?=$genel_content_date?>],
					datasets: [
						{
							label: "Haber Sayısı",
							fillColor: "rgb(210, 214, 222)",
							strokeColor: "rgb(210, 214, 222)",
							pointColor: "rgb(210, 214, 222)",
							pointStrokeColor: "#c1c7d1",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgb(220,220,220)",
							data: [<?=$genel_gunluk_haber?>]
						}
					]
				};

				var ChartData_genel_view =
				{
					labels: [ <?=$genel_content_date?>],
					datasets: [
						{
							label: "Okunma Sayısı",
							fillColor: "rgb(210, 214, 222)",
							strokeColor: "rgb(210, 214, 222)",
							pointColor: "rgb(210, 214, 222)",
							pointStrokeColor: "#c1c7d1",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgb(220,220,220)",
							data: [<?=$genel_gunluk_okunma?>]
						}
					]
				};

				//Create the line chart
				Chart_user_haber.Line(ChartData_user_haber, ChartOptions);
				Chart_user_view.Line(ChartData_user_view, ChartOptions);
				Chart_genel_haber.Line(ChartData_genel_haber, ChartOptions);
				Chart_genel_view.Line(ChartData_genel_view, ChartOptions);
			});

		</script>
	<?php endif ?>

	<?php if($_auth['log_self'] == 1 or $_auth['log_others'] == 1 or $_auth['log_anonim'] == 1) : ?>
		<script>
			$(document).ready(function()
			{
				$('#logData1').dataTable(
				{
					"aaSorting"		: [[0, "desc", 1]],
					"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
					"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
					"bFilter"		: false, 	//arama alanını açıp kapatır
					"bStateSave" 	: false,	//kalınan noktayı kaydetmeye yarar
					"bSort" 		: false, 	//sıralama özelliğini açıp kapatır
					"bInfo"			: false,	//bilgi alanını açıp kapatır
					//dil dosyalarını yükler
					"oLanguage" 	: { "sUrl": "<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.lang.js"}
				});
				$('#logData2').dataTable(
				{
					"aaSorting"		: [[0, "desc", 1]],
					"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
					"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
					"bFilter"		: false, 	//arama alanını açıp kapatır
					"bStateSave" 	: false,	//kalınan noktayı kaydetmeye yarar
					"bSort" 		: false, 	//sıralama özelliğini açıp kapatır
					"bInfo"			: false,	//bilgi alanını açıp kapatır
					//dil dosyalarını yükler
					"oLanguage" 	: { "sUrl": "<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.lang.js"}
				});
				$('#logData3').dataTable(
				{
					"aaSorting"		: [[0, "desc", 1]],
					"bPaginate" 	: false,	//sayfalama özelliğini açıp kapatır
					"bLengthChange"	: false, 	//sayı seçimini açıp kapatır
					"bFilter"		: false, 	//arama alanını açıp kapatır
					"bStateSave" 	: false,	//kalınan noktayı kaydetmeye yarar
					"bSort" 		: false, 	//sıralama özelliğini açıp kapatır
					"bInfo"			: false,	//bilgi alanını açıp kapatır
					//dil dosyalarını yükler
					"oLanguage" 	: { "sUrl": "<?=G_VENDOR_ADMINLTE?>plugins/datatables/jquery.dataTables.lang.js"}
				});
			});
		</script>
	<?php endif ?>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box text-center box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Hızlı Menü</h3>
					</div>
					<div class="box-body">
						<p></p>
						<?php if($_auth['content_add'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=content&amp;do=add" class="btn btn-app">
								<i class="ion ion-document"></i> Haber Ekle
							</a>
						<?php endif;?>

						<?php if($_auth['content_list'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=content&amp;do=list" class="btn btn-app">
								<i class="ion ion-android-list"></i> Haber Listesi
							</a>
						<?php endif;?>

						<?php if($_auth['gallery_add'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=gallery&amp;do=add" class="btn btn-app">
								<i class="ion ion-image"></i> Galeri Ekle
							</a>
						<?php endif;?>

						<?php if($_auth['gallery_list'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=gallery&amp;do=list" class="btn btn-app">
								<i class="ion ion-android-list"></i> Galeri Listesi
							</a>
						<?php endif;?>
						<?php if($_auth['user_add'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=article&amp;do=add" class="btn btn-app">
								<i class="ion ion-compose"></i> Makale Ekle
							</a>
						<?php endif;?>

						<?php if($_auth['user_list'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=content&amp;do=list" class="btn btn-app">
								<i class="ion ion-android-list"></i> Makale Listesi
							</a>
						<?php endif;?>

						<?php if($_auth['comment_list'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list" class="btn btn-app">
								<i class="ion ion-chatbox"></i> Yorum Listesi
								<?php if($commentDraft > 0) : ?> <span class="badge bg-red"><?=$commentDraft?></span> <?php endif ?>
							</a>
						<?php endif;?>

						<?php if($_auth['contact_list'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list" class="btn btn-app">
								<i class="ion ion-ios-chatbubble"></i> İletişim Mesajları
								<?php if($contactDraft > 0) : ?> <span class="badge bg-orange"><?=$contactDraft?></span><?php endif ?>
							</a>
						<?php endif;?>

						<?php if($_auth['page_list'] == 1) : ?>
							<a href="<?=LINK_ACP?>&amp;view=page&amp;do=list" class="btn btn-app">
								<i class="ion ion-android-document"></i> Sayfalar
							</a>
						<?php endif;?>

						<a href="<?=LINK_ACP?>&amp;view=stats&amp;do=add" class="btn btn-app">
							<i class="ion ion-stats-bars"></i> İstatistikler
						</a>
					</div>
				</div>
			</div>

		</div>
		<div>
			<ol class="breadcrumb">
				<li><a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> Ana Sayfa</a></li>
				<li class="active"><i class="ion ion-arrow-swap"></i> Giriş-Çıkış Kayıtları</li>
			</ol>
		</div>

		<?php if($_auth['comment_edit'] == 1 && $commentDraft > 0) :?>
			<div class="callout callout-warning">
				<h4><i class="ion ion-information-circled"></i>&nbsp; Editör Bilgilendirme</h4>
				<p><a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list"><b><?=$commentDraft?></b> yorum denetim için bekliyor.</a></p>
			</div>

		<?endif?>

		<?php if($_auth['contact_edit'] == 1 && $contactDraft > 0) :?>
			<div class="callout callout-info">
				<h4><i class="ion ion-information-circled"></i>&nbsp; Editör Bilgilendirme</h4>
				<p><a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list"><b><?=$contactDraft?></b> iletişim formu mesajı denetim için bekliyor.</a></p>
			</div>
		<?endif?>

		<?php if($_auth['stat_common'] == 1 && $ortalama_genel <> '') :?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header"> <h3 class="box-title">Genel Haber Sayısı / Okunma Sayısı</h3> </div>
					<div class="box-body"> <div class="chart"> <canvas id="Chart_genel_haber" style="height: 250px;"></canvas> </div> </div>
					<div class="box-body"> <div class="chart"> <canvas id="Chart_genel_view" style="height: 250px;"></canvas> </div> </div>
				</div>
			</div>
		</div>
		<?php endif ?>

		<?php if($_auth['stat_self'] == 1 && $ortalama_user <> '') :?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header"> <h3 class="box-title">Kişi Haber Sayısı / Okunma Sayısı</h3> </div>
					<div class="box-body"> <div class="chart"> <canvas id="Chart_user_haber" style="height: 250px;"></canvas> </div> </div>
					<div class="box-body"> <div class="chart"> <canvas id="Chart_user_view" style="height: 250px;"></canvas> </div> </div>
				</div>
			</div>
		</div>
		<?php endif ?>

		<div class="row">
			<div class="col-xs-12">
				<?php if($_auth['log_self'] == 1 && $logSelf <> '') :?>
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title">Size Ait Giriş-Çıkış Kayıtları</h3>
						</div>
						<div class="box-body">
							<table id="logData1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="150">Tarih</th>
										<th>Log</th>
										<th width="100">İp Adresi</th>
									</tr>
								</thead>
								<tbody>
									<?=$logSelf?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif ?>

				<?php if($_auth['log_others'] == 1 && $logOthers <> '') :?>
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title">Diğer Kullanıcılara Ait Giriş-Çıkış Kayıtları</h3>
						</div>
						<div class="box-body">
							<table id="logData2" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="150">Tarih</th>
										<th>Log</th>
										<th width="100">İp Adresi</th>
									</tr>
								</thead>
								<tbody>
									<?=$logOthers?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif ?>

				<?php if($_auth['log_anonim'] == 1 && $logAnonim <> '') :?>
					<div class="box box-danger">
						<div class="box-header">
							<h3 class="box-title">Anonim Erişim Denemeleri</h3>
						</div>
						<div class="box-body">
							<table id="logData3" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="150">Tarih</th>
										<th>Log</th>
										<th width="100">İp Adresi</th>
									</tr>
								</thead>
								<tbody>
									<?=$logAnonim?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</section>
