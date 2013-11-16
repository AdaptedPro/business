$(function() {
	var img_upload_state = false;
	//Hide elements
	$('#browse_tool').hide();
	$('#browse_tool_helper').hide();	
	$('#reset').click();
	
	$('#reset').click(function(){
		if (img_upload_state) {			
			$('#browse_tool').hide();
			$('#browse_tool_helper').hide();
			$('#news_item_lib_image').val('');
			$('#upload_tool').fadeIn();		
		}
	});

	//Initiate table sorter
    //$("#news_table").tablesorter();

	$('#news_item_title').jqEasyCounter({
	    'maxChars': 60,
	    'maxCharsWarning': 50,
	    'msgFontSize': '12px',
	    'msgFontColor': '#999',
	    'msgFontFamily': 'Arial',
	    'msgTextAlign': 'left',
	    'msgWarningColor': '#F00',
	    'msgAppendMethod': 'insertBefore'              
	});
    
	$('#news_item_summary').jqEasyCounter({
	    'maxChars': 250,
	    'maxCharsWarning': 220,
	    'msgFontSize': '12px',
	    'msgFontColor': '#999',
	    'msgFontFamily': 'Arial',
	    'msgTextAlign': 'left',
	    'msgWarningColor': '#F00',
	    'msgAppendMethod': 'insertBefore'              
	});

	$('#news_item_details').jqEasyCounter({
	    'maxChars': 500,
	    'maxCharsWarning': 480,
	    'msgFontSize': '12px',
	    'msgFontColor': '#999',
	    'msgFontFamily': 'Arial',
	    'msgTextAlign': 'left',
	    'msgWarningColor': '#F00',
	    'msgAppendMethod': 'insertBefore'              
	});		

	//Filter text input
	$('#news_item_title,#news_item_summary,#news_item_details').bind('keypress', function (event) {
		var value = this.value;
		var theEvent = event || window.event;
		var key = theEvent.keyCode || theEvent.which;
	
		if(key == 9 || key == 37 || key == 38 || key == 39 || key == 8 || key == 46|| key == 13) { //Backspace, Delete keys
		    return;
		}		
		
    	var regex = new RegExp("^[a-zA-Z0-9 @ ,() ! ? \" . _ - : $ % ']+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    }); 	

	//Check file attachment
	if ($("#news_item_image")[0].files) {
		$("#news_item_image").bind('change', function () {
			var allow	= ["bmp","gif","pdf","jpg","jpeg"];
			var str		= this.files[0].type;
			var substr	= str.split('/');
			var ftype	= substr[1];
			var fsize	= this.files[0].size;
 
			if ($.inArray(ftype, allow) > -1 ) {
				if (fsize > 1048576) {
					$("#news_item_image").val('');
					$("#f_msg").html("<em><u>File size too large must be 1.5MB.</u></em>");
				} else {
					$("#f_msg").html("");
				}
			} else {
				$("#news_item_image").val('');
				$("#f_msg").html("<em><u>Invalid file type.</u><br />Only .bmp, .gif, .jpg, .jpeg, and .png files allowed.</em>");
			}
		});
	}      

	//Toggle image upload source
	$('.image_source_radio').change(function() {
		if ($(this).val() == 'upload') {
			$('#browse_tool').hide();
			$('#browse_tool_helper').hide();
			$('#news_item_lib_image').val('');
			$('#upload_tool').fadeIn();
			img_upload_state = false;
		} else {
			$('#upload_tool').hide();
			$('#browse_tool').fadeIn();
			$("#f_msg").html("");
			img_upload_state = true;
		}
	});     
    
    //Browse Tool
    $("#browse_tool ul li").click(function () {
    	$("#browse_tool ul li").each(function() {
    		$(this).removeClass('selected');
    	});
    	$(this).addClass('selected');
		$('#browse_tool_helper').fadeIn();
		$('#news_item_lib_image').val($('img', this).attr('src'));		
    });
    
	//Intercept from submission
    $('#create_program_news_form').submit(function(event) {
        if (img_upload_state == true) {        	
        	event.preventDefault();
        	process_form();        	
        }
    });	
    
    //Add another item
    $("#news_form_holder").delegate('#add_link', 'click', function() {
    	$("#news_form_holder" ).load( "news/ajaxadd" );
    });

	function process_form()
	{
		$.ajax({
			type: 'POST',
			data: $(".news_form_data").serialize(),
			url: 'news/ajaxcreate',
			success:function(msg) {
				$("#news_form_holder").html(msg);				
			},
			error:function(e) {}
		});
	}	     
}); 