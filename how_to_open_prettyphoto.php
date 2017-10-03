<?php
session_start();
require_once 'config.inc.php';
global $config;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Open a PrettyPhoto Iframe</title>
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
        <h2 class="centered">How To Open a PrettyPhoto Iframe Inside a PrettyPhone Iframe</h2>
        <p>When I open the PrettyPhoto iframe I pass it a URL that I want it to go to when the iframe closes. When Inside the iframe that I opened with PrettyPhoto I have a link that opens another PrettyPhoto Iframe. I wanted the second PrettyPhoto Iframe to replace the first PrettyPhoto iframe, not open up inside the iframe. When the second iframe is closed I want to go back to the first iframe. </p> 
        <p>To accomplish this, I had three challenges to overcome. 
        <ul>
        	<li>I had to close the PrettyPhoto iframe within the iframe when the link was clicked.</li>
            <li>I had to be able to go back to the first PrettyPhoto iframe when the second iframe was closed.</li>
            <li>I had to be able to go back to the URL that I originally passed when the fisrt PrettyPhoto iframe is closed with the close button.</li>
        </ul>
        </p>
        <p class="center"><img src="reference/images/example_open_2nd_iframe.jpg" width="596" height="556" /></p>
        <ol>
            <li>From a page on my website I have a link that opens an intermediate page, beforeAfter.php, that the user never sees. This will be PrettyPhoto's parent page that opens PrettyPhoto. In this link I pass the slideshow id of the slideshow I want to display and I pass the URL that I want to return to when this 1st PrettyPhoto iframes is closed by the close button.<br /><br />
            <img src="reference/images/example_open_1.jpg" width="721" height="58" /> 
                <p>The first thing in this program I do is save the slideshow id and the URL that was passed in.<br />
    				<img src="reference/images/example_open_2.jpg" width="327" height="99" />                
                </p>
                <p>In the head section of the page I link to PrettyPhoto's css and js files and I setup PrettyPhoto's callback function. The callback function is run when PrettyPhoto is closed and I force it to close when the user clicks on either the View Slideshow or View Gallery button on the Before & After Kitchen renovation page. <br />
    				<img src="reference/images/example_open_3.jpg" width="847" height="348" />                
                </p>
                <p>In the body section, the first thing I do is open PrettyPhoto using the onLoad event which gets called when the page has finished loading. As you can see, I pass the URL that was passed to this page that I had saved. I also have 4 forms defined. 
                	<ul>
                    	<li>The first form has the "whatToView" field defined. </li>
                        <li>The second form is called "default" and will open the page of the url that was passed to beforeAfter.php. This gets invoked if the user never clicks one of the View links, but only closes the PrettyPhoto window.</li> 
                        <li>The other two forms will open either the slideshow page or the gallery page. The values in these forms will get set when the user clicks on either the View Slideshow or View Gallery button on the Before & After Kitchen renovation page. <br /><br />
                <img src="reference/images/example_open_4.jpg" width="876" height="356" /></li>
                	</ul>
                </p>
            </li> 
            <li>At this point PrettyPhoto has opened viewBeforeAfter.php as an iframe and the slideshow id and return url has been passed to viewBeforeAfter.php as GET parameters. The first thing I do in viewBeforeAfter.php is save the slideshow id and url. <br /><br />
    			<img src="reference/images/example_open_5.jpg" width="356" height="108" /> 
                <p>In the body of the page I have the two links. <br /><br />
    				<img src="reference/images/example_open_6.jpg" width="812" height="156" /> <br /><br />
                	If the user clicks either of the links, a JavaScript function is called. I pass the slideshow id and the return URL to the function. (I also pass the password, but for this tutorial I am ignoring the password.) Look carefully at the second parameter which is the url field. This is how I know where to go when the user closes the PrettyPhoto Slideshow window and is returned to the PrettyPhoto Before & After Kitchen renovation window and they close that window using the close button. The value of the url field includes parameters for the slideshow id and the url that was originally passed to beforeAfter.php. <br /><br />
    				<img src="reference/images/example_open_7.jpg" width="596" height="285" /> <br /><br />
                    These functions set the "whatToView" value in the beforAfterForm that was defined in beforeAfter.php to either "beforeAfterSlideshowForm" or "beforeAfterGalleryForm" depending on which link they clicked. The functions also set the values of the slideshow id and the url and then forces PrettyPhoto to close using the JavaScript eval() function.<br /><br />
                    When PrettyPhoto closes, the callback function defined in beforeAfter.php is executed. The callback function gets the value in the "whatToView" field to know which form to submit and then submits the form. The id and url are passed as POST data to either slideshow.php or gallery.php.                    
                    </p>         
            </li>
            <li>The first thing I do in slideshow.php and gallery.php is save the the slideshow id and url that were passed in as POST data.<br /><br />
    			<img src="reference/images/example_open_8.jpg" width="361" height="108" /><br /><br />
                <p>In slideshow.php, I set up the array of photos for the slideshow and in the head I define the PrettyPhoto function and open PrettyPhoto. I also set the callback function so that when PrettyPhoto closes, the page defined in the url that was passed in as POST data will be loaded. <br /><br />
    				<img src="reference/images/example_open_9.jpg" width="613" height="201" /></p>
                <p>In gallery.php, I do things very similar to slideshow.php except I open PrettyPhoto using the onLoad event. <br /><br />
    				<img src="reference/images/example_open_10.jpg" width="776" height="309" />
                </p>
            </li>
        </ol>
    </div>
</body>
</html>
