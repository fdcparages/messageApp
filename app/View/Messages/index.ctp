

<?php $lastMessage = null; ?>
<div id="messages">
	<div class="wrapper">
		<div class="messages-head">
			<h2>Messages</h2>
			<a href="new-message">New Message</a>
		</div>
		<div class="messages-search">
			<div class="search-container">
				<input type="text" class="form-control" placeholder="Search message or recipient here" name="searchMessage" id="searchMessage">
			</div>
		</div>
		<div class="messages-con">
			<?php
				$counter = 1;
				foreach ($messages as $msg) {
					if ($counter <= 10) {
						$dateCreated = date('m/d/Y h:i:s A', strtotime($msg["users"]["created"]));
						echo '
							<div class="msg-con" id="'.$msg["messages"]["convocode"].'">
								<img src="profile-img/'.$msg["users"]["profile_image"].'" alt="">
								<h3>'.$msg["users"]["name"].'</h3>
								<p class="msg-content">'.$msg["messages"]["content"].'</p>
								<div class="msg-options">
									<a href="message-details/'.$msg["users"]["id"].'/'.$msg["messages"]["convocode"].'">View</a>
									<a href="#" class="delete-msg" data-convocode="'.$msg["messages"]["convocode"].'">Delete</a>
									<p>'.$dateCreated.'</p>
								</div>

							</div>
						';
						$lastMessage = $msg["messages"]["id"];
					}
					$counter += 1;
				}

				if (count($messages) > 10) {
					echo '<button class="btn btn-primary" id="show-more" data-msg="'.$lastMessage.'">Show More</button>';
				}
			?>
		</div>
		<div class="messages-footer"></div>
	</div>
</div>


