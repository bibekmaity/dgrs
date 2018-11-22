// JavaScript Document
function validateEmail(txtEmail){
   var a = document.getElementById(txtEmail).value;
   var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
    if(filter.test(a)){
        return true;
    }
    else{
        return false;
    }
}

$( "#block" ).change(function() {
	  	  var ID = $(this).val();
		 // console.log(ID);
		  var request = $.ajax({
		  url: "./back/user_back.php",
		  method: "POST",
		  data: { id:ID,tag:'SHOW-BLOCK-PS' },
		  dataType: "html",
		  success:function( msg ) {
			//console.log(msg);
		  $( "#div_ps" ).html( msg);
		  
		}
	});
});

$( "#dept" ).change(function() {
	 var ID = $(this).val();
//	 alert(ID);
	 if(ID<4)
	 {
		 $("#sub_division").prop("disabled",true);
		 $("#block").prop("disabled",true);
		 $("#ps").prop("disabled",true);
	 }else{
		 $("#sub_division").prop("disabled",false );
		 $("#block").prop("disabled",false );
		 $("#ps").prop("disabled",false );
		 
	 }
		
});




$( "#edit" ).click(function() {

	var hid_token =$('#hid_token').val();
	var hid_log_user =$('#hid_log_user').val();  
	var hid_uid =$('#hid_uid').val();  
	var dept =$('#dept').val();
	var comp_type =$('#comp_type').val();
	var user_nm =$('#user_nm').val();
	var user_id =$('#user_id').val();
	var pwd =$('#pwd').val();
	var user_status =$('#user_status').val();
	var user_type =$('#user_type').val();
	var district =$('#district').val();
    var sub_division =$('#sub_division').val();
    var block =$('#block').val();
    var ps =$('#ps').val();

   if(dept=="")
   {
	  alertify.error('Please select Department');
	  $('#dept').select2('open');
	  return false;
   }
   if(comp_type=="")
   {
	  alertify.error('Please Complaint Type');
	  $('#comp_type').select2('open');
	  return false;
   }
   if(user_nm=="")
   {
	  alertify.error('Please Input User Name');
	  return false;
   }
   if(user_status=="")
   {
	  alertify.error('Please Select User Status');
	  $('#user_status').select2('open');
	  return false;
   }
   if(user_type=="")
   {
	  alertify.error('Please Select User Type');
	  $('#user_type').select2('open');
	  return false;
   }
   
   
   if(district=="")
   {
	  alertify.error('Please select a District');
	  $('#district').select2('open');
	  return false;
   }
   
   if(dept>4)
   {
	if(sub_division=="")
	{
	  alertify.error('Please select a Subdivision');
	  $('#sub_division').select2('open');
	  return false;
	}
	if(block=="")
	{
	  alertify.error('Please select a Block');
	  $('#block').select2('open');
	  return false;
	}
	if(ps=="")
	{
	  alertify.error('Please select a Police Station');
	  $('#ps').select2('open');
	  return false;
	}
	   
   }

	
   if(user_nm!="")
   {
   	
   	  if(!/^[A-Za-z0-9 ]+$/.test(user_nm))
	   {
         alertify.error("Please input valid User Name")
         $('#user_nm').focus();
	     return false;
       }
   }
  /* if(user_id!="")
   {
   	
   	  if(!/^[A-Za-z0-9]+$/.test(user_id))
	   {
         alertify.error("Please input valid User ID")
         $('#user_id').focus();
	     return false;
       }
   }
   
   
	*/
	  var request = $.ajax({
	  url: "./back/user_back.php",
	  method: "POST",
	  data: {
		  	  hid_token:hid_token,hid_log_user:hid_log_user,hid_uid:hid_uid
			 ,comp_type:comp_type,dept:dept
			 ,user_nm:user_nm,user_id:user_id,pwd:pwd,user_status:user_status
			 ,user_type:user_type,district:district,sub_division:sub_division
			 ,block:block,ps:ps
			,tag:'UPDATE-USER'},
	  dataType: "html",
	  success:function( msg) {
	  	
			   $( "#info" ).html( msg );
	}
	});
});

