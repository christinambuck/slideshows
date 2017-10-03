<?
session_start();

$row['userId'] = 1;
$row['title'] = 'kitchen';

//if (!$_GET['id']) $msg = 'Album Id is required.';
//else {
//	require_once 'config.inc.php';
//	$sql = "SELECT title, userId FROM albums WHERE album_id = '$_GET[id]'";
//	$result = mysql_query ("$sql");	
//	$num_rows = mysql_num_rows($result);
//	if (!$num_rows)  
//		$msg = 'Invalid Album Id.';
//	else {
//		$row = mysql_fetch_array($result);
		$userId = $row['userId'];
		$folder = $row['title'];
		$folder = str_replace (' ','_',$folder);
		$fileloc = 'members/'.$userId.'/'; // get the location of the member's album images/descriptions 
		$imagesdir = $fileloc.'images/'.$folder;
		$thumbsdir = $fileloc.'thumbnails/'.$folder;	
		$descsdir = $fileloc.'descriptions/'.$folder;	
		$dir =scandir($imagesdir);
		if (!count($dir)) $msg = 'There are no photos in the album';
		else {
			$images = array();
			$titles = array();
			$descriptions = array();
			$names = array();
			for ($i=0; $i < count($dir); $i++) {
				if ($dir[$i] != '.' && $dir[$i] != '..') {
					$images[] = $imagesdir.'/'.$dir[$i];
					$path_parts = pathinfo($dir[$i]);
					$names[] = $path_parts['filename'];
					$descurl = $descsdir.'/'.$path_parts['filename'].'.txt';
					// If the file exists then there is a description for the image. Get the contents from the text file
					if (file_exists($descurl)){
						$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
						$contents = str_replace('"',"'",$contents); // replace all double quotes with single quotes because prettyPhoto will crash with double quotes
						$pos = strpos($contents, ';;');
						if ($pos == 0) $titles[] = '';
						else $titles[] = substr($contents,0,$pos);
						if (strlen($contents) > $pos+2)
							$descriptions[] = substr($contents,$pos+2);
						else $descriptions[] = '';
					}
					else {
						$titles[] = '';
						$descriptions[] = '';
					}	
				} // END if it is an image file		
			} // END for loop
			$numImages = count($images);
		} // END else there are images
	//}			
//} // END if view album was clicked
			
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>View Albums</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<link rel="stylesheet" href="prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script src="prettyPhoto/js/jquery.prettyPhotoAuto.js" type="text/javascript" charset="utf-8"></script>
	<link href="Impromptu/viva-imp.css.php" rel="stylesheet" type="text/css" />	
	<script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script>
</head>
<body id="viewAlbum" <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?> >
    <? if (!$msg) { ?>			
		<script type="text/javascript" charset="utf-8">
		<? 
			$imagestring = 'gallery=[';
			$titlestring = 'titles=[';
			$descstring = 'descriptions=[';
			for ($i=0; $i<$numImages; $i++){
				$imagestring .= '"'.$images[$i].'",';
				$titlestring .= '"'.$titles[$i].'",';
				$descstring .= '"'.$descriptions[$i].'",';
			}
			$imagestring = substr($imagestring,0,-1).'];';
			$titlestring = substr($titlestring,0,-1).'];';
			$descstring = substr($descstring,0,-1).'];';
			echo $imagestring;
			echo $titlestring;
			echo $descstring;
		?>		
			
        </script>  
	<? } ?>	
	
	<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("area[rel^='prettyPhoto']").prettyPhoto();
				$.prettyPhoto.open(gallery,titles,descriptions);
			});
				
    </script>   
   
</body>
</html>
