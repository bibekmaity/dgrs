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

$( "#user_type" ).change(function() {
	  	  var ID = $(this).val();
		  var request = $.ajax({
		  url: "./back/register.php",
		  method: "POST",
		  data: { id:ID,tag:'DTLS' },
		  dataType: "html",
		  success:function( msg ) {
		  $( "#div_dtls" ).html( msg);
		  
		}
	});
});
$( "#submit" ).click(function() {

	var password =$('#password').val();
	var hid_uid =$('#hid_uid').val();  
	var user_type =$('#user_type').val();
	var district =$('#district').val();
	var user_nm =$('#user_nm').val();
	var user_id =$('#user_id').val();
	var addr =$('#addr').val();
	var contact_no =$('#contact_no').val();
	var email =$('#email').val();
	var base =$('#base').val();
   var fileinput =$('#fileinput').val();
   var filearr=fileinput.split('.');

   if(user_type=="")
   {
	  alertify.error('Please select a User Level');
	  $('#user_type').select2('open');
	  return false;
   }
   if(district=="")
   {
	  alertify.error('Please select a District');
	  $('#district').select2('open');
	  return false;
   }
   if(user_type!="D")
   {
	 	var block =$('#block').val();

	   if(block=="")
	   {
		  alertify.error('Please select a Block');
		  $('#block').select2('open');
		  return false;
	   }
	    if(user_type=="B")
       {
		   var office =$('#office').val();

		   if(office=="")
		   {
			  alertify.error('Please select a Office');
			  $('#office').select2('open');
			  return false;
		   }
	   }
	    if(user_type=="L")
       {
		  var lessee =$('#lessee').val();

		   if(lessee=="")
		   {
			  alertify.error('Please select a Lessee');
			  $('#lessee').select2('open');
			  return false;
		   }
	   }
	}
   if(user_nm=="")
   {
	  alertify.error('Please input a User Name');
	  $('#user_nm').focus();
	  return false;
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
   if(user_id=="")
   {
	  alertify.error('Please input a User ID');
	  $('#user_id').focus();
	  return false;
   }
   if(user_id!="")
   {
   	
   	  if(!/^[A-Za-z0-9]+$/.test(user_id))
	   {
         alertify.error("Please input valid User ID")
         $('#user_id').focus();
	     return false;
       }
   }
	  
   if(password=="")
   {
	  alertify.error('Please input a Password');
	  $('#password').focus();
	  return false;
   }
	   if(contact_no=="")
	   {
		  alertify.error('Please input a Contact No');
		  $('#contact_no').focus();
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
	  
	  //addr=$.parseHTML(addr);
	  var request = $.ajax({
	  url: "./back/register.php",
	  method: "POST",
	  data: {
		  hid_uid:hid_uid,user_type:user_type,user_nm:user_nm,user_id:user_id,
		  password:password, contact_no:contact_no,email:email
		  ,addr:addr,district:district,block:block,base:base
		  ,office:office,lessee:lessee,tag:'REGISTER'},
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

//-->
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


$( "#edit" ).click(function() {
      var user_status =$('#user_status').val();  
      var hid_id =$('#hid_id').val();  
      var hid_uid =$('#hid_uid').val();  
	  var user_type =$('#user_type').val();
	  var district =$('#district').val();
	   if(user_type=="")
	   {
		  alertify.error('Please select a User Level');
		  $('#user_type').select2('open');
		  return false;
	   }
	   if(district=="")
	   {
		  alertify.error('Please select a District');
		  $('#district').select2('open');
		  return false;
	   }
	   if(user_type!="D")
	   {
		 	var block =$('#block').val();
  
		   if(block=="")
		   {
			  alertify.error('Please select a Block');
			  $('#block').select2('open');
			  return false;
		   }
		    if(user_type=="B")
	       {
			   var office =$('#office').val();

			   if(office=="")
			   {
				  alertify.error('Please select a Office');
				  $('#office').select2('open');
				  return false;
			   }
		   }
		    if(user_type=="L")
	       {
			  var lessee =$('#lessee').val();

			   if(lessee=="")
			   {
				  alertify.error('Please select a Lessee');
				  $('#lessee').select2('open');
				  return false;
			   }
		   }
	   }

	   var user_nm =$('#user_nm').val();
	   var user_id =$('#user_id').val();
	   var password =$('#password').val();
	   var addr =$('#addr').val();
	   var contact_no =$('#contact_no').val();
	   var email =$('#email').val();
	   var base =$('#base').val();
	   var fileinput =$('#fileinput').val();
       var filearr=fileinput.split('.');
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
	   
	   
	   
	   
	   if(user_nm=="")
	   {
		  alertify.error('Please input a User Name');
		  $('#user_nm').focus();
		  return false;
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
	   if(user_id=="")
	   {
		  alertify.error('Please input a User ID');
		  $('#user_id').focus();
		  return false;
	   }
	  
	   if(contact_no=="")
	   {
		  alertify.error('Please input a Contact No');
		  $('#contact_no').focus();
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
	  if ($('#email').val() != "") {
		 if(!validateEmail('email')){
		  alertify.error(" Invalid Email ID");
			$('#email').css("border-color","#FF0000");
			$('#email').focus();
			return false;								  
		}
	  }
	  var request = $.ajax({
	  url: "./back/register.php",
	  method: "POST",
	  data: {
		  hid_uid:hid_uid,user_type:user_type,user_nm:user_nm,user_id:user_id,
		  contact_no:contact_no,email:email,hid_id:hid_id
		  ,addr:addr,district:district,block:block,base:base,user_status:user_status
		  ,office:office,lessee:lessee,tag:'REGISTER-OLD'},
	  dataType: "html",
	  success:function( msg) {
			   $( "#info" ).html( msg );
	}
	});
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

	   
	   if(password=="")
	   {
		  alertify.error('Please input Password');
		  $('#password').focus();
		  return false;  
	   }
	   
	 
	  var request = $.ajax({
	  url: "./back/register.php",
	  method: "POST",
	  data: {
		  user_nm:user_nm,user_id:user_id, password:password,hid_uid:hid_uid
		 ,csrftoken:csrftoken,hid_id:hid_id
		  ,tag:'REGISTER-PROFILE'},
	  dataType: "html",
	  success:function( msg) {
	  //	 alert(msg);
			   $( "#info" ).html( msg );
			  
	}
	});
}); 
