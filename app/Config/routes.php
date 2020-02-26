<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'Messages', 'action' => 'index'));
	Router::connect('/register', array('controller' => 'Users', 'action' => 'registration'));
	Router::connect('/register-user', array('controller' => 'Users', 'action' => 'registerUser'));
	Router::connect('/thank-you', array('controller' => 'Users', 'action' => 'thankYou'));
	Router::connect('/login', array('controller' => 'Users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'Users', 'action' => 'logout'));
	Router::connect('/profile', array('controller' => 'Users', 'action' => 'profile'));
	Router::connect('/update-profile', array('controller' => 'Users', 'action' => 'updateProfile'));
	Router::connect('/new-message', array('controller' => 'Messages', 'action' => 'createMessage'));
	Router::connect('/get-messages', array('controller' => 'Messages', 'action' => 'getMessages'));
	Router::connect('/message-details/:id/:convocode',
		array('controller' => 'Messages', 'action' => 'viewMessageDetails')
	);
	Router::connect('/reply-message', array('controller' => 'Messages', 'action' => 'replyMessage'));
	Router::connect('/get-message-paginate', array('controller' => 'Messages', 'action' => 'viewMessageDetailsPaginate'));
	Router::connect('/delete-message', array('controller' => 'Messages', 'action' => 'deleteMessage'));
	Router::connect('/delete-single-message', array('controller' => 'Messages', 'action' => 'deleteSingleMessage'));
	Router::connect('/search-message', array('controller' => 'Messages', 'action' => 'searchMessage'));


/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/messages/*', array('controller' => 'pages', 'action' => 'index'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
