<?
/**************************

Required parameters userName and slideshowID. This program uses https://github.com/blueimp/jQuery-File-Upload for the multiple / drag & drop file upload

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
$url = 'uploadphotos.php';
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

	require_once 'phpfunctions.php';	
	$error = 0;
	$userName = $_SESSION['userName'];	
	
	$sql = "SELECT userId FROM members WHERE userName ='".mysql_real_escape_string($userName)."'"; 
	$result = mysql_query ("$sql");	
	$row = mysql_fetch_array($result);
	$userId = $row['userId'];	
		
	//  set up location for images in the members/userid/images/slideshowId folder.  		
	if ($_REQUEST['slideshowId']) $slideshowId = $_REQUEST['slideshowId'];
	else $msg = 'Invalid slideshow Id.';	
	
	if ($slideshowId){
		$_SESSION['slideshowId'] = $slideshowId;
		$_SESSION['userId'] = $userId;
		$sql = "SELECT title FROM slideshows WHERE slideshowId ='$slideshowId'"; 
		$result = mysql_query ("$sql");	
		$row = mysql_fetch_array($result);
		$title = $row['title'];
	}		
	
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
    
    <!-- Bootstrap styles -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
	<script src="js/jquery.placeholder.js"></script>
	
</head>
<body <? if ($msg) { ?>  onLoad="$.prompt('<? echo $msg; ?>',{show:'show', top:'30%'})" <? }  ?>>
<? require_once 'heading.inc.php'; ?>  
<? require_once 'myaccountNav.inc.php'; ?>  
	<div id="page-wrap"> 
			<? if ($slideshowId){ ?>
                <!-- File upload section before pressing the Upload button -->           
                <div id="divTop" name="divTop">
                    <h3 class="imageUploaderBG" ><? echo $title ?></h3>
                    <div id="uploadBox">                   
                            <!-- for jquery upload -->
                            <span class="customButton fileinput-button">
                                <span>Select files...</span>
                                <!-- The file input field used as target for the file upload widget -->
                                <input id="fileupload" type="file" name="files[]" multiple>
                            </span> 
                            <span style="color:#512112;font-size: 1.2em;line-height: 1.6em; font-weight:bold"> &nbsp;&nbsp;or Drag & Drop on Page</span> 
                            <br>
                            <br>
                            <!-- The global progress bar -->
                            <div id="progress" class="progress">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                            <!-- The container for the uploaded files -->
                            <div id="files" class="files"></div>
                            <br>                         
                            <!-- -->
                        <form action="uploadphotos2.php" method="post" id="uploadphotos2">
                            <div id="continueUploadButton" class="customButton" alt="Add Titles and Descriptions to Photos" title="Add Titles and Descriptions to Photos" > 
                            <img class="buttonImg" src="images/edit2.png" > &nbsp; &nbsp;Continue</div>
                            <input name="uploaded_files" id="uploaded_files" type="hidden">
                        </form>
                    </div> 
                        <div id="uploadTips">
                            <span class="imageUploaderBG">TIPS:</span> 
                            <ul>
                                <li>When overwriting a photo, you may need to clear your browser's history/cache to see the new photo.</li>
                                <li>If you are having problems uploading, you may try using the old upload page from <a href="uploadphotosOld.php<?php echo '?userName='.$_SESSION['userName']. '&slideshowId='.$slideshowId ?>">here</a>.</li>
                                <li>Photos are automatically resized to 1024px x 768px.</li>
                            </ul>
                        </div><!-- END uploadTips --> 
                </div> <!-- END divTop -->  
			<? } ?><!-- END if slideshowID was passed -->
            </div> <!-- END page-wrap -->
            
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="upload/js/jquery.fileupload.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = 'upload/server/php/';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
			var uploaded_names = '';
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#files');
				if ($('#uploaded_files').val())
				{					
					uploaded_names = $('#uploaded_files').val();
					uploaded_names = uploaded_names + ',' + file.name;
				}
				else
				{
					uploaded_names = file.name;
				}
				$('#uploaded_files').val(uploaded_names);
            });
			
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
</body>
</html>
 <? } ?> <!-- END if userName was passed --> 