// Displays the navigaion in a drop down menu on small screens. Called from heading.inc.php
var menuNavShowing = 0;
function displayMenu(){		
	if (menuNavShowing){ // show normal menu bar
		menuNavShowing = 0;	
		document.getElementById("heading").style.height = 70 + "px";	
		document.getElementById("menuNavigation").style.display = "none";	
		}
	else { // show dropdown menu
		menuNavShowing = 1;	
		document.getElementById("heading").style.height = 180 + "px";			
		document.getElementById("menuNavigation").style.display = "block";	
		}	
}


// Called from login.php
function validateLoginFields(form) {
	if (document.getElementById("userName").value <= ' '){
			alert ('The username is required');
			loginForm.userName.focus();
			return false; 
			}
	if (document.getElementById("pw").value <= ' '){
			alert ('The password is required');
			loginForm.password.focus();
			return false; 
			} 
	document.loginForm.submit();	
}


// Called when browser is resized
function updateSize (id) {
	 var p = document.getElementById(id);
	 if (p) {
		if(document.defaultView && document.defaultView.getComputedStyle) {       
			var s = document.defaultView.getComputedStyle(p, '');        
			size = s.getPropertyValue('z-index');     
		} else if (p.currentStyle) {        
			size = p.currentStyle['zIndex'];   
		}
		if (id == 'bgOne') {
			if (size == 960)
				$.backstretch("images/TuckerBG_80_1024x768.jpg");
			else if (size > 639)				
				$.backstretch("images/TuckerBG_85_720x540.jpg");
			else if (size > 426 )				
				$.backstretch("images/BG_Camera_480x640.jpg");
			else if (size > 320)				
				$.backstretch("images/BG_Camera_320x480.jpg");
			else	
				$.backstretch("images/BG.jpg");
		}
		else if (id == 'bgTwo') {
			if (size > 959)
				$.backstretch("images/BG_Camera_1024x768.jpg");
			if (size > 719)
				$.backstretch("images/BG_Camera_1024x768.jpg");
			else if (size >= 480)				
				$.backstretch("images/BG_Leaves_480x640.jpg");
			else if (size < 480)				
				$.backstretch("images/BG.jpg");
		}
		else if (id == 'bgThree') {		
				$.backstretch("images/BG.jpg");
		}
	 }
}


// Used on all pages that need the user to be logged in first
function loginCallback(v,m,f){
	if(v == '1'){
		 document.getElementById('deleteSlideshowForm').submit(); 
	}
}		
function goToLogin(slideshowId){
	document.getElementById('deleteSlideshowId').value = slideshowId;
	$.prompt("Are you sure you want to delete this slideshow?",{
			callback: deleteCallback,
			buttons: { Yes: '1', No: '0' }
			});	
}

$(document).ready(function(){
	updateSize('bgOne'); 		// get screen size when page first loads to display correct background image
	$(window).resize(function() {	// then get screen size to display correct background image whenever browser is resized
		updateSize('bgOne');
	});
	updateSize('bgTwo'); 		// get screen size when page first loads to display correct background image
	$(window).resize(function() {	// then get screen size to display correct background image whenever browser is resized
		updateSize('bgTwo');
	});
	updateSize('bgThree'); 		// get screen size when page first loads to display correct background image
	$(window).resize(function() {	// then get screen size to display correct background image whenever browser is resized
		updateSize('bgThree');
	});
	$('#moreInfo').click(function(){
  		window.location = 'moreInfo.php';
		}); 
	
	// used on header.inc.php for search link	
	$(".searchLink").hover(
		function(){
			$(this).addClass('searchLinkHover');
			},
		function(){
			$(this).removeClass('searchLinkHover');
			}
		);
			
	// for the search bar in the header		
	$("#searchButton").click(function(){
			 $("#searchForm").submit();	
		});	
	$("#searchKeywords").click(function(){	
			document.getElementById("searchKeywords").value = "";
		});	
		
	// used on newaccount.php		
	$("#registerButton").click(function(){		
		if (document.getElementById("username").value <= ' '){
			alert ('User Name is required');
			newAccountForm.userName.focus();
			return false; 
			} 
		if (document.getElementById("email").value <= ' '){
			alert ('Email is required');
			newAccountForm.email.focus();
			return false; 
			} 
		if (document.getElementById("userpw").value <= ' '){
			alert ('Password is required');
			newAccountForm.userpw.focus();
			return false; 
			} 		
		if (document.getElementById("userpw").value != document.getElementById("rePassword").value)
		{
			alert ('Passwords do not match.');
			newAccountForm.userpw.focus();
			return false; 
		}
		else		
			$("#newAccountForm").submit();		
		});	
			
	// Used for login and logout in the header	
	$("#loginButton").click(function(){	
		validateLoginFields();
		});
	$("#logoutButton").click(function(){		
		$("#logoutForm").submit();	
		});	
	
	$(".customButton").hover(
		function(){
			$(this).addClass('customButtonHover');
			},
		function(){
			$(this).removeClass('customButtonHover');
			}
		);
	
	//Used on forgot page
	$("#forgotButton").click(function(){
		if (document.forgotForm.userName.value <= ' ' && document.forgotForm.email.value <= ' '){
			alert ('Either the user name or email is required');
			forgotForm.userName.focus();
			return false; 
			} 
		else		
			$("#forgotForm").submit();	
		});	
			
	// used on viewBeforeAfter.php			
	$(".whiteLink").hover(
		function(){
			$(this).addClass('whiteLinkHover');
			},
		function(){
			$(this).removeClass('whiteLinkHover');
			}
		);	
			
	$(".darkLink").hover(
		function(){
			$(this).addClass('darkLinkHover');
			},
		function(){
			$(this).removeClass('darkLinkHover');
			}
		);	
		
	// used on search.php and possibly other pages (generic link looks like <a href>	
	$(".link").hover(
		function(){
			$(this).addClass('linkHover');
			},
		function(){
			$(this).removeClass('linkHover');
			}
		);	
		
	// Used on advanced search page	
	$("#advancedSearchButton").click(function(){
		$("#advancedSearchForm").submit();	
		});	

	

}); // END Document Ready

