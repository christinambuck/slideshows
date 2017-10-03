<?
// if session has expired but cookie is still enabled then go login first
if ($_COOKIE['userName'] && !$_SESSION['nickname']) { 
?>
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/login.php?url='.$url ?>">	
<?  
exit;
}
?>
<!-- Start of heading -->
	<!-- Start of logo and title -->
		<div id="logoHeading">
            <img name="header" src="images/header.jpg" width="220" height="77" border="0" id="header" title="slideshows.ungrental.com - Create and share slideshows" alt="slideshows.ungrental.com - Create and share slideshows" />
            <h1>The Place to Create and Share Slideshows</h1>
        </div>
	<!-- End of logo and title -->
	<!-- Start of Search field -->
        <div id="searchHeading">
            <form id="searchForm" action="search.php" method="post" enctype="application/x-www-form-urlencoded" > 
                <input id= "searchKeywords" name="searchKeywords" type="text" value="Enter keywords or Id" size="25" maxlength="100" title="Enter comma separated keywords or slideshow id." />
            </form>
	<!-- End of Search field -->
	<!-- Start of navigation buttons -->
            <div id="searchButton" class="custombutton"><h8>Search</h8></div>
            <div id="homeButton" class="custombuttonYellow"><h8>Home</h8></div>   
            <div id="newAccountButton" class="custombuttonYellow"><h8>New Account</h8></div>   
            <div id="myAccountButton" class="custombuttonYellow"><h8>My Account</h8></div> 
        </div>
	<!-- End of navigation buttons -->
	<!-- Start of login box if user is NOT logged in -->
        <? if (!$_SESSION['userName']) { ?> 
        <div id="loginBox">
            <form id="loginForm" action="login.php<? if ($url) echo '?url='.$url; ?>" name="loginForm" method="post" enctype="application/x-www-form-urlencoded" > 
                <p id="username">User Name: <input id="userName" name="userName" type="text"  size="18" maxlength="50" value="<? echo $_GET['userName']; ?>" /> <? echo '<font color="red">'.$_GET['userError'].'</font>'; ?></p>
                <p id="password">Password: <input id="pw" name="pw"  type="password"  size="18" maxlength="50" /> <? echo '<font color="red">'.$_GET['pwError'].'</font>'; ?></p>
                <p id="remember">
                    <input name="keep" type="checkbox" value="1" /> <span>Remember Me</span>
                    <a href="forgot.php">Forgot Login</a>
                </p>
           <div id="loginButton" class="custombutton"><h8>Login</h8></div>       
            </form>
            <? echo '<font color="red">'.$_GET['error'].'</font>'; ?>
        </div>
        <? } else { ?>
	<!-- End of login box  -->
	<!-- Start of logout box if user IS logged in -->
        <div id="logoutBox" >
        <p>Welcome, <? echo $_SESSION['nickname'] ?></p>
        <form id="logoutForm" action="logout.php" name="logoutForm" method="post" enctype="application/x-www-form-urlencoded" > 
           <input id="logoutFld" name="logoutFld" type="hidden"  value="1"/>
        </form>
        <div id="logoutButton" class="custombutton"><h8>Logout</h8></div> 
        </div>
        <? } ?>
	<!-- End of logout box -->
<!-- End of heading -->
    