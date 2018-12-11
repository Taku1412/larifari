$(document).ready(function(){
   
    //button 
    $("#change_data").on('click',function(){
		$("[name^=change]").prop('disabled', false); //enables all that start with change
		$("[name=confirm_change]").show();
		$("[name=confirm_password]").show();
	});
	
});