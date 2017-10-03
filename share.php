<?
require_once 'config.inc.php';
global $config;
session_start();
$url = 'share.php';
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
    <title>Share Photos - Slideshows</title>
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
	if ($_GET['slideshowId']) $id = $_GET['slideshowId'];
	elseif ($_POST['slideshowId']) $id = $_POST['slideshowId'];
	
	if ($config['root']) $root = $config['root']."/";
	
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Share Slideshow</title>
		<!-- Mobile viewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
		
		<link href="css/newStyle.css" rel="stylesheet" type="text/css" />
		<link href="css/share.css" rel="stylesheet" type="text/css" />
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
				<h1 >Share Links</h1>
				<div id="mainContentFields">
	<? if ($id) { ?>
		<h6 >Before & After:<br>
		<span class="shareColor"><? echo 'www.'.$config['http_db_server'].$root.'?id='.$id ?></span></h6> 
		<h6>Slideshow:<br> 
		 <span class="shareColor"><? echo 'www.'.$config['http_db_server'].$root.'?sid='.$id ?></span></h6> 
		<h6>Gallery:<br> 
		 <span class="shareColor"><? echo 'www.'.$config['http_db_server'].$root.'?gid='.$id ?></span></h6>
	<? } else { ?>
		<h6>Missing Slideshow Id</h6>
	<? } ?>
	</body>
	</html>
<? } ?>
