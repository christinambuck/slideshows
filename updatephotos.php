<?
/*
*	updatephotos.php - 
*	called from managephotos.php / slideshows.js using AJAX to update the photo title 
*	and/or description and to delete a photo
*	called from managephotos.php / popup.js using AJAX to save the order automatically whenever user drags an image.
*	called from uploadphotos.php when the Save button is clicked to save all the titles and descriptions
*	of the uploaded images
*/
session_start();
require_once 'config.inc.php';

// Make sure the user is logged in
if (!$_SESSION['userName'] && $_COOKIE['userName'])
	$_SESSION['userName'] = $_COOKIE['userName'];
if (!$_SESSION['userName']) // if user is not logged in display the login page
{
	$msg = 'Please close this window and login first.';	
}
//	if user is logged in and Update button was clicked from the Upload Photos page 
//	(uploadphotos.php, update the title and description
elseif ($_POST['upUpdate']) { 
	for ($i=0; $i < $_POST['numfiles']; $i++) 
	{
		$descurl = 'descurl'.$i;
		$imagetitletxt = 'imagetitle'.$i;
		$desctxt = 'desc'.$i;
		if ($_POST[$descurl]) 
		{						
			// write image description into text file		
			$file = fopen($_POST[$descurl], "w");
			if ($file) {			
				fwrite($file, stripslashes($_POST[$imagetitletxt]).';;'.stripslashes($_POST[$desctxt]));
				fclose($file);
			}
			else { $msg .= 'could not open txt file: '.$_POST[$descurl].'<br>';  $error = 1; }
		} // END if
	} // END for
	$msg= 'Your titles and descriptions have been updated.';				
} // END if Update clicked

//if user is logged in	
else {
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	$userName= $_SESSION['userName'];	
	$sql = "SELECT userId FROM members WHERE userName ='".mysql_real_escape_string($userName)."'"; 
	$result = mysql_query ("$sql");			 
	$row = mysql_fetch_array($result);
	$userId = $row['userId'];
	
	//  set up location for images in the members/userid/images/slideshowId folder.  		
	$slideshowId = $_POST['slideshowId']; 	
	$fileloc = 'members/'.$userId.'/'; 	
	$imagesdir = $fileloc.'images';					
	$imageloc = $fileloc.'images/'.$slideshowId;
	
	// If Update button was clicked, update the description	
	if ($_POST['mpupdate']) 
	{	
		$path_parts = pathinfo($_POST['descurl']);
		$dir = $path_parts['dirname'];
		if (!file_exists($dir))
			mkdir($dir);		// create directory if it does not exist
		$file = fopen($_POST['descurl'], "w");
			
		// title max lenght is 30 and description max length is 80	
		$title = stripslashes(substr($_POST['imagetitle'],0,30));	
		$desc = stripslashes(substr($_POST['desc'],0,80));	
		fwrite($file, $title.';;'.$desc); 
		fclose($file);
		//$msg= 'Your title and description has been updated.';
	}
	// If Save Order button was clicked, save the order	
	elseif ($_POST['imageOrder']) // If Update Order button was clicked, update the order of the photos
	{							
		// get the image file names in the current order and put them into an array
		$images = get_photos($imageloc);
		
		// get the new order from the string  $_POST[imageOrder] and put it in an array
		$imageOrder = explode(",", $_POST['imageOrder']);
		
		//put the new order of the image file names back into the text file
		$fh = fopen($imageloc.'/photos.txt', 'w');
		foreach ($imageOrder as $key ){
			fwrite($fh, $images[$key].',');
		}
		fclose($fh);				
		$_POST['mpcurrent'] = 'mpdiv';
	}
	// If member changed the order of a photo in their slideshow, then update the 	
	elseif ($_POST['saveImageOrder']) 
	{		
		//	put the new order of the imaged into the ordered.txt file.
		//	the image order numbers are comma separated.
		//	will create the text file if it does not exist. 
		//	and will erase the existing order if it already exists.
		$fh = fopen($imageloc.'/ordered.txt', 'w');
		fwrite($fh, $_POST['saveImageOrder']);
		fclose($fh);
	}
	elseif ($_POST['mpdelete']) // If Delete button was clicked
	{	
		// make it relative
		$_POST['imageurl'] = str_replace($config['http_db_server'],'..',$_POST['imageurl']); 
		
		// get the image file names in the current order and put them into an array
		$images = get_photos($imageloc);
		
		//remove the image file name from the text file
		$fh = fopen($imageloc.'/photos.txt', 'w');
		foreach ($images as $image )
		{
			//echo "image = ".$image." post image = ".$_POST['imageurl'].'<br>';
			if ($image != $_POST['imageurl'])
				fwrite($fh, $image.',');
		}
		fclose($fh);		
		unlink($_POST['imageurl']);
		if (file_exists($_POST['descurl']))
			unlink($_POST['descurl']);	
		$_POST['thumburl'] = str_replace($config['http_db_server'],'..',$_POST['thumburl']); // make it relative
		unlink($_POST['thumburl']);			
		$_POST['mpcurrent'] = 'mpdiv';
	}
		
} //END if user is logged in
   
