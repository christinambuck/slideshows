<?
require_once 'config.inc.php';
global $config;
session_start();
if ($_GET['slideshowId']) $id = $_GET['slideshowId'];
elseif ($_POST['slideshowId']) $id = $_POST['slideshowId'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Slideshow</title>
<head>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body >	
<? if ($id) { ?>
	<h3  style=" text-align:center;">Your share link to this Before & After slideshow is: 
    <spam style="color:#FF0000; "><? echo $config['http_db_server'].$config['root'].'/?id='.$id ?></h3> 
	<h3  style=" text-align:center;">Your share link to this slideshow is: 
    <spam style="color:#FF0000; "><? echo $config['http_db_server'].$config['root'].'/?sid='.$id ?></h3> 
    <h3 style=" text-align:center; margin-top:15px;">Your share link to this gallery is: 
    <span style="color:#FF0000;"><? echo $config['http_db_server'].$config['root'].'/?gid='.$id ?></h3>
<? } else { ?>
	<h3>Missing Slideshow Id</h3>
<? } ?>
</body>
</html>
