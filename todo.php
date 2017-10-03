<?php
session_start();
require_once 'config.inc.php';
global $config;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Outstanding Items - Slideshows</title>
<meta name="robots" content="noindex, follow">
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
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
			$("#accordion").accordion({ collapsible: true, active:false });
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
            <h1>Outstanding Items to Implement</h1>            	
            <div id="accordion">
                <h4><a href="#">Administrative tasks</a></h4>
                <div>
                    <ul>
                        <li>Delete accounts that have been inactive for over one year.</li>
                        <li>Delete unverified accounts after 30 days.</li>
                        <li>Add a terms of use agreement that must be accepted when registering.</li>
                    </ul>
                </div>          
                <h4><a href="#">Add social links.</a></h4>            
                <div>
                <p></p>
                </div>          
                <h4><a href="#">Add music to slideshows.</a></h4>            
                <div>
                    <p></p>
                </div>           
                <h4><a href="#">Be able to share slideshows automatically</a></h4>            
                <div>
                    <ul>
                        <li>To Facebook</li>
                        <li>send email with message and link</li>
                    </ul>                   
                </div>             
                <h4><a href="#">Add ads as a footer to some of the pages</a></h4>            
                <div>
                <p></p>
                </div>
            </div><!-- End of accorion -->
        </div><!-- End faq --> 
    </div><!-- End page wrap --> 
</body>
</html>