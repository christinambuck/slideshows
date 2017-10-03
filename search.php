<?
/* 
search.php - This page is called 
	1. after pressing the search button on the heading of all pages 
	2. from the advanced search page
	3. after displaying a slideshow or gallery not initiated on this page. When returning here from a slideshow or gallery, the id parameter is
	   passed in so we can find the user id for that slideshow. Once we get the user id then we can display all 
	   slideshows created by that user.
	4. The user is returned back to search.php after viewing the before and after photo page, slideshow page and gallery page from selecting a link on this page.
*/		   
require_once 'config.inc.php';
include 'phpfunctions.php';
global $config;
session_start();
if ($_GET['msg']) $msg = $_GET['msg'];
// connect to database
$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
if (!$link) {
   die('Could not connect: ' . mysql_error());
	}

$db_selected = mysql_select_db($config['db_database'], $link);
if (!$db_selected) {
   die ('Can\'t use database : ' . mysql_error());
	} 
if ($_GET['keywords']) $_POST['searchKeywords'] = $_GET['keywords'];
if ($_POST['searchKeywords'] == "Enter keywords or Id") $_POST['searchKeywords'] = "";
if ($_GET['id'] || $_POST['id']) {
	if ($_POST['id']) $id = $_POST['id'];
	else $id = $_GET['id'];
	$sql = "SELECT userId FROM slideshows WHERE	slideshowId = ".$id;
	$result = mysql_query ("$sql");
	$num_slideshows = mysql_num_rows($result);
	if ($num_slideshows) {
		$row = mysql_fetch_array($result);
		$sql = "SELECT * FROM slideshows WHERE private = 0 AND userId = ".$row['userId'];
		$id = true;
	}
}
elseif ($_POST['searchKeywords']) {	
	$keywords = $_POST['searchKeywords'];
	$sql = "SELECT * FROM slideshows WHERE private = 0 AND"; // only select slideshows that are NOT private
	
	// put list of keywords into array. List should be separated by commas.
	//$keywords = str_replace(',', ' ', $_POST['searchKeywords']);
	//$keywords = str_replace('  ', ' ', $keywords);
	$keywordArray = explode(",", $keywords);
	$count = count($keywordArray);
	if ($count == 1 && is_numeric($keywordArray[0]) ) { // if it is a numeric number assume it is a slideshow id
		$sql .= " slideshowId = $keywordArray[0]";
		$slideshowId = $keywordArray[0];
		$id = true;	
	}
	elseif ($count == 1) {
		$sql .= " (keywords LIKE '%".mysql_real_escape_string($keywords)."%') OR";
		$sql .= " (title LIKE '%".mysql_real_escape_string($keywords)."%') OR";
		$sql .= " (description LIKE '%".mysql_real_escape_string($keywords)."%')";
		$title = 'Search Results';
	}
	else { // list of one or more keywords
		
		foreach ($keywordArray as $keyword){
			$sql .= " (keywords LIKE '%".mysql_real_escape_string($keyword)."%') OR";
			}	
		foreach ($keywordArray as $keyword){
			$sql .= " (title LIKE '%".mysql_real_escape_string($keyword)."%') OR";
			}	
		foreach ($keywordArray as $keyword){
			$sql .= " (description LIKE '%".mysql_real_escape_string($keyword)."%') OR";
			}		
		$sql = substr ($sql,0,-3);			// remove the last OR
		$title = 'Search Results';
	}
}
elseif ($_POST['userName']) {
	$sql = "SELECT userId FROM members WHERE userName ='".$_POST['userName']."'";
	$result = mysql_query ("$sql");		
	$row = mysql_fetch_array($result);	
	$sql = "SELECT * FROM slideshows WHERE private = 0 AND userId = ".$row['userId'];
	$title = "Slideshows created by ".$_POST['userName'];	
}
elseif ($_POST['nickname']) {
	$sql = "SELECT userId FROM members WHERE nickname ='".$_POST['nickname']."'";
	$result = mysql_query ("$sql");		
	$row = mysql_fetch_array($result);	
	$sql = "SELECT * FROM slideshows WHERE private = 0 AND userId = ".$row['userId'];
	$title = "Slideshows created by ".$_POST['nickname'];	
}
elseif ($_POST['ssid']) {
	$sql = "SELECT * FROM slideshows WHERE private = 0 AND slideshowId = ".$_POST['ssid'];
	$slideshowId = $_POST['ssid'];	
}
elseif ($_POST['key']) {
	$keywords = $_POST['key'];
	$sql = "SELECT * FROM slideshows WHERE private = 0 AND"; // only select slideshows that are NOT private	
	$keywordArray = explode(",", $keywords);
	$count = count($keywordArray);		
		$title = 'Search Results';
	if ($_POST['keyInTitle'] == 'title' || $_POST['keyInAll'] == 'all') 	
		foreach ($keywordArray as $keyword){
			$sql .= " (title LIKE '%".mysql_real_escape_string($keyword)."%') OR";
			}
	if ($_POST['keyInDesc'] == 'desc' || $_POST['keyInAll'] == 'all') 			
		foreach ($keywordArray as $keyword){
			$sql .= " (description LIKE '%".mysql_real_escape_string($keyword)."%') OR";
			}
	if ($_POST['keyInAll'] == 'all') 	
		foreach ($keywordArray as $keyword){
			$sql .= " (keywords LIKE '%".mysql_real_escape_string($keyword)."%') OR";
			}	
	$sql = substr ($sql,0,-3);			// remove the last OR
}
else { // if no search term entered then go to advanced search page
?>	
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/advancedsearch.php?msg='.$msg; ?>">
<?
exit;
}
if ($sql){
	$sql .= " ORDER BY date DESC";
	$result = mysql_query ("$sql");
	$num_slideshows = mysql_num_rows($result);
	if ($num_slideshows) {
		$row = mysql_fetch_array($result);
	}
	if ($slideshowId) {
		$sql = "SELECT nickname, userName FROM members WHERE userId ='".$row['userId']."'"; 
		$resultMembers = mysql_query ("$sql");		
		$rowMembers = mysql_fetch_array($resultMembers);
		if ($rowMembers['nickname'])$title = "Slideshow ".$slideshowId." created by ".$rowMembers['nickname'];
		else $title = "Slideshow ".$slideshowId." created by ".$rowMembers['userName'];	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Search - Slideshows</title>
	<!-- Mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/search.css" rel="stylesheet" type="text/css" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script type="text/javascript" src="js/slideshows.js"></script>
</head>

<body <? if ($msg) { ?> onLoad="$.prompt('<? echo $msg ?>',{top:'30%'})" <? } ?>>
	<? require_once 'heading.inc.php'; ?>
	<div id="page-wrap">              	
    	<a id="advancedSearchLink" href="advancedsearch.php" >Advanced Search</a>
                <?  if ($num_slideshows) {
						if ($title) { ?><h2 style="text-align:center"><? echo $title; ?></h2><? } 
				   		else { ?><h2 style="text-align:center">More Slideshows</h2><? } 
                    }
                    else { ?>
						<h2 style="text-align:center">No Slideshows Found</h2
					><? } 		
                    if ($num_slideshows) { ?>
                        <?
                        $i = 0;
                        do {
                            // get 1st thumbnail image
                            $fileloc = 'members/'.$row['userId'].'/'; 	
                            $imagesdir = $fileloc.'images';					
                            $imageloc = $fileloc.'images/'.$row['slideshowId'];
                            $images = get_photos($imageloc);
                            $numImages = count($images);
                            if ($numImages) {
                                $i++;
                                $path_parts = pathinfo($images[0]);
                                $thumbnail = str_replace('images','thumbnails',$path_parts['dirname']).'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            ?>	<form action="slideshow.php" method="post" enctype="application/x-www-form-urlencoded" id="viewSlideshowForm<? echo $i; ?>" name="viewSlideshowForm" target="_self">
                                    <input name="id" type="hidden" value="<? echo $row['slideshowId'] ?>" />
                                    <input name="url" type="hidden" value="search.php?<? if ($_POST['searchKeywords']) echo 'keywords='.$_POST['searchKeywords']; else echo 'id='.$row['slideshowId']; ?>" />
                                 </form>
                                 <form action="gallery.php" method="post" enctype="application/x-www-form-urlencoded" id="viewGalleryForm<? echo $i; ?>" name="viewGalleryForm" target="_self">
                                    <input name="id" type="hidden" value="<? echo $row['slideshowId'] ?>" />
                                    <input name="url" type="hidden" value="search.php?<? if ($_POST['searchKeywords']) echo 'keywords='.$_POST['searchKeywords']; else echo 'id='.$row['slideshowId']; ?>" />
                                 </form>
                                 <form action="beforeAfter.php" method="post" enctype="application/x-www-form-urlencoded" id="viewBeforeAfterForm<? echo $i; ?>" name="viewBeforeAfterForm" target="_self">
                                    <input name="id" type="hidden" value="<? echo $row['slideshowId'] ?>" />
                                    <input name="url" type="hidden" value="search.php?<? if ($_POST['searchKeywords']) echo 'keywords='.$_POST['searchKeywords']; else echo 'id='.$row['slideshowId']; ?>" />
                                 </form>
                                 <div class="searchResultBox">
                                    <div class ="imageLeft" style="background:url('<? echo $thumbnail ?>') center center no-repeat;" onClick='document.getElementById("viewBeforeAfterForm<? echo $i; ?>").submit();' title="Click to View Before & After Photos"></div>            
                                    <div class="leftDiv">
                                    	<h4 class="title"><? echo $row['title']; ?></h4>
                                    	<p><? echo $row['description']; ?></p>
                                    </div> 
                                    <div class ="imageTop" style="background:url('<? echo $thumbnail ?>') center center no-repeat;" onClick='document.getElementById("viewBeforeAfterForm<? echo $i; ?>").submit();' title="Click to View Before & After Photos"></div>           
                                    <div class="rightDiv">
                                        <div class="viewLinks">
                                            <h6 class="link viewGallery" title="View slideshow <? echo $row['slideshowId'] ?>" onclick='document.getElementById("viewGalleryForm<? echo $i; ?>").submit();' >Gallery</h6>
                                            <h6 class="link viewSlideshow" title="View slideshow <? echo $row['slideshowId'] ?>" onclick='document.getElementById("viewSlideshowForm<? echo $i; ?>").submit();' >Slideshow</h6>
                                        </div>
                                    	<div class="clearIt"></div>
                                    	<h6 class="rightText"><? echo formatDateSlashes($row['date']); ?></h6>
                                    	<h6 class="rightText"><? echo  $numImages.' photo'; if ($numImages > 1) echo 's'; ?></h6>
                                   	 	<h6 class="rightText"><? if ($row['password']) echo 'Password Required'; ?></h6> 
                                    </div>
                                </div>
                            <?	}				
                            } while ($row = mysql_fetch_array($result)); // get next user from memeber's table
                            if ($i==0){
                            ?>
                            <h3>No Slideshows found </h3>
                            <? } ?>							  
                		</div> 
                        <? } // END if slideshows exist ?> 
	</div>
</body>
</html>