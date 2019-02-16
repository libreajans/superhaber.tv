<?php
	/**
	* SimpleImage.php
	* Author: Simon Jarvis
	* Modified by : Sabri Unal / yakushabb@gmail.com
	* Copyright: 2006 Simon Jarvis
	* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
	*/

	class SimpleImage
	{
		var $image;
		var $image_type;

		function load($filename)
		{
			$image_info = getimagesize($filename);
			$this->image_type = $image_info[2];
			if($this->image_type == IMAGETYPE_JPEG)
			{
				$this->image = imagecreatefromjpeg($filename);
			}
			elseif($this->image_type == IMAGETYPE_GIF)
			{
				$this->image = imagecreatefromgif($filename);
			}
			elseif($this->image_type == IMAGETYPE_PNG)
			{
				$this->image = imagecreatefrompng($filename);
			}
		}


		function save_new($oldfilename, $newfilename, $mkdir = null)
		{
			//mkdir ile gelen değerleri / ile diziye alıp,
			//count sayısınca son 3 tanesine
			//klasörü var mı diye kontrol etmek gerekiyor
			if($mkdir != null)
			{
				mkdir($mkdir, 0755);
				chmod($mkdir, 0755);
			}
			/**
			* Bu şekliyle resmi hiç dokunmadan sadece kopyalamış oluyoruz
			* Güvenlik açısından olumsuz lakin resmin orjinal haline dokunmaması açısından
			* Çok daha efektif
			*/
			copy($oldfilename, $newfilename);
		}

		function save($filename, $mkdir = null, $image_type = IMAGETYPE_JPEG, $compression = 100, $permissions = null)
		{

			if($mkdir != null)
			{
				mkdir($mkdir, 0755);
				chmod($mkdir, 0755);
			}

			if($image_type == IMAGETYPE_JPEG)
			{
				imagejpeg($this->image, $filename, $compression);
			}
			elseif($image_type == IMAGETYPE_GIF)
			{
				imagegif($this->image, $filename);
			}
			elseif($image_type == IMAGETYPE_PNG)
			{
				imagepng($this->image, $filename);
			}

			if($permissions != null)
			{
				chmod($filename, $permissions);
			}
		}

		function output($image_type = IMAGETYPE_JPEG)
		{
			if($image_type == IMAGETYPE_JPEG)
			{
				imagejpeg($this->image);
			}
			elseif($image_type == IMAGETYPE_GIF)
			{
				imagegif($this->image);
			}
			elseif($image_type == IMAGETYPE_PNG)
			{
				imagepng($this->image);
			}
		}

		function getWidth()
		{
			return imagesx($this->image);
		}

		function getHeight()
		{
			return imagesy($this->image);
		}

		function resizeToHeight($height)
		{
			$ratio = $height / $this->getHeight();
			$width = $this->getWidth() * $ratio;
			$this->resize($width, $height);
		}

		function resizeToWidth($width)
		{
			$ratio = $width / $this->getWidth();
			$height = $this->getheight() * $ratio;
			$this->resize($width, $height);
		}

		function scale($scale) {
			$width = $this->getWidth() * $scale / 100;
			$height = $this->getheight() * $scale / 100;
			$this->resize($width, $height);
		}

		function resize($width,$height)
		{
			$new_image = imagecreatetruecolor($width, $height);
			if($this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG)
			{
				$current_transparent = imagecolortransparent($this->image);
				if($current_transparent != -1)
				{
					$transparent_color = imagecolorsforindex($this->image, $current_transparent);
					$current_transparent = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
					imagefill($new_image, 0, 0, $current_transparent);
					imagecolortransparent($new_image, $current_transparent);
				}
				elseif($this->image_type == IMAGETYPE_PNG)
				{
					imagealphablending($new_image, false);
					$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
					imagefill($new_image, 0, 0, $color);
					imagesavealpha($new_image, true);
				}
			}
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
			$this->image = $new_image;
		}

		function kut($filename, $image_type = IMAGETYPE_JPEG, $compression = 100, $permissions = null)
		{
			if($image_type == IMAGETYPE_JPEG)
			{
				imagejpeg($this->image, $filename, $compression);
			}
			elseif($image_type == IMAGETYPE_GIF)
			{
				imagegif($this->image, $filename);
			}
			elseif($image_type == IMAGETYPE_PNG)
			{
				imagepng($this->image, $filename);
			}
			if($permissions != null)
			{
				chmod($filename, $permissions);
			}
		}

	}
