	<div id="loader"><img src="<?php echo $this->baseUrl('public/images/ajax-loader.gif')?>" alt="Loading" /></div>
	<table id="news_table" class="tablesorter">
		<thead>
			<tr>
				<th>Title</th>
				<th>Image</th>
				<th>Details</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $this->table_output; ?>
		</tbody>
	</table>
	<div id="pager" class="pager">
		<form>
			<img src="<?php echo $this->baseUrl('public/images/pager/icons/first.png');?>" class="first"/>
			<img src="<?php echo $this->baseUrl('public/images/pager/icons/prev.png');?>" class="prev"/>
			<input type="text" class="pagedisplay"/>
			<img src="<?php echo $this->baseUrl('public/images/pager/icons/next.png');?>" class="next"/>
			<img src="<?php echo $this->baseUrl('public/images/pager/icons/last.png');?>" class="last"/>
			<!-- 
			<select class="pagesize">
				<option selected="selected" value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<option  value="20">20</option>
			</select>
			-->
		</form>	
	</div>	
	<script src="<?php echo $this->baseUrl('public/js/jquery.tablesorter.js'); ?>" type="text/javascript"></script>
	<script>
	$(function(){
				
	   $("#news_table").tablesorter({ 
	        headers: {1: {sorter: false},3: {sorter: false}} 
	    }).tablesorterPager({container: $("#pager")}); 

		$('#loader').hide();
	    $('.delete').click(function() {
	    	var delete_id = "";
				delete_id = $(this).parent().parent().attr('data-id');
			open_alert(delete_id);
	    });

	    function open_alert(dId)
	    {
	    	var x;
	    	var r=confirm("Are you sure you want to delete this news item? This can not be undone.");
	    	if (r==true) {
	    		$('#loader').fadeIn();
	    		delete_action(dId);
	    	} 
	    }

	    function delete_action(d)
	    {
			$.ajax({
				type:'GET',
				url:'news/delete/id/'+d,
				success:function(msg) {
					$('#loader').hide();
					window.location.reload(true);
				}
			});
	    } 		    
	});
	</script>