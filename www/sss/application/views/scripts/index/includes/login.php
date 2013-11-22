<div>
	<form id="signin_form" action="<?php $_SERVER['REQUEST_URI']?>" method="post">
		<?php echo $this->login_message ? "<br />{$this->login_message}"  : "";  ?>
		<p>
		<input type="text" name="username" id="username" maxlength="40" placeholder="Username" required/>
		</p>
		
		<p>
		<input type="password" name="password" id="password" maxlength="40" placeholder="Password" required/>
		</p>
		
		<p>
		<input type="submit" name="submit" id="submit" value="Login" class="custom_btn" />
		</p>
	</form>
</div>