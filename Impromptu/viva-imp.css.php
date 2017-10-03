<?php
header("Content-type: text/css");
session_start();
require_once '../../group/'.$_SESSION['groupName'].'/custom_config.php'; 	// For whitle label
?>
/*
------------------------------
	Impromptu
------------------------------
*/
.jqifade{
	position: absolute; 
	background-color: #aaaaaa; 
}
div.jqi{ 
	width: 400px; 
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; 
	position: absolute; 
	background-color: #ffffff; 
	font-size: 11px; 
	text-align: left; 
	border: solid 1px #eeeeee;
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	padding: 7px;
}
div.jqi .jqicontainer{ 
	font-weight: bold; 
}
div.jqi .jqiclose{ 
	position: absolute;
	top: 4px; right: -2px; 
	width: 18px; 
	cursor: default; 
	color: #bbbbbb; 
	font-weight: bold; 
}
div.jqi .jqimessage{ 
	padding: 10px; 
	line-height: 20px; 
	color: #444444; 
}
div.jqi .jqibuttons{ 
	text-align: right; 
	padding: 5px 5px 5px 5px; 
	border: solid 1px #eeeeee; 
	background-color: #f4f4f4;
}
div.jqi button{
	margin-left:5px;
	text-align:center;
	padding: 3px 15px; 
	font-weight: bold; 
	font-size: 14px;  
	height: auto;
	width:auto;
	background-color:<? echo $mpConfig['customButtonColor'] ?>; 
	border-right:3px outset <? echo $mpConfig['customButtonColor'] ?>;	
	border-bottom:3px outset <? echo $mpConfig['customButtonColor'] ?>;	
	border-top:1px inset <? echo $mpConfig['customButtonColor'] ?>;		
	border-left:1px inset <? echo $mpConfig['customButtonColor'] ?>;
	color: <? echo $mpConfig['customButtonTextColor'] ?>; 
}
div.jqi button:hover{	
	cursor:pointer;	
	border-top:3px inset <? echo $mpConfig['customButtonColor'] ?>;
	border-left:3px inset <? echo $mpConfig['customButtonColor'] ?>;		
	border-right:1px outset <? echo $mpConfig['customButtonColor'] ?>;			
	border-bottom:1px outset <? echo $mpConfig['customButtonColor'] ?>;
}
div.jqi button.jqidefaultbutton{	
	background-color:<? echo $mpConfig['customButtonColor'] ?>; 
}
.jqiwarning .jqi .jqibuttons{ 
	background-color:  #666;
}
