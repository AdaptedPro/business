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
		<input type="submit" name="submit" id="submit" value="Save" class="custom_btn" />
		<input type="reset" name="reset" id="reset" value="Clear" class="custom_btn" />
		</p>
	</form>

