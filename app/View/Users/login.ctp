
<div id="login">
	<div class="login-con">
		<div class="log-head">
			<h2>Message Board</h2>
		</div>
		<div class="log-body">
			<div class="users form">
				<?php
					echo $this->Flash->render();
					echo $this->Form->create('User');
					echo $this->Form->input('email',
						array('class' => 'form-control','label' => "Email:",
							'div' => array(
								'class' => 'form-group'
							)
						)
					);
					echo $this->Form->input('password',
						array('class' => 'form-control','label' => "Password:",
							'div' => array(
								'class' => 'form-group'
							)
						)
					);
					$options = array(
						'label' => 'Login',
						'class' => 'btn btn-primary',
						'div' => array(
							'class' => 'glass-pill',
						)
					);
					echo $this->Html->link(
						'Register',
						array(
							'controller' => 'Users',
							'action' => 'registration'
						),
						array(
							'class' => 'btn-reg btn btn-primary'
						)
					);
					echo $this->Form->end($options);
				?>
			</div>
		</div>
	</div>
</div>

