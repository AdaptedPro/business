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
		<label for="news_item_is_public">Make public:</label>&nbsp;
			<select id="news_item_is_public" name="news_item_is_public" class="news_form_data">
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
		</p>
		
		<p>
      	<input type="radio" class="image_source_radio" name="image_source" checked="checked" value="upload" class="news_form_data" />&nbsp; Upload image<br />
      	<input type="radio" class="image_source_radio" name="image_source"  value="browse" class="news_form_data" />&nbsp; Choose from images<br />
		</p>
		
		<div id="upload_tool">
			<input type="file" id="news_item_image" name="news_item_image" class="browse" />
		    <br /><small id="f_msg"></small>
	    </div>		
		<div id="browse_tool">
			<ul>
				<li><img alt="" src="https://scontent-b.xx.fbcdn.net/hphotos-frc3/421336_507527002634171_1247625800_n.jpg" /></li>
				<li><img alt="" src="https://scontent-a-pao.xx.fbcdn.net/hphotos-ash3/p480x480/1170703_541495202570684_2024681942_n.jpg" /></li>
				<li><img alt="" src="https://scontent-b.xx.fbcdn.net/hphotos-frc3/1461759_574273369292867_366746566_n.jpg" /></li>
				<li><img alt="" src="https://scontent-a-pao.xx.fbcdn.net/hphotos-ash3/p480x480/1170703_541495202570684_2024681942_n.jpg" /></li>
				<li><img alt="" src="https://scontent-a-pao.xx.fbcdn.net/hphotos-ash3/p480x480/1170703_541495202570684_2024681942_n.jpg" /></li>
				<li><img alt="" src="https://scontent-a-pao.xx.fbcdn.net/hphotos-ash3/p480x480/1170703_541495202570684_2024681942_n.jpg" /></li>
				<li><img alt="" src="https://scontent-a-pao.xx.fbcdn.net/hphotos-ash3/p480x480/1170703_541495202570684_2024681942_n.jpg" /></li>
			</ul>
		</div>
		<div id="browse_tool_helper">
			<p>
			<input type="text" id="news_item_lib_image" name="news_item_lib_image" class="news_form_data" readonly />
			</p>
		</div>	    	
		
		<p>
		<input type="submit" name="submit" id="submit" value="Save" class="custom_btn" />
		<input type="reset" name="reset" id="reset" value="Clear" class="custom_btn" />
		</p>
	</form>