//___________________________________________________________________

// Used on Album Creation pages
function checkRadio (frmName, rbGroupName) {
 var radios = document[frmName].elements[rbGroupName];
 for (var i=0; i <radios.length; i++) {
  if (radios[i].checked) {
   return true;
  }
 }
 return false;
} 



function validateAlbumFields(form){
	if (document.ca2Form.title.value <= ' '){
			alert ('The title is required');
			ca2Form.title.focus();
			return false; 
			} 
	if (!checkRadio("ca2Form","category")){
			alert ('The category is required');
			ca2Form.title.focus();
			return false; 
			}	 
	// get selected id
	var e = document.getElementById("country");
	var id = e.options[e.selectedIndex].value;
	if (id <= '0'){		
			alert ('Country is required');
			ca2Form.country.focus();
			return false; 
			} 
	else {
		document.getElementById("countryName").value = e.options[e.selectedIndex].text;
	}
	if (document.ca2Form.city.value <= ' '){
			alert ('City is required');
			ca2Form.city.focus();
			return false; 
			}  	 
	document.ca2Form.submit();	 			
}
// Used on createSlideshowTwo.php 
function goToPhotoUploader(server,userName,slideshowId,num)
{	
	$(document).ready(function(){
		$("area[rel^='prettyPhoto']").prettyPhoto(
			{
				  theme: 'facebook', //'light_square', 'pp_default', 'light_rounded', 'facebook',
				  callback: function() { 
						var url = server+'/createSlideshowTwo.php?id='+slideshowId;
						window.location.href = url;
						}
			}
		);
	});
	parm = server+'/uploadphotos.php?userName='+userName+'&slideshowId='+slideshowId+'&num='+num+'&iframe=true&width=660&height=550';
	$.prettyPhoto.open(parm,'','Upload Photos');
}
function goToPhotoManager(server,userName,slideshowId)
{
	$(document).ready(function(){
		$("area[rel^='prettyPhoto']").prettyPhoto(
			{
				  theme: 'facebook', //'light_square', 'pp_default', 'light_rounded', 'facebook',
				  callback: function() { 
						var url = server+'/createSlideshowTwo.php?id='+slideshowId;
						window.location.href = url;
						}
			}
		);
	});
	$.prettyPhoto.open(server+'/managephotos.php?userName='+userName+'&slideshowId='+slideshowId+'&iframe=true&width=700&height=600','','Manage Photos');
}

// Used on myaccount.php
function goToEditSlideshow(server,userName,slideshowId)
{
	alert ('here');
	parm = server+'/editSlideshow.php?userName='+userName+'&slideshowId='+slideshowId;
	alert (parm);
	$.fancybox({
		href: parm,
		title : 'Edit Slideshow'
	});

}
function goToEditPhotoUploader(server,userName,slideshowId,num)
{
	$(document).ready(function(){
		$("area[rel^='prettyPhoto']").prettyPhoto(
			{
				  theme: 'facebook', //'light_square', 'pp_default', 'light_rounded', 'facebook',
				  callback: function() {location.reload();}
			}
		);
	});	
	parm = server+'/uploadphotos.php?userName='+userName+'&slideshowId='+slideshowId+'&num='+num+'&iframe=true&width=660&height=550';
	$.prettyPhoto.open(parm,'','Upload Photos');
}
function goToGallery(server,userName,slideshowId)
{
	$(document).ready(function(){
		$("area[rel^='prettyPhoto']").prettyPhoto(
			{
				  theme: 'facebook', //'light_square', 'pp_default', 'light_rounded', 'facebook',
				  callback: function() { 
						var url = server+'/myaccount.php';
						window.location.href = url;
						}
			}
		);
	});
	$.prettyPhoto.open(server+'/gallery.php?id='+slideshowId+'&iframe=true&width=700&height=600','','Gallery');
}





