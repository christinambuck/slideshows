<?php
session_start();
require_once 'config.inc.php';
global $config;
if ($_GET['msg']) $msg = $_GET['msg'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Advanced Search - Slideshows</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/advancedsearch.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>

</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>
	<div id="page-wrap">
    	<div id="advancedSearch">
            <h1 >Advanced Search</h1>
            <div class="searchBox">
                <form action="search.php" method="post" enctype="application/x-www-form-urlencoded" id="advancedSearchForm" name="advancedSearchForm"> 	
                    <p class="bold">User Name<br /><input id="username" name="userName" type="text" size="30" maxlength="50" /></p>
                    <p id="or">or</p>
                    <p class="bold">Nickname<br /><input id="nickname" name="nickname" type="text" size="30" maxlength="30" /></p>
                    <p id="or">or</p>
                    <p class="bold">Slideshow Id<br /><input id="ssid" name="ssid" type="text" size="11" maxlength="11" /></p>
                    <p id="or">or</p>
                    <p class="bold">Keywords <span class="small">(comma separated)</span><br /><input id="key" name="key" type="text" size="40" maxlength="100" /></p>
                    <div id="checkboxTitles">
                        <input name="keyInTitle" type="checkbox" value="title" /> in title: <br />
                        <input name="keyInDesc" type="checkbox" value="desc" />in description: <br />
                        <input name="keyInAll" type="checkbox" value="all" checked /> all: <br />
                    </div>                  
                    <div id="advancedSearchButton" class="customButton">Search</div> 
                </form>
			</div><!-- End searchKeywords -->
        </div><!-- End advancedSearch --> 
    </div><!-- End page wrap --> 
</body>
</html>




           