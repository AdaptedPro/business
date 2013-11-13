	<form id="create_program_news_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">	
		<p><?php echo $error_array['title']; ?>
		<label for="news_item_title">Title:</label><br />
		<input type="text" id="news_item_title" name="news_item_title" value="<?php echo $news_item_title; ?>" maxlength="26" class="news_form_data" />
		</p>
	
		<p><?php echo $error_array['summary']; ?>
		<label for="news_item_summary">Summary:</label><br />
		<textarea id="news_item_summary" name="news_item_summary" class="news_form_data"><?php echo $news_item_summary; ?></textarea>
		</p>	
		
		<p><?php echo $error_array['details']; ?>
		<label for="news_item_details">Details:</label><br />
		<textarea id="news_item_details" name="news_item_details" class="news_form_data"><?php echo $news_item_details; ?></textarea>
		</p>
		
		<p>
		<label for="news_item_is_public">Make public:</label><br />
			<select id="news_item_is_public" name="news_item_is_public" class="news_form_data">
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
		</p>	
		
		<p>
			<label for="news_item_image">Upload Image:</label><br />
			<input type="file" id="news_item_image" name="news_item_image" />
		</p>
		
		<p>
		<input type="submit" name="submit" id="submit" />
		<input type="reset" name="reset" id="reset" value="Clear" />
		</p>
	</form>

