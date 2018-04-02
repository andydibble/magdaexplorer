<?php
class Location extends AppModel {
	
	public $hasMany = array(
			'Trip' => array(
					'className' => 'Trip',
					'foreignKey' => 'location_id',
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
	
	/**
	 * Gets the most recently created trip for this Location.
	 * If the auth user is an admin, that trip does not need to be visible
	 * @param unknown_type $id
	 * @param unknown_type $isVisible
	 */
	function latestTripId($id, $mustBeVisible=null) {
		if ($mustBeVisible === null) {
			$mustBeVisible = !(CakeSession::read('isAdmin'));
		}
				
		if ($mustBeVisible) {
			$conds = array('location_id' => $id, 'is_visible' => 1);
		} else {
			$conds = array('location_id' => $id);			
		}
		return $this->Trip->field('Trip.id', $conds, array('Trip.start_date DESC'));
	}
	
	function tripIds($id) {
		return $this->Trip->find('list', array(
			'fields' => array('Trip.id'),
			'conditions' => array('location_id' => $id),
			'order' => array('Trip.start_date DESC')
		));
	}
	
	/**
	 * Find list of locations that are visible and have at least one visible trip
	 * @return multitype:
	 */
	function findUserAccessibleLocationList() {
		$locs = $this->Trip->find('all', array(
				'conditions' => array('Location.is_visible' => 1, 'Trip.is_visible' => 1),
				'fields' => array('location_id', 'Location.name'),
				'contain' => array('Location.is_visible', 'Location.name')
		));
		$locIds = Set::extract('/Trip/location_id', $locs);
		$locNames = Set::extract('/Location/name', $locs);
		$locs = array_combine($locIds, $locNames);
		return $locs;
	}
}