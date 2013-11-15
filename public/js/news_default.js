$(function() {
	//Hide elements
	//$("#news_form_holder").hide();
	//$("#news_table_holder").hide();
	//$('#browse_tool').hide();

	$('#reset').click();

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
			$('#upload_tool').fadeIn();
		} else {
			$('#upload_tool').hide();
			$('#browse_tool').fadeIn();
			$("#f_msg").html("");
		}
	});   

	/*
	//Show elements
	$(".btn_link").click(function() {
		var holder = "";
		var label_1 = "";
		var label_2 = "";
		switch($(this).attr('id')) {
			case 'form_link_1':
				holder = "news_form_holder";
				label_1 = "Cancel";
				label_2 = "Create new item";
				break;
			case 'form_link_2':	
				holder = "news_table_holder";
				label_1 = "Hide news list";
				label_2 = "View news";
				break;
		}

		if ($(this).attr("data-state") == 1 ) {
			$("#"+holder).slideDown();
			$(this).attr("data-state", 2);
			$(this).html(label_1);
			$('html,body').animate({scrollTop:$(this).offset().top}, 500);
		} else {
			$("#"+holder).slideUp();
			$(this).attr("data-state", 1);
			$(this).html(label_2);
		}		
	});	
	*/

	//Intercept from submission
    $('#create_program_news_form').submit(function(event) {
        event.preventDefault();
        process_form();
    });	

	function process_form()
	{
		$.ajax({
			type: 'POST',
			data: $(".news_form_data").serialize(),
			url: 'ajaxcreate',
			success:function(msg) {
				$("#news_form_holder").html(msg);
			},
			error:function(e) {}
		});
	}	     
}); 