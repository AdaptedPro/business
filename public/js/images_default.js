$(function() {
	var img = "";
	$('#loader,#loader2').hide();	
	$('#browse_tool_helper').hide();
	
	//Check file attachment
	$("#news_item_image").bind('change', function () {
		var allow	= ["bmp","gif","pdf","jpg","jpeg"];
		var str		= this.files[0].type;
		var substr	= str.split('/');
			var ftype	= substr[1];
			var fsize	= this.files[0].size;
 
			if ($.inArray(ftype, allow) > -1 ) {
				if (fsize > 1048576) {
					$("#news_item_image").val('');
				$("#f_msg").html("<em><u>File size too large must be less than 1.5MB.</u></em>");
			} else {
				$("#f_msg").html("");
			}
		} else {
			$("#news_item_image").val('');
			$("#f_msg").html("<em><u>Invalid file type.</u><br />Only .bmp, .gif, .jpg, .jpeg, and .png files allowed.</em>");
		}
	});    	
	
	//Intercept from submission
    $('#image_upload_form').submit(function(event) {
        if ($("#news_item_image").val() == '' || $("#news_item_image").val() == null) {        	
        	event.preventDefault();    	
        } else {
        	$('#loader').fadeIn();
        }
    });	
    
    $('#image_delete_form').submit(function(event) {       	
        event.preventDefault();
        if ($('#news_item_lib_image').val() != '' || $('#news_item_lib_image').val() != null) {
        	openAlert();        	
        }
    });
    
    //Browse Tool
    $("#browse_tool").on("click","ul li",function() {
    	$("#browse_tool ul li").each(function() {
    		$(this).removeClass('selected');
    	});
    	$(this).addClass('selected');
		$('#browse_tool_helper').fadeIn();
		$('#news_item_lib_image').val($('img', this).attr('src'));		
    });   
    
    function openAlert()
    {
    	var x;
    	var r=confirm("By 'OK', news items using using this image will use the default image instead.");
    	if (r==true) {
    		$('#loader2').fadeIn();
    		delete_action();
    	} 
    }    
	
	function delete_action()
	{
		$.ajax({
			type: 'POST',
			data: $(".image_delete_form_data").serialize(),
			url: 'images/delete',
			success:function(msg) {
				$("#browse_tool").html('');
				$("#browse_tool").html(msg);
				$('#loader2').hide();
				$('#delete_message').html('The image has been successfully deleted.');
			},
			error:function(e) {}	
		}); 
	}
});