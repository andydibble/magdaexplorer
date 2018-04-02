<?php
class Text extends AppModel {
	
	/**
	 * Uses the homepage_location id value to find the first trip id for that location (i.e. the id for the first page of the location)
	 * If the homepage_location id is for a location that is now invisible, the homepageTripId is for the Urbanwatermelon trip.
	 */
	public function findHomepageTripId() {
		$locId = $this->field('value', array('name' => 'homepage_location'));
				
		$locModel = ClassRegistry::init('Location');
		
		if ($locModel->field('is_visible', array('id' => $locId))) {
			return $locModel->latestTripId($locId);
		} else {
			return Configure::read('URBAN_WATERMELON_TRIP_ID');
		}				
	}
	
	
}