$( "#submit" ).click(function() {

	var hid_token =$('#hid_token').val();
	var hid_log_user =$('#hid_log_user').val();  
	var dept =$('#dept').val();
	var comp_type =$('#comp_type').val();
	var user_nm =$('#user_nm').val();
	var user_id =$('#user_id').val();
	var pwd =$('#pwd').val();
	var user_status =$('#user_status').val();
	var user_type =$('#user_type').val();
	var district =$('#district').val();
    var sub_division =$('#sub_division').val();
    var block =$('#block').val();
    var ps =$('#ps').val();
   if(dept=="")
   {
	  alertify.error('Please select Department');
	  $('#dept').select2('open');
	  return false;
   }
   if(comp_type=="")
   {
	  alertify.error('Please Complaint Type');
	  $('#comp_type').select2('open');
	  return false;
   }
   if(user_nm=="")
   {
	  alertify.error('Please Input User Name');
	  return false;
   }
   if(user_status=="")
   {
	  alertify.error('Please Select User Status');
	  $('#user_status').select2('open');
	  return false;
   }
   if(user_type=="")
   {
	  alertify.error('Please Select User Type');
	  $('#user_type').select2('open');
	  return false;
   }
   
   
   if(district=="")
   {
	  alertify.error('Please select a District');
	  $('#district').select2('open');
	  return false;
   }
   
   if(dept>4)
   {
	if(sub_division=="")
	{
	  alertify.error('Please select a Subdivision');
	  $('#sub_division').select2('open');
	  return false;
	}
	if(block=="")
	{
	  alertify.error('Please select a Block');
	  $('#block').select2('open');
	  return false;
	}
	if(ps=="")
	{
	  alertify.error('Please select a Police Station');
	  $('#ps').select2('open');
	  return false;
	}
	   
   }

	
   if(user_nm!="")
   {
   	
   	  if(!/^[A-Za-z0-9 ]+$/.test(user_nm))
	   {
         alertify.error("Please input valid User Name")
         $('#user_nm').focus();
	     return false;
       }
   }
  /* if(user_id!="")
   {
   	
   	  if(!/^[A-Za-z0-9]+$/.test(user_id))
	   {
         alertify.error("Please input valid User ID")
         $('#user_id').focus();
	     return false;
       }
   }
   
   
	*/
	  var request = $.ajax({
	  url: "./back/user_back.php",
	  method: "POST",
	  data: {
		  	  hid_token:hid_token,hid_log_user:hid_log_user
			 ,comp_type:comp_type,dept:dept
			 ,user_nm:user_nm,user_id:user_id,pwd:pwd,user_status:user_status
			 ,user_type:user_type,district:district,sub_division:sub_division
			 ,block:block,ps:ps
			,tag:'INSERT-USER'},
	  dataType: "html",
	  success:function( msg) {
			   $( "#info" ).html( msg );
	}
	});
}); 
 

$(document).ready(function(){
$("#user_id").change(function() { 
var usr = $("#user_id").val();
if(usr.length >= 4)
{
    $.ajax({  
    type: "POST",  
    url: "./back/check.php",  
    data: "u_id="+ usr,  
    success: function(msg)
	{ 
	var str=msg.trim();
	if(str>'0')
	{
		alertify.error("The user ID is not available");
		$('#user_id').focus();
	}
	else
	{
		alertify.success("The user ID is available");
	}
    } 
  }); 

}
else
	{
	alertify.error("The username should have at least <strong>4</strong>");
	$('#user_id').focus();
	$("#user_id").removeClass('object_ok'); // if necessary
	$("#user_id").addClass("object_error");
	}

});

});


