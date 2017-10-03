<? 
function sendMail($to, $subject, $msg) {
	// Always set content-type when sending HTML email
	$headers = "From: christy@mindpal.com\r\n" .
			"Reply-To: christy@mindpal.com\r\n".
		   'X-Mailer: PHP/' . phpversion() . "\r\n" .
		   "MIME-Version: 1.0\r\n" .
		   "Content-Type: text/html; charset=iso-8859-1\r\n" .
		   "Content-Transfer-Encoding: 8bit\r\n\r\n";
		   
	//$headers .= "charset=utf-8\r\n";
		   
	return(mail ($to, $subject, $msg, $headers));		
}

// scan_directory_recursively( directory to scan, filter )
// expects path to directory and optional an extension to filter

function scan_directory_recursively($directory, $filter=FALSE)
{
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory))
	{
		return FALSE;
	}elseif(is_readable($directory))
	{
		$directory_tree = array();
		$directory_list = opendir($directory);
		while($file = readdir($directory_list))
		{
			if($file != '.' && $file != '..')
			{
				$path = $directory.'/'.$file;
				if(is_readable($path))
				{
					$subdirectories = explode('/',$path);
					if(is_dir($path))
					{
						$directory_tree[] = array(
							'path'      => $path,
							'name'      => end($subdirectories),
							'kind'      => 'directory',
							'content'   => scan_directory_recursively($path, $filter));
					}elseif(is_file($path))
					{
						$extension = end(explode('.',end($subdirectories)));
						if($filter === FALSE || $filter == $extension)
						{
							$directory_tree[] = array(
							'path'		=> $path,
							'name'		=> end($subdirectories),
							'extension' => $extension,
							'size'		=> filesize($path),
							'kind'		=> 'file');
						}
					}
				}
			}
		}
		closedir($directory_list);
		return $directory_tree;
	}else{
		return FALSE;
	}
}

// display an error box. When the user presses close the window closes
function errorbox($error) { ?>
<div id="msglayer" style="padding:5px; position:absolute; border-style:solid; border-color:#0041FF; border-width:thick; left:130px; top:90px; z-index:4; background-color: #FFFFFF; layer-background-color: #FFFFFF; visibility: visible;">
  <table width="500" border="0" cellpadding="10">
    <!--DWLayoutTable-->
   	<tr>
	  <td colspan="2" valign="top" align="center">
	  <? 	  
	  		if ($error) { ?>
	    	<p><font color="#990000"><? echo $error ?></font></p>	  
	  <? } ?>
	  </td>		
  </tr>
  </table>	
<? } 

