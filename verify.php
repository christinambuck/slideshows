<?php

require_once 'config.inc.php';
global $config;
session_start();
// connect to database
$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
if (!$link) {
   die('Could not connect: ' . mysql_error());
	}

$db_selected = mysql_select_db($config['db_database'], $link);
if (!$db_selected) {
   die ('Can\'t use database : ' . mysql_error());
	} 
	
$sql = "SELECT * FROM members WHERE userId = ".$_GET['id'];
$result = mysql_query ("$sql", $link); // check to see if the user is already in the database
if (!$result)
	$msg = 'Could not select userid '.$GET['id'].' from members table. '.mysql_error($link);
else {
	$num_rows = mysql_num_rows($result);
	if (!$num_rows) $msg = 'This account is no longer on file.';
	else {
		$row = mysql_fetch_array($result);
		$sql = "UPDATE members SET
				verified='1'
				WHERE userName='$row[userName]'";	
				$result = mysql_query("$sql");	
				if (!$result)
					$msg = 'Could not update verification for user '.$row['userName'].' in members table. '.mysql_error();
	}	
}

if ($msg) { ?>
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/index.php?msg='.$msg ?>">	
<?php
exit;
}
else  {
?>
	<form id="loginForm" action="login.php?url=myaccount.php&msg=Your account has been verified." name="loginForm" method="post" enctype="application/x-www-form-urlencoded" > 
       <input id="userName" name="userName" type="hidden" value="<? echo $row['userName']; ?>" />
       <input id="pw" name="pw"  type="hidden"  value="<? echo $row['userPW']; ?>" />        
    </form>
    <script>	
		document.loginForm.submit();
	</script>

<?php } ?>