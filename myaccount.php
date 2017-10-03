<?
$url = 'myaccount.php';
session_start();
require_once 'config.inc.php';
global $config;
			
if ($_GET['msg']) $msg=$_GET['msg'];
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
    <title>My Account</title>
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
	
	// Update button was pressed	
	if ($_POST['userName']) {
		$sql = "UPDATE members SET
				userName='".mysql_real_escape_string($_POST['userName'])."',
				nickname='".mysql_real_escape_string($_POST['nickname'])."',
				email='".mysql_real_escape_string($_POST['email'])."',
				userPW='".mysql_real_escape_string($_POST['userpw'])."'
				WHERE userId='$_POST[userId]'";	
		$result = mysql_query("$sql");
		
		if ($result && $_POST['userName'] != $_SESSION['userName'])
		{
			$_SESSION['userName'] = $_POST['userName'];
			if ($_COOKIE['userName']){ // if we are keeping the user logged in the change cookie userName to new userName
					if (!setcookie("userName",$_POST['userName'],time()+7776000,'/',".".$config['http_db_server']) ||
						!setcookie("userName",$_POST['userName'],time()+7776000,'/',"www.".$config['http_db_server']) ||
						!setcookie("userName",$_POST['userName'],time()+7776000,'/',$config['http_db_server']) ||
						!setcookie("userName",$_POST['userName'],time()+7776000,'/'))
					{			
						$msg = 'Could not change User Name cookies in your browser.';
					}
			} // END if cookie userName
		}
		if ($result && ($_POST['nickname'] || $_SESSION['nickname']))
		{
			if ($_POST['nickname']) $_SESSION['nickname'] = $_POST['nickname'];	
			else $_SESSION['nickname'] = $_POST['userName'];	
		}
		
			/*	echo '<pre> post = ';				
				print_r( $_SESSION);
				echo '</pre>';
				
				echo '<pre> post = ';				
				print_r( $_POST);
				echo '</pre>';
				
				echo '<pre> post = ';				
				print_r( $_COOKIE);
				echo '</pre>';
				*/
	}
	// Delete slideshow was clicked
	elseif ($_POST['deleteSlideshowId']) {			
		$msg = '';		
		$sql = "SELECT userId FROM slideshows WHERE slideshowId = '$_POST[deleteSlideshowId]'";
		$result = mysql_query ("$sql"); 
		if (!$result)
			die ('Could not select slideshow '.$_POST['deleteSlideshowId'].' from slideshows table.<br>'.mysql_error());
		$row = mysql_fetch_array($result);
		if ($row['userId']) {
			// delete all slideshow directories
			$dir = 'members/'.$row['userId'].'/descriptions/'.$_POST['deleteSlideshowId']; // get the location of the descriptions directory
			if (!deldir($dir))
				$msg .= 'ERROR - could not remove '.$dir;	
			$dir = 'members/'.$row['userId'].'/images/'.$_POST['deleteSlideshowId']; // get the location of the images directory
			if (!deldir($dir))
				$msg .= 'ERROR - could not remove '.$dir;	
			$dir = 'members/'.$row['userId'].'/thumbnails/'.$_POST['deleteSlideshowId']; // get the location of the theumbnails directory
			if (!deldir($dir))
				$msg .= 'ERROR - could not remove '.$dir;	
				
			// delete slideshow from slideshows table
			$sql = "DELETE FROM slideshows WHERE slideshowId = '$_POST[deleteSlideshowId]'";
			$result =mysql_query($sql);
			if (!$result)
				$msg .= 'ERROR - could not delete '.$_POST['deleteSlideshowId'].' from slideshows table<br>'.mysql_error().'<br>'.$sql;	
		} 
		else $msg .= 'ERROR - no slideshowId found';
	} // End if delete slideshow was pressed
	$sql = "SELECT * FROM members WHERE userName ='".mysql_real_escape_string($_SESSION['userName'])."'"; 
	$result = mysql_query ("$sql");	
	$num_rows = mysql_num_rows($result);
	if (!$num_rows)  // The member was NOT found,
		$msg = "Error: Invalid Username - $_SESSION[userName]";	
	else {	
		$row = mysql_fetch_array($result);
		$userName = $row['userName'];
		$nickname = $row['nickname'];
		$password = $row['userPW'];
		$email = $row['email'];
		// Update lastLogin date 	
		$today = date('Ymd');
		$sql = "UPDATE members SET lastLogin=$today WHERE userId = $row[userId]";
		$result = mysql_query("$sql");
		
		$sql = "SELECT * FROM slideshows WHERE userId ='$row[userId]'";
		$result = mysql_query ("$sql");	
		$num_slideshows = mysql_num_rows($result);
		if ($num_slideshows)
			$row = mysql_fetch_array($result);
	} // End else inmalid user name

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My Account</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/myaccount.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>  
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
    <!-- for fancybox -->
    <link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>

