<?
/**************************

Called from myaccount.php with parameters userName and slideshowID

**************************/
session_start();
			/* Debug 
				echo '<pre> cookie = ';				
				print_r( $_COOKIE);
				echo '</pre>';  
				echo '<pre> session = ';				
				print_r( $_SESSION);
				echo '</pre>';  
				echo '<pre> get = ';				
				print_r( $_GET);
				echo '</pre>';  
				echo '<pre> post = ';				
				print_r( $_POST);
				echo '</pre>';  */

require_once 'config.inc.php';
$url = 'myaccount.php';
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

	require_once 'upload.php';	
	require_once 'phpfunctions.php';	
	$error = 0;
	$userName = $_SESSION['userName'];	
	
	$sql = "SELECT userId FROM members WHERE userName ='".mysql_real_escape_string($userName)."'"; 
	$result = mysql_query ("$sql");	
	$row = mysql_fetch_array($result);
	$userId = $row['userId'];
	
	// how many file upload fields on the page				
	if ($_GET['num']) $num = $_GET['num']; 
	elseif ($_POST['num']) $num = $_POST['num']; 
	else $num = 5; 							// default to 5 		
	
	//  set up location for images in the members/userid/images/slideshowId folder.  		
	if ($_GET['slideshowId']) $slideshowId = $_GET['slideshowId'];	
	elseif  ($_POST['slideshowId']) $slideshowId = $_POST['slideshowId'];
	else $msg = 'Sorry, time limit exceded uploading photos. Please close this window and resize your photos or upload fewer photos at a time.';	
	
	if ($slideshowId){
		$fileloc = 'members/'.$userId.'/'; 	
		$imagesdir = $fileloc.'images';					
		$imageloc = $fileloc.'images/'.$slideshowId;
				
		$sql = "SELECT title FROM slideshows WHERE slideshowId ='$slideshowId'"; 
		$result = mysql_query ("$sql");	
		$row = mysql_fetch_array($result);
		$title = $row['title'];
	}
	
	// If Upload button was clicked, upload the files
	
	if ($_POST['upUpload']) 
	{			
		$_POST['numfiles'] = 0;		
		for ($i=0; $i < $num; $i++)
		{ 				
			set_time_limit(0); 
			$filename = 'imagefile'.$i;	
			if ($_FILES[$filename]['name']){
				list($success,$image,$duplicate) = imageUpload($imageloc,$filename,true); 		// upload file
				if (!$success) 	{	
						$msg .= $image.'<br>';
						$error = 1;
				}
				else {	
					
					$thumbimage = str_replace ('/images/','/thumbnails/',$image);
					// insert image file name into photos.txt unless it is a duplicate
					if (!$duplicate) {
						$fh = fopen($imageloc.'/photos.txt', 'a');
						fwrite($fh, $image.',');
						fclose($fh);
					}
					else { // delete old thumbnail if duplicate
						//unlink($thumbimage);
					}
					resizeToFile ($image, 1024, 768, $image, 85);					// resize and optimize the photo
					resizeToFile ($image, 120, 120, $thumbimage, 50); 				// create a thumbnail version of the photo
					$upImages[]= str_replace('..',$config['http_db_server'], $image); // replace all occurances of .. with the website.com name so that  we have the full url of the image
					$thumbimages[] = $thumbimage;
					
					$path_parts = pathinfo($image);
					$descurl = str_replace('images','descriptions',$path_parts['dirname']);
					$descurl = $descurl.'/'.$path_parts['filename'].'.txt';
					
					$descurls[] = $descurl;
					$_POST['numfiles']++;	
				}
			} // END if file to upload
		} // END for
	} // END if Upload clicked		
	
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
    <link href="css/uploadphotos.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
	<script src="js/jquery.placeholder.js"></script>
	<script language="JavaScript" type="text/JavaScript"> 
    <!--
    
    function uploadImage(){	
        document.getElementById("divBottom").style.display = 'none';
        setTimeout("document.images['img-spinner'].src=document.images['img-spinner'].src",10); 
        document.getElementById("spinner").style.display = 'block';
        document.getElementById('upUpload').value = '1';
        document.getElementById('upForm1').submit();
    }
    function uploadMore(){	
        document.getElementById("divBottom").style.display = 'none';
        document.getElementById("divTop").style.display = 'block';
        document.getElementById("divMore").style.display = 'none';
        document.getElementById('upForm1').submit();
    }
    function updateImage(){
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
                <!-- File upload section before pressing the Upload button -->           
                <div id="divTop" name="divTop" style=" <? if (($_POST['upUpload'] || $_POST['upUpdate'])  && ($error != 1)) echo 'display:none;'; ?>">
        			<form id="upForm1" id="upForm1" action="uploadphotosajax.php" enctype="multipart/form-data" method="post"> 
                        <h3 class="imageUploaderBG" ><? echo $title ?></h3>
                        <div id="uploadBox">
                                <input name="num" type="hidden" value="<? echo $num ?>" />
                                <input name="slideshowId" type="hidden" value="<? echo $slideshowId; ?>" />
                                <input id="upUpload" name="upUpload" type="hidden" />
                                <? 				
                                for ($i=0; $i < $num; $i++) { 		
                                ?>
                                    <input class="fileUploadFields" name="imagefile<? echo $i ?>" type="file" id="imagefile<? echo $i ?>" >
                                <? } ?>       
                            <div class="customButton" onClick="uploadImage();" >Upload</div>
                        </div><!-- END uploadBox -->
                        <div id="uploadTips">
                            <span class="imageUploaderBG">TIPS:</span> 
                            <ul>
                                <li>If you are having problems uploading, you may need to resize your photos or limit the number uploaded at one time to keep under the total max limits set by the server.</li>
                                <li>Photos can be resized to 1024px x 768px.</li>
                                <li>Each photo must be under 8MB. 5 photos at 8MB each may exceed the total max limits.</li>
                                <li>When overwriting a photo, you may need to clear your browsing history or cache to see the new picture.</li>
                            </ul>
                        </div><!-- END uploadTips -->  
                	</form>     
                </div> <!-- END divTop --> 
                
        		<!-- Hide upload section after Upload is pressed -->
                <div id="divMore" style=" <? if ($_POST['upUpload'] || $_POST['upUpdate']) echo 'display:block;'; else echo 'display:none;'; ?> ">
                        <h3><? echo $title ?></h3> 
                </div><!-- END dimMore -->  
                
                <!-- Uploaded results section displayed after pressing the upload button -->
                <div id="divBottom" style=" <? if ((!$_POST['upUpload'] && !$_POST['upUpdate']) || ($_POST['numfiles'] == 0)) echo 'display:none;'; ?>">
                    <form action="uploadphotosajax.php" method="post" enctype="application/x-www-form-urlencoded" id="upForm2" name="upForm2">
                        <input name="num" type="hidden" value="<? echo $num ?>" />
                        <input name="numfiles" id="numfiles" type="hidden" value="<? echo $_POST['numfiles']; ?>" />
                        <input name="slideshowId" type="hidden" value="<? echo $slideshowId; ?>" />
                        <input name="upUpdate" type="hidden" id="upUpdate" />  
                        <? 
                            for ($i=0; $i < $_POST['numfiles']; $i++) { 				
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
                                            <p ><label class="inputLabel" style="margin-left:0.8em;">Filename:</label> <input class="filenameInput" type="text" name="url<? echo $i; ?>" id="url<? echo $i; ?>"  value="<? echo $filename; ?>" title="Filename - Read Only"  readonly /></p>
                                            <p><label class="inputLabel"   style="margin-left:3.133em;">Title:</label> <input class="titleInput" type="text" name="imagetitle<? echo $i; ?>" id="imagetitle<? echo $i; ?>" maxlength="30" value="<? echo $_POST[$postimagetitle]; ?>" title="Title" placeholder="Enter title" /></p>
                                            <p><label class="inputLabel">Description:</label> <input  class="descriptionInput"type="text" name="desc<? echo $i; ?>" id="desc<? echo $i; ?>" maxlength="80" value="<? echo $_POST[$postdesc]; ?>" title="Description" placeholder="Enter Description" /></p>
                                            <input  id="descurl<? echo $i; ?>" name="descurl<? echo $i; ?>" type="hidden" value="<? if ($descurls[$i]) echo $descurls[$i]; else echo $_POST[$postdescurl]; ?>" />
                                            <input name="thumburl<? echo $i; ?>" type="hidden" value="<? if ($thumbimages[$i]) echo $thumbimages[$i]; else echo $_POST[$postthumb]; ?>" />
                                        </div><!-- END uploadInfoBox -->
                                    </div><!-- END divBottomBox -->
                            <? } ?>
                        <? } // END for ?>
                    </form>
                    <div id="UploadButtonBox"><!-- to center the buttons on the page -->        
                        <div id="uploadMoreButton" class="customButton" onClick="uploadMore();" >Upload More</div>
                        <div id="uploadSaveButton" class="customButton" onClick="updateImage();" title="Update the Title and Description." >Update</div> 
                    </div>
        		</div><!-- END divBottom -->
			<? } ?><!-- END if slideshowID was passed -->
            </div> <!-- END page-wrap -->
</body>
</html>
 <? } ?> <!-- END if userName was passed --> 