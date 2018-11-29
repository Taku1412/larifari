$(document).ready(function(){
   
    //button 
    $("#change_userdata").on('click',function(){
		$("[name^=change]").prop('disabled', false); //enables all that start with change
		$("#confirm_button").show();
		$("#confirm_password").show();
	});
	
});
		