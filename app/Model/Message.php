<?php

App::uses('AppModel', 'Model');
use Cake\ORM\TableRegistry;

class Message extends AppModel {

	public $useTable = "messages";
	public $userId;

	public function __construct()
	{
		parent::__construct();
		// $this->userId = CakeSession::read('id');
		$this->userId = AuthComponent::user('id');
		$this->MessageStatus = ClassRegistry::init('MessageStatus');

	}

	public function createMessage($msgInput) {

		foreach ($msgInput["toId"] as $toId) {
			$checkConvoCodeExist = $this->query("SELECT convocode FROM messages WHERE (to_id = $toId AND from_id = $this->userId) OR (to_id = $this->userId AND from_id = $toId)");
			if ($checkConvoCodeExist != null) {
				$saveMsg["convocode"] = $checkConvoCodeExist[0]["messages"]["convocode"];
			} else {
				$saveMsg["convocode"] = $this->userId.''.$toId;
			}
			$saveMsg["to_id"] = $toId;
			$saveMsg["from_id"] = $this->userId;
			$saveMsg["content"] = $msgInput["content"];
			$this->create();
			if ($this->save($saveMsg)){
				$saveMsgStatus = array(
					"message_id" => $this->id,
					"convocode" => $saveMsg["convocode"],
				);
				$saveMsgStatus["user_id"] = $saveMsg["to_id"];
				$this->MessageStatus->saveMessageStatus($saveMsgStatus);

				$saveMsgStatus["user_id"] = $this->userId;
				$this->MessageStatus->saveMessageStatus($saveMsgStatus);
			}
		}

	}

	public function getAllMessages() {

		$messagesList = $this->query(
			"with messages as (
				select messages.id, messages.content, messages.from_id, messages.convocode, messages.to_id,
					   row_number() over(partition by messages.convocode order by messages.id desc) as RowNum
					from messages LEFT JOIN message_status ON message_status.message_id = messages.id WHERE message_status.user_id = $this->userId AND (messages.from_id = $this->userId OR messages.to_id = $this->userId)
			)
			select *
				from users RIGHT JOIN messages ON users.id = messages.from_id OR users.id = messages.to_id WHERE users.id != $this->userId GROUP BY convocode ORDER BY messages.id DESC LIMIT 11
			"
		);

