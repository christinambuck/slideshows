<!-- beforeAfter.php - This page first checks to see if a password is required to view the slideshow. If it is, an impromptu box is displayed for the user to input the password. The impromptu Ok button sumbits a form with post data back to beforeAfter.php and then displays the member's first and last photo from their slideshow. It also has links to display the slideshow and the gallery. When a user clicks one of the links,  it submits a form with post data that takes the user to either slideshow.php or gallery.php. The post data fields sent are: id=slideshow id, url=beforeAfter.php, url2=program that called beforeAfter.php, and slideshowPW=password (if one exists).
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
			//$thumbsdir = $fileloc.'thumbnails/'.$slideshowId;	
			$descsdir = $fileloc.'descriptions/'.$slideshowId;
			
			$images = get_photos($imagesdir);
			$numImages = count($images);
					
			if (!$numImages) $msg = 'There are no photos in the slideshow';
			else {				
				// Get the "Before" photo, title and description
				list($w, $h, $type, $attr) = getimagesize($images[0]);
				$bWidth = $w;
				$bHeight = $h;
				$new = getNewImageSize (310,240,$images[0]);
				$newBWidth = $new['width'];
				$newBHeight = $new['height'];
				$path_parts = pathinfo($images[0]);
				$descurl = $descsdir.'/'.$path_parts['filename'].'.txt';
				
				// If the file exists then there is a description for the image. Get the contents from the text file
				if (file_exists($descurl)){
					$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
					$contents = str_replace('"',"'",$contents); // replace all double quotes with single quotes because prettyPhoto will crash with double quotes
												
					$pos = strpos($contents, ';;');
					if ($pos == 0) $bTitle = '';
					else $bTitle = substr($contents,0,$pos);
					if (strlen($contents) > $pos+2)
						$bDescription = substr($contents,$pos+2);
					else $bDescription = '';
				}
				else {
					$bTitle = '';
					$bDescription = '';
				}		
				// Get the "After" photo (last photo), title and description
				list($w, $h, $type, $attr) = getimagesize($images[$numImages-1]);
				$aWidth = $w;
				$aHeight = $h;
				$new = getNewImageSize (310,240,$images[$numImages-1]);
				$newAWidth = $new['width'];
				$newAHeight = $new['height'];
				$path_parts = pathinfo($images[$numImages-1]);
				$descurl = $descsdir.'/'.$path_parts['filename'].'.txt';
				
				// If the file exists then there is a description for the image. Get the contents from the text file
				if (file_exists($descurl)){
					$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
					$contents = str_replace('"',"'",$contents); // replace all double quotes with single quotes because prettyPhoto will crash with double quotes
												
					$pos = strpos($contents, ';;');
					if ($pos == 0) $aTitle = '';
					else $aTitle = substr($contents,0,$pos);
					if (strlen($contents) > $pos+2)
						$aDescription = substr($contents,$pos+2);
					else $aDescription = '';
				}
				else {
					$aTitle = '';
					$aDescription = '';
				}
				if ($bTitle != '' && $aTitle == '') $aTitle = '&nbsp;';
				elseif ($aTitle != '' && $bTitle == '') $bTitle = '&nbsp;';
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
	else {
	?>
		<!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Before and After Photos</title>
        <head>
            <!-- Mobile viewport -->
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
            
            <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
			<link href="css/beforeAfter.css" rel="stylesheet" type="text/css" />
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
        <body id="beforeAfterPage" onLoad="$.promptExt(txt1,{ submit: callbackPW, buttons: { 'Submit':'slideshowPWForm' } })">	
        </body>
        </html>
	<? }	
} else {
?>
	?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
	<title>Before and After Photos</title>
	<head>
        <!-- Mobile viewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
        
		<link href="css/newStyle.css" rel="stylesheet" type="text/css" />
		<link href="css/beforeAfter.css" rel="stylesheet" type="text/css" />
   		<link href="css/media-queries.css" rel="stylesheet" type="text/css" />
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/slideshows.js"></script>
        <!-- Include jQuery Popup Overlay -->
        <script src="jquery-popup-overlay/jquery.popupoverlay.js"></script>
	</head>
	
	<body id="beforeAfterPage" >
        <div id="modal"> 
        	<a href="#" class="modal_close" style="float:right;margin-right:10px"><img src="images/close-button.png" width="25" height="25" title="Close"></a>
            <h2><? echo $row['title']; ?></h2>
            <ul id="gallery">    
			<?
            if ($numImages) {
                ?>
				<!-- Before photo -->
                <li id="div<? echo $i ?>"  title="Click to enlarge." onclick="window.open('<? echo $images[0]; ?>', 'photo0', 'width=<? echo $bWidth; ?>, height=<? echo $bHeight; ?>');">
                    <h6><? echo $bTitle; ?></h6>
                    <img style=" 
                        width: <? echo $newBWidth; ?>px;
                        height: <? echo $newBHeight; ?>px;
                        margin-top: <? echo (240 - $newBHeight)/2; ?>px;"
                        src="<? echo $images[0]; ?>" 
                        width="<? echo $newBWidth; ?>" 
                        height="<? echo $newBHeight; ?>" />
                    <p><? echo $bDescription; ?></p>
            	</li>
                    
                <!-- After photo -->
                <li id="div<? echo $i ?>"  title="Click to enlarge." onclick="window.open('<? echo $images[$numImages-1]; ?>', 'photo<? echo $numImages-1; ?>', 'width=<? echo $aWidth; ?>, height=<? echo $aHeight; ?>');">
                    <h6><? echo $aTitle; ?></h6>
                    <img style=" 
                        width: <? echo $newAWidth; ?>px;
                        height: <? echo $newAHeight; ?>px;
                        margin-top: <? echo (240 - $newAHeight)/2; ?>px;"
                        src="<? echo $images[$numImages-1]; ?>" 
                        width="<? echo $newAWidth; ?>" 
                        height="<? echo $newAHeight; ?>" />
                    <p><? echo $aDescription; ?></p>
            	</li> 
                <? 	} // END if numimages ?> 
        	</ul>                
                   <p>&nbsp;</p>
                    <h2 style="clear:both;" title="View slideshow <? echo $slideshowId ?>" class="whiteLink"  onclick='document.beforeAfterSlideshowForm.submit();' >View Slideshow</h2>
                    
                    <h2 title="View gallery <? echo $slideshowId ?>" class="whiteLink"  onclick='document.beforeAfterGalleryForm.submit();' >View Gallery</h2>
    	</div> <!-- End modal -->
        
        <form action="slideshow.php" method="post" enctype="application/x-www-form-urlencoded" id="beforeAfterSlideshowForm" name="beforeAfterSlideshowForm" target="_self">
            <input id="id" name="id" type="hidden" value="<? echo $slideshowId; ?>" />
            <input id="url" name="url" type="hidden" value="beforeAfter.php" />
            <input id="url2" name="url2" type="hidden" value="<? echo $url ?>" />
            <input id="slideshowPW" name="slideshowPW" type="hidden" value="<? echo  $password ?>" />
        </form>
        <form action="gallery.php" method="post" enctype="application/x-www-form-urlencoded" id="beforeAfterGalleryForm" name="beforeAfterGalleryForm" target="_self">
            <input id="id" name="id" type="hidden" value="<? echo $slideshowId; ?>" />
            <input id="url" name="url" type="hidden" value="beforeAfter.php" />
            <input id="url2" name="url2" type="hidden" value="<? echo $url ?>" />
            <input id="slideshowPW" name="slideshowPW" type="hidden" value="<? echo  $password ?>" />
        </form>
        <script>
		$(document).ready(function(){
			$('#modal').popup({
					autozindex: true,
					autoopen:	true,
					opacity:	1.0,
					onClose : function() {window.location = '<? echo $url ?>';} 
					});	
		});      
		</script>	  
	</body>
	</html>
<? } ?>