<?
require_once 'config.inc.php';
global $config;
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Template</title>
<head>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>

</head>

<body id="index" <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<div id="page-wrap">
	<? include 'header.php'; ?>
    
    
    </div> 
</body>
</html>