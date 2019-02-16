<?php if(!defined('APP')) die('...');

	//admin değilse bu sayfaya hiç erişemesin
	$_user->check_admin();

	function cropImage
	(
		$from,
		$to,
		$targ_w,
		$targ_h,
		$startX,
		$startY,
		$width,
		$height
	)
	{
		$targ_w = number_format($targ_w, 0, '', '');
		$targ_h = number_format($targ_h, 0, '', '');
		$startX = number_format($startX, 0, '', '');
		$startY = number_format($startY, 0, '', '');
		$width = number_format($width, 0, '', '');
		$height = number_format($height, 0, '', '');

		if(RC_Imagemagick <> 1)
		{
			$img_r = imagecreatefromjpeg($from);
			$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
			imagecopyresampled($dst_r, $img_r, 0, 0, $startX, $startY, $targ_w, $targ_h, $width, $height);
			imagejpeg($dst_r, $to, 100);
			//orjinal resim ile işimiz bitti, silelim
			//bakım fonksiyonları ile geri gelip silme ihtiyacı kalmasın
			/**
			* TODO
			* Hiç de güvenli olmayan bir metotla, dışarıdan gelen
			* değerleri kontrol etmeden dosya silmek intihardır
			*/
			unlink($from);
		}
		else
		{
			$im = new \Imagick(realpath($from));
			$im->cropImage($width, $height, $startX, $startY);

			//düz yöntemde resmi yeni boyutuna küçültüyoruz
			$im->thumbnailImage($targ_w, $targ_h, false);

			//resmi tekrar yerine yazıyoruz
			$im->writeImage($to);

			//kaynak resmi siliyoruz
			/**
			* TODO
			* Hiç de güvenli olmayan bir metotla, dışarıdan gelen
			* değerleri kontrol etmeden dosya silmek intihardır
			*/
			unlink($from);
		}
	}

	function thumbImage
	(
		$from,
		$to,
		$targ_w
	)
	{

		if(RC_Imagemagick <> 1)
		{
			include $_SERVER['DOCUMENT_ROOT'].'vendors/classes/class.thumbnail.php';
			$tn_image = new Thumbnail($from, $targ_w, 0, 100);
			$tn_image->save($to);
		}
		else
		{
			$im = new \Imagick(realpath($from));

			//düz yöntemde resmi yeni boyutuna küçültüyoruz
			$im->thumbnailImage($targ_w, null, false);

			//resmi tekrar yerine yazıyoruz
			$im->writeImage($to);
		}
	}


	$type				= myReq('type', 1);
	$img				= myReq('img', 1);
	$cropped			= myReq('cropped', 1);
 	$content_image_dir	= myReq('content_image_dir', 1);
	$content_type		= myReq('content_type', 2);

	if($type == 'author')
	{
		$dir 				= 'author/';
		$input 				= 'org_author_image';
		$targ_w 			= $array_author_image_wh['w'];
		$targ_h 			= $array_author_image_wh['h'];
	}

	if($type == 'content')
	{
		$dir 			= 'content/';
		$input 			= 'org_content_image';
		$targ_w 		= $array_content_image_wh['w'];
		$targ_h 		= $array_content_image_wh['h'];
	}

	if($type == 'manset')
	{
		$dir 			= 'manset/';
		$input 			= 'org_content_image_manset';
		$targ_w 		= $array_content_manset_wh[$content_type]['w'];
		$targ_h 		= $array_content_manset_wh[$content_type]['h'];
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$cropped_name	= 'cropped_'.$img;
		$src			= IMAGE_DIRECTORY.$dir.$content_image_dir.$img;
		$src_cropped	= IMAGE_DIRECTORY.$dir.$content_image_dir.$cropped_name;
		$img_small_path	= IMAGE_DIRECTORY.'thumbs/'.$content_image_dir.$cropped_name;
		$img 			= $cropped_name;

		cropImage
		(
			$from 	= $src,
			$to 	= $src_cropped,
			$targ_w,
			$targ_h,
			$_REQUEST['x'],
			$_REQUEST['y'],
			$_REQUEST['w'],
			$_REQUEST['h']
		);


		if($type == 'content')
		{
			thumbImage
			(
				$from 	= $src_cropped,
				$to 	= $img_small_path,
				$targ_w = 350
			);
		}
	}

	$ratio = $targ_w / $targ_h;

?>
<!DOCTYPE HTML>
<html lang="tr">
	<head>
		<!-- Uyumluluk kipini kapatmaya zorla -->
		<!--[if IE 8]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"><![endif]-->
		<!--[if IE 9]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9"><![endif]-->
		<!--[if IE 10]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10"><![endif]-->
		<!--[if IE 11]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11"><![endif]-->
		<!--[if IE 12]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE12"><![endif]-->
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<title>Resmi Kırp</title>
		<script src="<?=G_VENDOR_JQUERY?>jquery.js"></script>
		<script src="<?=G_VENDOR_JQUERY?>jCrop/js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="<?=G_VENDOR_JQUERY?>jCrop/css/jquery.Jcrop.css"/>

<script>
	$(function()
	{
		$('#cropbox').Jcrop({
			aspectRatio: <?=$ratio?>,
			onSelect: updateCoords
		});
	});

	function updateCoords(c)
	{
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};

	function checkCoords()
	{
		if(parseInt($('#w').val())) return true;
		alert('Lütfen, kırpılacak alanı seçiniz.');
		return false;
	};
</script>

<style>
#target
{
	background-color: #ccc;
	width: 500px;
	height: 330px;
	font-size: 24px;
	display: block;
}
</style>
</head>

<body>
<img src="<?=G_IMGLINK.$dir.$content_image_dir.$img?>?<?=time()?>" id="cropbox"/>

<?php if($cropped <> 1) : ?>
<form action="" method="post" onsubmit="return checkCoords();">
<input type="hidden" name="page" value="crop"/>
<input type="hidden" name="type" value="<?=$type?>"/>
<input type="hidden" name="img" value="<?=$img?>"/>
<input type="hidden" name="content_image_dir" value="<?=$content_image_dir?>"/>


<input type="hidden" id="x" name="x"/>
<input type="hidden" id="y" name="y"/>
<input type="hidden" id="w" name="w"/>
<input type="hidden" id="h" name="h"/>
<input type="hidden" name="cropped" value="1"/>
<input style="margin-top:10px;" type="submit" value="Resmi Kırp"/>
</form>
<? endif ?>
<script>
function stopUpload(imgName)
{
var result = '';
if(imgName)
{
result = imgName;
}
window.opener.document.forms['form1'].<?=$input?>.value = result;
window.close();
}
</script>

<?php if($cropped == 1) : ?>
	<input style="margin-top:10px;" type="button" type="submit" value="Fotoğrafı Aktar ve Devam Et" onclick="stopUpload('<?=$img?>')">
<?php endif ?>
</body>
</html>
