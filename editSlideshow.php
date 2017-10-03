<?
$url = 'myaccount.php';
session_start();
require_once 'config.inc.php';
global $config;
			
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
    <title>Edit Slideshow</title>
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
else { 
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	if ($_POST['title']) {

		if (strlen($_POST['description']) > 255) $_POST['description'] = substr($_POST['description'],0,255);
		// update slideshow database
		$sql = "UPDATE slideshows SET
					title='".mysql_real_escape_string($_POST['title'])."',
					description='".mysql_real_escape_string($_POST['description'])."',
					password='".mysql_real_escape_string($_POST['slideshowPW'])."',
					private='$_POST[private]',
					keywords='".mysql_real_escape_string($_POST['keywords'])."'
					WHERE slideshowId='$_POST[slideshowId]'";	
		$result = mysql_query("$sql");	
		if (!$result) $msg = 'ERROR: Could not update database.';
		else {	
		?>
        <meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/managephotos.php?userName='.$_SESSION['userName'].'&slideshowId='.$_POST['slideshowId']; ?>">	
        <?
		exit;	
		}
	}
		
	$userName= $_SESSION['userName'];	
	$sql = "SELECT userId FROM members WHERE userName ='".mysql_real_escape_string($userName)."'"; 
	$result = mysql_query ("$sql");			 
	$row = mysql_fetch_array($result);
	$userId = $row['userId'];
	
	//  set up location for images in the members/userid/images/slideshowId folder.  		
	if ($_GET['slideshowId']) $slideshowId = $_GET['slideshowId'];	
	else $slideshowId = $_POST['slideshowId']; 
	
	$sql = "SELECT * FROM slideshows WHERE slideshowId ='$slideshowId'"; 
	$result = mysql_query ("$sql");	
	$row = mysql_fetch_array($result);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Slideshow</title>
<meta name="robots" content="noindex, follow">
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/editSlideshow.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>  
	<? require_once 'heading.inc.php'; ?> 
	<? require_once 'myaccountNav.inc.php'; ?> 
	<div id="page-wrap">
    	<div id="mainContent">
        	<h1>Edit Slideshow</h1>            
            <div id="mainContentFields">
                <form  method="post" enctype="application/x-www-form-urlencoded" id="editSlideshowForm" name="editSlideshowForm">
                    <p class="bold">Title<br /><input name="title" type="text"  style="width:95%" maxlength="40" value="<? echo stripslashes($row['title']); ?>" required /></p>
                    <p>Description<br /><textarea name="description" cols="30" rows="3" maxlength="200" ><? echo stripslashes($row['description']); ?></textarea></p>
                    <p>Password <span class="small">(to password protect slideshow)</span><br /><input id="slideshowPW" name="slideshowPW" type="text" size="20" maxlength="20" value="<? echo stripslashes($row['password']); ?>" /></p>
                    <p>Private <span class="small">(slideshow NOT searchable to public)</span><br /><input id="private" name="private"  type="checkbox"  value="1" <? if ($row['private']) echo 'checked'; ?>  /></p>
                     <div id="keywordsDiv" <? if ($row['private']) echo 'style="display:none;"';  ?>>
                     	<p>Keywords <span class="small">(for searching if not Private)</span>
                        <br /><input id="keywords" name="keywords" type="text"  style="width:95%" maxlength="255" value="<? echo stripslashes($row['keywords']); ?>" />
                        <br /><span class="small">Comma separated.</span></p>
                     </div>
                     <input name="slideshowId" type="hidden" value="<? echo $slideshowId; ?>" /> 
                </form>
                <div id="continueEditButton" class="customButton">Continue</div> 
                <!-- <div onClick="javascript:parent.$.fancybox.close();">Close</div> -->
			</div><!-- End mainContentFields -->
    	</div><!-- End mainContent -->
    </div><!-- End page-wrap --> 
</body>
</html>
<? } ?>