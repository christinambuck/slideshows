<?php
echo 'sid = '.$_SESSION['slideshowId'].' : '.$_SESSION['userId'].'<br>';
if ($_SESSION['slideshowId'] && $_SESSION['userId'])
{
		require_once '../config.inc.php';			
		require_once '../phpfunctions.php';	
		$duplicate = FALSE;
		$fileloc = 'members/'.$_SESSION['userId'].'/'; 	
		$imageloc = $fileloc.'images';					
		$targetPath = $imageloc.'/'.$_SESSION['slideshowId'].'/';
		
		if (!empty($_FILES)) {     
			$tempFile = $_FILES['file']['tmp_name']; 
			$image =  $targetPath. $_FILES['file']['name'];
			if(file_exists($image))
			{ 
				$duplicate = TRUE;
			}
			move_uploaded_file($tempFile,$image);
			$thumbimage = str_replace ('/images/','/thumbnails/',$image);
			// insert image file name into photos.txt unless it is a duplicate
			if (!$duplicate) {
				$fh = fopen($imageloc.'/photos.txt', 'a');
				fwrite($fh, $image.',');
				fclose($fh);
			}		
			resizeToFile ($image, 1024, 768, $image, 85);					// resize and optimize the photo
			resizeToFile ($image, 120, 120, $thumbimage, 50); 				// create a thumbnail version of the photo
			$upImages[]= str_replace('..',$config['http_db_server'], $image); // replace all occurances of .. with the website.com name so that  we have the full url of the image
			$thumbimages[] = $thumbimage;
			
			$path_parts = pathinfo($image);
			$descurl = str_replace('images','descriptions',$path_parts['dirname']);
			$descurl = $descurl.'/'.$path_parts['filename'].'.txt';
			
			$descurls[] = $descurl;
			
		} // END if files to upload

}
else echo 'no slideshow id';




?>