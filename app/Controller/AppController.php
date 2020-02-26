<?php

App::uses('Controller', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class AppController extends Controller {

	public $components = array(
		'Session',
        'Flash',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'Messages',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'Messages',
                'action' => 'index'
			),
			'authenticate' => array('Form' => array( 'userModel' => 'User',
                                    'fields' => array(
                                                        'username' => 'email',
                                                        'password' => 'password'
                                                        )
                                                )
                            ),
			'authError' => "You can't access this page",
			'authorize' => array('Controller')
        )
	);


	public function isAuthorized($user) {
		return true;
	}

    public function beforeFilter() {
		$userProfile = AuthComponent::user();
		$this->set("userProfile", $userProfile);
        $this->Auth->allow('register', 'thank-you', 'registerUser');
	}



}
