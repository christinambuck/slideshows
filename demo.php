<?
require_once 'config.inc.php';
global $config;

if ($_GET['msg']) $msg = $_GET['msg'];
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Demo - Slideshows</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/demo.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script src="backstretch/jquery.backstretch.min.js"></script> <!-- required for backstretch to display background image -->
	<script src="js/slideshows.js"></script>
</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?> 
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
        <div class="titleText"><h1>There are 3 formats you can choose to demo.</h1></div>   
        <div class="mainText">
            <ul>
                <li>
                    <h2><a class="darkLink" href="beforeAfter.php?id=3&url=demo.php">Before & After</a></h2>
                </li>
                <li>
                    <h2><a class="darkLink" href="slideshow.php?id=1&url=demo.php" >Slideshow</a></h2>
                </li>    
                <li>
                    <h2><a class="darkLink" href="gallery.php?id=2&url=demo.php" >Gallery</a></h2>
                </li>
            </ul>
    	</div>
    </div>
    <span id="bgTwo"  class="bgSize"></span> <!-- required for backstretch to display background image -->
</body>
</html>