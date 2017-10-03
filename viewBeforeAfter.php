<!-- viewBeforeAfter.php - This program is called by beforeAfter.php. This page displays the member's first and last photo
 		from their slideshow. It also has links to display the slideshow and the gallery. When a user clicks one of the 
        links, it runs a JavaScript function defined in slideshows.js which saves the slideshow id, the return url, and the
        password (if there is one) in a form defined in beforeAfter.php. The JavaScript functions also closes this 
        PrettyPhoto iframe and upon closing PrettyPhoto has a callback function which submits the form that was filled in 
        with the id, return url, and pw. This causes either the slideshow or gallery to open. When the slideshow or gallery
        are closed, it brings them back to this page. When this page is closed by the user it takes them back to the page 
        that opened it.
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
				$new = getNewImageSize (340,340,$images[0]);
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
				$new = getNewImageSize (340,340,$images[$numImages-1]);
				$newAWidth = $new['width'];
				$newAHeight = $new['height'];
				$path_parts = pathinfo($images[$numImages-1]);
				$descurl = $descsdir.'/'.$path_parts['filename'].'.txt';
				
				// If the file exists then there is a description for the image. Get the contents from the text file
				if (file_exists($descurl)){
					$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
					$contents = str_replace('"',"'",$contents); // replace all double quotes with single quotes because prettyPhoto will crash with double quotes
												
					$pos = strpos($contents, ';;');
					if ($pos == 0) $bTitle = '';
					else $aTitle = substr($contents,0,$pos);
					if (strlen($contents) > $pos+2)
						$aDescription = substr($contents,$pos+2);
					else $aDescription = '';
				}
				else {
					$aTitle = '';
					$aDescription = '';
				}
			} // END else there are images
		} // END if not password required
	}	// END else valid slideshowId
	?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
	<title>Before and After Photos</title>
	<head>
		<link href="css/newStyle.css" rel="stylesheet" type="text/css" />
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
	
	<body id="beforeAfterPage" <? if ($row['password'] && $row['password'] != $password) { ?> onLoad="$.promptExt(txt1,{ submit: callbackPW, buttons: { 'Submit':'slideshowPWForm' } })" <? } ?> >
    	
        <h2 style="text-align:center; line-height:25px;"><? echo $row['title']; ?></h2>
        <div style="position:relative; width:680px; height:490px; overflow:auto;">
		<?
        if ($numImages) {
			?>
            	<!-- Before photo -->
				<div style="position:absolute; top:0px; left:0px; width:340px; height:420px;">               
					<div style="height:25px; width:340px;"><h3 style="text-align:center; "><? echo $bTitle; ?></h3></div>
					<div style="cursor:pointer;  width:340px;" onclick="window.open('<? echo $images[0]; ?>', 'Before', 'width=<? echo $bWidth; ?>, height=<? echo $bHeight; ?>');" title="Click to enlarge">
						<img style=" width: <? echo $newBWidth; ?>px;
									 height: <? echo $newBHeight; ?>px;
									 margin-left: <? echo (340- $newBWidth)/2; ?>px; 
								   " 
								src="<? echo $images[0]; ?>" width="<? echo $bWidth; ?>" height="<? echo $bHeight; ?>" />
					</div>
					<div style="width:340px; text-align:center;"><? echo $bDescription; ?></div>				
                </div>
                
                <!-- After photo -->
				<div style="position:absolute; top:0px; right:0px; width:340px; height:420px;">               
					<div style="height:25px; width:340px;"><h3 style="text-align:center; "><? echo $aTitle; ?></h3></div>
					<div style="cursor:pointer; width:340px;" onclick="window.open('<? echo $images[$numImages-1]; ?>', 'After', 'width=<? echo $aWidth; ?>, height=<? echo $aHeight; ?>');" title="Click to enlarge">
						<img style=" width: <? echo $newAWidth; ?>px;
									 height: <? echo $newAHeight; ?>px;
									 margin-left: <? echo (340- $newAWidth)/2; ?>px; 
								   " 
								src="<? echo $images[$numImages-1]; ?>" width="<? echo $aWidth; ?>" height="<? echo $aHeight; ?>" />
					</div>
					<div style="width:340px; text-align:center;"><? echo $aDescription; ?></div>				
                </div>
           		
                <h2 style=" line-height:20px;position:absolute; left:0px; width:660px; top:395px;text-align:center; height:25px " title="View slideshow <? echo $slideshowId ?>" class="darkLink"  onclick='goToViewSlideshowFromBeforeAfter("<? echo $slideshowId; ?>","<? echo "beforeAfter.php?id=".$slideshowId."&url=".$url."&slideshowPW=".$password; ?>","<? echo  $password ?>" );' >View Slideshow</h2>
                
                <h2 style=" line-height:20px;position:absolute; left:0px; width:660px; top:445px;text-align:center; height:25px  " title="View gallery <? echo $slideshowId ?>" class="darkLink"  onclick='goToViewGalleryFromBeforeAfter("<? echo $slideshowId; ?>","<? echo "beforeAfter.php?id=".$slideshowId."&url=".$url."&slideshowPW=".$password; ?>","<? echo  $password ?>" );' >View Gallery</h2>
                
			<? 	} // END if numimages ?>            
        </div>		  
	</body>
	</html>
<? } else { ?> 
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/'.$url.'?msg=Missing Slideshow Id' ?>">	
<? exit; } ?>