<?php 

namespace App\Models;

class Image
{

	public function generate_filename($length)
	{

		$array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$text = "";

		for($x = 0; $x < $length; $x++)
		{

			$random = rand(0,61);
			$text .= $array[$random];
		}

		return $text;
	}

	public function crop_image($original_file_name, $cropped_file_name, $max_width, $max_height, $new_aspect_ratio = null)
	{
		if (file_exists($original_file_name)) {
			$ext = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
	
			if ($ext == "jpg" || $ext == "jpeg") {
				$original_image = imagecreatefromjpeg($original_file_name);
			} elseif ($ext == "png") {
				$original_image = imagecreatefrompng($original_file_name);
			} elseif ($ext == "gif") {
				$original_image = imagecreatefromgif($original_file_name);
			} else {
				return;
			}
	
			$original_width = imagesx($original_image);
			$original_height = imagesy($original_image);
	
			// Calculate the aspect ratio of the original image
			$aspect_ratio = $original_width / $original_height;
	
			// Calculate the new dimensions while preserving aspect ratio
			if ($new_aspect_ratio !== null) {
				// Use the specified new aspect ratio if provided
				$new_width = $max_width;
				$new_height = $max_width / $new_aspect_ratio;
			} else {
				// Otherwise, use the original aspect ratio
				if ($aspect_ratio > 1) {
					$new_width = $max_width;
					$new_height = $max_width / $aspect_ratio;
				} else {
					$new_width = $max_height * $aspect_ratio;
					$new_height = $max_height;
				}
			}
	
			// Create a new image with the calculated dimensions
			$new_image = imagecreatetruecolor($new_width, $new_height);
	
			// Crop the image to fit within the specified dimensions
			$x = 0;
			$y = 0;
			if ($new_width > $max_width) {
				$x = ($new_width - $max_width) / 2;
			}
			if ($new_height > $max_height) {
				$y = ($new_height - $max_height) / 2;
			}
	
			imagecopyresampled($new_image, $original_image, 0, 0, $x, $y, $max_width, $max_height, $new_width, $new_height);
	
			imagedestroy($original_image);
	
			// Save the cropped image
			if ($ext == "jpg" || $ext == "jpeg") {
				imagejpeg($new_image, $cropped_file_name, 90);
			} elseif ($ext == "png") {
				imagepng($new_image, $cropped_file_name, 9);
			} elseif ($ext == "gif") {
				imagegif($new_image, $cropped_file_name);
			}
	
			imagedestroy($new_image);
		}
	}
	


	//resize the image
	public function resize_image($original_file_name,$resized_file_name,$max_width,$max_height)
	{

		if(file_exists($original_file_name))
		{
			$ext = strtolower(pathinfo($original_file_name,PATHINFO_EXTENSION));

			if($ext == "jpg" || $ext == "jpeg"){
				$original_image = imagecreatefromjpeg($original_file_name);
 			}elseif($ext == "png"){
 				$original_image = imagecreatefrompng($original_file_name);
 			}elseif($ext == "gif"){
				$original_image = imagecreatefromgif($original_file_name);
 			}else{
 				return;
 			}

			$original_width = imagesx($original_image);
			$original_height = imagesy($original_image);

			if($original_height > $original_width)
			{
				//make width equal to max width;
				$ratio = $max_width / $original_width;

				$new_width = $max_width;
				$new_height = $original_height * $ratio;

			}else
			{

				//make width equal to max width;
				$ratio = $max_height / $original_height;

				$new_height = $max_height;
				$new_width = $original_width * $ratio;
			}
 
		//adjust incase max width and height are different
		if($max_width != $max_height)
		{

			if($max_height > $max_width)
			{

				if($max_height > $new_height)
				{
					$adjustment = ($max_height / $new_height);
				}else
				{
					$adjustment = ($new_height / $max_height);
				}

				$new_width = $new_width * $adjustment;
				$new_height = $new_height * $adjustment;
			}else
			{

				if($max_width > $new_width)
				{
					$adjustment = ($max_width / $new_width);
				}else
				{
					$adjustment = ($new_width / $max_width);
				}

				$new_width = $new_width * $adjustment;
				$new_height = $new_height * $adjustment;
			}
		}

		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

		imagedestroy($original_image);

		imagejpeg($new_image,$resized_file_name,90);
		imagedestroy($new_image);
		}
	}

	//create thumbnail for cover image
	public function get_thumb_cover($filename)
	{

		$thumbnail = $filename . "_cover_thumb.jpg";
		if(file_exists($thumbnail))
		{
			return $thumbnail;
		}

		$this->crop_image($filename,$thumbnail,1366,488);

		if(file_exists($thumbnail))
		{
			return $thumbnail;
		}else
		{
			return $filename;
		}
	}

	//create thumbnail for profile image
	public function get_thumb($filename)
	{

		$thumbnail = $filename;
		if(file_exists($thumbnail))
		{
			return $thumbnail;
		}

		$this->crop_image($filename,$thumbnail,600,600);

		if(file_exists($thumbnail))
		{
			return $thumbnail;
		}else
		{
			return $filename;
		}
	}


	//create thumbnail for post image
	public function get_thumb_post($filename)
	{

		$thumbnail = $filename;
		if(file_exists($thumbnail))
		{
			return $thumbnail;
		}

		$this->crop_image($filename,$thumbnail,700,974);

		if(file_exists($thumbnail))
		{
			return $thumbnail;
		}else
		{
			return $filename;
		}
	}


}