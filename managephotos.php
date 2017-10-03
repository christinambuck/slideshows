<?
/*
 managephotos.php - called from createSlideshowTwo.php and from edit.php which is called from myaccount.php. This page allows
 the user to edit their photo title and description and allows them to drag the photos in the order they want thme to appear 
 in the slideshow.
*/
session_start();
require_once 'config.inc.php';
$url = 'myaccount.php';
if ($_GET['msg']) $msg=$_GET['msg'];
if (!$_SESSION['userName'] && $_COOKIE['userName'])
	$_SESSION['userName'] = $_COOKIE['userName'];
if (!$_SESSION['userName']) // if user is not logged in display the login page
{
	if (!$msg) $msg = 'Please close this window and login first.';	
}
else { // if user is logged in
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	$userName= $_SESSION['userName'];	
	$sql = "SELECT userId FROM members WHERE userName ='".mysql_real_escape_string($userName)."'"; 
	$result = mysql_query ("$sql");			 
	$row = mysql_fetch_array($result);
	$userId = $row['userId'];
	
	//  set up location for images in the members/userid/images/slideshowId folder.  		
	if ($_GET['slideshowId']) $slideshowId = $_GET['slideshowId'];	
	else $slideshowId = $_POST['slideshowId']; 
	
	$fileloc = 'members/'.$userId.'/'; 	
	$imagesdir = $fileloc.'images';					
	$imageloc = $fileloc.'images/'.$slideshowId;
	
	$sql = "SELECT title FROM slideshows WHERE slideshowId ='$slideshowId'"; 
	$result = mysql_query ("$sql");	
	$row = mysql_fetch_array($result);
	$title = $row['title'];
	
	// Set current url/desc div that is displaying to default if one is not displaying
	if (!$_POST['mpcurrent'])  $_POST['mpcurrent'] = 'mpdiv';

	$images = get_photos($imageloc);
	$numImages = count($images);
	if (!$numImages) $msg = 'There are no photos in the slideshow';
	else {		
		// get the description for each image in the selected folder or root folder if one was not selected - descriptions are stored in .txt files of the same name as the image file. Get the descriptions from the files and put them in an array called $descriptions with the same structure as images
		$descriptions = array();
		$descurls = array();
		$thumbnails = array();
			
			for ($i=0; $i < $numImages; $i++) {	 
				$path_parts = pathinfo($images[$i]);
				$thumbnail = str_replace('images','thumbnails',$path_parts['dirname']).'/'.$path_parts['filename'].'.'.$path_parts['extension'];
				$thumbnails[] = $thumbnail; // there will always be a thumbnail for each image
				$descurl = str_replace('images','descriptions',$path_parts['dirname']);
				$descurl = str_replace($config['http_db_server'],'..',$descurl); // make it relative
				$descurl = $descurl.'/'.$path_parts['filename'].'.txt';
				// If the file exists then there is a description for the image. Get the contents from the text file
				if (file_exists($descurl)){
					$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
					$pos = strpos($contents, ';;');
					if ($pos == 0) $imagetitles[] = '';
					else $imagetitles[] = substr($contents,0,$pos);
					if (strlen($contents) > $pos+2)
						$descriptions[] = substr($contents,$pos+2);
					else $descriptions[] = '';
					$descurls[] = $descurl;
				}
				else {
					$imagetitles[] = '';
					$descriptions[] = '';
					$descurls[] = $descurl;
				}
			} // END foreach				
			/*** debug 		 	
				echo '<pre>';
				print_r( $descurls);
				print_r( $descriptions);			
				print_r( $thumbnails);
				echo '</pre>';
			***/	
	} // END else there were images	or directories	
} // END else not invalid username

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title>Photo Manager</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/managephotos.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>		
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
    <script type="text/javascript" src="js/slideshows.js"></script> 
    <!-- Include jQuery Popup Overlay -->
	  <script src="js/popup.js"></script>
    <script>
		 var imgOrder = '';
		 $(document).ready(function(){ 
	
			$("#sortable").sortable({
				update: function(event, ui) {
					imgOrder = $("#sortable").sortable('toArray').toString();
					document.getElementById("imageOrder").value = imgOrder;
					saveImageOrder();
				}
			});
			//$("#sortable").disableSelection(); 	
		}); // END Document Ready
	</script>		
      <style>
      /* Add these styles once per website */
      .popup_background {
        z-index: 2000; /* any number */
      }
      .popup_wrapper {
        z-index: 2001; /* any number + 1 */
      }
      /* Add inline-block support for IE7 */
      .popup_align,
      .popup_content {
        *display: inline;
        *zoom: 1;
      }
      </style>
