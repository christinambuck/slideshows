<?
$url = 'createSlideshow.php';
require_once 'config.inc.php';
global $config;
if ($_GET['msg']) $msg=$_GET['msg'];
if (!$_SESSION['userName']) // if user is not logged in display the login page
{
	if (!$msg) $msg = 'Please login first.';
	?>
	<!DOCTYPE html>
	<html>
    <head>
    <meta charset="utf-8">
    <title>Create Slideshow</title>
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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Create Slideshow</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/createSlideshow.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>  
	<div id="page-wrap"> 
        <div id="mainContent">
        	<h1>Create Slideshow</h1>            
            <div id="mainContentFields">
                <form  method="post" action="createSlideshowTwo.php" enctype="application/x-www-form-urlencoded" id="createForm" name="createForm">
                    <p class="bold">Title<br /><input style="width:95%" name="title" type="text"  maxlength="40" required /></p>
                    <p>Description<br /><textarea name="description" cols="30" rows="3" maxlength="200" ></textarea></p>
                    <p>Password <span class="small">(to password protect slideshow)</span><br /><input id="slideshowPW" name="slideshowPW" type="text" size="20" maxlength="20" /></p>
                    <p>Private <span class="small">(slideshow NOT searchable to public)</span><br /><input id="private" name="private"  type="checkbox"  value="1" /></p>
                     <div id="keywordsDiv" ><p>Keywords <span class="small">(for searching if not Private)</span><br /><input id="keywords" name="keywords" type="text"  style="width:95%" maxlength="255" /><br /><span class="small">Comma separated.</span></p>
                     </div>
                    <input name="createSlideshowrFld" name="createSlideshowrFld" type="hidden" value="1" /> 
                </form>
                <div id="createSlideshowButton" class="customButton">Continue</div> 
			</div><!-- End mainContentFields -->
    	</div><!-- End mainContent -->
    </div><!-- End page-wrap --> 
</body>
</html>
<? } ?>