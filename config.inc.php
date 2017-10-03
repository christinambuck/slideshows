<?php
//$debug=0;
/**
 * Global config file
 *
 */

session_start();

include 'configdb.php';	// default URL and database configuration 	
global $config;

if (!$_SESSION['ieDetect'])
{
	$msg = ieDetect($_SERVER['HTTP_USER_AGENT']);
	$_SESSION['ieDetect'] = 1;
}
else $msg='';


function ieDetect ($server) {
	preg_match('/MSIE (.*?);/', $server, $matches);	
	$msg = '';
	if (count($matches)>1)
	{
		//Then we're using IE
		$version = $matches[1];
		
		switch(true)
		{
			case ($version<=9):
			  $msg = 'Your version of IE does not support the latest technologies used by this website. For a better user experience, we recommend using IE 10, Google Chrome, Firefox, or Safari.';
			  break;
		}
	}
	return($msg);	
}

function formatDate($date){
$day = substr($date,6,2);
$month = substr($date,4,2);
$year = substr($date,0,4);	
return($month.'/'.$day.'/'.$year);
}

function updateLastLogin($userId){
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 	
	$today = date('Ymd');
	$sql = "UPDATE members SET lastLogin=$today WHERE userId = $userId";
	$result = mysql_query("$sql");	
return;
}
// remove the directory, and all sub content
function deldir($dir) {
   $dh=opendir($dir);
   while ($file=readdir($dh)) {
       if($file!="." && $file!="..") {
           $fullpath=$dir."/".$file;
           if(!is_dir($fullpath)) {
               unlink($fullpath);
           } else {
               deldir($fullpath);
           }
       }
   }
   closedir($dh);   
   if(rmdir($dir)) {
       return true;
   } else {
       return false;
   }
} 


/*
* Displays a form list
* Where	$labels is an array of item labels
*			$values is an array of item values corresponding to the item label
*			$name is ithe field name and id name//			
*			$selected_value  - 	if you want to default the value selected by the user upon pressing the
*									submit button then enter "$_POST[$name]" 
*								else it is a string with the default value
*			$behavior - a string containing any behavior you want on the list, such as an onchange javascript
*/
function display_list ($labels,$values,$name,$selected_value,$behavior){
	echo '<select name="'.$name.'" id="'.$name.'" '.$behavior.'>'; // start list
	$j=0; 	
	foreach ($values as $val){
		$val = trim($val);	
		echo 'val = '.$val.' ; selected = '.trim($selected_value).'<br>';	
		if(strtoupper($val) == strtoupper(trim($selected_value)))			
			echo '<option value="'.$val.'" selected>'.$labels[$j].'</option><br>';				
		else 			
			echo '<option value="'.$val.'">'.$labels[$j].'</option><br>';
		$j++;
		}	// end foreach
	echo '</select>';	// end list
}	// end function

/*
*	There is a photos.txt file for each slideshow in members/<userId>/images/<slideshowId>
*	This .txt file contains all the photos in the slideshow separated by commas in the order to be displayed 
*	When photos are originally uploaded, they are added to the file in the order they are uploaded
*	In managephotos.php, the photos can be re-arranged in the order the user wants to display them. After they are arranged
*		and the user presses the submit button, the photos are placed in order in an array called $putPhotos
*	This program takes the array $putPhotos and puts the image file names into the photos.txt file.
*/
function put_photos( $putPhotos )
{	
}

/*
*	For viewing the slideshow or managing photos
*	This function gets the image file names from the photos.txt file and returns them in array called $photos.
*	Each file name is separated by a comma
*/
function get_photos( $fileloc )
{	
	$photos[] = array();				// reset array
	$file = file_get_contents($fileloc.'/photos.txt');
	if ($file) 
	{	
		if (strlen($file))
		{	
			$file = substr ($file,0,-1);			// remove the last ,
			$photos = explode (",",$file); 			// $photos is now an array of each image file name
		}
		/*
		*	if ordered.txt exists, then put the images in the order specified in ordered.txt 
		*	and write the images in the updated order back to photos.txt and delete ordered.txt. 
		*	Ordered.txt gets created whenever the member updates the image order in their 
		*	slideshow in on the manage photos page (managephotos.php).
		*/
		if (file_exists($fileloc.'/ordered.txt')) 
		{
			// get the new order from the ordered.txt file
			$file = file_get_contents($fileloc.'/ordered.txt');
			$imageOrder = explode(",", $file);
			
			//put the new order of the image file names back into the photos.txt file
			$fh = fopen($fileloc.'/photos.txt', 'w');
			foreach ($imageOrder as $key )
			{			
				fwrite($fh, $photos[$key].',');
			}			
			fclose($fh);
			
			// delete ordered.txt since the photos are now ordered correctly in photos.txt
			unlink($fileloc.'/ordered.txt'); 
			
			//now get the newly ordered image file names			
			$file = file_get_contents($fileloc.'/photos.txt');	
			$file = substr ($file,0,-1);			// remove the last ,
			$photos = explode (",",$file); 			// $photos is now an array of each image file name		
		}
	}
	if ($photos) return ($photos);
	else return (0);
} 

?>
