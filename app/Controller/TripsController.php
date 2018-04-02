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

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TripsController extends AppController {

	var $uses = array('Trip', 'Adventure', 'Text', 'Statistic', 'Photo', 'PollResponse', 'KnownEmail', 'Tag', 'Login', 'Comment', 'Location', 'HeaderImage');
	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Trips';

	/**
	 * Default helper
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Session');

	var $paginate = array(
		'Adventure' => array(
			'group' => 'Adventure.id',
			'order' => 'Adventure.date DESC, Adventure.id DESC',
			'limit' => 15,
			'maxLimit' => 170)
	);

	var $components = array('Image', 'Hug', 'Visit', 'Layout');

	var $adminOnly = array('add', 'edit', 'delete');

	var $formInputs = array(
		'is_visible' => array('type' => 'checkbox', 'default' => 0),
		'name' => array('type' => 'text', 'label' => 'Name (Start Date will replace Name, if Name is empty)'),
		'start_date' => array('type' => 'date'),
		'end_date' => array('type' => 'date', 'label' => 'End Date (will be updated on Adventure add and edit)'),
		'square_header_field' => array('type' => 'text'),
		'rectangle_header_field' => array('type' => 'text'),
		'header_text_color' => array('type' => 'color', 'default' => '#ffffff'),
		'message' => array('type' => 'textarea'),
		'poll_prompt' => array('type' => 'text'),
		'poll_prompt_caption' => array('type' => 'text'),
		'hugs_term' => array('type' => 'text', 'label' => 'Hugs Term (use singular form, e.g. "Hug")')
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('edit');

		$c = $this->Auth;


		//only Urban watermelon is accessible without authenticating.
		if (isset($this->params['pass']['0']) && is_numeric($this->params['pass']['0'])) {
			$id = $this->params['pass']['0'];
			if ($this->Trip->isUrbanWatermelonPageId($id)) {
				$this->Auth->allow('index');
				$this->Trip->visitSite(); //visit here because of users redirected from urbanwatermelon.com (this method prevents duplication of visit).
			}
		}

	}

	/**
	 * Get model data for the main site page: layout fields, statistics, paginated adventures,
	 * banner photos, poll responses, tags, explorer location info (from most recent ADMIN login),
	 * time/temp
	 * @param unknown_type $searchTerm
	 */
	public function index($id = null, $searchTerm = null) {

		if ($id == null) {
			$id = $this->Text->findHomepageTripId();
		} else {
			if (!$this->Trip->isVisible($id)) {
				//prevent non-admin from seeing hidden trips or unauth guest from seeing trips they can't see.
				if (!$this->isAdmin()) {
					$this->redirect($this->referer());
				} else {
					$this->Session->setFlash('This Trip is invisible to Users.  Set it to Visible in Edit Trip to change this.');
				}
			}
		}
		//convert request param to searchTerm action param.
		if (!empty($this->request->query['searchTerm'])) {
			$searchTerm = $this->request->query['searchTerm'];
		}

		$this->Visit->visit($id, array('is_main_page' => 0), 'Trip'); //will only mark as visited if user is non-admin and not the main page / all advs page

		$trip = $this->Trip->find('first', array(
			'conditions' => array('Trip.id' => $id),
			'recursive' => -1
		));
		$trip = $trip['Trip'];

		$locId = $trip['location_id'];

		$this->Visit->visit($locId, array(), 'Location');

		$loc = $this->Location->find('first', array(
			'conditions' => array('Location.id' => $locId),
			'recursive' => -1
		));
		$loc = $loc['Location'];
		$locationName = $loc['name'];
		$this->set('title_for_layout', $locationName);

		$isLocationPage = false;
		if ($id == $this->Location->latestTripId($locId) && !$loc['is_dummy_location']) {
			$isLocationPage = true;
			$this->set('location', $loc);
		}

		$this->Session->write('Trip.mostRecent', $trip);

		//restricts find results to a certain trip or location (unset for main page).
		$restictByTripIfNotAllAdvs = array();
		$isAllAdvs = true;
		if (!$trip['is_main_page']) {
			$isAllAdvs = false;
			$restictByTripIfNotAllAdvs = array('trip_id' => $id);
		}
		//restricts which adventures display on basis of their visibility.
		$visibilityConds = array();
		if (!$this->isAdmin()) {
			$visibilityConds['Adventure.is_visible'] = 1;
		}

		//assign stats for trip headers
		if ($isLocationPage) {
			$stats['Location']['posts'] = $loc['posts'];
			$stats['Location']['photos'] = $loc['photos'];
		}
		$stats['Trip']['posts'] = $trip['posts'];
		$stats['Trip']['photos'] = $trip['photos'];

		$paginateAdvConds = $restictByTripIfNotAllAdvs + $visibilityConds;
		if (strpos($searchTerm, 'adv') !== false) { //paginate to particular adventure.
			$advId = ltrim($searchTerm, 'adv'); //TODO: find a better way to find which page to paginate to?
			$advsPerPage = $this->paginate['Adventure']['limit'];
			$advIds = $this->Adventure->find('list', array(
				'fields' => array('id'),
				'order' => $this->paginate['Adventure']['order'],
				'conditions' => $paginateAdvConds,
			));

			$advIds = array_merge($advIds); //reorder indices.
			$advIndex = array_search($advId, $advIds);
			$page = ceil(($advIndex + 1) / $advsPerPage);

			$this->paginate['Adventure']['page'] = $page;

		} elseif (strpos($searchTerm, 'tag') !== false) { //paginate by tag id.

			$tagId = ltrim($searchTerm, 'tag');
			//paginate based upon HABTM
			$this->Adventure->bindModel(array('hasOne' => array('AdventuresTag')), false);

			$paginateAdvConds['AdventuresTag.tag_id'] = $tagId;

			$this->set('searchPerformed', true);
		} elseif (!empty($this->request->query['searchTerm'])) {
			$searchTerm = $this->request->query['searchTerm'];

			unset($paginateAdvConds['trip_id']);
			$paginateAdvConds['OR'] = array(
				'Adventure.title LIKE' => "%{$searchTerm}%",
				'Adventure.story LIKE' => "%{$searchTerm}%"
			);
			$this->set('searchPerformed', true);
		}
		$adventures = $this->paginate('Adventure', $paginateAdvConds);

		$tripNavIds = $this->Trip->find('neighbors', array(
			'field' => 'id',
			'value' => $id,
			'fields' => array('Trip.id', 'Trip.name'),
			'conditions' => array('location_id' => $locId),
			'recursive' => -1,
			'order' => array('location_id', 'Trip.id DESC')
		));

		$photos = $this->findBannerPhotos($id, $isLocationPage, $locId, $isAllAdvs, $visibilityConds);

		if ($isLocationPage || $isAllAdvs) {
			$advTitles = $this->Trip->find('all', array(
				'contain' => array('Adventure.id', 'Adventure.title'),
				'fields' => array('Trip.display_name', 'Trip.id'),
				'order' => array('Trip.start_date DESC' => array('Adventure.date DESC')),
				'conditions' => $isAllAdvs ? array() : array('location_id' => $locId)
			));
		} else {
			$advTitles = $this->Adventure->find('list', array(
				'fields' => array('id', 'title'),
				'order' => array('date DESC'),
				'conditions' => $restictByTripIfNotAllAdvs
			));
		}

		if ($trip['poll_prompt'] != '') {
			$resps = $this->PollResponse->find('list', array(
				'order' => 'id DESC',
				'fields' => array('value'),
				'conditions' => array('trip_id' => $id) //the main page still has its own poll, potentially
			));
			$resps = array_merge($resps); //reorder indices;
		} else {
			$resps = array();
		}

		if (!$isAllAdvs) {
			$tags = $this->Tag->findTagsByLocationId($locId);
		} else {
			$tags = $this->Adventure->AdventuresTag->findAllAdvTags();
		}


		//if a load open field is set in the session, open the div indicated in that field
		if ($this->Session->check('LoadOpen')) {
			$loadOpen = json_encode($this->Session->read('LoadOpen'));
			$this->Session->delete('LoadOpen');
		} else {
			$loadOpen = 0;
		}

		if ($this->Session->check('Trip.message')) {
			$onloadMessage = $this->Session->read('Trip.message');
			$this->Session->delete('Trip.message');
		} else {
			$onloadMessage = '';
		}

		unset($this->KnownEmail->validate);
		$this->set(compact('stats', 'adventures', 'photos', 'resps', 'loadOpen', 'sendEmails', 'sendAdvTitle', 'tags', 'localTime', 'advTitles', 'tripNavIds', 'locationName', 'onloadMessage'));

	}

	public function add($forLocId = 0) {

		if ($this->data) {
			$trip = $this->data;

			if (!$trip['HeaderImage']['id']) { //avoid insertion of blank records.
				unset($trip['HeaderImage']);
			} else { //manual crop.
				$cropY = $trip['HeaderImage']['crop_y'];
				$fname = $this->HeaderImage->field('filename', array('id' => $trip['HeaderImage']['id']));

				$fpath = $this->Image->removeCroppedSuffix($this->Image->HEADER_BKGR_DIR . '/' . $fname);
				$fnameForCroppedFile = $this->Image->addCroppedSuffix($fname);
				$fpathForCroppedFile = $this->Image->HEADER_BKGR_DIR . '/' . $fnameForCroppedFile;

				list($cropWidth, $cropHeight, $cropRatio) = $this->Image->headerImageCropDimensions($fname);

				if (copy($fpath, $fpathForCroppedFile)) {
					$this->Image->crop($fpathForCroppedFile, 0, $cropY, $cropWidth, $cropHeight);
				}
			}

			if (!$this->Session->isFlashSet()) {

				if ($this->Trip->saveAll($trip)) {
					$this->Session->setFlash('Your new Trip was saved.');
					$this->redirect('/trips/index/' . $this->Trip->id);
				} else {
					$this->Session->setFlash('Your new Trip could not be saved. Please try again.');
				}
			}
		}

		$this->set('forLocId', $forLocId);
	}

	public function beforeRender() {
		parent::beforeRender();

		$action = $this->request->params['action'];
		if (in_array($action, array('add', 'edit'))) {

			$locOptions = $this->Location->find('list', array('conditions' => array('is_dummy_location' => 0)));
			$this->set('locOptions', $locOptions);

			$this->set('formInputs', $this->Layout->formInputs());
		}
	}


	public function edit($id) {
		$trip = $this->Trip->find('first', array(
			'conditions' => array('Trip.id' => $id),
			'contain' => array('Location.is_dummy_location', 'HeaderImage') //for hiding location changing
		));

		if (!empty($trip['HeaderImage']['filename'])) { //has header image already
			//put the full path in the header image field.
			$fname = $trip['HeaderImage']['filename'];
			$headerBackgroundImgSrc = $this->Image->headerBackgroundImgSrc($fname, true);

			list($cropWidth, $cropHeight, $cropRatio) = $this->Image->headerImageCropDimensions($fname);
		}


		if ($this->data) {
			$trip = $this->data;

			if (!$this->Session->isFlashSet()) {

				//for manually cropping an already uploaded image.							
				if (!empty($trip['HeaderImage']['id'])) {
					$cropY = $trip['HeaderImage']['crop_y'];
					$fname = $this->HeaderImage->field('filename', array('id' => $trip['HeaderImage']['id']));
					$trip['HeaderImage']['filename'] = $fname;

					$headerBackgroundImgSrc = $this->Image->headerBackgroundImgSrc($fname, true);

					$fpath = $this->Image->removeCroppedSuffix($this->Image->HEADER_BKGR_DIR . '/' . $fname);

					list($cropWidth, $cropHeight, $cropRatio) = $this->Image->headerImageCropDimensions($fname);

					$fnameForCroppedFile = $this->Image->addCroppedSuffix($fname);
					//can assume this already exists  because a cropped version is already uploaded.
					$fpathForCroppedFile = $this->Image->HEADER_BKGR_DIR . '/' . $fnameForCroppedFile;

					if (copy($fpath, $fpathForCroppedFile)) {
						$this->Image->crop($fpathForCroppedFile, 0, $cropY, $cropWidth, $cropHeight);
					}
				}

				//avoid insertion of blank records.
				if (!$trip['HeaderImage']['id']) {
					unset($trip['HeaderImage']);
				}

				if (!empty($result['errMessage'])) {
					$this->Session->setFlash($result['errMessage']);
				} else {
					if ($this->Trip->saveAll($trip)) {
						$this->Session->setFlash('Your Trip has been updated.');
						$this->redirect('/trips/index/' . $id);
					} else {
						$this->Session->setFlash('Your Trip could not be updated. Please try again.');
					}
				}
			}
		}


		if (!empty($trip['HeaderImage']['filename'])) {
			$trip['HeaderImage']['url'] = $headerBackgroundImgSrc;
			$trip['HeaderImage']['crop_height'] = $cropHeight;
			$trip['HeaderImage']['crop_ratio'] = $cropRatio;
		}

		$this->set('trip', $trip);
		$this->request->data = $trip;
	}

	/**
	 * Delete trip and all child Adventures..  Tags are only deleted if they refer to no other adventures.
	 * Photos on fs are not deleted.
	 * @param unknown_type $id
	 */
	public function delete($id) {

		if ($this->Trip->isMainPage($id)) {
			$this->Session->setFlash('You cannot delete the All Adventrues Trip.  Please remove that status from this Trip before deleting it.');
			$this->redirect($this->referer());
		} else {
			if ($this->Trip->delete(array('id' => $id))) {
				$this->Session->setFlash('Your Trip has been deleted.');
			} else {
				$this->Session->setFlash('Your Trip could not be deleted.  Please try again.');
			}
		}

		if ($id == $this->Text->findHomepageTripId()) {
			$this->Text->updateAll(
				array('value' => Configure::read('URBAN_WATERMELON_TRIP_ID')),
				array('name' => 'homepage_trip')
			);
		}

		$this->redirect('/trips');
	}

	private function findBannerPhotos($tripId, $isLocationPage, $locId, $isAllAdvs, $visibilityConds) {


		if ($isLocationPage) {
			$tripIds = $this->Location->tripIds($locId);
		} else {
			$tripIds = array($tripId);
		}

		if ($isAllAdvs) {
			$conds = $visibilityConds;
		} else {
			$conds = array('Adventure.trip_id' => $tripIds) + $visibilityConds;
		}

		$photos = $this->Photo->find('all', array(
			'fields' => array('Photo.id', 'Photo.title', 'width', 'height', 'adventure_id', 'likes', 'filename'),
			'contain' => array('Adventure.id', 'Adventure.title', 'Adventure.trip_id', 'Adventure.date' => 'Trip.location_id'),
			'order' => array('Adventure.date DESC', 'Photo.adventure_id DESC', 'Photo.id ASC'),
			'conditions' => $conds,
			'limit' => 50
		));

		return $photos;
	}


	public function updateStats() {
		$ids = $this->Trip->find('all', array('fields' => array('Trip.id', 'Trip.visits', 'location_id')));
		foreach ($ids as $id) {
			$this->Location->save(array(
				'id' => $id['Trip']['location_id'],
				'visits' => $id['Trip']['visits']
			));
		}
	}

	public function updateDates() {
		$ids = $this->Trip->find('list', array('fields' => array('id')));
		foreach ($ids as $id) {
			$startDate = $this->Adventure->field('date', array('trip_id' => $id), array('date ASC'));

			$endDate = $this->Adventure->field('date', array('trip_id' => $id), array('date DESC'));

			$this->Trip->save(array(
				'id' => $id,
				'start_date' => $startDate,
				'end_date' => $endDate
			));
		}
	}

	function initCounts() {
		$tripIds = $this->Trip->find('list', array('fields' => 'Trip.id'));
		foreach ($tripIds as $id) {
			if ($id != $this->findMainTripId()) {
				$this->Trip->updatePhotoCount($id);
				$this->Trip->updatePostCount($id);
			}
		}
	}
}
