	<form id="create_program_news_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">	
		<p><?php echo $this->error_array['title']; ?>
		<input type="text" id="news_item_title" name="news_item_title" value="<?php echo $this->news_item_title; ?>" maxlength="60" class="news_form_data" placeholder="Title" required/>
		</p>
	
		<p><?php echo $this->error_array['summary']; ?>
		<textarea id="news_item_summary" name="news_item_summary" class="news_form_data" rows="5" placeholder="Summary..." wrap="hard" required><?php echo $this->news_item_summary; ?></textarea>
		</p>	
		
		<p><?php echo $this->error_array['details']; ?>
		<textarea id="news_item_details" name="news_item_details" class="news_form_data" rows="10" placeholder="Details..." wrap="hard" required><?php echo $this->news_item_details; ?></textarea>
		</p>
		
		<p>
		<label for="news_item_is_public">Make public:</label><br />
			<select id="news_item_is_public" name="news_item_is_public" class="news_form_data">
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
		</p>	
		
		<p>
			<input type="radio" class="image_source_radio" name="image_source" checked="checked" value="upload" />&nbsp; Upload image<br />
			<input type="radio" class="image_source_radio" name="image_source"  value="browse" />&nbsp; Choose from images<br />
		</p>
		<div id="upload_tool">
			<input type="file" id="news_item_image" name="news_item_image" class="browse" />
			<br /><small id="f_msg"></small>
		</div>
		<div id="browse_tool1">
			<?php echo $this->image_select; ?>
		</div>
		
		<p>
		<input type="submit" name="submit" id="submit" value="Save" class="custom_btn" />
		<input type="reset" name="reset" id="reset" value="Clear" class="custom_btn" />
		</p>
	</form>