function readURL(input) 
{
	
	
	if (input.files && input.files[0]) 
	{
		var reader = new FileReader();
		reader.onload = function (e)
		{
			$('#falseinput').attr('src', e.target.result);
			
			$('#base').val(e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}

$(document).ajaxStart(function ()
{
	$('body').addClass('wait');
	 
}).ajaxComplete(function () {

	$('body').removeClass('wait');

});

 

function RemScript(data)
{
	
	var content = $(data);
	content.find('script').remove();
	if(typeof content.html()==="undefined")
	return data;
	else
    return content.html();

}

$( "#profile" ).click(function() {
       var hid_uid =$('#hid_uid').val();  
	   var user_nm =$('#user_nm').val();
	   var user_id =$('#user_id').val();
	   var password =$('#password').val();
	   var addr =$('#addr').val();
	   var contact_no =$('#contact_no').val();
	   var email =$('#email').val();
	   var base =$('#base').val();
	   var fileinput =$('#fileinput').val();
       var filearr=fileinput.split('.');
       var csrftoken =$('#csrftoken').val();
       var hid_id =$('#hid_id').val();
	   if(user_nm=="")
	   {
		  alertify.error('Please input a Name');
		  $('#user_nm').focus();
		  return false;
	   }
	    if(!/^[0-9a-zA-Z]+$/.test(user_nm))
		{
		 alertify.error('Invalid Name');
		  $('#user_nm').focus();
		  return false;	
		}
	   if(user_id=="")
	   {
		  alertify.error('Please input a User ID');
		  $('#user_id').focus();
		  return false;
	   }
	  
	   if(contact_no!="")
	   {
	   	  if(contact_no.length!="10")
		  {
			  alertify.error('Please input a Valid Contact No');
			  $('#contact_no').focus();
			  return false;
		  }
		  if(!/^[0-9]+$/.test(contact_no))
		   {
	         alertify.error('Please input a Valid Contact No');
	         $('#contact_no').focus();
		     return false;
	       }
	   }
	   
	   if(addr!="")
	   {
	   	   if(!/^[A-Za-z0-9_@./#&+-,()']*$/.test(addr))
		   {
	         alertify.error("Please input valid Address")
	         $('#addr').focus();
		     return false;
	       }
	   }
	   
	   if(email=="")
	   {
		  alertify.error('Please input a Email ID');
		  $('#email').focus();
		  return false;
	   }
	   if(password=="")
	   {
		  alertify.error('Please input Password');
		  $('#password').focus();
		  return false;  
	   }
	  if ($('#email').val() != "") {
		 if(!validateEmail('email')){
		  alertify.error(" Invalid Email ID");
			$('#email').css("border-color","#FF0000");
			$('#email').focus();
			return false;								  
		}
	  }
	   if(filearr.length>2)
	   {
			alert('Double extension files are not allowed.'); 
			$('#fileinput').focus();
			return false;    
	   }
	   if(fileinput!="")
	   {

		   	var extension = fileinput.substr(fileinput.lastIndexOf('.') + 1).toLowerCase(); 
		   	var allowedExtensions = ['jpg', 'jpeg', 'png'];
		   	if (fileinput.length > 0) 
		   	{ 
		   		if (allowedExtensions.indexOf(extension) === -1) 
		   		{ 
		   			alert('Invalid file Format. Only ' + allowedExtensions.join(', ') + ' are allowed.'); 
		   			$('#fileinput').focus();
		   			return false; 
		   		} 
		   	}
	   }
	  
	  var request = $.ajax({
	  url: "./back/register.php",
	  method: "POST",
	  data: {
		  user_nm:user_nm,user_id:user_id, password:password,
		  contact_no:contact_no,email:email,hid_uid:hid_uid
		  ,addr:addr,base:base,csrftoken:csrftoken,hid_id:hid_id
		  ,tag:'REGISTER-PROFILE'},
	  dataType: "html",
	  success:function( msg) {
			   $( "#info" ).html( msg );
	}
	});
}); 
