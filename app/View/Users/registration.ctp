<div id="register">
	<div class="register-con">
		<div class="reg-left-con">
			<h2>Message Board</h2>
		</div>
		<div class="reg-right-con">
			<h3>Register</h3>
			<div class="error-messages">
			</div>
			<div id="reg-msg-errors"></div>
				<?php
					echo $this->Form->create('User');
					$options = array(
						'class' => 'form-control',
						'div' => array(
							'class' => 'form-group'
						)
					);
					echo $this->Form->input('name',$options);
					echo $this->Form->input('email', $options);
					echo $this->Form->input('password', $options);
					echo $this->Form->input('password_confirmation',
						array(
							'type' => 'password',
							'class' => 'form-control',
							'div' => array(
								'class' => 'form-group'
							)
						)
					);
					$optionsRegbtn = array(
						'label' => 'Submit',
						'class' => 'btn btn-primary',
						'div' => array(
							'class' => 'glass-pill',
						)
					);
					echo $this->Html->link(
						'Login',
						array(
							'controller' => 'Users',
							'action' => 'login'
						),
						array(
							'class' => 'btn-login btn btn-primary'
						)
					);
					echo $this->Form->end($optionsRegbtn);
				?>
		</div>
	</div>
</div>

