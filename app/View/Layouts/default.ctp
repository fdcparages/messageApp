<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<title> Messages App</title>
		<?php

			echo $this->Html->css('bootstrap.min.css');
			echo $this->Html->css('fontawesome.min.css');
			echo $this->Html->css('datepicker.css');
			echo $this->Html->css('select2.min.css');
			echo $this->Html->css('custom');
			echo $this->Html->css('media');
			echo $this->Html->script('https://kit.fontawesome.com/4b1fdca523.js');

		?>
	</head>
	<body>
	<?php
	$notAllowPage = array("login", "registration", "thankYou");
	if (!in_array($this->request->params['action'], $notAllowPage)){ ?>
		<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
			<div class="wrapper">
				<ul class="navbar-nav float-left">
					<li class="nav-item">
						<a class="nav-link comp-name" href="<?php echo Router::url('/'); ?>">Message Board</a>
					</li>
				</ul>
				<ul class="navbar-nav float-right">
					<li class="nav-item">
						<?php
								echo $this->Html->link(
									$userProfile["name"],
									array(
										'controller' => 'Users',
										'action' => 'profile'

									),
									array(
										'class' => 'nav-link'
									)
								);
							?>
					</li>
					<li class="nav-item">
							<?php
								echo $this->Html->link(
									'Logout',
									array(
										'controller' => 'Users',
										'action' => 'logout'

									),
									array(
										'class' => 'nav-link'
									)
								);
							?>
					</li>
				</ul>
			</div>
		</nav>
	<?php } ?>
		<?php echo $this->fetch('content'); ?>
	</body>

	<?php
		echo $this->Html->script('jquery-3.4.1.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('datepicker.js');
		echo $this->Html->script('select2.min.js');
	?>

	<script>
		$( function() {
			$( "#birthdate" ).datepicker({
				dateFormat: "yy-m-d"
			});
		} );

		$(document).ready(function() {
			$('.js-example-basic-multiple').select2();
		});
	</script>
	<?php
		echo $this->Html->script('custom.js');
	?>
</html>

