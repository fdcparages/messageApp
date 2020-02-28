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
				$userData["last_login_time"] = date('Y-m-d H:i:s');
				$this->User->id = $this->Auth->user('id');
				$this->User->save($userData);
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
		$this->render('/Layouts/ajax');

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
		$errors = null;
		if (isset($this->request->data) && $this->request->data) {
			$userData = $this->request->data;

			//CHECK IF EMAIL VALUE IS NOT CHANGE
			if ($userData["email"] == $this->Auth->user('email')){
				unset($userData["email"]);
			}

			//CHECK IF PASSWORD AND PASSWORD CONFIRMATION IS NULL VALUE
			if ($userData["password"] == null && $userData["password_confirmation"] == null){
				unset($userData["password"]);
				unset($userData["password_confirmation"]);
			}

			//UPLOAD PHOTO
			$uploadResponse = array();
			if($_FILES['upt_profile_image']['error'] == 0) {
				$uploadResponse = $this->doUploadProfile($_FILES["upt_profile_image"]);
				if ($uploadResponse["sts_code"] == 203) {
					$errors = 1;
				}
			}

			//DO VALIDATION FORM
			$this->User->set($userData);
			if ($this->User->validates() && $errors == null) {
				if (isset($userData["password"])){
					$userData["password"] = AuthComponent::password($userData["password"]);
				}
				if ($uploadResponse != null && $uploadResponse["sts_code"] == 201){
					$userData["profile_image"] = $uploadResponse["profile_image"];
				}
				$userData["modified_ip"] = $this->request->clientIp();
				$userData["modified"] = date('Y-m-d H:i:s');
				//UPDATE USER DATA TO DATABASE
				$this->Users->read(null, $this->Auth->user('id'));
				$this->Users->set($userData);
				$this->Users->save();

				$authUser = $this->User->findById($this->Auth->user('id'))["User"];
				$this->Auth->login($authUser);
				return $this->redirect(array('controller' => 'Users', 'action' => 'profile'));

			} else {
				$errors = "<ul>";
				if ($uploadResponse != null && $uploadResponse["sts_code"] == 203) {
					$errors .= '<li>'.$uploadResponse["msg"].'</li>';
				}
				foreach ($this->User->validationErrors as $err ){
					$errors .= '<li>'.$err[0].'</li>';
				}
				$errors .= "</ul>";
				$this->set("errors", $errors);
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

		//CHECK FILE IS EXIST AND RENAME IF ITS EXIST
		if (file_exists($profPathCheck)) {
			$profName = time().'-'.rand().'-'.$profName;
		}

		//CHECK FILE EXTENSION AND UPLOAD IF VALID
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
