<?php
App::import('Component', 'Auth');

class MyAuthComponent extends AuthComponent {

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

/**
 * Redirects to the previous page the user was on if she had to auth to access it.  Has no effect if the login page was
 * visited directly
 */
	public function redirectToPrevious() {
		if ($authRedirect = $this->controller->Session->read('Auth.redirect')) {
			$authRedirect = Utility::ltrim_string($authRedirect, Configure::read('APPROOT'));
			$this->controller->redirect('/' . $authRedirect);
		}
	}
}