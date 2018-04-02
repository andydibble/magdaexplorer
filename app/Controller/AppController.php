<?php
App::import('Core', 'String');
App::import('Lib', 'Utility');
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $adminOnly = array();

	public $uses = array('Trip', 'Text', 'Login', 'Adventure', 'Location');

	public $components = array(
		'Session',
		'MyEmail' => array(
			'smtpOptions' => array(
				'port' => '465',
				'timeout' => '30',
				'delivery' => 'smtp',
				'host' => 'ssl://mail.magdaexplorer.com',
				'username' => 'magda@magdaexplorer.com',
				'password' => "June2012"
			),
		),
		'RequestHandler',
		'MyAuth' => array(
			'loginError' => 'That is not the right password.  Please try again.',
			'loginAction' => array(
				'controller' => 'pages',
				'action' => 'login',
			),
			'authError' => 'Please login to access this page.',
			'allowedActions' => array('login', 'sign_up')
		)
	);

	public $helpers = array(
		'Js' => array('Jquery')
	);

	public function constructClasses() {
		parent::constructClasses();
		$this->Email = $this->MyEmail;
		$this->Auth = $this->MyAuth;
	}

	public function beforeFilter() {

		$this->prepAjax();

		//configure whether debug info is seen.
		if (Configure::read('HTTP_HOST') == 'localhost' || $this->Session->check('Configure.debug') && $this->Session->read('Configure.debug')) {
			Configure::write('debug', 2);
		} else {
			Configure::write('debug', 0);
		}

		if (!$this->Auth->loggedIn()) { //if the user has not logged in assume they are a normal user.
			$this->Session->write('isAdmin', false);
		}

		$isAdmin = $this->isAdmin();

		//stop non-admin from accessing any admin actions.
		if (in_array($this->request->action, $this->adminOnly)) {
			if (!$isAdmin) {
				$this->redirect($this->referer());
			}
		}

		if ($this->Session->check('loginPassword')) {
			$this->set('loginPassword', $this->Session->read('loginPassword'));
		}

		$isMobile = $this->RequestHandler->isMobile() ? 1 : 0;

		$this->set(compact('isAdmin', 'isMobile'));
	}


	/*public function appError($method)
	{
		die('Application error: called handler method '.$method);
	}*/

	//get trip list for navigation info on all pages.
	public function beforeRender() {
		$locations = $this->locationList(true);
		$this->set('locations', $locations);
		if ($this->request['action'] != 'login' && $this->request['controller'] != 'pages') {
			if ($this->Session->check('Trip.mostRecent')) {
				$lastVisited = $this->Session->read('Trip.mostRecent');
			} else {
				$lastVisited = $this->Trip->find('first', array(
					'conditions' => array('Trip.is_main_page' => 1),
					'recursive' => -1
				));
				$lastVisited = $lastVisited['Trip'];
			}

			$id = $lastVisited['id'];
			$this->set('tripId', $id);
			$this->set('locationId', $lastVisited['location_id']);

			$this->set('tripDisplayName', $lastVisited['display_name']);
			$this->set('isNormalTrip',
				!$lastVisited['is_main_page'] &&
				$lastVisited['is_urban_watermelon']);

			//layout fields (trip specific and global to site)
			$texts = $this->Text->find('list', array('fields' => array('name', 'value')));

			$trip = $this->Trip->find('first', array(
				'conditions' => array('Trip.id' => $id),
				'contain' => array('Location', 'HeaderImage')));
			$loc = $trip['Location'];

			if ($trip['Trip']['is_main_page']) { //if is main page, then use fields associated with the trip of the most recent adv as defaults.
				$tripIdOfMostRecentAdv = $this->Adventure->field('trip_id', array(), 'trip_id DESC');
				$tripOfMostRecentAdv = $this->Trip->find('first', array(
					'conditions' => array('id' => $tripIdOfMostRecentAdv),
					'contain' => array()
				));
				$trip['Trip'] = array_filter($trip['Trip']); //to make sure empty values do not overwrite non-empty.
				$texts = array_merge($texts, $tripOfMostRecentAdv['Trip'], $trip['Trip']);
			} else {
				$texts = array_merge($texts, $trip['Trip']);
			}

			$texts = $this->setFieldToDefaultIfEmpty($texts);
			$this->set('headerBackground', $trip['HeaderImage']['filename']);
			$this->set('texts', $texts);
			$this->set('location', $loc);
		}

		//for map display
		$explorerLocInfo = $this->Login->find('first', array('order' => 'date DESC'));

		//find local time for header display.
		date_default_timezone_set($explorerLocInfo["Login"]['timezone']);
		$localTime = date(Configure::read('DISP_TIME_FORMAT'), time());

		$explorerLocInfo = json_encode($explorerLocInfo['Login']);

		//for updating tracking information of explorer/administrator login
		if ($this->Session->check('Login.isLoginSaved') && !$this->Session->read('Login.isLoginSaved')) {
			$this->set('saveAdminLogin', 1);
			$this->Session->write('Login.isLoginSaved', true);
		}

		$this->set(compact('explorerLocInfo', 'localTime'));
	}


	public function isAdmin() {
		return $this->Session->check('isAdmin') && $this->Session->read('isAdmin');
	}

	public function findMostRecentTripId() {
		if (!$this->isAdmin()) {
			$conds = array('is_visible' => 1);
		} else {
			$conds = array();
		}

		$mostRecentTripId = $this->Trip->field('id', $conds, 'id DESC');
		return $mostRecentTripId;
	}

	/**
	 * Returns the id of the main page trip.
	 * @return unknown
	 */
	public function findMainTripId() {
		if (!$this->isAdmin()) {
			$conds = array('is_visible' => 1, 'is_main_page' => 1);
		} else {
			$conds = array('is_main_page' => 1);
		}

		$mainPageId = $this->Trip->field('id', $conds, 'id DESC');
		return $mainPageId;
	}

	/**
	 * Overrides cake functionality which prevents ajax calls by stopping a layout from being
	 * rendered and tetting rid of any debug output.
	 */
	public function prepAjax() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			//Configure::write('debug', 0);
		}
	}

	/**
	 * Deletes all $records from the associated table
	 * that have $model field delete set to true (or equiv)
	 * @param unknown_type $records
	 * @param unknown_type $model
	 */
	public function deleteIfSelected($records, $model, $primary = true) {

		$modelName = $model->name;
		if ($primary) {
			if (isset($records[0][$modelName])) {
				foreach ($records as $i => $record) { //delete checked responses
					if ($record[$modelName]['delete']) {
						$model->delete($record[$modelName]['id']);
						unset($records[$i]);
					}
				}
			}
		} else {
			if (isset($records[$modelName])) {
				foreach ($records[$modelName] as $i => $record) { //delete checked responses
					if ($record['delete']) {
						$model->delete($record['id']);
						unset($records[$modelName][$i]);
					}
				}
			}
		}
		return $records;
	}

	/**
	 * Creates a link to this site, using controller and action passed.
	 * @param unknown_type $controller
	 * @param unknown_type $action
	 * @param unknown_type $id
	 * @param unknown_type $fromApproot if this is set, the domain name is not included in the link url
	 * @return string
	 */
	public function constructSiteLink($controller, $action = '', $id = null, $fromApproot = false) {
		if ($fromApproot) {
			$root = Configure::read('APPROOT');
		} else {
			$root = 'http://' . env('HTTP_HOST') . Configure::read('APPROOT');
		}
		$url = $root . Inflector::lcfirst($controller) . '/' . $action;
		if ($id !== null) {
			$url .= '/' . $id;
		}

		return $url;
	}

	function like() {
		return $this->Like->like();
	}

	function hug() {
		return $this->Hug->hug();
	}


	/**
	 * Returns a list of trip.id => trip.name pairs.  If the user is
	 * an admin returns all, including not visible trips.  Only returns
	 * the main page trip if includeMainPage is true, in which case the main page appears at the top of the list.
	 * @return unknown
	 */
	public function tripList($includeMainPage = false, $includeInvisibleIfAdmin = true) {

		if ($includeMainPage) {
			$orderClause = array('is_main_page DESC', 'id DESC');
			$conditionsClause = array();
		} else {
			$orderClause = array('id DESC');
			$conditionsClause = array('is_main_page' => 0);
		}

		if ($this->isAdmin() && $includeInvisibleIfAdmin) {
			$list = $this->Trip->find('list', array(
					'fields' => array('Trip.id', 'Trip.display_name'),
					'order' => $orderClause,
					'recursive' => -1,
					'conditions' => $conditionsClause)
			);
		} else {
			$list = $this->Trip->find('list', array(
				'fields' => array('Trip.id', 'Trip.display_name'),
				'order' => $orderClause,
				'recursive' => -1,
				'conditions' => $conditionsClause + array('is_visible' => 1)
			));
		}
		return $list;
	}

	/**
	 * Used for header navigation menus.
	 * @return unknown
	 */
	public function locationList() {
		$conditionsClause = array();
		if (!$this->isAdmin()) {
			$conditionsClause += array('is_visible' => 1);
		}

		$list = $this->Location->find('all', array(
			'order' => 'Location.name ASC',
			'contain' => array('Trip' => array(
				'fields' => array('Trip.start_date', 'Trip.end_date', 'Trip.id', 'Trip.display_name'),
				'order' => array('Trip.start_date DESC'), //TODO: change to start_date DESC?
				'conditions' => $conditionsClause
			)),
			'conditions' => $conditionsClause
		));

		return $list;
	}

	/**
	 * If the array passed has keys of the form ''default_<field>', where <field> is a underscore or camel-cased field name,
	 * the value of key '<field>' in the array returned will be the value of 'default_<field>' if it is empty and not an array
	 * or '<field>' is unset.
	 * @param unknown_type $texts
	 * @return unknown
	 */
	public function setFieldToDefaultIfEmpty($texts) {

		foreach ($texts as $field => $value) {
			$field = Inflector::underscore($field);
			$parts = explode('_', $field);
			if ($parts[0] == 'default') {
				unset($parts[0]);
				$nonDefaultField = implode('_', $parts);

				if (array_key_exists($nonDefaultField, $texts)) {
					$nonDefaultValue = $texts[$nonDefaultField];
					if (empty($nonDefaultValue) && !is_array($nonDefaultValue)) {
						$texts[$nonDefaultField] = $value;
					}
				} else {
					$texts[$nonDefaultField] = $value;
				}
			}
		}

		return $texts;
	}
}
