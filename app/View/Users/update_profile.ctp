
<div id="profile">
	<div class="profile-head">
		<h3>Update Profile</h3>
	</div>
	<div class="profile-con">
		<form action="" method="POST" enctype="multipart/form-data">
			<div class="profile-img-con">
				<?php if ($userProfile["profile_image"] != null) { ?>
					<figure><img src="profile-img/<?php echo $userProfile["profile_image"]; ?>" alt="CakePHP" /></figure>
				<?php } else { ?>
					<figure><img src="img/profile-dummy.png" alt="Profile image" /></figure>
				<?php } ?>
				<input type='file' name="upt_profile_image" class="custom-file-input" id="imgFile"/>
			</div>
			<div class="profile-details-con upt-profile-con">
				<!-- <?php echo $this->Flash->render(); ?> -->
					<div class="error-messages">
						<?php
							if (isset($errors) && $errors != null) {
								echo $errors;
							}
						?>
					</div>
					<div class="form-group">
						<label for="">Name:</label>
						<input type="text" class="form-control" placeholder="Enter name" value="<?php echo $userProfile["name"]; ?>" name="name" required>
					</div>
					<div class="form-group">
						<label for="">Email:</label>
						<input type="email" class="form-control"  placeholder="Enter email" value="<?php echo $userProfile["email"]; ?>" name="email" required>
					</div>
					<div class="form-group">
						<label for="">Change Password:</label>
						<input type="password" class="form-control"  placeholder="Enter password" name="password">
					</div>
					<div class="form-group">
						<label for="">Password Confirmation:</label>
						<input type="password" class="form-control"  placeholder="Enter password" name="password_confirmation">
					</div>
					<div class="form-group">
						<label for="">Gender:</label>
						<div class="radio">
							<label><input type="radio" name="gender" value="1" <?php if ($userProfile["gender"] == 1) { echo 'checked';} ?> required> Male</label>
						</div>
						<div class="radio">
							<label><input type="radio" name="gender" value="2" <?php if ($userProfile["gender"] == 2) { echo 'checked';} ?> required> Female</label>
						</div>
					</div>
					<div class="form-group">
						<label for="">Age:</label>
						<input type="int" class="form-control" placeholder="Enter age" value="<?php echo $userProfile["age"]; ?>" name="age" required>
					</div>
					<div class="form-group">
						<label for="">Birthdate:</label>
						<input type="text" class="form-control" id="birthdate" placeholder="Enter birthdate" name="birthdate" value="<?php echo $userProfile["birthdate"]; ?>" required>
					</div>
					<div class="form-group">
						<label for="">Hubby:</label>
						<textarea name="hubby" class="form-control" placeholder="Enter hubby..." required><?php echo $userProfile["hubby"]; ?></textarea>
					</div>

					<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>
	</div>
	<div class="dummy-div"></div>
</div>

