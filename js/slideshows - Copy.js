
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
function goToPhotoUploader(server,userName,slideshowId,num)
{	
	parm = server+'/uploadphotos.php?userName='+userName+'&slideshowId='+slideshowId+'&num='+num+'&iframe=true&width=660&height=550';
	$.prettyPhoto.open(parm,'','Upload Photos');
}
function goToPhotoManager(server,userName,slideshowId)
{
	$.prettyPhoto.open(server+'/managephotos.php?userName='+userName+'&slideshowId='+slideshowId+'&iframe=true&width=700&height=600','','Manage Photos');
}
function goToEditSlideshow(server,userName,slideshowId)
{
	parm = server+'/editSlideshow.php?userName='+userName+'&slideshowId='+slideshowId+'&iframe=true&width=700&height=600';
	$.prettyPhoto.open(parm,'','Edit Slideshow');
}

function goToViewSlideshow(key)
{		
	document.getElementById('viewSlideshowForm'+key).submit();
}


function goToShareSlideshow(server,slideshowId)
{
	parm = server+'/slideshowURL.php?slideshowId='+slideshowId+'&iframe=true&width=700&height=150';
	$.prettyPhoto.open(parm,'','Share Slideshow');
}
// Used on My Account page to delete a slideshow
function goToDeleteSlideshow(slideshowId){
	document.getElementById('deleteSlideshowId').value = slideshowId;
	document.getElementById('deleteSlideshowForm').submit();
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
	$("#search").click(function(){		
			 $("#searchForm").submit();	
		});	
	$("#searchFld").click(function(){	
			document.getElementById("searchFld").value = "";
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
	$("#login").click(function(){	
		validateLoginFields();
		});
	$("#logout").click(function(){		
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