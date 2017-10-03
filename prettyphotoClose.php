<?
require_once 'config.inc.php';
global $config;
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search</title>
<head>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>

</head>

<body>
    <div id="page-wrap">
		<? include 'header.php'; ?>
        <h2 style="text-align:center; margin-top:70px;">How to move the PrettyPhoto close button to the top right</h2>
        <p style="text-align:center"><img src="reference/images/example.jpg" width="518" height="294" alt="Example of pretty photo close button in top right" /></p>
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
        </ol>
    </div>
</body>
</html>
