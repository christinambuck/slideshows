<?php
session_start();
require_once 'config.inc.php';
global $config;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>FAQ - Slideshows</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Make it Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/faq.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
	<script>
		$(document).on('ready', function(){
			$("#accordion").accordion();
			$(window).resize(function() {	// then get screen size to display correct background image whenever browser is resized
				setTimeout("location.reload();",1000);
			});
		});
	</script>

</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>
	<div id="page-wrap">
    	<div class="faq">
            <h1>Frequently Asked Questions</h1>            	
            <div id="accordion">
                <h4><a href="#">I am not able to upload my photos. What can I do?</a></h4>
                <div>
                <p>The max upload limits have been set as hight as possible without affecting the site's stability. Depending on the size of your files and your internet upload speed, you may have a problem uploading more than 3 to 5 photos at a time. If you wish to upload 10 at a time, you may be required to resize your pictures first. Do not resize them any smaller that 800 width x 600 height since that is the size that will be dispayed in the slideshow. </p>
                </div>          
                <h4><a href="#">How do I create a gallery?</a></h4>            
                <div>
                <p>A gallery is automatically created for you when you create your slideshow. In the link you share with your friends or put on your website, the only difference between displaying a slideshow, gallery, or before and after page is the name of the parameter you pass. For a slideshow, you would pass sid=&lt;slideshow id&gt;; for a gallery, your would pass gid=&lt;slideshow id&gt;; and for the before and after page you would pass id=&lt;slideshow id&gt; as you can see when you click on the "Share" link on your My Account page.</p>
                </div>          
                <h4><a href="#">How can I include the share link on my web page?</a></h4>            
                <div>
                    <p>Replace &lt;slideshow id&gt; with your slideshow id.<br /> 
                        <textarea class="mylink" ><? echo '<a href="http://'.$config['http_db_server']; if ($config['root']) $root = $config['root']."/"; echo $root.'?id=<slideshow id>">Slideshow Name</a>' ?></textarea>
                    </p>
                </div>           
                <h4><a href="#">When I include the slideshow link on my website, can I return to my website when the slideshow is closed?</a></h4>            
                <div>
                    <p>Yes you can. Just include the URL in the slideshow link as shown below. Replace &lt;slideshow id&gt; with your slideshow id and &lt;URL&gt; with your website address.<br /> 
                        <textarea class="mylink"><? echo '<a href="http://'.$config['http_db_server']; if ($config['root']) $root = $config['root']."/"; echo $root.'?id=<slideshow id>&url=http://<URL>">Slideshow Name</a>' ?></textarea>
                    </p>
                </div>             
                <h4><a href="#">How do I find out what the slideshow id is?</a></h4>            
                <div>
                <p>On your "My Account" page, move your mouse over the slideshow title, move your mouse over the "View" link, or click on the "Share" link.</p>
                </div>
            </div><!-- End of accorion -->
        </div><!-- End faq --> 
    </div><!-- End page wrap --> 
</body>
</html>