</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>  
	<div id="page-wrap"> 
    	<div id="myAccount">
        	<h1>Profile</h1> 
            <form method="post" enctype="application/x-www-form-urlencoded" id="profileForm" name="profileForm">
                <p class="bold floatLeft" style="margin-left:3.867em; margin-right:0.667em;">User Name </p> <p class="floatLeft"><input id="userName" name="userName" type="text" size="17" maxlength="50" value="<? echo $userName; ?>" required /></p>
                <div class="clearFloat"></div>
                <p class="floatLeft" style="margin-left:4.733em; margin-right:0.667em;">Nickname </p> <p class="floatLeft"><input id="nickname" name="nickname" type="text" size="17" maxlength="30" value="<? echo $nickname; ?>" /></p>
                <div class="clearFloat"></div>
                <p class="bold floatLeft" style="margin-left:6.533em; margin-right:0.667em;">Email </p> <p class="floatLeft"><input id="email" name="email" type="email" size="17" maxlength="100" value="<? echo $email; ?>" required /></p>
                <div class="clearFloat"></div>
                <p class="bold floatLeft" style="margin-left:4.467em; margin-right:0.667em;">Password </p> <p class="floatLeft"><input id="userpw" name="userpw" type="password" size="17" maxlength="20" value="<? echo $password; ?>" required /></p>
                <div class="clearFloat"></div>
                <p class="bold floatLeft" style="margin-right:0.667em;">Re-enter Password </p> <p class="floatLeft"><input id="rePassword" name="rePassword" type="password" size="17" maxlength="20" required /></p>
                <div class="clearFloat"></div>
                <input name="userId" type="hidden" value="<? echo $row['userId'] ?>" />    
            </form>
            <form  action="cancel.php" method="post" enctype="application/x-www-form-urlencoded" id="cancelMembershipForm" name="cancelMembershipForm">              
                <input name="cancelUserId" type="hidden" value="<? echo $row['userId'] ?>" />    
            </form>
            <div id="updateProfile" class="customButton" style="float:left; margin-left:20px;">Update</div>
            <p class=" buttonRed cancel" style=" float:left; margin-left:20px; width:130px;; line-height:1.1em; " onclick='document.getElementById("cancelMembershipForm").submit();' >Cancel<br />Membership</p>
		</div> <!-- END myAccount -->
        
    	<div id="mySlideshows">
            <h1 style="text-align:center;">Slideshows  <span id="createButton" class="link" style="font-size:.6em; margin-left:20px;">Create Slideshow</span></h1>
           
            
           
						
            	<? 				
				if ($num_slideshows) {
					$i = 0;
					do {
						$i++;
						?>
                         <form action="beforeAfter.php" method="post" enctype="application/x-www-form-urlencoded" id="viewBeforeAfterForm<? echo $i; ?>" name="viewBeforeAfterForm" target="_self">
                            <input name="id" type="hidden" value="<? echo $row['slideshowId'] ?>" />
                            <input name="url" type="hidden" value="myaccount.php" />
                         </form> 
                         <form action="deleteSlideshow.php" method="post" enctype="application/x-www-form-urlencoded" id="deleteSlideshowForm<? echo $i; ?>" name="deleteSlideshowForm"> 
                            <input id="slideshowId" name="slideshowId" type="hidden" value="<? echo $row['slideshowId'] ?>" />             
                         </form>	
                         <div class="slideshowBox">
                           <h6 class="slideshowTitle" title="Slideshow Id <? echo $row['slideshowId'] ?>"><? echo $row['title']; ?></h6>
                            <img class="buttonImg" src="images/delete.png" alt="Delete Slideshow <? echo $row['slideshowId'] ?>" title="Delete slideshow <? echo $row['slideshowId'] ?>"  onclick='document.getElementById("deleteSlideshowForm<? echo $i; ?>").submit();'>
                            <a href="<? echo 'http://'.$config['http_db_server'].$config['root'].'/share.php?slideshowId='.$row['slideshowId'] ?>" ><img class="buttonImg" src="images/share.png" alt="Share Slideshow <? echo $row['slideshowId'] ?>" title="Share slideshow <? echo $row['slideshowId'] ?>" ></a>
                            <a href="<? echo 'http://'.$config['http_db_server'].$config['root'].'/uploadphotos.php?userName='.$_SESSION['userName'].'&slideshowId='.$row['slideshowId'] ?>" ><img class="buttonImg" src="images/upload.png" alt="Upload Photos" title="Upload more photos to slideshow <? echo $row['slideshowId'] ?>" ></a>
                            <a href="<? echo 'http://'.$config['http_db_server'].$config['root'].'/editSlideshow.php?userName='.$_SESSION['userName']. '&slideshowId='.$row['slideshowId'] ?>" ><img class="buttonImg" src="images/edit.png" alt="Edit Slideshow <? echo $row['slideshowId'] ?>" title="Edit slideshow info and arrange photos" ></a>
                            <a href="#" onClick='document.getElementById("viewBeforeAfterForm<? echo $i; ?>").submit();' style="text-decoration:none;" ><img class="buttonImg" src="images/view.png" alt="View slideshow <? echo $row['slideshowId'] ?>" title="View slideshow <? echo $row['slideshowId'] ?>" ></a>
                        </div><!-- End slideshowBox -->
                        <?						
						} while ($row = mysql_fetch_array($result)); // get next user from memeber's table
					} // END if slideshows exist
					else {
						?>
                        <h3>No slideshows created.</h3>
                        <? }
				?>
            </div><!-- END mySlideshows -->
		</div> <!-- END page wrap -->
<!-- if a new slideshow was just created display a modal message box-->
<? if ($_GET['newSL']) { ?>
	<div id="newSL_modal">
    	<a href="#" class="newSL_modal_close" ><img src="images/close-button.png" width="30" height="30" title="Close"></a>
    	<p>Your slideshow has been created.<br>Please use the Upload Photos button to add photos to your slideshow.</p>
        <img src="images/newSlideshow.jpg" width="318" height="166">
	</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
    <!-- Include jQuery Popup Overlay -->
    <script src="jquery-popup-overlay/jquery.popupoverlay.js"></script>   
	<script>
    	$(document).ready(function() {
			$('#newSL_modal').popup({
				autozindex: true,
				autoopen:	true,
				opacity:	0.6
				});	
		});	
	</script>
<? } ?>	    
<script>
    $(document).ready(function() {	
		$('.fancybox').fancybox({
			padding:0,
			margin:0,
			autosize:'false',
			autoresize:'true',
			width:'700',
			height:'800',
			afterShow: function(){
				$.fancybox.update()
			},
			
			onComplete : function() {
    			$('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
      				$('#fancybox-content').height($(this).contents().find('body').height()+30);
    			});
  			}
		});
	});
</script>
 
	
</body>
</html>
<? } ?>