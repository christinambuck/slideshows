<? 

/*	Cancel a member's membership - This program will:
*	1. delete the member's user id directory and all subdirectories and photos and description/text files
*	2. remove the member from the member's table
*		
*/

if ($_POST['userId']) {
	session_start();
	require_once 'config.inc.php';
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 		
	$error = '';		
	$sql = "SELECT userId FROM members WHERE userId = '$_POST[userId]'";
	$result = mysql_query ("$sql"); 
	if (!$result)
		die ('Could not select user '.$_POST['userId'].' from members table.<br>'.mysql_error());
	$row = mysql_fetch_array($result);
	if ($row['userId']) {
		// delete all user specific files and directories
		$dir = 'members/'.$row['userId']; // get the location of the member's photo images/descriptions
		if (!deldir($dir))
			$error .= 'ERROR - could not remove '.$dir;	
			
		// delete user from members table
		$sql = "DELETE FROM members WHERE userId = $row[userId]";
		$result =mysql_query($sql);
		if (!$result)
			$error .= 'ERROR - could not delete '.$_POST['userId'].' from members table<br>'.mysql_error().'<br>'.$sql;	
		} 
		else $error .= 'ERROR - no userId found';
		

		$sql = "SELECT slideshowId FROM slideshows WHERE userId ='$row[userId]'";
		$result = mysql_query ("$sql");	
		$num_slideshows = mysql_num_rows($result);
		if ($num_slideshows) {
			$row = mysql_fetch_array($result);		
			do {
				$sql = "DELETE FROM slideshows WHERE slideshowId = '$row[slideshowId]'";
				$res =mysql_query($sql);
				if (!$res)
					$msg .= 'ERROR - could not delete '.$row['slideshowId'].' from slideshows table<br>'.mysql_error().'<br>'.$sql;									
			} while ($row = mysql_fetch_array($result)); // get next slideshowId 
		}// End if slideshows exist
		if (!$error) {		 
			session_unset();  //remove all the variables in the session		
			setcookie("userName", "", time()-3600); // to delete the cookie set the expiration date to one hour ago
			?> 
			<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/index.php?msg=Your membership has been cancelled.' ?>">	
		<? 	
			exit;
		} 
	}
	elseif (!$_POST['cancelUserId']) $error .= 'ERROR - missing user ID';
	if ($error) { ?>    
		<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/myaccount.php?msg='.$error; ?>">
	<? 	
        exit;
		} ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Delete Slideshow</title>
<meta name="robots" content="noindex, follow">
<head>
    <link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
    <script src="Impromptu/jquery-impromptu-ext.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/slideshows.js"></script>
    <script type='text/javascript'>
		function cancelCallback(v,m,f){
			if(v == '1')
				 $("#cancelMembershipForm").submit(); 
			else
				window.location="myaccount.php";
		}					
		function cancelMembership(){
			$.prompt("Are you sure you want to cancel your membership?",{
				callback: cancelCallback,
				buttons: { Yes: '1', No: '0' }
				});	
		}
    </script> 
</head>
<body onLoad="cancelMembership();">	
	<form action="cancel.php" method="post" enctype="application/x-www-form-urlencoded" id="cancelMembershipForm" name="cancelMembershipForm"> 
        <input id="userId" name="userId" type="hidden" value="<? echo $_POST['cancelUserId'] ?>"/>             
    </form>
</body>
</html>