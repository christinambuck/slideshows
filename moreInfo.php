<?php
session_start();
require_once 'config.inc.php';
global $config;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Slideshows More Info</title>
<meta name="description" content="Slideshow creator. Upload and organize your photos to create and share slideshows from remodels and makeovers to vacations and loved ones growing up. It free and easy.">
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/moreInfo.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="backstretch/jquery.backstretch.min.js"></script> <!-- required for backstretch to display background image -->
	<script src="js/slideshows.js"></script>

</head>

<body>
	<? require_once 'heading.inc.php'; ?>
    <div id="page-wrap"> 
    	<div class="mainText"><h2>From remodels and makeovers to vacations and loved ones growing up, in just minutes you can create your own slideshow.</h2></div>   
        <div id="bullets">
            <ul>
                <li>
                    <h3>It's free to sign up for a new account.</h3>
                    <h4>Just access your account or slideshow at least once a year to keep it open. <a class="darkLink" href="newaccount.php">Sign Up &#9658;</a></h4>
                </li>
                <li>
                    <h3>Creating a slideshow is easy.</h3>
                    <h4>Once logged in, just upload and organize your photos.</h4>
                    <h4>Create as many slideshows as you want. <a class="darkLink" href="createSlideshow.php">Create &#9658;</a></h4>
                </li>    
                <li>
                    <h3>Share your slideshow.</h3>
                    <h4>Each slideshow has a unique web address that you can post or send to others.  <a class="darkLink" href="share.php">Share &#9658;</a></h4>
                </li>
            </ul>
        </div>
    </div>
    <span id="bgTwo" class="bgSize"></span> <!-- required for backstretch to display background image -->
</body>
</html>