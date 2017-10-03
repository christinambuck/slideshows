<!-- slideshow.php - This page opens up PrettyPhoto and displays all the photos in the slideshow. When the user closes
		PrettyPhoto window it will take the user back to the page they started on which is passed to this page in the url
        parameter. If there is no url parm then it takes the user to the search page and displays all the slideshows by the
        same member that created the slideshow they just saw.
 -->
<?
require_once 'config.inc.php';
global $config;
session_start();
/*
print_r($_POST);
exit;
*/
if ($_GET['slideshowPW']) $password = $_GET['slideshowPW'];
elseif ($_POST['slideshowPW']) $password = $_POST['slideshowPW'];

if ($_GET['id']) $slideshowId = $_GET['id'];
elseif ($_POST['id']) $slideshowId = $_POST['id'];

if ($_GET['url']) $url = $_GET['url']; 
elseif ($_POST['url']) $url = $_POST['url']; 
else $url = 'search.php?id='.$slideshowId;

if ($_POST['url2']) $url2 = $_POST['url2'];

if ($slideshowId){ 
	// connect to database
	$link = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
		}
	
	$db_selected = mysql_select_db($config['db_database'], $link);
	if (!$db_selected) {
	   die ('Can\'t use database : ' . mysql_error());
		} 
	
	$sql = "SELECT * FROM slideshows WHERE slideshowId = '$slideshowId'";
	$result = mysql_query ("$sql");	
	$num_rows = mysql_num_rows($result);
	if (!$num_rows) { 
		$msg = 'Invalid slideshow id.';
	}
	else {
		$row = mysql_fetch_array($result);
		if (!$row['password'] || $row['password'] == $password) { 
			// Update lastLogin date 	
			$today = date('Ymd');
			$sql = "UPDATE members SET lastLogin=$today WHERE userId = $row[userId]";
			$result = mysql_query("$sql");
			$fileloc = 'members/'.$row['userId'].'/'; // get the location of the member's slideshow images/descriptions		
			$imagesdir = $fileloc.'images/'.$slideshowId;
			$thumbsdir = $fileloc.'thumbnails/'.$slideshowId;	
			$descsdir = $fileloc.'descriptions/'.$slideshowId;
			
			$images = get_photos($imagesdir);	
			//print_r($images);
			//exit;			
			$numImages = count($images);

			//echo 'images dir '.$imagesdir.' num = '. $numImages	;						
			if (!$numImages) $msg = 'There are no photos in the slideshow';
			else {
				$titles = array();
				$descriptions = array();
				$names = array();
				for ($i=0; $i < $numImages; $i++) {						
						//echo $images[$i];
						$path_parts = pathinfo($images[$i]);
						$names[] = $path_parts['filename'];
						$descurl = $descsdir.'/'.$path_parts['filename'].'.txt';
						//echo 'desc url '.$descurl;
						// If the file exists then there is a description for the image. Get the contents from the text file
						if (file_exists($descurl)){
							$contents = file_get_contents($descurl); // get title and description - they are separated by ;;
							$contents = str_replace('"',"'",$contents); // replace all double quotes with single quotes because prettyPhoto will crash with double quotes
							//echo 'contents '.$contents; 
							
							$pos = strpos($contents, ';;');
							$len = $pos+2;
							if ($pos == 0) $titles[] = '';
							else $titles[] = substr($contents,0,$pos);
							if (strlen($contents) > $len)
								$descriptions[] = substr($contents,$pos+2);
							else $descriptions[] = '';
						}
						else {
							$titles[] = '';
							$descriptions[] = '';
						}		
				} // END for loop
				for ($i=0; $i<$numImages; $i++){
					$imagestring .= "{href : '".$images[$i]."', title : '";
					if ($titles[$i]) { // if there is a title
						$titles[$i] = str_replace("'","\'",$titles[$i]); // remove all embedded spaces
						$imagestring .= $titles[$i]; // include the title
						if ($descriptions[$i]) {// if there is a description
							$descriptions[$i] = str_replace("'","\'",$descriptions[$i]); // remove all embedded spaces
							$imagestring .= " - ".$descriptions[$i]; // include the description
						}
						$imagestring .= "'},"; 	
					} elseif ($descriptions[$i]) { // if there is a description but no title
						$descriptions[$i] = str_replace("'","\'",$descriptions[$i]); // remove all embedded spaces
						$imagestring .= $descriptions[$i]; // include the description
						$imagestring .= "'},"; 	
					} else $imagestring .= "'},";
				}
				$imagestring = substr($imagestring,0,-1);
				//echo $imagestring;
			} // END else there are images
		} // END if not password required
	}	// END else valid slideshowId
