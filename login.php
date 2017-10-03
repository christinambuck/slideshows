<? 
$url = 'myaccount.php';

// from forgot email
if ($_GET['user']) $_POST['userName'] = $_GET['user'];
if ($_GET['pw']) $_POST['pw'] = $_GET['pw'];
//

require_once 'config.inc.php';
require_once 'phpfunctions.php';
global $config;
session_start();
$msg = $_GET['msg'];
if (!$_POST['userName'])
	if ($_COOKIE['userName']) $_POST['userName'] = $_COOKIE['userName'];
	elseif ($_SESSION['userName']) $_POST['userName'] = $_SESSION['userName'];
if ($_POST['userName']) {	
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	$sql = "SELECT * FROM members WHERE userName = '".mysql_real_escape_string($_POST['userName'])."'";
	$result = mysql_query ("$sql", $link); // check to see if the user is already in the database
	if (!$result)
		die ('Could not select user '.$_POST['userName'].' from members table.<br>'.mysql_error($link));
	$num_rows = mysql_num_rows($result);
	if ($num_rows == 0){	// if the user name is not in the database, then display an error			
		$error['user']='*';
		$msg = 'Invalid User Name.';			
		} // end username was not in membeers table
	else { // member is in table
		$row = mysql_fetch_array($result);
		if ($_POST['pw'] || (!$_SESSION['userId'] && !$_COOKIE['userName'])) { // username was in members table so if they were not already logged in check for valid pw		
			if ($row['userPW'] != $_POST['pw']){
				$error['pw']='*';
				$msg = 'Invalid Password.';
				}
			else {// if valid password
			 	if ($row['verified']) {
					if (isset($_POST['keep'])){ // if we should keep the user logged in
						if (!setcookie("userName",$_POST['userName'],time()+7776000,'/',".".$config['http_db_server']) ||
							!setcookie("userName",$_POST['userName'],time()+7776000,'/',"www.".$config['http_db_server']) ||
							!setcookie("userName",$_POST['userName'],time()+7776000,'/',$config['http_db_server']) ||
							!setcookie("userName",$_POST['userName'],time()+7776000,'/'))
						{			
						$msg = 'Could not set cookies in your browser. Please set your browser to allow cookies.';
						}	
					} //END if keep is set
				}
				else {
					$url = 'index.php';
					if ($row['nickname']) $name = $row['nickname'];
					else $name = $row['userName'];					
					$to = $row ['email'];
					$subject = "Slideshows Email Verification";
					$sendMsg = "
						<p>Hello ".$name.",</p>
						<p>To complete your account registration, you must click on this link: <a href=\"http://".$config['http_db_server'].$config['root']."/verify.php?id=".$row['userId']."\">Verify Email</a>.</p>
						<p>Once you click on the link, you will be able to login and start creating slideshows. If you do not verify your email address within 30 days, your account will be removed, however, you can create a new account at any time.</p>
						<p>Have a wonderful day,</p>
						<br />
						Christy Buckholdt<br />
						Web Administrator,<br />
						slideshows.ungrental.com
						";
					if (sendMail ($to, $subject, $sendMsg)) 
						$msg = 'Your Account has not been verified. A verification link has just been sent to the email address we have on file.'; 
					else $msg = 'Problem sending Verification email.';	
				}
			}//END if valid password
		} //END if user was logging in
		
		//if the user logged in successfully
		if (!$msg) {
			$_SESSION['userName'] = $_POST['userName'];
			$_SESSION['userId'] = $row['userId'];
			if ($row['nickname']) 
				$_SESSION['nickname'] = $row['nickname'];
			else 
				$_SESSION['nickname'] = $row['userName'];
				
			// Update lastLogin date 	
			$today = date('Ymd');
			$sql = "UPDATE members SET lastLogin=$today WHERE userId = $row[userId]";
			$result = mysql_query("$sql");	
			?>
            <meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/myaccount.php' ?>">	
            <?
            exit;
		}		
	} // end username was in members table	
	
} // END if user clicked Login
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login - Slideshows</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/login.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>

</head>

<body <? if ($msg) {  ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php';  ?>
	<div id="page-wrap">
    	<div id="login">
            <h1 >Log in to your account</h1>
            	<div id="loginFields">
            		<form id="loginForm" action="login.php" name="loginForm" method="post" enctype="application/x-www-form-urlencoded" > 
                	<p class="bold">User Name<br /><input id="userName" name="userName" type="text"  size="30" maxlength="50" value="<? echo $userName; ?>" /> <? echo '<font color="red">'.$userError.'</font>'; ?></p>
                	<p class="bold">Password<br /><input id="pw" name="pw"  type="password"  size="18" maxlength="50" /> <? echo '<font color="red">'.$pwError.'</font>'; ?> <a href="forgot.php">Forgot?</a></p>
                	<p id="remember"><input name="keep" type="checkbox" value="1" /> Remember Me</p>
           			<div id="loginButton" class="customButton">Login</div>       
            		</form> 
              </div><!-- End loginFields --> 
        </div><!-- End login --> 
    </div><!-- End page wrap --> 
</body>
</html>