</head>
<body id="photoManager"  <? if ($msg) { ?>  onLoad="$.prompt('<? echo $msg; ?>',{show:'show', top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>  
	<? require_once 'myaccountNav.inc.php'; ?>  
	<div id="page-wrap">
    <?
		$key=0;
		if ($numImages) {
	 		foreach ($images as $file) { 
				$path_parts = pathinfo($file);	
				$fileName = $path_parts['basename'];
			?>
            <!-- $key has the subdirectory name. $file has the absolute image url. A div is created for each image and is displayed when the user clicks on the image  -->
           <div class="photoManagerBox" id="mpdiv<? echo $key ?>" name="mpdiv" style=" <? if ($_POST['mpcurrent'] != 'mpdiv'.$key) echo 'display:none;'; ?>  ">
                
                <form action="managephotos.php" method="post" enctype="application/x-www-form-urlencoded" id="form<? echo $key ?>" name="form<? echo $key ?>">
            		<input id="mpcurrent<? echo $key ?>" name="mpcurrent" type="hidden" value="<? echo $_POST['mpcurrent'] ?>" />
                    <input id="slideshowId" name="slideshowId" type="hidden" value="<? echo $slideshowId ?>" />  
                    <input id="descurl<? echo $key ?>" name="descurl"  type="hidden" value="<? echo $descurls[$key] ?>" />
                    <input id="thumburl<? echo $key ?>" name="thumburl"  type="hidden" value="<? echo $thumbnails[$key] ?>" />
                    <input id="mpdelete<? echo $key ?>" name="mpdelete" type="hidden" />
                    <input id="mpupdate<? echo $key ?>" name="mpupdate" type="hidden" />
                    <input id="imageurl<? echo $key ?>" name="imageurl" type="hidden"  value="<? echo $file;?>"/> 
       				<input id="imageOrderNew<? echo $key ?>" name="imageOrderNew" type="hidden"  /> 
       				<input id="key<? echo $key ?>" name="key" type="hidden"  value="<? echo $key;?>" />                           
        			<div class="photoManagerInfoBox"> <!-- to center the title and description inside the box -->
                    	<a href="#" class="mpdiv<? echo $key ?>_close" style="float:right;margin-top:-3px"><img src="images/close-button.png" width="15" height="15" title="Close"></a>
                        <p class="filename" ><? echo $fileName; ?></p>
                        <p class="title" >Title: <input class="titleInput" type="text" name="imagetitle" id="imagetitle<? echo $key ?>"  maxlength="30" value="<? echo $imagetitles[$key] ?>"></p>
                        <p class="description">Description: <input class="descriptionInput" type="text" name="desc" id="desc<? echo $key ?>"  maxlength="80" value="<? echo $descriptions[$key] ?>"></p>
                        
                        <div class="photoButtonBox"><!-- to center the buttons inside the box -->
                            <div class="customButton deletePhoto" onclick="deleteImageAjax('<? echo $key ?>');" title="Delete photo and description from website.") >Delete Photo</div>
                            <div class="customButton savePhotoInfo" onclick="updateImageAjax('<? echo $key ?>');" title="Update photo description." >Save</div>
                        </div>
                    </div>
                </form>
            </div><!-- END photoManagerBox -->
            <? $key++; ?>
    	<? } // END foreach ?>
    <? } // END if images ?>
    <!-- area to sort the photos in the order they are to be displayed in the slideshow -->
	<div id="photosToSort">
    	<form action="managephotos.php" method="post" enctype="application/x-www-form-urlencoded" id="mpform" name="mpform">
        <!-- create a drop down menu of all the folders. Onchange submit form. -->
        <div id="instructions" >
            <h4 class="imageUploaderBG"><? echo $title ?></h4> 
            <p>Click on photo to edit info or delete photo.<br />
            Drag photos to place them in the order your want them displayed.</p>
           	<!--<div id="updateOrderAjax" name="updateOrder" class="customButton" onclick="updateOrderAjax();" title="Save the new order of your photos." ><h9>Save Order</h9></div>-->
        </div>
        <input id="mpcurrent" name="mpcurrent" type="hidden" value="<? echo $_POST['mpcurrent'] ?>" />
        <input id="slideshowId" name="slideshowId" type="hidden" value="<? echo $slideshowId ?>" />   
        <input id="imageOrder" name="imageOrder" type="hidden"  />              
        <ul id="sortable">         
        <? 
        $key = 0;	
        if ($numImages) {	
            foreach ( $thumbnails as $file ) { 				
                $path_parts = pathinfo($file);	
                $fileName = $path_parts['basename'];
            ?>
               <li id="<? echo $key ?>" class="mpdiv<? echo $key ?>_open" onMouseDown="mouseDown();"  title="Click to display/edit info for <? echo $fileName ?>."><img src="<? echo $file ?>" /></li>
               
                <? $key++; ?> 
            <? } // END foreach ?>
       <? } // END if images ?>   
       </ul>
       <input id="keyNum" type="hidden" value="<? echo $key ?>" />
    	</form>   
    </div> <!-- END photosToSort -->
    </div><!-- END page-wrap -->   
    <script>
	$(document).ready(function() {
		key = document.getElementById("keyNum").value;
		i = 0;
		while  (i < key) {
		 	$('#mpdiv'+i).popup({
				autozindex: true,
				vertical: 'top'
				});
		i++;
		}		
	});
	</script>
</body>
</html>
   
