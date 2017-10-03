<?
require_once 'config.inc.php';
global $config;
session_start();

if ($_GET['id']) $slideshowId = $_GET['id'];
elseif ($_POST['id']) $slideshowId = $_POST['id'];

if ($slideshowId){ 
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	$sql = "SELECT * FROM slideshows WHERE slideshowId = '$slideshowId'";
	$result = mysql_query ("$sql");	
	$num_rows = mysql_num_rows($result);
	if (!$num_rows) { 
		$msg = 'Invalid slideshow id.';
	}
	else {
		$row = mysql_fetch_array($result);
		$fileloc = 'members/'.$row['userId'].'/'; // get the location of the member's slideshow images/descriptions		
		$imagesdir = $fileloc.'images/'.$slideshowId;
		$thumbsdir = $fileloc.'thumbnails/'.$slideshowId;	
		$descsdir = $fileloc.'descriptions/'.$slideshowId;
		
		$images = get_photos($imagesdir);	
		//print_r($images);
		//exit;			
		$numImages = count($images);
				
		if (!$numImages) $msg = 'There are no photos in the slideshow';
		else {
			$titles = array();
			$descriptions = array();
			$names = array();
			for ($i=0; $i < $numImages; $i++) {						
					//echo $images[$i];
					$path_parts = pathinfo($images[$i]);
					$names[] = $path_parts['filename'];
					$descurl = $descsdir.'/'.$path_parts['filename'].'.txt';
					//echo 'desc url '.$descurl;
					// If the file exists then there is a description for the image. Get the contents from the text file
					if (file_exists($descurl)){
						$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
						$contents = str_replace('"',"'",$contents); // replace all double quotes with single quotes because prettyPhoto will crash with double quotes
						//echo 'contents '.$contents; 
						
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
			} // END for loop
		} // END else there are images
	}	// END else valid slideshowId
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Slideshow</title>
<head>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
	<script src="prettyPhoto/js/jquery.prettyPhotoAuto.js" type="text/javascript" charset="utf-8"></script> 
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
	
	<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("area[rel^='prettyPhoto']").prettyPhoto(
					{
						theme: 'facebook', //'light_square', 'pp_default', 'light_rounded', 'facebook',
					}
				);
				$.prettyPhoto.open(gallery,titles,descriptions);
			});
				
    </script>    
</head>

<body >	
 
<!--<meta http-equiv="Refresh" content="0; url=http://<? //echo $config['http_db_server'].$config['root'].'/'.$url.'?msg='.$msg ?>">-->	  
</body>
</html>
<? }
else { ?> 
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/'.$url.'?msg=Missing Slideshow Id' ?>">	
<? 
exit;
} ?>