// Used on Manage Photos page
function deleteImage(key){
	formid = 'form'+key;
	mpdeleteid = 'mpdelete'+key;
	imageOrderNew = 'imageOrderNew'+key;
	document.getElementById(mpdeleteid).value = '1';
	document.getElementById(imageOrderNew).value = document.getElementById('imageOrder').value;
	document.getElementById(formid).submit();
}

function updateImage(key){
	formid = 'form'+key;
	mpupdateid = 'mpupdate'+key;
	imageOrderNew = 'imageOrderNew'+key;
	document.getElementById(mpupdateid).value = '1';
	//document.getElementById(imageOrderNew).value =  document.mpform.imageOrder.value;
	document.getElementById(imageOrderNew).value = document.getElementById('imageOrder').value;
	document.getElementById(formid).submit();
}

	// Use on Manage Phots page using AJAX calls	
	
	var cursorTop = '';
	
	function deleteImageAjax(key){		
		slideshowId = document.getElementById('slideshowId').value;
		imageOrderNew = document.getElementById('imageOrder').value;
		if (!imageOrderNew) imageOrderNew = 0;
		descurlKey = 'descurl'+key
		descurl = document.getElementById(descurlKey).value;
		imageurlKey = 'imageurl'+key
		imageurl = document.getElementById(imageurlKey).value;
		thumburlKey = 'thumburl'+key
		thumburl = document.getElementById(thumburlKey).value;
		$.ajax({
            url: "updatephotos.php",
            type: "POST",
            data: { 'imageOrderNew': imageOrderNew, 'slideshowId': slideshowId, 'mpdelete': '1', 'descurl': descurl, 'imageurl': imageurl, 'thumburl': thumburl },                   
            success: function(data){   
   				$('#mpdiv'+key).hide(); // if successful hide popup                                   
   				$('#'+key).remove(); // if successful remove image                                
			}
        });	
	}
	
	function updateImageAjax(key){		
					
		slideshowId = document.getElementById('slideshowId').value;
		imageOrderNew = document.getElementById('imageOrder').value;
		if (!imageOrderNew) imageOrderNew = 0;
		descurlKey = 'descurl'+key
		descurl = document.getElementById(descurlKey).value;
		imagetitleKey = 'imagetitle'+key
		imagetitle = document.getElementById(imagetitleKey).value;
		descKey = 'desc'+key
		desc = document.getElementById(descKey).value;
		$.ajax({
            url: "updatephotos.php",
            type: "POST",
            data: { 'imageOrderNew': imageOrderNew, 'slideshowId': slideshowId, 'mpupdate': '1', 'descurl': descurl, 'imagetitle': imagetitle, 'desc': desc},                   
            success: function(data){
				//alert('Info has been saved.'); // this will print you any php / mysql error as an alert    
   				$('#mpdiv'+key).hide();				
				document.location.reload();  // had to do this otherwise the info div box would display and disappear quickly. Could not figure out why and tried many things so resorted to this                      
			}
        });	
	}
	
	function updateOrderAjax(){
		imageOrder = document.mpform.imageOrder.value;
		slideshowId = document.mpform.slideshowId.value;
		$.ajax({
            url: "updatephotos.php",
            type: "POST", 
            data: { 'imageOrder': imageOrder, 'slideshowId': slideshowId }, 
			success: function(data){
   				alert('Order has been saved.');                              
			},                 
            error: function(data){
   				alert('Problem saving order.');                               
			}
        });	
	}
	
	/* 
	*	will save the image order in ordered.txt in updatephotos.php. 
	*	This is called after the user drags an image to a new spot to 
	*	change the order of the photos in the slideshow
	*/
	function saveImageOrder(){
		imageOrder = document.mpform.imageOrder.value;
		slideshowId = document.mpform.slideshowId.value;
		$.ajax({
            url: "updatephotos.php",
            type: "POST", 
            data: { 'saveImageOrder': imageOrder, 'slideshowId': slideshowId }, 			               
            error: function(data){
   				alert('Problem saving order.');                               
			}
        });	
	}
	
	function mouseDown() {
		if(window.pageYOffset != undefined)
		{
			cursorTop = pageYOffset;
		}
		else if (document.body.scrollTop != undefined) 
		{
			cursorTop =  document.body.scrollTop;
		}
		else if (document.documentElement.scrollTop != undefined) 
		{
			cursorTop =  document.documentElement.scrollTop;					
		}
		
	}
		