/*
* 	a function that deletes a directory - beginning with the deepest directory and its files.
*
*	- it really works if you have enough rights.
*	- it returns a boolean value if everything is properly done.
*/
function deleteDir($dir)
{
   if (substr($dir, strlen($dir)-1, 1) != '/')
       $dir .= '/';
   if ($handle = opendir($dir))
   {
       while ($obj = readdir($handle))
       {
           if ($obj != '.' && $obj != '..')
           {
               if (is_dir($dir.$obj))
               {
                   if (!deleteDir($dir.$obj))
                       return false;
               }
               elseif (is_file($dir.$obj))
               {
                   if (!unlink($dir.$obj))
                       return false;
               }
           }
       }

       closedir($handle);

       if (!@rmdir($dir))
           return false;
       return true;
   }
   return false;
}
/*
* Advanced sort array by second index function, which produces ascending (default) or descending output and uses optionally natural case insensitive sorting (which can be optionally case sensitive as well).
* Only the first two arguments are required. Can replace asort if $updateindex is set to FALSE.
*/
function natisortmulti ($array, $index, $order='asc', $natsort=FALSE, $case_sensitive=FALSE, $updateindex=FALSE) {
	if(is_array($array) && count($array)>0) 
	{
   		foreach(array_keys($array) as $key) 
   			$temp[$key]=$array[$key][$index];
   		if(!$natsort) 
			($order=='asc')? asort($temp) : arsort($temp);
   		else 
		{
		 	($case_sensitive)? natsort($temp) : natcasesort($temp);
		 	if($order!='asc') 
				$temp=array_reverse($temp,TRUE);
	   	}
		if ($updateindex)
		{
			foreach(array_keys($temp) as $key) 
				(is_numeric($key))? $sorted[]=$array[$key] : $sorted[$key]=$array[$key];
   			return $sorted;
		}
		else
   			return $temp;
	}
	return $array;
}
/*
* In place of asort. Can do a natural case insensitive sort and return the keys unsorted so you can sort a second array
* based on the sort of the first array.
*/
function natisort ($array, $order='asc', $natsort=FALSE, $case_sensitive=FALSE, $updateindex=FALSE) {
	if(is_array($array) && count($array)>0) 
	{
   		foreach(array_keys($array) as $key) 
   			$temp[$key]=$array[$key];
   		if(!$natsort) 
			($order=='asc')? asort($temp) : arsort($temp);
   		else 
		{
		 	($case_sensitive)? natsort($temp) : natcasesort($temp);
		 	if($order!='asc') 
				$temp=array_reverse($temp,TRUE);
	   	}
		if ($updateindex)
		{
			foreach(array_keys($temp) as $key) 
				(is_numeric($key))? $sorted[]=$array[$key] : $sorted[$key]=$array[$key];
   			return $sorted;
		}
		else
   			return $temp;
	}
	return $array;
}

/* 
* This file contains all the date functions needed for handling subscriptions subscriptions
*/
function formatDateSlashes($date)
{
	if (!$date)
			return('00/00/00');
	$day = substr($date,6,2);
	$month = substr($date,4,2);
	$year = substr($date,0,4);	
	return($month.'/'.$day.'/'.$year);
}
function formatPhone($phone)
{
	$phone = '('.substr($phone,0,3).')'.substr($phone,3,3).'-'.substr($phone,6,4);
	return ($phone);
}
function formatPhoneExt($phone)
{
	$ph = '('.substr($phone,0,3).')'.substr($phone,3,3).'-'.substr($phone,6,4);
	if (strlen($phone) > 10)
	 $ph .= ' Ext '.substr($phone,10);
	return ($ph);
}


function display_list_label ($labels,$values,$name,$selected_value,$behavior,$useoffset){
	echo '<select name="'.$name.'" id="'.$name.'" '.$behavior.'>'; // start list
	$j=0; 	
	foreach ($labels as $lab){
		$lab = trim($lab);	
		if ($useoffset)
			$val = $j;	
		else			
			$val = trim($values[$j]);	
		if(strtoupper($lab) == strtoupper(trim($selected_value)))			
			echo '<option value="'.$val.'" selected>'.$lab.'</option><br>';				
		else 			
			echo '<option value="'.$val.'">'.$lab.'</option><br>';
		$j++;
		}	// end foreach
	echo '</select>';	// end list
}	// end function

