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
		<tfoot>
			<tr>
				<td colspan="4">
					<div id="pager" class="pager">
						<form>
							<img src="<?php echo $this->baseUrl('public/images/pager/icons/first.png');?>" class="first"/>
							<img src="<?php echo $this->baseUrl('public/images/pager/icons/prev.png');?>" class="prev"/>
							<input type="text" class="pagedisplay"/>
							<img src="<?php echo $this->baseUrl('public/images/pager/icons/next.png');?>" class="next"/>
							<img src="<?php echo $this->baseUrl('public/images/pager/icons/last.png');?>" class="last"/>
							<select class="pagesize">
								<option selected="selected"  value="10">10</option>
								<option value="20">20</option>
								<option value="30">30</option>
								<option  value="40">40</option>
							</select>
						</form>	
					</div>
				</td>
			</tr>
    	</tfoot>
	</table>
	<script src="<?php echo $this->baseUrl('public/js/jquery.tablesorter.js'); ?>" type="text/javascript"></script>
	<script>
	$(function(){
	   $("#news_table").tablesorter({ 
	        headers: {1: {sorter: false},3: {sorter: false}} 
	    });//.tablesorterPager({container: $("#pager")}); 
	});
	</script>