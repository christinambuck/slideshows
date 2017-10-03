<?
/*
	Only upload images of type gif, jpg, jpeg, or png.
	Max files size is 8MB.
	If $overwrite = true, a duplicate filename will overwrite.
	
	I had to use a newer version of php on my host and up the max limits to allow 10 images each around 2MB in size to upload without crashing.
*/

function imageUpload($destDir,$fieldName,$overwrite)
{ 

	$file = $_FILES[$fieldName];
	if (($file["type"] != "image/gif") && ($file["type"] != "image/jpeg") && ($file["type"] != "image/pjpeg") && ($file["type"] != "image/png"))
		return array(false,$file["name"].' must be of type GIF, JPEG, or PNG.\n');
	if ($file["size"] > 8*(1024 * 1024))
		return array(false,$file["name"].' is to large. Max size is 8 MB.\n');
	if ($file["error"] > 0)
		return array(false,'Error uploading '.$file["name"].': Return Code= '.$file["error"].'\n');
	if( substr($destDir, -1) != "/")
       $destDir = $destDir.'/';	   	
	$file['name'] = str_replace(" ","",$file['name']); // remove all embedded spaces
	$file['name'] = str_replace(")","-",$file['name']); // remove all embedded (
	$file['name'] = str_replace("(","-",$file['name']); // remove all embedded )
	if (file_exists($destDir.$file["name"])){
		if ($overwrite) {
			//unlink($destDir.$file['name']);
			$duplicate = true;			
		}
		else
			return array(false,$file["name"] . " already exists.\n");
	}
	if(!@copy($file['tmp_name'], $destDir.$file['name']))
   		return array(false,'Could not write the file "'.$file['name'].'" to: "'.$destDir.'".');
	else {
		$file['name'] = $destDir.$file['name'];
		if ($duplicate)
			return array(true,$file['name'],true);
		else
			return array(true,$file['name'],false);
	}
}
?>
