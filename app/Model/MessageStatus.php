<?php

App::uses('AppModel', 'Model');

class MessageStatus extends AppModel {

	public $useTable = "message_status";
	public $userId;

	public function __construct()
	{
		parent::__construct();
		$this->userId = AuthComponent::user('id');
		$this->Message = ClassRegistry::init('Message');

	}

	public function saveMessageStatus($data) {
		$this->create();
		$this->save($data);
	}

	// DELETE SINGLE MESSAGE
	public function deleteSingleMessageStatus($msgid) {

		$deleteMessage = $this->deleteAll(array(
			'message_id' => $msgid,
			'user_id' => $this->userId
		), false);

		if ($deleteMessage) {
			echo "Success";
		} else {
			echo "Error";
		}

	}

	// DELETE CONVERSATION MESSAGE
	public function deleteConversationMessageStatus($convocode) {
		$deleteConversationMessage = $this->deleteAll(array(
			'convocode' => $convocode,
			'user_id' => $this->userId
		), false);
		if ($deleteConversationMessage) {
			$countConvocode = $this->find("all",
				array(
					"conditions" => array(
						"convocode" => $convocode
					)
				)
			);
			if ($countConvocode == null) {
				$this->Message->deleteAllMessageWithConvocode($convocode);
			}
			echo "Success";
		} else {
			echo "Error";
		}

	}


}
