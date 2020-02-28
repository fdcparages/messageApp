<?php

App::uses('AppController', 'Controller');
App::uses('User', 'AppModel');

class MessagesController extends AppController{

	public $uses = array(
		'User',
		'Message',
		'MessageStatus'
	);

	public $components = array('RequestHandler');

	public function __construct($request = null, $response = null)
	{
		parent::__construct($request, $response);
	}

	//DASHBOARD
	public function index() {
		$messagesList = $this->Message->getAllMessages();
		$this->set("messages", $messagesList);
	}

	//CREATE MESSAGE
	public function createMessage() {
		$usersToSendMessage = $this->User->getUsersToSendMessage();
		$this->set("users", $usersToSendMessage);
		if (isset($this->request->data) && $this->request->data) {
			$msgInput = $this->request->data;
			$responseCreateMessage = $this->Message->createMessage($msgInput);
			return $this->redirect(array('controller' => 'Messages', 'action' => 'index'));
		}

	}

	// DASHBOARD GET MESSAGES USING AJAX
	public function getMessages() {
		if (isset($this->request->data) && $this->request->data) {
			$inputData = $this->request->data;
			if (isset($inputData["searchValue"]) && $inputData["searchValue"]) {
				$messagesList = $this->Message->getMessagesPagination($inputData["lastMsgId"], $inputData["searchValue"]);
			} else {
				$messagesList = $this->Message->getMessagesPagination($inputData["lastMsgId"]);
			}
		}
		echo $messagesList;
		$this->render('/Layouts/ajax');
	}

	// MESSAGE DETAILS
	public function viewMessageDetails() {
		$recipient = $this->request->params['id'];
		$convocode = $this->request->params['convocode'];
		$recipientData = $this->User->getSingleUser($recipient)["User"];
		$listMsg = $this->Message->viewMessageDetails($recipient, $convocode);
		$this->set(array(
				"recipientData" => $recipientData,
				"messages" => $listMsg,
				"convocode" => $convocode
			)
		);
	}

	//VIEW SEE OLDER MESSAGE
	public function viewMessageDetailsPaginate() {
		if (isset($this->request->data) && $this->request->data) {
			$dataReq = $this->request->data;
			$response = $this->Message->viewMessageDetailsPaginate($dataReq);
			echo $response;
			$this->render('/Layouts/ajax');
		}

	}

	//REPLY MESSAGE
	public function replyMessage() {
		if (isset($this->request->data) && $this->request->data) {
			$replyContent = $this->request->data;
			$recipientData = $this->User->getSingleUser($replyContent["to_id"]);
			if ($recipientData == null ){
				echo '
					<div class="msg-txt error-msg">
						<p>Error occured!</p>
					</div>
				';
			} else {
				$response = $this->Message->replyMessage($replyContent);
			}
		}
		$this->render('/Layouts/ajax');
	}

	// DELETE CONVERSATION
	public function deleteMessage() {
		if (isset($this->request->data) && $this->request->data) {
			$convocode = $this->request->data("convocode");
			$response = $this->MessageStatus->deleteConversationMessageStatus($convocode);
		}
		$this->render('/Layouts/ajax');
	}

	//DELETE SINGLE MESSAGE
	public function deleteSingleMessage() {
		if (isset($this->request->data) && $this->request->data) {
			$msgid = $this->request->data("msgid");
			$response = $this->MessageStatus->deleteSingleMessageStatus($msgid);
		}
		$this->render('/Layouts/ajax');
	}

	//DASHBOARD SEARCH MESSAFGE BY RECIPIENT
	public function searchMessage() {
		if (isset($this->request->data) && $this->request->data) {
			$searchValue = $this->request->data("searchValue");
			$response = $this->Message->searchMessage($searchValue);
			echo $response;
		}
		$this->render('/Layouts/ajax');
	}


}


