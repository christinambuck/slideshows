<?php
session_start();
require_once 'config.inc.php';
global $config;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>How To - Slideshows</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>
	<div id="page-wrap">
    	<div class="reference">
            <h1 >How To . . .</h1>            	
            <h4><a href="how_to_move_prettyphoto_close_button.php">How to move the PrettyPhoto close button to the top right.</a></h3>
            <h4><a href="how_to_open_prettyphoto.php">How To Open a PrettyPhoto Iframe Inside a PrettyPhone Iframe</a></h3>
        </div><!-- End reference --> 
    </div><!-- End page wrap --> 
</body>
</html>