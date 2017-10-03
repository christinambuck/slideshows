<?
require_once 'config.inc.php';
require_once 'phpfunctions.php';
global $config;
session_start();
$msg = $_REQUEST['msg'];
if ($_POST['userName'] || $_POST['email']){ // If Continue was clicked from the forot page
		
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
	if ($_POST['email']) {
		$sql = "SELECT userName, userPW, email FROM members WHERE email = '".mysql_real_escape_string($_POST['email'])."'";
		$result = mysql_query ("$sql"); 
		if (!$result)
			$msg = 'Could not select '.$_POST['email'].' from members table.'.mysql_error();
		else {
			$num_rows = mysql_num_rows($result);	
			if ($num_rows == 0)	
				$msg = '* Invalid Email Address.';
		}
	} // END if email
	elseif ($_POST['userName']) {
		$sql = "SELECT userName, userPW, email FROM members WHERE userName = '".mysql_real_escape_string($_POST['userName'])."'";
		$result = mysql_query ("$sql"); 
		if (!$result)
			$msg = 'Could not select '.$_POST['userName'].' from members table. '.mysql_error();
		else {
			$num_rows = mysql_num_rows($result);	
			if ($num_rows == 0)	
				$msg = '* Invalid User Name.';
		}
	} //END if username
	if (!$msg) {
		$row = mysql_fetch_array($result);
		// create email response to send
		$to = $row['email'];
		$subject = "Slideshows Request Login Credentials";
		if ($num_rows > 1) {
			$sendMsg = "
				<html>
				<head>
				<title>Slideshows -  Forgot User Name/Password</title>
				</head>
				<body>
				<p>Multiple user names have been found for this email address. They are:</p>";
			do {
				 $sendMsg .= '<p style="margin-left:30px;">User Name: '.$row['userName'].' &nbsp;&nbsp;&nbsp; Password: '.$row['userPW'].' &nbsp;&nbsp;&nbsp; <a href="http://'.$config['http_db_server'].$config['root'].'/login.php?user='.$row['userName'].'&pw='.$row['userPW'].'">Login</a></p>';
				} while ($row = mysql_fetch_array($result));
			$sendMsg .="<p>Have a wonderful day,</p>
				<br />
				Christy Buckholdt<br />
				Web Administrator,<br />
				slideshows.ungrental.com
				</body>
				</html>";
			}
		else {
			$sendMsg = "
				<html>
				<head>
				<title>Slideshows -  Forgot User Name/Password</title>
				</head>
				<body>
				<p>As you requested, here is your:</p>
				<p style='padding-left:30px'>User Name: ".$row['userName']."<br>
				<span style='padding-left:10px'>Password: ".$row['userPW']."</span></p>
				<p><a href=\"http://".$config['http_db_server'].$config['root']."/login.php?user=".$row['userName']."&pw=".$row['userPW']."\">Login</a></p>
				<p>Have a wonderful day,</p>
				<br />
				Christy Buckholdt<br />
				Web Administrator,<br />
				slideshows.ungrental.com
				</body>
				</html>";
	 		}		
		if (sendMail ($to, $subject, $sendMsg)) 
			$msg = 'Your user name and password have just been sent to the email address we have on file.'; 
		else $msg = 'Problem sending login credentials.';	
		?>
        <meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/login.php?msg='.$msg; ?>">	
        <?
        exit;
	} // END if valid user name or email
} // END if user name or email was entered

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Forgot - Slideshows</title>
<meta name="robots" content="noindex, follow">
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/forgot.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>

</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>
	<div id="page-wrap">
    	<div id="forgot">
            <h1>Forgot Password?</h1>
            	<div id="forgotFields">
            		<form method="post" enctype="application/x-www-form-urlencoded" id="forgotForm" name="forgotForm"> 
                	<p class="bold">User Name<br /><input id="username" name="userName" type="text" size="20" maxlength="50" /></p>
                    <p id="or">or</p>
                	<p class="bold">Email<br /><input id="email" name="email" type="email" size="20" maxlength="100" /></p> 
           			<div id="forgotButton" class="customButton">Continue</div>        
            		</form>
               </div><!-- End forgotFields -->
        </div><!-- End forgot --> 
    </div><!-- End page wrap --> 
</body>
</html>