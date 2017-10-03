
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
	parm = server+'/editSlideshow.php?userName='+userName+'&slideshowId='+slideshowId+'&iframe=true&width=700&height=600';
	$.prettyPhoto.open(parm,'','Edit Slideshow');
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
function goToViewGallery(key)
{		
	document.getElementById('viewGalleryForm'+key).submit();
}

function goToViewSlideshow(key)
{		
	document.getElementById('viewSlideshowForm'+key).submit();
}


function goToShareSlideshow(server,slideshowId)
{
	parm = server+'/share.php?slideshowId='+slideshowId+'&iframe=true&width=700&height=150';
	$.prettyPhoto.open(parm,'','Share Slideshow');
}

// Called from viewBeforeAfter.php and the form is defined in beforeAfter.php
function goToViewSlideshowFromBeforeAfter(id,url,pw)
{	
	parent.document.getElementById('whatToView').value = 'beforeAfterSlideshowForm';
	parent.document.getElementById('sid').value = id;
	parent.document.getElementById('surl').value = url;
	parent.document.getElementById('sslideshowPW').value = pw;
	parent.eval('$.prettyPhoto.close()');		
}
function goToViewGalleryFromBeforeAfter(id,url,pw)
{	
	parent.document.getElementById('whatToView').value = 'beforeAfterGalleryForm';
	parent.document.getElementById('gid').value = id;
	parent.document.getElementById('gurl').value = url;
	parent.document.getElementById('gslideshowPW').value = pw;
	parent.eval('$.prettyPhoto.close()');		
}

// Used on My Account page to delete a slideshow
function deleteCallback(v,m,f){
	if(v == '1'){
		 document.getElementById('deleteSlideshowForm').submit(); 
	}
}		
function goToDeleteSlideshow(slideshowId){
	document.getElementById('deleteSlideshowId').value = slideshowId;
	$.prompt("Are you sure you want to delete this slideshow?",{
			callback: deleteCallback,
			buttons: { Yes: '1', No: '0' }
			});	
}
// Used on Manage Photos page
function deleteImage(key){
	formid = 'form'+key;
	mpdeleteid = 'mpdelete'+key;
	document.getElementById(mpdeleteid).value = '1';
	document.getElementById(formid).submit();
}

function updateImage(key){
	formid = 'form'+key;
	mpupdateid = 'mpupdate'+key;
	document.getElementById(mpupdateid).value = '1';
	document.getElementById(formid).submit();
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
	$(".buttonLight").hover(
		function(){
			$(this).addClass('buttonLightHover');
			},
		function(){
			$(this).removeClass('buttonLightHover');
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
// for the search bar in the header		
	$("#searchButton").click(function(){		
			 $("#searchForm").submit();	
		});	
	$("#searchKeywords").click(function(){	
			document.getElementById("searchKeywords").value = "";
		});	
		
// Cancel membership from the my accounts page	
 	function cancelCallback(v,m,f){
		if(v == '1'){
			 $("#cancelMembershipForm").submit(); 
		}
	}					
	$("#cancelMembership").click(function(){
		$.prompt("Are you sure you want to cancel your membership?",{
			callback: cancelCallback,
			buttons: { Yes: '1', No: '0' }
			});	
		});		
	
// Used for login and logout in the header	
	$("#loginButton").click(function(){	
		validateLoginFields();
		});
	$("#logoutButton").click(function(){		
		$("#logoutForm").submit();	
		});	
	$('#homeButton').click(function(){
  		window.location = 'index.php';
		}); 	
	$('#newAccountButton').click(function(){
  		window.location = 'newaccount.php';
		}); 	
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

//Used on edit slideshow page

	$("#continueEditButton").click(function(){
		if (document.editSlideshowForm.title.value <= ' '){
			alert ('The title is required');
			editSlideshowForm.title.focus();
			return false; 
			} 
		else		
			$("#editSlideshowForm").submit();	
		});		
	
// Used on the manage photos page
	var imgOrder = '';

	$(function() {
	  $("#sortable").sortable({
		update: function(event, ui) {
		  imgOrder = $("#sortable").sortable('toArray').toString();
	   	  document.getElementById("imageOrder").value = imgOrder;
		}
	  });
	  $("#sortable").disableSelection();  
	});
	

	$("#updateOrder").click(function(){		
		$("#mpform").submit();	
		});
		
// Used on search page		
	$("#advancedSearchLink").click(function(){		
		window.location = 'advancedsearch.php';	
		});	
			
// Used on advanced search page	
	$("#advancedSearchUserButton").click(function(){	
		$("#advancedSearchUserForm").submit();	
		});	
	$("#advancedSearchIdButton").click(function(){	
		$("#advancedSearchIdForm").submit();	
		});	
	$("#advancedSearchKeyButton").click(function(){	
		$("#advancedSearchKeyForm").submit();	
		});	
					
// Used on New Account page
	$("#register").click(function(){		
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
					
		
	}); // END Document Ready