		return $messagesList;

	}

	public function getMessagesPagination($lastMsgId, $searchValue = null) {

		$output = '';
		$lastMessageId = null;

		if ($searchValue == null) {
			$messagesList = $this->query(
				"with messages as (
					select messages.id, messages.content, messages.from_id, messages.convocode, messages.to_id,
					   row_number() over(partition by messages.convocode order by messages.id desc) as RowNum
					from messages LEFT JOIN message_status ON message_status.message_id = messages.id WHERE message_status.user_id = $this->userId AND (messages.from_id = $this->userId OR messages.to_id = $this->userId)
				)
				select *
					from users RIGHT JOIN messages ON users.id = messages.from_id OR users.id = messages.to_id WHERE users.id != $this->userId GROUP BY convocode HAVING messages.id < $lastMsgId ORDER BY messages.id DESC LIMIT 11
				"
			);
		} else {
			$messagesList = $this->query(
				"with messages as (
					select messages.id, messages.content, messages.from_id, messages.convocode, messages.to_id,
					   row_number() over(partition by messages.convocode order by messages.id desc) as RowNum
					from messages LEFT JOIN message_status ON message_status.message_id = messages.id WHERE message_status.user_id = $this->userId AND (messages.from_id = $this->userId OR messages.to_id = $this->userId)
				)
				select *
					from users RIGHT JOIN messages ON users.id = messages.from_id OR users.id = messages.to_id WHERE users.id != $this->userId AND users.name LIKE '%$searchValue%' OR messages.content LIKE '%$searchValue%' GROUP BY convocode HAVING messages.id < $lastMsgId ORDER BY messages.id DESC LIMIT 11
				"
			);
		}

		if ($messagesList != null) {
			$counter = 1;
			foreach ($messagesList as $msg) {
				if ($counter <= 10) {
					$dateCreated = date('m/d/Y h:i:s A', strtotime($msg["users"]["created"]));
					$output .= '
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
					$lastMessageId = $msg["messages"]["id"];
				}
				$counter += 1;

			}
			if (count($messagesList) > 10) {
				$output .= '<button class="btn btn-primary" id="show-more" data-msg="'.$lastMessageId.'">Show More</button>';
			}

		}

		return $output;

	}

	public function viewMessageDetails($recipient, $convocode) {
		$messages = $this->query(
			"select msg.* FROM
			(
				select messages.id, messages.content, messages.convocode, messages.from_id, messages.to_id, message_status.user_id
				from messages
				LEFT JOIN message_status ON message_status.message_id = messages.id WHERE message_status.convocode = $convocode AND message_status.user_id = $this->userId
				order by messages.id DESC LIMIT 11

			) msg
			order by msg.id ASC
			"
		);
		return $messages;
	}


	public function viewMessageDetailsPaginate($dataReq) {

		$recipient = $dataReq['recipient'];
		$lastMsgId = $dataReq['lastMsgId'];
		$lastMsgIdButton = null;
		$output = "";
		$messages = $this->query(
			"select msg.*FROM
			(
				select *
				from messages
				WHERE (to_id = $recipient AND from_id = $this->userId) OR (to_id = $this->userId AND from_id = $recipient)
				HAVING id < $lastMsgId order by id DESC  limit 11

			) msg LEFT JOIN message_status ON message_status.message_id = msg.id WHERE message_status.user_id = $this->userId
			order by msg.id ASC
			"
		);

		$counter = 0;
		foreach ($messages as $message) {
			if ( count($messages) >= 11) {
				if($counter > 0 && $counter < 11){
					$msg = $message["msg"];
					if ($msg["from_id"] == $recipient) {
						$output .= '
							<div class="msg-txt from-msg" id="'.$msg["id"].'">
								<p>'.$msg["content"].'<span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span></p>
							</div>
						';
					} else {
						$output .= '
							<div class="msg-txt to-msg" id="'.$msg["id"].'">
								<p><span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span>'.$msg["content"].'</p>

							</div>
						';
					}
				}
			} else {
				$msg = $message["msg"];
				if ($msg["from_id"] == $recipient) {
					$output .= '
						<div class="msg-txt from-msg" id="'.$msg["id"].'">
							<p>'.$msg["content"].'<span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span></p>
						</div>
					';
				} else {
					$output .= '
						<div class="msg-txt to-msg" id="'.$msg["id"].'">
							<p><span id="delete-single-msg" data-msgid="'.$msg["id"].'"><i class="fa fa-trash"></i></span>'.$msg["content"].'</p>

						</div>
					';
				}
			}

			if ($counter == 1) {
				$lastMsgIdButton = $msg["id"];
			}
			$counter = $counter + 1;
		}
		if (count($messages) > 10) {
			$output .= '<button class="btn btn-primary" id="see-older-msg" data-msgid="'.$lastMsgIdButton.'">See older message</button>';
		}

		return $output;


	}



	public function replyMessage($replyContent) {

		$replyContent["from_id"] = $this->userId;
		$this->save($replyContent);

		$saveMsgStatus = array(
			"message_id" => $this->id,
			"convocode" => $replyContent["convocode"],
		);
		$saveMsgStatus["user_id"] = $replyContent["to_id"];
		$this->MessageStatus->saveMessageStatus($saveMsgStatus);

		$saveMsgStatus["user_id"] = $this->userId;
		$this->MessageStatus->saveMessageStatus($saveMsgStatus);

		if ($this->id != null){
			echo '
				<div class="msg-txt to-msg">
					<p>'.$replyContent["content"].'<span id="delete-single-msg" data-msgid="'.$this->id.'"><i class="fa fa-trash"></i></span></p>
				</div>
			';
		} else {
			echo '
				<div class="msg-txt error-msg">
					<p>Error occured!</p>
				</div>
			';
		}

	}

	public function searchMessage($searchValue) {

		$output = "";

		$messagesList = $this->query(
			"with messages as (
				select messages.id, messages.content, messages.from_id, messages.convocode, messages.to_id,
				   row_number() over(partition by messages.convocode order by messages.id desc) as RowNum
				from messages LEFT JOIN message_status ON message_status.message_id = messages.id WHERE message_status.user_id = $this->userId AND (messages.from_id = $this->userId OR messages.to_id = $this->userId)
			)
			select *
				from users RIGHT JOIN messages ON users.id = messages.from_id OR users.id = messages.to_id WHERE users.id != $this->userId AND users.name LIKE '%$searchValue%' OR messages.content LIKE '%$searchValue%' GROUP BY convocode ORDER BY messages.id DESC LIMIT 11
			"
		);
		$counter = 1;

		if ($messagesList != null) {
			foreach ($messagesList as $msg) {
				if ($counter <= 10) {
					$dateCreated = date('m/d/Y h:i:s A', strtotime($msg["users"]["created"]));
					$output .= '
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
					$lastMessageId = $msg["messages"]["id"];
				}
				$counter += 1;
			}
			if (count($messagesList) > 10) {
				$output .= '<button class="btn btn-primary" id="show-more" data-msg="'.$lastMessageId.'">Show More</button>';
			}

		} else {
			$output .= '
				<div class="msg-con" style="padding-left: 10px;">
					<h3>No results found...</h3>
				</div>
			';
		}

		return $output;

	}


	//DELETE MESSAGES WITH CONVOCODE PARAM
	public function deleteAllMessageWithConvocode($convocode) {
		$this->deleteAll(array(
			'convocode' => $convocode
		), false);
	}


}


// 124