function changeFolder() {
	document.getElementById("mpcurrent").value = 'mpdiv';
	document.getElementById('mpform').submit();	
}
function imageInfo(key) {
	// hide current div	
	current = document.getElementById("mpcurrent").value;
	document.getElementById(current).style.display = "none";
	// display clicked on div
	name = 'mpdiv'+key;
	document.getElementById(name).style.display = "";	
	// save new div in both current fields in the top and bottom divs
	mpcurrentid = 'mpcurrent'+key;	
	document.getElementById(mpcurrentid).value = name;	
	document.getElementById("mpcurrent").value = name;
}

$(document).ready(function(){							   
	
	$(".custombutton").hover(
		function(){
			$(this).addClass('custombuttonhover');
			},
		function(){
			$(this).removeClass('custombuttonhover');
			}
		);
	$(".custombuttonYellow").hover(
		function(){
			$(this).addClass('custombuttonYellowhover');
			},
		function(){
			$(this).removeClass('custombuttonYellowhover');
			}
		);
	$(".pointer").hover(
		function(){
			$(this).addClass('pointer');
			},
		function(){
			$(this).removeClass('pointer');
			}
		);
	
	
	$('#myAccountButton').click(function(){
  		window.location = 'myaccount.php';
		}); 
		
// Used on home page
		
	$('#createButton').click(function(){
  		window.location = 'createSlideshow.php';
		}); 		
	$('#signUpButton').click(function(){
  		window.location = 'newaccount.php';
		}); 
		
// Used on create slideshow page
	$("#createSlideshowButton").click(function(){
		if (document.createForm.title.value <= ' '){
			alert ('The title is required');
			createForm.title.focus();
			return false; 
			} 
		else		
			$("#createForm").submit();	
		});
/*		
	$("#private").click(function(){
		if ($("#private").is(":checked")){
			$("#keywordsDiv").hide();
			$("#keywordsDiv2").hide();
			document.getElementById("keywords").value = "";
			}
		else {
			document.getElementById("slideshowPW").value = "";	
			$("#keywordsDiv").show();	
			$("#keywordsDiv2").show();
			}	
		});
		
		$("#slideshowPW").focusout(function(){
		 if (document.getElementById("slideshowPW").value > ' '){ // if a password, then check private and hide keywords
			$('#private').prop('checked', true);
			$("#keywordsDiv").hide(); 
			$("#keywordsDiv2").hide(); 
			document.getElementById("keywords").value = "";
		 }
		});
*/

	$("#private").click(function(){
		if ($("#private").is(":checked")){
			$("#keywordsDiv").hide();
			$("#keywordsDiv2").hide();
			document.getElementById("keywords").value = "";
			}
		else {
			//document.getElementById("slideshowPW").value = "";	
			$("#keywordsDiv").show();	
			$("#keywordsDiv2").show();
			}	
		});
		
		//$("#slideshowPW").focusout(function(){
		// if (document.getElementById("slideshowPW").value > ' '){ // if a password, then check private and hide keywords
			//$('#private').prop('checked', true);
			//$("#keywordsDiv").hide(); 
			//$("#keywordsDiv2").hide(); 
			//document.getElementById("keywords").value = "";
		// }
		//});
		
	

//Used on edit slideshow page

	$("#continueEditButton").click(function(){
		if (document.editSlideshowForm.title.value <= ' '){
			alert ('The title is required');
			editSlideshowForm.title.focus();
			return false; 
			} 
		else {
			$("#editSlideshowForm").submit();	
		}
		});
	
	$("#updateProfile").click(function(){		
		if (document.getElementById("userName").value <= ' '){
			alert ('User Name is required');
			profileForm.userName.focus();
			return false; 
			} 
		if (document.getElementById("email").value <= ' '){
			alert ('Email is required');
			profileForm.email.focus();
			return false; 
			} 
		if (document.getElementById("userpw").value <= ' '){
			alert ('Password is required');
			profileForm.userpw.focus();
			return false; 
			} 		
		if (document.getElementById("userpw").value != document.getElementById("rePassword").value)
		{
			alert ('Passwords do not match.');
			profileForm.userpw.focus();
			return false; 
		}
		else		
			$("#profileForm").submit();	
		});			
					
// used on upload photos
	$("#continueUploadButton").click(function(){
			$("#uploadphotos2").submit();	
		});
		
	}); // END Document Ready
	
