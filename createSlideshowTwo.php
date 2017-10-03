<?
require_once 'config.inc.php';
global $config;
session_start();
$url = 'createSlideshow.php';
if ($_GET['msg']) $msg=$_GET['msg'];
if ($_GET['id']) $slideshowId= $_GET['id'];
/*
print_r($_SESSION);
echo '<hr />';
print_r($_POST);
*/
if (!$_SESSION['userName'] && $_COOKIE['userName'])
	$_SESSION['userName'] = $_COOKIE['userName'];
if (!$_SESSION['userName']) // if user is not logged in display the login page
{
	if (!$msg) $msg = 'Please login first.';
	?>
	<!DOCTYPE html>
	<html>
    <head>
    <meta charset="utf-8">
    <title>Create Slideshow</title>
	<meta name="robots" content="noindex, follow">
        <!-- Mobile viewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
        
        <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
        <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
        <link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
        <script type="text/javascript" src="js/slideshows.js"></script>
    
    </head>
    
    <body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
        <? require_once 'heading.inc.php'; ?>
    </body>
    </html>
    <?
}
else { // if user is logged in
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	$sql = "SELECT userId FROM members WHERE userName ='".mysql_real_escape_string($_SESSION['userName'])."'"; 
	$result = mysql_query ("$sql");	
	$num_rows = mysql_num_rows($result);
	if (!$num_rows)  // The member was NOT found,
		$msg = "Error: Invalid Username - $_SESSION[userName]";	
	else {	
		$row = mysql_fetch_array($result);
		$userId = $row['userId'];
					
		// first time here, insert the slideshow in the database
		if ($_POST['createSlideshowrFld']) { 
			$date = date('Ymd');			
			$title = mysql_real_escape_string($_POST['title']);	
			
			$sql = "SELECT slideshowId FROM slideshows WHERE title = '".mysql_real_escape_string($_POST['title'])."' && date = '$date'";
			$result = mysql_query ("$sql");	
			$num_rows = mysql_num_rows($result);
			if ($num_rows) {	
				$msg = 'A slideshow with this title and date already exists.';
				$row = mysql_fetch_array($result);
				$slideshowId = $row['slideshowId'];
			}
			else {	
				if (strlen($_POST['description']) > 255) $_POST['description'] = substr($_POST['description'],0,255);
				// insert the slideshow into the database and get the slideshow id to be used as the folder name
				$sql = "INSERT INTO slideshows (userId, password, private, keywords, title, description, date) 
					VALUES (
					$userId,
					'".mysql_real_escape_string($_POST['slideshowPW'])."',
					'$_POST[private]', 
					'".mysql_real_escape_string($_POST['keywords'])."', 
					'".mysql_real_escape_string($_POST['title'])."',
					'".mysql_real_escape_string($_POST['description'])."',  
					'$date')";
				$result = mysql_query ("$sql"); 
				if (!$result) {
					$msg = 'Error: Could not add slideshow to slideshow database.';
					//echo mysql_error();
				}
				else {
					$slideshowId = mysql_insert_id();
					$fileloc = 'members/'.$userId.'/'; 			// get the location of the member's slideshow images/descriptions
					mkdir($fileloc.'images/'.$slideshowId);			// create image directory for slideshow
					mkdir($fileloc.'thumbnails/'.$slideshowId);		// create thumbnail directory for slideshow
					mkdir($fileloc.'descriptions/'.$slideshowId);	// create descriptions directory for slideshow	
					// create photos.txt to keep the order of the photos
					$db = $fileloc.'images/'.$slideshowId.'/photos.txt';	 
					$file = fopen ($db,'w'); // create new text file 
					if (!$file) $msg = 'ERROR: Could not create photos.txt for slideshow '.$title.'.';				
				} // END else no insert error	
			} // END the slideshow already exists
		} // END if first time here		
	} // END member was found
if (!$msg && $_POST['createSlideshowrFld']) {
	header( "Location: myaccount.php?newSL=1" );
	exit;	
} else {
	header( "Location: myaccount.php?msg=$msg" );
	exit;
}
?>
<? } // END if user was logged in ?> 