$ieLess10 = ieDetect($_SERVER['HTTP_USER_AGENT']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Slideshow</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" /><!-- Responsive -->
    <link href="css/newStyle.css" rel="stylesheet" type="text/css" />
	<link href="Impromptu/viva-imp.css" rel="stylesheet" type="text/css" />
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="Impromptu/jquery-impromptu.js"></script> 
	<script src="Impromptu/jquery-impromptu-ext.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/slideshows.js"></script>
    
    <!-- for fancybox -->
    <? 	if (!$ieLess10){ ?>
    <link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>
<? } else { ?>
	<script> alert('<? echo $ieLess10 ?>');</script>
    <link rel="stylesheet" href="fancybox/source/jquery.fancyboxie.css" type="text/css" media="screen" />
    <script type="text/javascript" src="fancybox/source/jquery.fancyboxie.js"></script>
<? } ?>
    
    

	<? if ($row['password']  && $row['password'] != $password){ ?>
			<script type='text/javascript'>
				var txt1 = 'Password Required<br />'
				+'<form name="slideshowPWForm" id="slideshowPWForm"> <input type="password" name="slideshowPW"/> <input type="hidden" name="id"  value="<? echo $slideshowId; ?>"/> <input type="hidden" name="url"  value="<? echo $url; ?>"/>  </form>';
				function callbackPW(v,m,f){
					if(v == 'slideshowPWForm')
					 	document.getElementById('slideshowPWForm').submit(); 
						//$.promptExt(f.slideshowPW);
				}
            </script>       
       	
	 <? } ?>
</head>

<body <? if ($row['password'] && $row['password'] != $password) { ?> onLoad="$.promptExt(txt1,{ submit: callbackPW, buttons: { 'Submit':'slideshowPWForm' } })" <? } ?> >

<form action="<? echo $url ?>" method="post" enctype="application/x-www-form-urlencoded" id="returnFromSlideshowForm" name="returnFromSlideshowForm" target="_self">
    <input id="id" name="id" type="hidden" value="<? echo $slideshowId ?>" />
    <input id="url" name="url" type="hidden" value="<? echo $url2 ?>" />
    <input id="slideshowPW" name="slideshowPW" type="hidden" value="<? echo $password ?>" />
</form>
<? 	if (!$ieLess10){ ?>
<script>
	$(document).ready(function(){		
		$.fancybox.open( [<? echo  $imagestring ?>], {
			padding    : 0,
			margin     : 0,
			nextEffect : 'fade',
			prevEffect : 'none',
			autoCenter : false,
			autoPlay   : true,
			aspectRatio: true,
			playSpeed  : 4000,
			helpers:  {
				title : {
					type : 'over'
				}
			},
			afterShow: function() {
				var imageWidth = $(".fancybox-image").width();
				$(".fancybox-title-over-wrap").css({
					"width": imageWidth,
					"paddingLeft": 0,
					"paddingRight": 0,
					"paddingTop": 0,
					"paddingBottom": 0,
					"textAlign": "center"
				});
			},
			afterLoad  : function () {
				this.title = (this.title ? '' + this.title + ' : ' : '') + (this.index + 1) + ' of ' + this.group.length;
				$.extend(this, {
					type    : 'html',
					width   : '100%',
					height  : '100%',
					content : '<div class="fancybox-image" style="background-image:url(' + this.href + '); background-size: contain; background-position:50% 50%;background-repeat:no-repeat;height:100%;width:100%;" /></div>'
				});
			},
			afterClose : function() {document.returnFromSlideshowForm.submit();}  
		});   
	});           
</script> 
<? } else { ?>
	<script>
        $(document).ready(function(){		
            $.fancybox.open( [<? echo  $imagestring ?>], {
                nextEffect : 'fade',
                prevEffect : 'none',
                autoCenter : true,
                autoPlay   : true,
                aspectRatio: true,
                playSpeed  : 4000,
                helpers:  {
                    title : {
                        type : 'over'
                    }
                },
                afterShow: function() {
                    var imageWidth = $(".fancybox-image").width();
                    $(".fancybox-title-over-wrap").css({
                        "width": imageWidth,
                        "paddingLeft": 0,
                        "paddingRight": 0,
                        "paddingTop": 0,
                        "paddingBottom": 0,
                        "textAlign": "center",
                        "fontSize": "80%"
                    });	
                },
                afterClose : function() {document.returnFromSlideshowForm.submit();}  
            });   
        });           
    </script> 
<? } ?>
</body>
</html>
<? }
else { ?> 
<meta http-equiv="Refresh" content="0; url=http://<? echo $config['http_db_server'].$config['root'].'/'.$url.'?msg=Missing Slideshow Id' ?>">	
<? 
exit;
} ?>