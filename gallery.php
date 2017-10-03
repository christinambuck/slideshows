<!-- gallery.php - This page uses jquery-popup-overlay as a modal box to display the gallery of photos. When the user closes the modal window it will 				         take the user back to the page that called this one which is passed to this page in the post url field. If there is no url 
         parm then it takes the user to the search page and displays all the slideshows by the same member that created the
         gallery they just saw. When the user closes the gallery modal window. It submits a form with post data back to the page contained in $_POST[url]. If the previous page was beforeAfter.php then $_POST[url2] will contain the name of the page called beforeAfter.php and url2 will be submitted back to beforeAfter.php as a post data field so that when the beforeAfter.php modal window is  closed it knows what page to go back to.
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

if ($_POST['url2']) $url2 = $_POST['url2'];

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
		if ($row['password'] && $row['password'] != $password) { 
			if ($password) 
				$msg = 'Invalid Password';
			else
				$msg = 'Password Required';
			$pw_required = true;
		}
		else {
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
						$new = getNewImageSize (310,240,$images[$i]);
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
} // END if slideshow id was passed the this program
else $msg = 'Missing Slideshow Id';
if ($msg) {
	if (!$pw_required) { 
		header( "Location: $url?msg=$msg&id=$slideshowId" );	
		exit; 
	}
	else { ?>
		<!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Gallery</title>
        <head>
            <!-- Mobile viewport -->
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
            
            <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
            <link href="css/gallery.css" rel="stylesheet" type="text/css" />
            <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
            <link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
            <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
            <script src="Impromptu/jquery-impromptu-ext.js" type="text/javascript"></script>
            <script type="text/javascript" src="js/slideshows.js"></script>
			<script type='text/javascript'>
                var txt1 = '<? echo $msg; ?><br />'
                +'<form name="slideshowPWForm" id="slideshowPWForm" method="post"> <input type="password" name="slideshowPW"/> <input type="hidden" name="id"  value="<? echo $slideshowId; ?>"/> <input type="hidden" name="url"  value="<? echo $url; ?>"/>  </form>';
                function callbackPW(v,m,f){
                    if(v == 'slideshowPWForm')
                        document.getElementById('slideshowPWForm').submit(); 
                }
            </script> 
        </head>
        <body id="galleryPage" onLoad="$.promptExt(txt1,{ submit: callbackPW, buttons: { 'Submit':'slideshowPWForm' } })">	
        </body>
        </html>
	<? }	
} else { ?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
	<title>Gallery</title>
	<head>
        <!-- Mobile viewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
        
		<link href="css/newStyle.css" rel="stylesheet" type="text/css" />
		<link href="css/gallery.css" rel="stylesheet" type="text/css" />
   		<link href="css/media-queries.css" rel="stylesheet" type="text/css" />
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/slideshows.js"></script> 
        <!-- Include jQuery Popup Overlay -->
        <script src="jquery-popup-overlay/jquery.popupoverlay.js"></script>
       
	</head>
	<body id="galleryPage" >
        	<div id="modal"> 
            	<a href="#" class="modal_close" style="float:right;margin-right:10px"><img src="images/close-button.png" width="25" height="25" title="Close"></a>
              	<h2><? echo $row['title']; ?></h2>
                <ul id="gallery">    
                <?
                $i = 0;
                if ($numImages) {	
                    foreach ( $images as $filename ) { 
                    ?> 
                        <li id="div<? echo $i ?>"  title="Click to enlarge." onclick="window.open('<? echo $images[$i]; ?>', 'photo<? echo $i; ?>', 'width=<? echo $width[$i]; ?>, height=<? echo $height[$i]; ?>');">
                            <h6><? echo $titles[$i]; ?></h6>
                            <img style=" 
                                width: <? echo $newWidth[$i]; ?>px;
                                height: <? echo $newHeight[$i]; ?>px;
                                margin-top: <? echo (240 - $newHeight[$i])/2; ?>px;"
                                src="<? echo $images[$i]; ?>" 
                                width="<? echo $newWidth[$i]; ?>" 
                                height="<? echo $newHeight[$i]; ?>" />
                            <p><? echo $descriptions[$i]; ?></p>
                        </li>
                    <? 				
                        $i++;
                    } // END foreach
                } // END if numimages ?> 
                </ul>
        	</div>
        <form action="<? echo $url ?>" method="post" enctype="application/x-www-form-urlencoded" id="returnFromGalleryForm" name="returnFromGalleryForm" target="_self">
            <input id="id" name="id" type="hidden" value="<? echo $slideshowId ?>" />
            <input id="url" name="url" type="hidden" value="<? echo $url2 ?>" />
            <input id="slideshowPW" name="slideshowPW" type="hidden" value="<? echo $password ?>" />
        </form>
        <script>
		$(document).ready(function(){
			$('#modal').popup({
					autozindex: true,
					autoopen:	true,
					opacity:	1.0,
					onClose : function() {document.returnFromGalleryForm.submit();} 
					});	
		});      
		</script>
	</body>
	</html>
<? }  ?>