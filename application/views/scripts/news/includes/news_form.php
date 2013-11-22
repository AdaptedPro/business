	<form id="create_program_news_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">	
		<p><?php echo $this->error_array['title'] ? $this->error_array['title'].'<br />' : ""; ?>
		<input type="text" id="news_item_title" name="news_item_title" value="<?php echo $this->news_item_title; ?>" maxlength="60" class="news_form_data" placeholder="Title" required/>
		</p>
	
		<p><?php echo $this->error_array['summary'] ? $this->error_array['summary'].'<br />' : ""; ?>
		<textarea id="news_item_summary" name="news_item_summary" class="news_form_data" rows="5" placeholder="Summary..." wrap="hard" required><?php echo $this->news_item_summary; ?></textarea>
		</p>	
		
		<p><?php echo $this->error_array['details'] ? $this->error_array['details'].'<br />' : ""; ?>
		<textarea id="news_item_details" name="news_item_details" class="news_form_data" rows="10" placeholder="Details..." wrap="hard" required><?php echo $this->news_item_details; ?></textarea>
		</p>
		
		<p>
		<label for="news_item_is_public">Make public:</label>&nbsp;
			<select id="news_item_is_public" name="news_item_is_public" class="news_form_data">
				<option value="Y"<?php if ($this->news_item_public == 'Y') { echo " selected='selected'"; }?>>Yes</option>
				<option value="N"<?php if ($this->news_item_public == 'N') { echo " selected='selected'"; }?>>No</option>
			</select>
		</p>
		
		<p><?php echo $this->error_array['image'] ? $this->error_array['image'].'<br />' : ""; ?>
      	<input type="radio" class="image_source_radio" name="image_source" <?php echo $this->news_item_id ? '' : ' checked="checked" ';?>value="upload" class="news_form_data" />&nbsp; Upload image<br />
      	<input type="radio" class="image_source_radio" name="image_source" <?php echo $this->news_item_id ? 'checked="checked" ' : '';?>value="browse" class="news_form_data" />&nbsp; Choose from images<br />
		</p>

		<div id="upload_tool">
			<input type="file" id="news_item_image" name="news_item_image" class="browse"/>
		    <br /><small id="f_msg"></small>
	    </div>		
		<div id="browse_tool" class="hidden">
			<?php echo $this->image_list; ?>
		</div>
		<div id="browse_tool_helper" class="hidden">
			<p>
			<input type="text" id="news_item_lib_image" name="news_item_lib_image" class="news_form_data" readonly/>
			</p>
		</div>	    	
		<div id="loader"><img src="<?php echo $this->view->baseUrl('public/images/ajax-loader.gif')?>" alt="Loading" /></div>
		<p>
		<input type="submit" name="submit" id="submit" value="Save" class="custom_btn" />
		<input type="reset" name="reset" id="reset" value="Clear" class="custom_btn" />
		</p>
	</form>
	<?php if ($this->news_item_id!='' || $this->news_item_id != NULL) {?>
	<script type="text/javascript">
	<!--
	$(function() {
		setTimeout(fake_change,500);
		function fake_change() {
			$(".image_source_radio").change();
			$("#browse_tool ul li").each(function() {
				if ($('img',this).attr('src') == '<?php echo 'https://rccsss.s3-us-west-2.amazonaws.com/'.$this->news_item_image;?>') {
					$('img',this).click();
				console.log('Test');
				}
			});
		}
	});
	//-->
	</script>
	<?php } ?>
