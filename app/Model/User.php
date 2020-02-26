<?php

// app/Model/User.php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $validate = array(
        'name' => array(
			'Name is required and should 5-20 Characters' => array(
				'rule' => array('between', 5, 20),
				'message' => 'Name is required and should 5-20 Characters'
			),
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Name is required'
            )
		),
		'email' => array(
			'Valid email' => array(
				'rule' => array('email'),
				'message'=> 'Please enter a valid email address'
			),
			'Email is already exist!' => array(
				'rule' => 'isUnique',
				'message' => 'Email is already exist!'
			),
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A password is required'
			),
			'Match passwords' => array(
				'rule' => 'matchPasswords',
				'message' => 'Your passwords does not match!'
			)
		),
		'password_confirmation' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Password confirmation is required'
            )
		),
		'birthdate' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Birthdate is required'
            )
		),
		'gender' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Gender is required'
            )
		),
		'age' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Age is required'
            )
		),
		'hubby' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Hubby is required'
            )
		),
	);

	public function __construct()
	{
		parent::__construct();
		$this->userId = AuthComponent::user('id');

	}


	public function matchPasswords($data) {
		if ($this->data[$this->alias]['password'] == $this->data[$this->alias]['password_confirmation']) {
			return true;
		}
		// $this->invalidate('password_confirmation', 'Your passwords does not match!');
		return false;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data["User"]['password'])) {
			$this->data["User"]['password'] = AuthComponent::password($this->data["User"]['password']);
		}
		return true;
	}


	public function getUsersToSendMessage() {
		$usersList = $this->find('all',
				array('conditions' => array(
						'id !=' => $this->userId
					)
				)
			);
		return $usersList;
	}

	public function getSingleUser($id) {
		$userData = $this->find('first', array(
				'conditions' => array(
					'id' => $id
				)
			)
		);
		return $userData;
	}


}
