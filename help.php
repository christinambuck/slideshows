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
<title>Help - Slideshows</title>
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

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>> 
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
        <div class="titleText">
            <ul>
                <li>
                    <h1><a class="darkLink" href="faq.php" >FAQ</a></h1>
                </li>    
                <li>
                    <h1><a class="darkLink" href="howTo.php" >"How To" Articles</a></h1>
                </li>    
                <li>
                    <h1><a class="darkLink" href="todo.php" >Outstanding Items</a></h1>
                </li>
                <li>
                    <h1><a class="darkLink" href="mailto:christy@mindpal.com">E-mail</a></h1>
                </li>
            </ul>
    	</div>
    </div>
    <span id="bgTwo" class="bgSize"></span> <!-- required for backstretch to display background image -->
</body>
</html>