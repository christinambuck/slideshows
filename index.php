<?php
session_start();
require_once 'config.inc.php';
global $config;
// If no url is included as a parameter in the url link, then it defaults to search.php. In other words, when the user closes the slideshow/gallery/before and after window, they will be taken to the search.php page.
if ($_GET['id']) { 
	if ($_GET['url']) $url = $_GET['url'];
	elseif ($_POST['url']) $url = $_POST['url'];
?> 
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/beforeAfter.php?id='.$_GET['id'].'&url='.$url; ?>">	
<?php 
exit;
}
if ($_GET['sid']) { 
	if ($_GET['url']) $url = $_GET['url'];
	elseif ($_POST['url']) $url = $_POST['url'];
?> 
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/slideshow.php?id='.$_GET['sid'].'&url='.$url; ?>">	
<?php 
exit;
}
if ($_GET['gid']) { 
	if ($_GET['url']) $url = $_GET['url'];
	elseif ($_POST['url']) $url = $_POST['url'];
?>  
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/gallery.php?id='.$_GET['gid'].'&url='.$url; ?>">
<?php 
exit;
}
if ($_GET['msg']) $msg = $_GET['msg'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slideshows Home Page</title>
    <meta name="description" content="Just upload and organize your photos to create and share slideshows from transformations to vacations, on your mobile, tablet, laptop or desktop. It free and easy.">
    <!-- Mobile viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->

    <link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/index.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
    <!--<link href="css/index-media-queries.css" rel="stylesheet" type="text/css" />-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script>
    <script src="backstretch/jquery.backstretch.min.js"></script> <!-- required for backstretch to display background image -->
    <script src="js/slideshows.js"></script>
</head>

<body id="indexPage"  <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>> 
	<!-- Start of facebook like button script -->
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    <!--End of facebook -->
	<? require_once 'heading.inc.php'; ?>
    <div id="page-wrap">    
        <h1 class="titleText">Create & Share Slideshows</h1>
        <h2 class="mainText">From transformations to vacations.</h2>
        <h2 class="mainText">It's FREE and easy. </h2>
        <h2 id="moreInfo" class="darkLink" title="Click for More Info" >&#9658;</h2>
    </div>
    <span id="bgOne" class="bgSize"></span> <!-- required for backstretch to display background image -->   
</body>
</html>