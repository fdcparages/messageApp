<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
	<div class="wrapper">
		<ul class="navbar-nav float-left">
			<li class="nav-item">
				<a class="nav-link comp-name" href="<?php echo Router::url('/'); ?>">Message Board</a>
			</li>
		</ul>
		<ul class="navbar-nav float-right">
			<li class="nav-item">
				<a class="nav-link" href="profile"><?php echo $this->Session->read('name'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="
					<?php
						echo $this->Html->link(
							'logout',
							array(
								'controller' => 'Users',
								'action' => 'logout'
							)
						);
					?>
				">Log out</a>
			</li>
		</ul>
</nav>