//
// Displays a form list allowing multiple selections
// Where	$labels is an array of item labels
//			$values is an array of item values corresponding to the item label
//			$name is ithe field name and id name
//			$size is how many lines you want displayed in the list (you can scroll to undisplayed lines)
//			$selected_values  - if you want to default the values selected by the user upon pressing the
//									submit button then enter "$_POST[$name]" 
//								else it is an arry of default values
//			$behavior - a string containing any behavior you want on the list, such as an onchange javascript
//			$useoffset - if this is set, then the offset into the $values table will be used as the value instead of the actual value
//
function display_multi_list ($labels,$values,$name,$selected_values,$size,$behavior,$useoffset)
{
	echo '<select name="'.$name.'[]" size="'.$size.'" multiple id="'.$name.'"'.$behavior.'">'; // start list
	$j=0; 
	foreach ($values as $val){
		$count = count($selected_values);
		$selected = FALSE;
		$val = trim($val);
		for ($i=0; $i<$count; $i++){ 
			if (strtoupper($val) == strtoupper(trim($selected_values[$i]))){ 
				$selected = TRUE;
				break;
				}
			}	
		if ($useoffset)
			$val = $j;
		if ($selected == TRUE)
			echo '<option value="'.$val.'" selected>'.$labels[$j].'</option><br>';		
		else
			echo '<option value="'.$val.'">'.$labels[$j].'</option><br>';
		$j++;
		}	// end foreach
	echo '</select>';	// end list
}	// end function
//
// Displays a form list allowing multiple selections
// Where	$labels is an array of item labels
//			$values is an array of item values corresponding to the item label
//			$name is ithe field name and id name
//			$size is how many lines you want displayed in the list (you can scroll to undisplayed lines)
//			$selected_values  - if you want to default the values selected by the user upon pressing the
//									submit button then enter "$_POST[$name]" 
//								else it is an arry of default values
//			$behavior - a string containing any behavior you want on the list, such as an onchange javascript
//			$class - a string containing any class you want on the list;
//
function display_multi_list_class($labels,$values,$name,$selected_values,$size,$behavior,$class){
	echo '<select name="'.$name.'[]" size="'.$size.'" multiple id="'.$name.'"'.$behavior.'" class="'.$class.'">'; // start list
	$j=0; 	
	foreach ($values as $val){
		$count = count($selected_values);
		$selected = FALSE;
		$val = trim($val);
		for ($i=0; $i<$count; $i++){ 
			if (strtoupper($val) == strtoupper(trim($selected_values[$i]))){ 
				$selected = TRUE;
				break;
				}
			}	
		if ($selected == TRUE)
			echo '<option value="'.$val.'" selected>'.$labels[$j].'</option><br>';		
		else
			echo '<option value="'.$val.'">'.$labels[$j].'</option><br>';
		$j++;
		}	// end foreach	
	echo '</select>';	// end list
}	// end function

/*
*	Formats a number into a dollar amount in the format of $x,xxx.xx
*		The number does not have to have a decimal point. If it does not, a decimal point will be added
*/
function dollar_format($amount, $nodec) 
{	
	if ($nodec)
		$new_amount = '$'.number_format($amount);
	else
	{
		if (strpos($amount,'.') === false)
		{
			if (strlen($amount) == 0)
				$amount = '.00';
			elseif (strlen($amount) == 1)
				$amount = '.0'.$amount;
			elseif (strlen($amount) == 2)
				$amount = '.'.$amount;
			else
				$amount = substr($amount,0,-2).'.'.substr($amount,-2);
		}
		$new_amount = '$'.number_format($amount, 2);
	}
	return $new_amount;
}
function decimal_format($amount)
{
	$amount = substr($amount,0,-2).'.'.substr($amount,-2);
	return $amount;
}
function removeDollarFormat($amount)
{
	if (strpos($amount,'.') !== false)
		$amount = substr($amount,0,-3); // remove decimal place
	$amount = str_replace('$','',$amount);
	$amount = str_replace(',','',$amount);
	return $amount;
}

function gohome($url,$error)
{
		?>
		<meta http-equiv="Refresh" content="0; url=http://<? echo $url.'?error='.$error ?>">
		<?
}

