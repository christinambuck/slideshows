<?
require_once 'config.inc.php';
require_once 'phpfunctions.php';
global $config;
session_start();
if ($_POST['registerFld']){ // If register was clicked
		
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	// make sure the user name is not already taken
	$sql = "SELECT userName FROM members WHERE userName = '".mysql_real_escape_string($_POST['userName'])."'";
	$result = mysql_query ("$sql", $link); // check to see if the user is already in the database
	if (!$result)
		die ('Could not select user '.$_POST['userName'].' from members table.<br>'.mysql_error($link));	
	$num_rows = mysql_num_rows($result);
	if ($num_rows == 0) //  If user name is not in the database, then continue creating account
	{
		$today = date('Ymd');
		$sql = "INSERT INTO members (userName, userPW, email, nickname, lastLogin)";
		$sql .= " VALUES (
			'".mysql_real_escape_string($_POST['userName'])."',
			'".mysql_real_escape_string($_POST['userpw'])."',
			'".mysql_real_escape_string($_POST['email'])."',
			'".mysql_real_escape_string($_POST['nickname'])."',
			'$today')";	
		$result = mysql_query ("$sql", $link); 
		if ($result) // If successfully added user	
		{ 		
			// get the user id of the user just added and use that for creating the new directories
				$res = mysql_query("SELECT LAST_INSERT_id()", $link);	
				$lastid = mysql_fetch_array($res); 				
				$sql = "SELECT * FROM members WHERE userid = '$lastid[0]'";
				$result = mysql_query("$sql");  // check to see if the user is in the database
				if (!$result)
					die ('Could not select user '.$lastid[0].' from members table.<br>'.mysqli_error($mysqli));					
				$row = mysql_fetch_array($result);
			// create user directory named <user id> and directories for the photos and descriptions
				$fileloc = 'members/'.$lastid[0].'/';
				mkdir($fileloc);
				mkdir($fileloc.'images');														// create user directory directory
				mkdir($fileloc.'descriptions');	
				mkdir($fileloc.'thumbnails');				
				if ($row['nickname']) 
					$name = $row['nickname'];
				else 
					$name = $row['userName'];
			// Send email verification
				$to = $row ['email'];
				$subject = "Slideshows Email Verification";
				$sendMsg = "
					<p>Hello ".$name.",</p>
					<p>To complete your account registration, you must click on this link <a href=\"http://".$config['http_db_server'].$config['root']."/verify.php?id=".$row['userId']."\">Verify Email</a> or cut and paste the link below in your browser.</p>
					<p><a href=\"http://".$config['http_db_server'].$config['root']."/verify.php?id=".$row['userId']."\">".$config['http_db_server'].$config['root']."/verify.php?id=".$row['userId']."</a></p>
					<p>Once you click on the link, you will be able to login and start creating slideshows. If you do not verify your email address within 30 days, your account will be removed, however, you can create a new account at any time.</p>
					<p>Have a wonderful day,</p>
					<br />
					Christy Buckholdt<br />
					Web Administrator,<br />
					slideshows.ungrental.com
					";
				if (sendMail ($to, $subject, $sendMsg)) 
					$msg = 'A verification link has just been sent to the email address you provided.'; 
				else $msg = 'Problem sending Verification email.';	
			?>
			<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/index.php?msg='.$msg; ?>">	
			<?
        exit;
		} // END if successfully added user
		else 
		{
			 $msg = $_POST['userName'].' could not be added to memebers table. '.mysql_error(); 
		}	
	} // END user name was NOT already taken
	else $msg = "This User Name has already been taken";
	
} // END if register was clicked
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>New Account</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/newaccount.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>

</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>
	<div id="page-wrap">
    	<div id="newAccount">
        	<h1 >Account Registration</h1>
            <div id="newAccountFields">
                <form method="post" enctype="application/x-www-form-urlencoded" id="newAccountForm" name="newAccountForm"> 	
                    <p class="bold">User Name<br /><input id="username" name="userName" type="text" size="30" maxlength="50" /></p>
                    <p class="bold">Nickname (displayed on Search page)<br /><input id="nickname" name="nickname" type="text" size="30" maxlength="30" /></p>
                    <p class="bold">Email*<br /><input id="email" name="email" type="email" size="20" maxlength="100" /></p>
                    <p class="bold">Password<br /><input id="userpw" name="userpw" type="password" size="20" maxlength="20" /></p>
                    <p class="bold">Re-enter Password<br /><input id="rePassword" name="rePassword" type="password" size="20" maxlength="20" /></p>	
                    <input id="registerFld" name="registerFld" type="hidden"  value="1"/>  
                </form> 
                <div id="registerButton" class="customButton">Register</div> 
            </div> 
        </div>
        <p class="note">* You will be emailed a verification link that you must click before you can login to your account and start creating slideshows.</p> 
    </div><!-- End page wrap --> 
</body>
</html>