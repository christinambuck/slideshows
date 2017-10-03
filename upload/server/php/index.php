<?php
session_start();
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
if ($_SESSION['slideshowId'] && $_SESSION['userId'])
{
		$userId = $_SESSION['userId'];
		$slideshowId = $_SESSION['slideshowId'];
}
else
{	
		$userId = '0';
		$slideshowId = '0';
}
$fileloc = '../../../members/'.$userId.'/';	
$imagePath = $fileloc.'images/'.$slideshowId.'/';
$thumbPath = $fileloc.'thumbnails/'.$slideshowId.'/';

error_reporting(E_ALL | E_STRICT); 
require('UploadHandler.php'); 
$options = array(
	'upload_dir'=>$imagePath, 
	'upload_url'=>$imagePath,
	'image_versions' => array(
                // The empty image version key defines options for the original image:
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),               
                '' => array(
                    'max_width' => 1024,
                    'max_height' => 768
                ),				
                'thumbnail' => array(
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
                    //'upload_url' => $this->get_full_url().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    //'crop' => true,
					'upload_dir' =>$thumbPath, 
					'upload_url' =>$thumbPath, 
                    'max_width' => 120,
                    'max_height' => 120
                )
            )
	); 
$upload_handler = new UploadHandler($options);