
<div id="create-msg">
	<div class="wrapper">
		<div class="new-msg-head">
			<h3>New Message</h3>
		</div>
		<div class="create-msg-con">
			<form id="msg-form" method="POST" action="">
				<div class="form-group row">
					<label for="staticEmail" class="col-sm-2 col-form-label">To:</label>
					<div class="col-sm-10">

						<select class="js-example-basic-multiple form-control" name="toId[]" multiple="multiple" required>
							<?php
								foreach ($users as $user){
									echo '<option value="'.$user["User"]["id"].'">'.$user["User"]["name"].'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="inputPassword" class="col-sm-2 col-form-label">Message:</label>
					<div class="col-sm-10">
						<textarea name="content" class="form-control" required></textarea>
					</div>
				</div>
				<div class="form-group row">
					<label for="inputPassword" class="col-sm-2 col-form-label"></label>
					<div class="col-sm-10">
						<button type="submit" class="btn btn-primary" >Send Message</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
