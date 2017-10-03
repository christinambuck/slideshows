<?
/**************************

Called from uploadphotos.php

**************************/
session_start();
require_once 'config.inc.php';
$url = 'uploadphotos2.php';
if ($_GET['msg']) $msg=$_GET['msg'];
if (!$_SESSION['userName'] && $_COOKIE['userName'])
	$_SESSION['userName'] = $_COOKIE['userName'];
if (!$_SESSION['userName']) // if user is not logged in display the login page
{
	if (!$msg) $msg = 'Please login first.';
	?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Upload Photos - Slideshows</title>
	<meta name="robots" content="noindex, follow">
        <!-- Mobile viewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
        
        <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
        <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
        <link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
        <script type="text/javascript" src="js/slideshows.js"></script>
    
    </head>
    
    <body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
        <? require_once 'heading.inc.php'; ?>
    </body>
    </html>
    <?
}
elseif (!$_POST['uploaded_files'] || !$_SESSION['userId'] || !$_SESSION['slideshowId'])  // if no files were uploaded
{
	echo 'Missing parmameters';
}
else
{
	$userId = $_SESSION['userId'];
	$slideshowId = $_SESSION['slideshowId'];
	
	// how many file upload fields on the page	
	$uploaded_files = explode(',',$_POST['uploaded_files']);
	$num = count($uploaded_files);
	$fileloc = 'members/'.$userId.'/'; 	
	$imagesdir = $fileloc.'images';					
	$imageloc = $fileloc.'images/'.$slideshowId;
	$thumbloc = str_replace ('/images/','/thumbnails/',$imageloc);
	$descloc = str_replace('/images/','/descriptions/',$imageloc);
				
	$sql = "SELECT title FROM slideshows WHERE slideshowId ='$slideshowId'"; 
	$result = mysql_query ("$sql");	
	$row = mysql_fetch_array($result);
	$title = $row['title'];
	
	$numfiles = 0;		
	for ($i=0; $i < $num; $i++)
	{ 				 
		// remove any '(', ')' in file name - also changed uploadhandler.php to remove them in the filename in photos.txt
		$bad = array("(", ")"," ");
		$good_file = str_replace($bad, "", $uploaded_files[$i]);
		$uploaded_image_file = $imageloc.'/'.$uploaded_files[$i];
		$new_image_file = $imageloc.'/'.$good_file;
		$uploaded_thumb_file = $thumbloc.'/'.$uploaded_files[$i];
		$new_thumb_file = $thumbloc.'/'.$good_file;
		rename($uploaded_image_file,$new_image_file);
		rename($uploaded_thumb_file,$new_thumb_file);
		
		$upImages[]= $config['http_db_server'].'/'.$imageloc.'/'.$good_file; // replace all occurances of .. with the website.com name so that  we have the full url of the image
		$thumbimages[] = $thumbloc.'/'.$good_file;
		$path_parts = pathinfo($good_file);
		$descurl = $descloc.'/'.$path_parts['filename'].'.txt';
		$descurls[] = $descurl;
		if (file_exists($descurl)){
			$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
			$pos = strpos($contents, ';;');
			if ($pos == 0) $imagetitles[] = '';
			else $imagetitles[] = substr($contents,0,$pos);
			if (strlen($contents) > $pos+2)
				$descriptions[] = substr($contents,$pos+2);
			else $descriptions[] = '';
		}
		else {
			$imagetitles[] = '';
			$descriptions[] = '';
		}
	} // END for
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Add Descriptions to Upload Photos - Slideshows</title>
<meta name="robots" content="noindex, follow">
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/uploadphotos.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
	<script src="js/jquery.placeholder.js"></script>
	<script language="JavaScript" type="text/JavaScript"> 
    <!--
   
    function updateImage(nextURL){
		// Loop through the number of upladed images and update the title and description for each one
		var dataString = {};
		dataString['upUpdate'] = '1';
		dataString['numfiles'] = document.getElementById('numfiles').value;
		for ($i=0; $i < dataString['numfiles']; $i++) 
		{	
			descurlId = 'descurl'+$i;
			dataString[descurlId] = document.getElementById(descurlId).value;
			imagetitletxtId = 'imagetitle'+$i;
			dataString[imagetitletxtId] = document.getElementById(imagetitletxtId).value;
			desctxtId = 'desc'+$i;
			dataString[desctxtId] = document.getElementById(desctxtId).value;
		}			
		//alert(JSON.stringify(dataString));
		$.ajax({
            url: "updatephotos.php",
            type: "POST",
            data: dataString,                   
            success: function(data){
				alert('Image Information has been saved.'); // this will print you any php / mysql error as an alert 
				window.location = nextURL;
			}
        });	
    }
    -->
    </script>
</head>
<body <? if ($msg) { ?>  onLoad="$.prompt('<? echo $msg; ?>',{show:'show', top:'30%'})" <? }  ?>>
<? require_once 'heading.inc.php'; ?>  
<? require_once 'myaccountNav.inc.php'; ?>  
	<div id="page-wrap"> 
			<? if ($slideshowId){ ?>
                <div id="spinner">
                    <img id="img-spinner" src="images/spinner.gif" alt="Loading"/>
                </div>
                
                <!-- Uploaded results section displayed after pressing the upload button -->
                <div id="divBottom">
                	<h3 class="imageUploaderBG" ><? echo $title ?></h3>
                    <form action="uploadphotos2.php" method="post" enctype="application/x-www-form-urlencoded" id="upForm2" name="upForm2">
                        <input id="numfiles" name="numfiles" type="hidden" value="<? echo $num ?>" />
                        <input name="upUpdate" type="hidden" id="upUpdate" />  
                        <? 
                            for ($i=0; $i < $num; $i++) { 				
                                $postthumb = 'thumburl'.$i;
                                if ($_POST[$postthumb]) $thumbimages[$i] = $_POST[$postthumb];
                                if ($thumbimages[$i]) {
                                    $posturl = 'url'.$i;
                                    $postimagetitle = 'imagetitle'.$i;
                                    $postdesc = 'desc'.$i;
                                    $postdescurl = 'descurl'.$i;
									if ($upImages[$i]) $filename = basename($upImages[$i]); 
									else $filename = basename($_POST[$posturl]);

                                    list($width, $height, $type, $attr) = getimagesize($thumbimages[$i]);
                            ?>      <div class="divBottomBox imageUploaderBG" style="height:<? if ($height < 120) echo $height+10; else echo $height; ?>px;"> 
                                        <div style="float:left;text-align:center; width:120px; height:<? echo $height; ?>px; "><img src="<? if ($thumbimages[$i]) echo $thumbimages[$i]; else echo $_POST[$postthumb]; ?>" />   						
                                        </div>
                                        <div class="uploadInfoBox"> 
                                            <p style="margin-bottom:2px;" ><label class="inputLabel" style="margin-left:0.8em;">Filename:</label> <input class="filenameInput" type="text" name="url<? echo $i; ?>" id="url<? echo $i; ?>"  value="<? echo $filename; ?>" title="Filename - Read Only"  readonly /></p>
                                            <p style="margin-bottom:2px; margin-top:0px;"><label class="inputLabel"   style="margin-left:3.133em;">Title:</label> <input class="titleInput" type="text" name="imagetitle<? echo $i; ?>" id="imagetitle<? echo $i; ?>" maxlength="30" value="<? echo $imagetitles[$i]; ?>" title="Title" placeholder="Enter title" /></p>
                                            <p style="margin-bottom:2px; margin-top:0px;"><label class="inputLabel">Description:</label> <input  class="descriptionInput"type="text" name="desc<? echo $i; ?>" id="desc<? echo $i; ?>" maxlength="80" value="<? echo $descriptions[$i]; ?>" title="Description" placeholder="Enter Description" /></p>
                                            <input  id="descurl<? echo $i; ?>" name="descurl<? echo $i; ?>" type="hidden" value="<? if ($descurls[$i]) echo $descurls[$i]; else echo $_POST[$postdescurl]; ?>" />
                                            <input name="thumburl<? echo $i; ?>" type="hidden" value="<? if ($thumbimages[$i]) echo $thumbimages[$i]; else echo $_POST[$postthumb]; ?>" />
                                        </div><!-- END uploadInfoBox -->
                                    </div><!-- END divBottomBox -->
                            <? } ?>
                        <? } // END for ?>
                    </form>
                    <div id="UploadButtonBox"><!-- to center the buttons on the page -->        
                        <div id="uploadSaveButton" class="customButton" onClick="updateImage('<?php echo 'http://'.$config['http_db_server'].$config['root'].'/editSlideshow.php?userName='.$_SESSION['userName']. '&slideshowId='.$slideshowId ?>');"  alt="Update and organize photos" title="Update and organize photos" ><img class="buttonImg" src="images/edit2.png" > &nbsp; &nbsp;Continue</div> 
                    </div>
        		</div><!-- END divBottom -->
			<? } ?><!-- END if slideshowID was passed -->
            </div> <!-- END page-wrap -->
</body>
</html>
 <? } ?> <!-- END if userName was passed --> 