/* resizeToFile resizes a picture and writes it to the harddisk
*  
* $sourcefile = the filename of the picture that is going to be resized
* $dest_x  = X-Size of the target picture in pixels
* $dest_y  = Y-Size of the target picture in pixels
* $targetfile = The name under which the resized picture will be stored
* $jpegqual   = The Compression-Rate that is to be used
*/
function resizeToFile ($sourcefile, $maxW, $maxH, $targetfile, $jpegqual)
{
	//detect image format
	$img["format"]=ereg_replace(".*\.(.*)$","\\1",$sourcefile);
	$img["format"]=strtoupper($img["format"]);
	if ($img["format"]=="JPG" || $img["format"]=="JPEG") //JPEG
	{				
		$img["format"]="JPEG";
		$img["src"] = ImageCreateFromJPEG ($sourcefile);
	} 
	elseif ($img["format"]=="PNG") //PNG
	{			
		$img["format"]="PNG";
		$img["src"] = ImageCreateFromPNG ($sourcefile);
	} 
	elseif ($img["format"]=="GIF") //GIF
	{			
		$img["format"]="GIF";
		$img["src"] = ImageCreateFromGIF ($sourcefile);
	} 
	elseif ($img["format"]=="WBMP") //WBMP
	{			
		$img["format"]="WBMP";
		$img["src"] = ImageCreateFromWBMP ($sourcefile);
	} 
	else //DEFAULT
	{			
		$error = "Not Supported File: ".$img["format"];
		return array(false,$error);
	}	
	$newsize = getNewImageSize ($maxW,$maxH,$sourcefile); // keep the picture in proportion so get the new image size
	$img["quality"]=$jpegqual; //default quality jpeg
	$img["new_y"]=$newsize['height'];
	$img["new_x"]=$newsize['width'];
	$img["x"] = imagesx($img["src"]);
	$img["y"] = imagesy($img["src"]);
	if ($img["format"]=="PNG" || $img["format"]=="GIF")
	{
		// Create indexed 
		$img["dest"] = imagecreate($img["new_x"],$img["new_y"]);
		// Copy the palette
		imagepalettecopy($img["dest"],$img["src"]);
		
		$color_transparent = imagecolortransparent($img["src"]);
		if ($color_transparent >= 0) 
		{
		  // Copy transparency
		  imagefill($img["dest"],0,0,$color_transparent);
		  imagecolortransparent($img["dest"], $color_transparent);
		}
	}
	else 
		$img["dest"] = ImageCreateTrueColor($img["new_x"],$img["new_y"]); /* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
	imagecopyresampled ($img["dest"], $img["src"], 0, 0, 0, 0, $img["new_x"], $img["new_y"], $img["x"], $img["y"]);
	if ($img["format"]=="JPG" || $img["format"]=="JPEG") 
		imageJPEG($img["dest"],"$targetfile",$img["quality"]);
	elseif ($img["format"]=="PNG")
		imagePNG($img["dest"],"$targetfile");
	elseif ($img["format"]=="GIF")
		imageGIF($img["dest"],"$targetfile");
	elseif ($img["format"]=="WBMP")
		imageWBMP($img["dest"],"$targetfile");	
	return array(true,'');
}

/*
* If the image size is smaller than $maxW x $maxH, it will return the size it actually is.
* If the image is larger than $maxW x $maxH, it checks to see which dimension is bigger and calculates
* the new dimensions so the aspect ratio will be preserved then returns the new dimensions.
* 
*/
function getNewImageSize ($maxW,$maxH,$image)
{
	list($width, $height, $type, $attr) = getimagesize($image);
	if ($width > $maxW) // the image width is greater than the allowed width
	{
		$new['width'] = $maxW;
		$percentage = $maxW/$width;	// Calculate the percentage it must be reduced by
		$new['height'] = round($height * $percentage); // to keep the aspect ratio the same, reduce the height by the same percentage
		if ($new['height'] > $maxH)	// if the new height is greater than the allowed height, then start over by reducing the height first
		{
			$new['height'] = $maxH;
			$percentage = $maxH/$height; // Calculate the percentage it must be reduced by
			$new['width'] = round($width * $percentage); // to keep the aspect ratio the same, reduce the width by the same percentage
		}
	}
	elseif ($height > $maxH) // if the image width was ok, but the image height is greater than the allowed height
	{		
		$new['height'] = $maxH;
		$percentage = $maxH/$height;
		$new['width'] = round($width * $percentage);
	}
	else // neither dimension is too large so display the image as is.
	{	
		$new['width'] = $width;
		$new['height'] = $height;
		$new['original'] = 1; // size not changed
	}
	return ($new);
}

?>
