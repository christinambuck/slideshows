<div id="myAccountNav">    
    <a href="beforeAfter.php?id=<? echo $slideshowId ?>&url=myaccount.php" style="text-decoration:none;" ><img class="buttonImg" src="images/view.png" alt="View slideshow <? echo $slideshowId ?>" title="View slideshow <? echo $slideshowId ?>" ></a>
    <a href="<? echo 'http://'.$config['http_db_server'].$config['root'].'/editSlideshow.php?userName='.$_SESSION['userName']. '&slideshowId='.$slideshowId ?>" ><img class="buttonImg" src="images/edit.png" alt="Edit Slideshow <? echo $slideshowId ?>" title="Edit slideshow info and arrange photos" ></a>
    <a href="<? echo 'http://'.$config['http_db_server'].$config['root'].'/uploadphotos.php?userName='.$_SESSION['userName'].'&slideshowId='.$slideshowId ?>" ><img class="buttonImg" src="images/upload.png" alt="Upload Photos" title="Upload more photos to slideshow <? echo $slideshowId ?>" ></a>
    <a href="<? echo 'http://'.$config['http_db_server'].$config['root'].'/share.php?slideshowId='.$slideshowId ?>" ><img class="buttonImg" src="images/share.png" alt="Share Slideshow <? echo $slideshowId ?>" title="Share slideshow <? echo $slideshowId ?>" ></a>
    <img class="buttonImg" src="images/delete.png" alt="Delete Slideshow <? echo $slideshowId ?>" title="Delete slideshow <? echo $slideshowId ?>"  onclick='document.getElementById("deleteSlideshowForm").submit();'>
 
    <form action="deleteSlideshow.php" method="post" enctype="application/x-www-form-urlencoded" id="deleteSlideshowForm" name="deleteSlideshowForm"> 
    <input id="slideshowId" name="slideshowId" type="hidden" value="<? echo $slideshowId ?>" />             
    </form>	
</div>
<div style="clear:right;"></div>