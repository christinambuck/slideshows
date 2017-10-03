<?php
session_start();
require_once 'config.inc.php';
global $config;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>PrettyPhoto Close Button</title>
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
        <h2 class="centered">How to move the PrettyPhoto close button to the top right</h2>
        <p class="centered"><img src="reference/images/example.jpg" width="518" height="294" alt="Example of pretty photo close button in top right" /></p>
        <ol>
            <li>To move the close button to the top right I edited <span class="bold">jquery.prettyPhoto.js</span>. In the markup: section I moved the pp_close link to right above the pp_content_container div. I had to add a z-index style for it to display on top.<br /> 
            <img src="reference/images/pp_close.jpg" width="556" height="196" alt="prettyphoto close button" /></li> 
            <li>I used prettyPhoto's facebook theme. The theme can either be defaulted in <span class="bold">jquery.prettyPhoto.js</span><br />    
            <img src="reference/images/facebookDefault.jpg" width="819" height="219" alt="prettyphot facebook default theme" /><br />
            or added to the script<br />
            <img src="reference/images/facebookTheme.jpg" width="706" height="172" alt="prettyphoto facebook theme in script " /></li>
            <li>I created a new close button to use <a href="reference/images/close-button.png" title="prettyphoto close button" target="_blank" style="text-decoration:none"><img src="reference/images/close-button.png" width="40" height="40" alt="prettyphoto close button" style="border-width:0px;" /></a>
            </li>
            <li>I modified prettyPhoto.css to use the new button instead of prettyPhoto's sprite. I changed the image name and witdth and height.<br />
            <img src="reference/images/newCloseButton.jpg" width="1096" height="51" alt="New prettyPhoto close button for top right" />
            </li>
        </ol>
    </div>
</body>
</html>
