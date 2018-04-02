<?php

class Trip extends AppModel {

	public $validate = array(
		//can now use date range alternatively as name
		/*'name' => array(
				'notempty' => array(
						'rule' => array('notempty'),
						'message' => 'Please enter a name.',
						'allowEmpty' => false,
						//'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
		)*/
	);


	public $hasMany = array(
		'Adventure' => array(
			'className' => 'Adventure',
			'foreignKey' => 'trip_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $hasOne = array(
		'HeaderImage' => array(
			'className' => 'HeaderImage',
			'foreignKey' => 'trip_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	public $belongsTo = array(
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/* If urbanwatermelon or all adventures, do not display date.  Display date after name, if name is given.  Otherwise, display only date */
	public $virtualFields = array(
		'display_name' =>
			"CASE 
				WHEN `Trip`.`id` IN (3,4)
					THEN `Trip`.`name`
				WHEN `Trip`.`name` IS NOT NULL AND `Trip`.`name` != ''
					THEN CONCAT(`Trip`.`name`, ' (', DATE_FORMAT(`start_date`, '%m/%d/%y'), ')')  				 
					ELSE DATE_FORMAT(`start_date`, '%m/%d/%y') 
			END"
	);


	public function isVisible($id) {
		return $this->field('is_visible', array('id' => $id));
	}

	public function isMainPage($id) {
		return $this->field('is_main_page', array('id' => $id));
	}

	public function isUrbanWatermelonPageId($id) {
		return $this->field('is_urban_watermelon', array('id' => $id));
	}

	public function findMainPageId() {
		return $this->field('id', array('is_main_page' => 1));
	}

	/**
	 * By convention, the visit number for the main page / all adventures page has the total number of visitors,
	 * so this method increments the number of visitors for the main page trip.
	 */
	public function visitSite() {

		if (!CakeSession::check('Trip.siteVisted')) {
			CakeSession::write('Trip.siteVisted', 1);
			$this->updateAll(
				array('Trip.visits' => 'Trip.visits+1'),
				array('Trip.is_main_page' => 1)
			);
		}
	}

	/**
	 * Updates posts count fields for both Trip and Location
	 * @param unknown_type $tripId
	 */
	function updatePostCount($tripId) {
		$tripPostCount = $this->Adventure->find('count', array(
			'conditions' => array('trip_id' => $tripId)
		));

		$this->save(array(
			'id' => $tripId,
			'posts' => $tripPostCount
		));

		$locId = $this->field('location_id', array('id' => $tripId));

		$locPostCount = $this->field('SUM(`Trip`.`posts`)', array('location_id' => $locId));

		$this->Location->save(array(
			'id' => $locId,
			'posts' => $locPostCount
		));

		$allAdvsTripId = $this->findMainPageId();

		$allAdvsPostCount = $this->field('SUM(`Trip`.`posts`)', array('`Trip`.`id` !=' => $allAdvsTripId));

		$this->save(array(
			'id' => $allAdvsTripId,
			'posts' => $allAdvsPostCount
		));

	}

	/**
	 * Updates photos count fields for both Trip and Location
	 * @param unknown_type $tripId
	 */
	function updatePhotoCount($tripId) {

		$tripPhotoCount = $this->Adventure->Photo->find('count', array(
			'conditions' => array('Adventure.trip_id' => $tripId),
			'contain' => array('Adventure.trip_id')
		));

		$this->save(array(
			'id' => $tripId,
			'photos' => $tripPhotoCount
		));

		$locId = $this->field('location_id', array('id' => $tripId));

		$locPhotoCount = $this->field('SUM(`Trip`.`photos`)', array('location_id' => $locId));

		$this->Location->save(array(
			'id' => $locId,
			'photos' => $locPhotoCount
		));

		$allAdvsTripId = $this->findMainPageId();

		$allAdvsPhotoCount = $this->field('SUM(`Trip`.`photos`)', array('`Trip`.`id` !=' => $allAdvsTripId));

		$this->save(array(
			'id' => $allAdvsTripId,
			'photos' => $allAdvsPhotoCount
		));

	}

	public function afterFind($results, $primary = false) {

		$results = $this->formatDates($results, $primary, 'Trip', array('start_date', 'end_date'));
		return $results;
	}

	public function afterSave($created) {

		//make sure that Location  is visible if Trip is now visible
		$new = $this->find('first', array(
				'contain' => array('Location.id', 'Location.is_visible'),
				'fields' => array('Trip.is_visible'),
				'conditions' => array('Trip.id' => $this->id)
			)
		);

		if (!$new['Location']['is_visible'] && $new['Trip']['is_visible']) {

			$this->Location->save(array(
				'id' => $new['Location']['id'],
				'is_visible' => 1
			));
		}
	}

	public function displayName($trip) {
		if ($trip['name']) {
			return "{$trip['name']} ({$trip['start_date']})";
		} else {
			return $trip['start_date'];
		}
	}

	/**
	 * Sets end_date to $date if $date is after end_date
	 * @param unknown_type $id
	 * @param unknown_type $date
	 */
	function updateEndDate($id, $date) {
		$curDate = $this->field('end_date', array('Trip.id' => $id));
		if ($curDate < $date) {
			$this->save(array('id' => $id, 'end_date' => $date));
		}
	}
}