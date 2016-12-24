$(document).ready(function (e) {	
	$("#user_registration").on('submit',(function(e) {
		e.preventDefault();
		var namePattern		=	new RegExp('^[a-zA-Z0-9\-]{5,15}$'); 				//	regular expression pattern is defined to validate name input
		var emailPattern	=	/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._]+\.[a-zA-Z]{2,4}$/;	//	regular expression pattern is defined to validate email input
		//var phonePattern	=	/^(\+91-|\+91|0)?\d{10}$/;							//	regular expression pattern is defined to validate phone input		
		var picAllowedExt	=	['jpeg', 'jpg', 'png', 'gif', 'bmp'];
		// Retrieving inputs from form
		var nameInputValue		=	$.trim($('#first_name').val());
		var genderInputValue	=	$.trim($('input[name="gender"] :checked').val());
		var emailInputValue		=	$.trim($('#email').val());
		var countryInputValue	=	$.trim($('input[name="country"] :selected').val());
		var inputErrorFlag		=	0;
		var commonMessage		=	'';
		// end
		
		//checks if fields are blank or not
		if(nameInputValue==''){
			commonMessage 	= 	'<li>Please enter first name</li>';
			$('#first_name').parent('div').addClass('has-error');
			$('#first_name').siblings("span:eq(1)").html('');
			inputErrorFlag++;
		}
		else{
				/*for name*/
				if(!namePattern.test(nameInputValue)){
					$('#first_name').parent('div').removeClass('has-success');
					$('#first_name').parent('div').addClass('has-error');
					$('#first_name').siblings("span:eq(1)").html('Name should be alpha-numeric, minimum of 5 characters and maximum of 15 characters in length');
					inputErrorFlag++;
				}
				else{
					$('#first_name').parent('div').removeClass('has-error');
					$('#first_name').siblings("span:eq(1)").html('');
					$('#first_name').parent('div').addClass('has-success');
				}
		}
		if(emailInputValue==''){
			commonMessage += '<li>Please enter email</li>';
			$('#email').parent('div').addClass('has-error');
			$('#email').siblings("span:eq(1)").html('');
			inputErrorFlag++;
		}
		else{
			   /*for email*/
				if(!emailPattern.test(emailInputValue)){
					$('#email').parent('div').removeClass('has-success');
					$('#email').parent('div').addClass('has-error');
					$('#email').siblings("span:eq(1)").html('Email should be a valid email address');
					inputErrorFlag++;		
				}
				else{
					$('#email').parent('div').removeClass('has-error');
					$('#email').siblings("span:eq(1)").html('');
					$('#email').parent('div').addClass('has-success');
				}	
		}
		var applyRuleToPic	=	'Yes';
		
		if($.trim($('#request').val())=='edit'){
				if($.trim($('#imgExists').val()))	applyRuleToPic = 'No';
		}
		//chceks if request mode is edit and image is not available then rule will get applied else not
		if(applyRuleToPic == 'Yes'){
										if(picInputValue==''){
												commonMessage += '<li>Please choose profile picture</li>';
												$('#profilepic').parent('div').addClass('has-error');
												$('#profilepic').siblings("span:eq(1)").html('');
												inputErrorFlag++;
										}
										else{ 
												/*for profile picture*/			
												var fileName = picInputValue.toString();
												var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
												if($.inArray(fileNameExt,picAllowedExt)== -1){	
													$('#profilepic').parent('div').removeClass('has-success');				
													$('#profilepic').parent('div').addClass('has-error');
													$('#profilepic').siblings("span:eq(1)").html('Formats allowed for profile picture are - jpeg, jpg, png, gif, bmp ');
													inputErrorFlag++;
												}
												else{
													$('#profilepic').parent('div').removeClass('has-error');
													$('#profilepic').siblings("span:eq(1)").html('');
													$('#profilepic').parent('div').addClass('has-success');
												}
										}
		}
		
		
		// checks if fields are not blank then input must meet the correct pattern
		if(inputErrorFlag==0){
			       return true;
			}
		else{
				if(commonMessage!=''){
					$('#commonMessageDiv').addClass('alert alert-danger');
					$('#commonMessageDiv').html('<ul>'+commonMessage+'</ul>');	
				}
			return false;	
		}
	}
));

$('#EditBtn').bind('click',function(){
	     window.location.href = $.trim($('#baseURL').val())+'user/edit/'+$.trim($('#userID').val());
	});

});