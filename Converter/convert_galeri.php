<?php
	/**
	| Root klasör altındaki php dosyaları SADECE çağrı dosyalarıdır
	| asıl dosyayı include etmekten başka görevleri yoktur
	| asıl dosyalarımız app/ klasörü altında bulunur
	| bu sayede yazdığımız gerçek kodlar tamamen app/ klasörü altında
	| depolanmış omaktadır
	*/
	die();
	define('APP', '1');

	//uzun çalışsın
	ini_set('max_execution_time', 0);

	set_time_limit(0);

	//türkiyeye geçelim
	date_default_timezone_set('Europe/Istanbul');

	//sayfa saatini başlatıyoruz
	$starttime = microtime(true);

	//--- [+]--- Root Path Doğrulaması ---
	$_SERVER['DOCUMENT_ROOT'] = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT']);
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
	{
		$_SERVER['DOCUMENT_ROOT'] .= '/';
	}
	//--- [-]--- Root Path Doğrulaması sonu ---

	//öncel ayarlar ayrı dosyaya alındı, bu sayede
	//init dosyasındaki yapılan değişikliklerden
	//yayındaki site daha az etkileniyor
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.config.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.prefunctions.php';
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/lib.session.php';

	//hata bastırma şeklini belirliyoruz
 	error_reporting(E_ERROR);
	//error_reporting(E_ALL);

	//cache de sayfa yoksa bu kısım işlemeye başlayacak
	//sayfayı iteleyelim
	include $_SERVER['DOCUMENT_ROOT'].'app/lib/init.php';

	function convert_galeri($_id)
	{
		/**
		Haber bilgilerini uzak sunucudan çeker
		*/

		global $conn;

		$sql = '
				SELECT
					id,
					photo_image,
					content_image_dir
				FROM
					'.T_GALLERY_IMAGES.','.T_CONTENT.'
				WHERE
					'.T_GALLERY_IMAGES.'.gallery_id = '.T_CONTENT.'.content_id
				AND
					id = ?
					';
		//print_pre($sql);
		$list = $conn->GetRow($sql, array($_id));

		if($list['id'] == $_id)
		{
			$new_file_path = IMAGE_DIRECTORY.'gallery/'.$list['content_image_dir'].$list['photo_image'];

			echo $new_file_path;

			print_pre($list);

			//her ihtimale karşı nesneyi boşaltalım
			unset($image_sizes);

			$image_sizes	= getimagesize($new_file_path);

			//içerik view tablosuna ekleniyor
			$record = array(
				'photo_width' 			=> $image_sizes[0],
				'photo_height' 			=> $image_sizes[1],
			);
			print_pre($record);
			$conn->AutoExecute(T_GALLERY_IMAGES, $record, 'UPDATE', 'id='.$_id);
		}
	}

	//yönlendirme
	//convert_galeri(13166);

	if($_REQUEST['submited'] == 1)
	{
		$start = intval($_REQUEST['start']);
		$limit = intval($_REQUEST['limit']);
		//echo $start;
		//echo $limit;

		for($i = $start; $i > ($start-$limit); $i--)
		{
			//echo $start;
			convert_galeri($i);
		}
	}

	?>
	<form name="form1" action="convert_galeri.php" method="get">
		<input type="hidden" name="submited" value="1">
		<input required type="number" name="start" value="<?=$start-$limit?>" placeholder="Başlangıç Sayısı"/>
		<input required type="number" name="limit" value="<?=$limit?>" placeholder="Kaç Adet İçerik Dönüştürülsün"/>
		<input type="submit" value="işleme başla"/>
	</form>
	<?php
		echo '<hr>';
		echo debug_min();
	?>
