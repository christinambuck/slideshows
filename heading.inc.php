	<div id="heading"> 
        <div id="heading-wrapper"> 
            <h3>Slideshows</h3> 
            <div id="searchHeading">
                <form id="searchForm" action="search.php" method="post" enctype="application/x-www-form-urlencoded" > 
                    <input id= "searchKeywords" name="searchKeywords" type="text" value="Enter keywords or Id" size="25" maxlength="100" title="Enter comma separated keywords or slideshow id." /> <span id="searchButton" class="searchLink">Search</span>
                </form>
            </div>  <!-- End of searchHeading -->
            <div id="navigation">
                	<a href="index.php">HOME</a>
                    <? if (!$_SESSION['userName']) { ?>
                    <a href="newaccount.php">NEW ACCOUNT</a>
                    <? } else {?>
                    <a href="myaccount.php">MY ACCOUNT</a>
                    <? } ?>
                    <a href="demo.php">DEMO</a>
                    <a href="help.php">HELP</a>
                    <? if (!$_SESSION['userName']) { ?>
                    	<a class="bold" href="login.php">LOGIN</a>
                    <? } else {?>
                    	<a class="bold" href="logout.php">LOGOUT</a>
                    <? } ?>
            </div><!-- End navigatiion -->
            <!-- display drop down menu on small screens -->
            <div id="menu"><a href="#" onclick="displayMenu();">MENU</a></div>
            <div id="menuNavigation">
            	<ul>
                	<li><a href="index.php">HOME</a></li>
                    <? if (!$_SESSION['userName']) { ?>
                    <li><a href="newaccount.php">NEW ACCOUNT</a></li>
                    <? } else {?>
                    <li><a href="myaccount.php">MY ACCOUNT</a></li>
                    <? } ?>
                    <li><a href="demo.php">DEMO</a></li>
                    <li><a href="help.php">HELP</a></li>
                    <? if (!$_SESSION['userName']) { ?>
                    	<li class="bold"><a href="login.php">LOGIN</a></li>
                    <? } else {?>
                    	<li class="bold"><a href="logout.php">LOGOUT</a></li>
                    <? } ?>
                </ul>
            </div><!-- End menu navigatiion -->
        </div><!-- heading-wrapper --> 
    </div><!-- heading -->