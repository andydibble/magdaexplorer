<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {		

	public $uses = array('Login', 'Text');
	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Pages';

	/**
	 * Default helper
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Session');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login','verifyHumanity');
	}


	/**
	 * Handles login for any of the three passwords:
	 * PASSWORD: normal user
	 * ADMIN_PASSWORD: admin (also the adventurer)
	 * DEV_ADMIN_PASSWORD: admin (same privileges as ADMIN but no adventurer tracking)
	 */
	public function login() {
		$this->set('title_for_layout', Configure::read('APP_NAME'));

		if ($this->data) {

			//determine default trip to redirect to by whether Magda is away or at home.
			$this->loadModel('Login');
			$redirectTripId = $this->Text->findHomepageTripId();

			$pwd = $this->data['Login']['password'];

			$provideLoginMessage = true;
			if ($pwd == Configure::read('PASSWORD')) {
				$this->Auth->login();
				$isAdmin = false;

				$this->Trip->visitSite();

			} elseif (in_array($pwd, array_values(Configure::read('ADMIN_PASSWORDS')))) {
				$this->Auth->login();
				$isAdmin = true;

				$isDebugMode = false;
				if ($pwd == Configure::read('ADMIN_PASSWORDS.UPDATE_LOCATION_ADMIN_PASSWORD')) { //only normal ADMIN login triggers tracking.
					$this->Session->write('Login.isLoginSaved', false); //trigger save of adventurer login.
					$provideLoginMessage = false;
				} elseif ($pwd == Configure::read('ADMIN_PASSWORDS.DEV_ADMIN_PASSWORD')) { //only normal ADMIN login triggers tracking.
					$isDebugMode = true;
				}
				$this->Session->write('Configure.debug', $isDebugMode);


			} else {
				$this->Session->setFlash('That is not the right password.  Please try again.');
				$this->redirect($this->Auth->loginAction);
			}

			if ($provideLoginMessage && ($loginMessage = $this->Text->field('value', array('name' => 'login_message')))) {
				$this->Session->write('Trip.message', $loginMessage);
			}

			$this->Session->write('isAdmin', $isAdmin);
			//used on logout to determine whether homepage should be reset.
			$this->Session->write('loginPassword', $this->data['Login']['password']);

			$this->Auth->redirectToPrevious();

			$this->redirect('/trips/index/' . $redirectTripId);
		}
	}

	public function destroy() {
		$this->Session->destroy();
		$this->redirect('/trips/index');
	}

	public function logout() {
		if ($this->Session->read('loginPassword') == Configure::read('ADMIN_PASSWORD') &&
			$this->request->query['changeHomepage'] &&
			$this->Trip->isVisible($this->request->query['tripId'])
		) {
			$sucess = $this->Text->updateAll(array('value' => $this->request->query['tripId']), array('name' => 'homepage_trip'));
			if ($sucess) {
				$this->Session->setFlash('Homepage was successfully changed.');
			} else {
				$this->Session->setFlash('Homepage change failed.');
			}
		}

		$this->Auth->logout();
		$this->Session->destroy();
		$this->redirect('/pages/login');
	}
	
	public function verifyHumanity() {		
		
		$gCaptchaResponse = $this->data['g-recaptcha-response'];
		$HttpSocket = new HttpSocket();		

		// array data
		$data = array(
			'secret' => Configure::read('Security.recaptchaSecret'),
			'response' => $gCaptchaResponse,
			'remoteip' => $this->request->clientIp()
		);		
		$results = $HttpSocket->post(
			'https://www.google.com/recaptcha/api/siteverify',
			$data
		);
		
		$results=json_decode($results,true);	
		$this->Session->write("HUMANITY_VERIFIED",$results['success']==1);	
		return $results['success']==1;			
	}
}
