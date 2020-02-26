
<?php
// $convocode = null;
$lastIdMsg = null;
?>

<div id="message-details">
	<div class="wrapper">

		<div class="message-details-head">
			<img src="<?php echo Router::url('/'); ?>profile-img/<?php echo $recipientData["profile_image"] ?>" alt="<?php echo $recipientData["name"] ?>">
			<h2><?php echo $recipientData["name"] ?></h2>
		</div>
		<div class="message-details-con" data-recipient="<?php echo $recipientData["id"] ?>">
			<?php

				$counter = 0;
				foreach ($messages as $message) {
					if ( count($messages) >= 11) {
						if($counter > 0 && $counter < 11){
							$msg = $message["msg"];
							if ($msg["from_id"] == $recipientData["id"]) {
								echo '
									<div class="msg-txt from-msg" id="'.$msg["id"].'">
										<p>'.$msg["content"].'<span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span></p>
									</div>
								';
							} else {
								echo '
									<div class="msg-txt to-msg" id="'.$msg["id"].'">
										<p><span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span>'.$msg["content"].'</p>

									</div>
								';
							}
						}
					} else {
						$msg = $message["msg"];
						if ($msg["from_id"] == $recipientData["id"]) {
							echo '
								<div class="msg-txt from-msg" id="'.$msg["id"].'">
									<p>'.$msg["content"].'<span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span></p>
								</div>
							';
						} else {
							echo '
								<div class="msg-txt to-msg" id="'.$msg["id"].'">
									<p><span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span>'.$msg["content"].'</p>

								</div>
							';
						}
					}

					if ($counter == 1) {
						$lastIdMsg = $msg["id"];
					}
					$counter = $counter + 1;
				}
				if (count($messages) > 10) {
					echo '<button class="btn btn-primary" id="see-older-msg" data-msgid="'.$lastIdMsg.'">See older message</button>';
				}
			?>




		</div>
		<div class="message-details-footer">
			<form id="reply-msg-form">
				<textarea name="content" class="form-control" id="msg-content" required></textarea>
				<input type="hidden" id="convocode" value="<?php echo $convocode; ?>">
				<button type="submit" value="<?php echo $recipientData["id"] ?>" id="reply-button">Send</button>
			</form>
		</div>
	</div>
</div>

