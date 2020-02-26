<div id="profile">
	<div class="profile-head">
		<h3>Profile Information</h3>
		<a href="update-profile">Update</a>
	</div>
	<div class="profile-con">
		<div class="profile-img-con">
			<?php if ($userProfile["profile_image"] != null) { ?>
				<figure><img src="profile-img/<?php echo $userProfile["profile_image"]; ?>" alt="CakePHP" /></figure>
			<?php } else { ?>
				<figure><img src="img/profile-dummy.png" alt="Profile image" /></figure>
			<?php } ?>
		</div>
		<div class="profile-details-con">
			<h2><?php echo $userProfile["name"]; ?></h2>
			<p><span>Email: </span><?php echo $userProfile["email"]; ?></p>
			<p><span>Age: </span><?php echo $userProfile["age"]; ?></p>
			<p><span>Gender: </span>
				<?php
					if ($userProfile["gender"] == 1){
						echo "Male";
					} else if ($userProfile["gender"] == 2) {
						echo "Female";
					} else {
						echo "Not specified";
					}
				?>
			</p>
			<p><span>Birthdate: </span>
				<?php
					if ($userProfile["birthdate"]) {
						$b_date = date_create($userProfile["birthdate"]);
						echo date_format($b_date, 'M d, Y');
					}
				?>
			</p>
			<p><span>Joined: </span>
				<?php
					$created_date = date_create($userProfile["created"]);
					echo date_format($created_date, 'M d, Y');
				?>
			</p>
			<p><span>Last Login: </span>
				<?php
					$created_date = date_create($userProfile["last_login_time"]);
					echo date_format($created_date, 'M d, Y H:i:s');
				?>
			</p>
			<div class="hubby">
				<span>Hubby:</span>
				<p><?php echo $userProfile["hubby"]; ?></p>
			</div>
		</div>
	</div>
	<div class="dummy-div"></div>
</div>

