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
		function deleteCallback(v,m,f){
			if(v == '1')
				 document.getElementById('deleteSlideshowForm').submit(); 
			else
				window.location="myaccount.php";
		}		
		function goToDeleteSlideshow(){
			$.prompt("Are you sure you want to delete this slideshow?",{
					callback: deleteCallback,
					buttons: { Yes: '1', No: '0' }
					});	
		}
    </script> 
</head>
<body onLoad="goToDeleteSlideshow();">	
	<form action="myaccount.php" method="post" enctype="application/x-www-form-urlencoded" id="deleteSlideshowForm" name="deleteSlideshowForm"> 
        <input id="deleteSlideshowId" name="deleteSlideshowId" type="hidden" value="<? echo $_POST['slideshowId'] ?>"/>             
    </form>
</body>
</html>
