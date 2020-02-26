<?php

App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


class UsersController extends AppController {


	public $components = array('RequestHandler');

	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
	}

	public function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->allow('registration', 'logout', 'registerUser');
	}

	//USER LOGIN
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Flash->error(__('Invalid username or password, try again'));
		}
	}

	//USER LOGOUT
	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

	// REGISTER PAGE
	public function registration() {
		//
	}

	//REGISTER USER
	public function registerUser() {
		$output = "";
		if ($this->request->is('post')) {
			$userInput = $this->request->data;
			$userInput["User"]["last_login_time"] = date('Y-m-d H:i:s');
			$userInput["User"]["profile_image"] = "profile-dummy.png";
			$userInput["User"]["created_ip"] = $this->request->clientIp();
            $this->User->create();
            if ($this->User->save($userInput)) {
				$authUser = $this->User->findById($this->User->id)["User"];
				$this->Auth->login($authUser);
            } else {
				$output = "<ul>";
				foreach ($this->User->validationErrors as $err) {
					$output .= '<li>'.$err[0].'</li>';
				}
				$output .= "</ul>";
			}
		}
		echo $output;
	}

	//THANK YOU PAGE
	public function thankYou() {
		//
	}

	// PROFILE PAGE
	public function profile() {
		//
	}

	// UPDATE PROFILE
	public function updateProfile() {
		$this->loadModel('Users');

		if (isset($this->request->data) && $this->request->data) {

			$uploadResponse = array();
			if($_FILES['upt_profile_image']['error'] == 0) {
				$uploadResponse = $this->doUploadProfile($_FILES["upt_profile_image"]);
		  	}
			$userData = $this->request->data;
			$checkEmailExist = null;
			if ($userData["email"] == $this->Auth->user('email')) {
				unset($userData["email"]);
			} else {
				$checkEmailExist = $this->Users->find('all', array(
					'conditions' => array('Users.email' => $userData["email"])
				));
			}
			if (strlen($userData["name"]) < 5 || strlen($userData["name"]) > 20) {
				$this->Flash->error(__('Name is required and should 5-20 Characters'));
			} else if($checkEmailExist != null) {
				$this->Flash->error(__('Email is already exist'));
			} else if ($uploadResponse != null && $uploadResponse["sts_code"] == 203) {
				$this->Flash->error(__($uploadResponse["msg"]));
			} else {
				if ($userData["password"] == null) {
					unset($userData["password"]);
				} else {
					$userData["password"] = AuthComponent::password($userData["password"]);
				}
				$userData["modified_ip"] = $this->request->clientIp();
				$userData["modified"] = date('Y-m-d H:i:s');
				if ($uploadResponse != null){
					$userData["profile_image"] = $uploadResponse["profile_image"];
				}
				$this->Users->read(null, $this->Auth->user('id'));
				$this->Users->set($userData);
				$this->Users->save();
				$authUser = $this->User->findById($this->Auth->user('id'))["User"];
				$this->Auth->login($authUser);
				return $this->redirect(array('controller' => 'Users', 'action' => 'profile'));
			}

		}
	}

	//USER UPLOAD PROFILE PHOTO
	public function doUploadProfile($imageFile) {

		$result = array();
		$allowExtension = array('gif', 'jpeg', 'png', 'jpg');
		$profName = $imageFile['name'];
		$profTmp = $imageFile['tmp_name'];
        $profTxt = substr(strrchr($profName, "."), 1);
		$profPathCheck = "profile-img/".$profName;

		if (file_exists($profPathCheck)) {
			$profName = time().'-'.rand().'-'.$profName;
		}

		$profPath = "profile-img/".$profName;
		if(in_array($profTxt, $allowExtension)){
			if (move_uploaded_file($profTmp, WWW_ROOT.$profPath)){
				$result["profile_image"] = $profName;
				$result["sts_code"] = 201;
				$result["msg"] = "Success";
			}
		} else {
			$result["profile_image"] = "";
			$result["sts_code"] = 203;
			$result["msg"] = "File extension is not allowed";
		}
		return $result;
	}


}
