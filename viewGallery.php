<!-- viewGallery.php - This program is called by gallery.php. It displays all the photos in the slideshow in a gallery 
	format.

-->
<?
require_once 'config.inc.php';	
require_once 'phpfunctions.php';
global $config;
session_start();

if ($_GET['slideshowPW']) $password = $_GET['slideshowPW'];
elseif ($_POST['slideshowPW']) $password = $_POST['slideshowPW'];

if ($_GET['id']) $slideshowId = $_GET['id'];
elseif ($_POST['id']) $slideshowId = $_POST['id'];

if ($_GET['url']) $url = $_GET['url'];
elseif ($_POST['url']) $url = $_POST['url'];
else $url = 'search.php?id='.$slideshowId;

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
		if (!$row['password'] || $row['password'] == $password) { 
			// Update lastLogin date 	
			$today = date('Ymd');
			$sql = "UPDATE members SET lastLogin=$today WHERE userId = $row[userId]";
			$result = mysql_query("$sql");
			
			$fileloc = 'members/'.$row['userId'].'/'; // get the location of the member's slideshow images/descriptions		
			$imagesdir = $fileloc.'images/'.$slideshowId;
			$thumbsdir = $fileloc.'thumbnails/'.$slideshowId;	
			$descsdir = $fileloc.'descriptions/'.$slideshowId;
			
			$images = get_photos($imagesdir);
			$numImages = count($images);
					
			if (!$numImages) $msg = 'There are no photos in the slideshow';
			else {
				$titles = array();
				$descriptions = array();
				$width = array();
				$newWidth = array();
				$height = array();
				$newHeight = array();
				for ($i=0; $i < $numImages; $i++) {	
						list($w, $h, $type, $attr) = getimagesize($images[$i]);
						$width[$i] = $w;
						$height[$i] = $h;
						$new = getNewImageSize (320,240,$images[$i]);
						$newWidth[$i] = $new['width'];
						$newHeight[$i] = $new['height'];
						$path_parts = pathinfo($images[$i]);
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
		} // END if not password required
	}	// END else valid slideshowId
	?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
	<title>Gallery</title>
	<head>
		<link href="css/oldStyle.css" rel="stylesheet" type="text/css" />
		<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
		<script src="Impromptu/jquery-impromptu-ext.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/slideshows.js"></script>
        <? if ($row['password']  && $row['password'] != $password){ ?>
        	<script type='text/javascript'>
				var txt1 = 'Password Required<br />'
				+'<form name="slideshowPWForm" id="slideshowPWForm"> <input type="password" name="slideshowPW"/> <input type="hidden" name="id"  value="<? echo $slideshowId; ?>"/> <input type="hidden" name="url"  value="<? echo $url; ?>"/>  </form>';
				function callbackPW(v,m,f){
					if(v == 'slideshowPWForm')
						document.getElementById('slideshowPWForm').submit(); 
				}
			</script>       
			
		 <? } ?>    
	</head>
	<body id="galleryPage" <? if ($row['password'] && $row['password'] != $password) { ?> onLoad="$.promptExt(txt1,{ submit: callbackPW, buttons: { 'Submit':'slideshowPWForm' } })" <? } ?> >
    	
        <h2 style="text-align:center"><? echo $row['title']; ?></h2>
        <div style="position:relative; width:680px; height:550px; overflow:auto;">
		<?
        $i = 0;	
        $row = 0;
        $col = 0;
		if ($numImages) {	
			foreach ( $images as $filename ) { 
			?>
				<div style="
                	background-color:#edddd1; 
                    position:absolute; 
                    top:<? echo $row * 328; ?>px; 
                    left:<? if ($col) echo $col * 332; else echo 0; ?>px; 
                    width:<? if ($col) echo "328"; else echo "332"; ?>px; 
                    height:328px;">
                    <div id="div<? echo $i ?>" style="
                    	background-color:#FFF; 
                        position:absolute; 
                        width:324px; 
                        top:<? if ($row) echo "0"; else echo "4"; ?>px; 
                        left:<? if ($col) echo "0"; else echo "4"; ?>px; 
						height:<? if ($row) echo "324"; else echo "320"; ?>px;">
                        <div style="height:20px; width:324px">
                        	<h3><? echo $titles[$i]; ?></h3>
                        </div>
                        <div style="
                        	cursor:pointer; 
                            height:240px; 
                            width:324px" 
                            onclick="window.open('<? echo $images[$i]; ?>', 'photo<? echo $i; ?>', 'width=<? echo $width[$i]; ?>, height=<? echo $height[$i]; ?>');" 
                            title="Click to enlarge">
                            <img style=" 
                                width: <? echo $newWidth[$i]; ?>px;
                                height: <? echo $newHeight[$i]; ?>px;
                                margin-top: <? echo (240 - $newHeight[$i])/2; ?>px;
                                margin-left: <? echo ((320- $newWidth[$i])/2)+3; ?>px;" 
                                src="<? echo $images[$i]; ?>" 
                                width="<? echo $width[$i]; ?>" 
                                height="<? echo $height[$i]; ?>" />
                        </div>
                        <div style="height:60px; width:324px; text-align:center;"><? echo $descriptions[$i]; ?></div>
                    </div>
                </div>
			<? 
				
				if ($col) $row++;		//	go to next row if we just did the second column
				$col = ($col) ? 0 : 1; 	// toggle the column	
				$i++;
			} // END foreach 
		} // END if numimages ?>            
        </div>		  
	</body>
	</html>
<? } else { ?> 
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/'.$url.'?msg=Missing Slideshow Id' ?>">	
<? exit; } ?>