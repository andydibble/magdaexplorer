<?php
class LocationsController extends AppController {
	
	public $helpers = array('Html', 'Session');
	
	var $paginate = array(			
	);
	
	var $components = array('Auth', 'Image', 'HeaderBackgroundImage', 'Layout', 'Hug', 'Visit');
	
	var $adminOnly = array('add', 'edit', 'delete');
	
	var $formInputs = array(
			'is_visible' => array('type' => 'checkbox', 'default' => 0),					
			'name' =>  array('type' => 'text'),
			'square_header_field' =>  array('type' => 'text'),
			'rectangle_header_field' =>  array('type' => 'text'),
			'message' =>  array('type' => 'textarea'),					
			'hugs_term' =>  array('type' => 'text', 'label' => 'Hugs Term (use singular form, e.g. "Hug")')
			//'header_background_image' =>  array('type' => 'file', 'label' => 'Header Background Image (.jpg, .png, or .gif)'),
			//'crop_banner_image' => array('type' => 'checkbox', 'label' => 'Crop Image equally on all sides to fit banner dimensions (i.e. "zoom in" on image center)?', 'default' => 1)
	);
	
	public function beforeRender() {
		parent::beforeRender();

		$this->set('formInputs', $this->Layout->formInputs());
	}
	
	public function index($id) {
		
		if ($this->isAdmin()) {
			$latestTrip = $this->Location->latestTripId($id, false);			
		} else {
			$latestTrip = $this->Location->latestTripId($id, true);
		}
		
		$this->redirect('/trips/index/'.$latestTrip);
		
	}
	
	public function add() {
	
		if ($this->data) {
				
			$this->HeaderBackgroundImage->upload($this->data);
	
			if (!$this->Session->isFlashSet()) {
				if ($this->Location->save($this->data)) {
					$this->Session->setFlash('Your new Location was saved.');
					$this->redirect('/trips/add/'.$this->Location->id);
				} else {
					$this->Session->setFlash('Your new Location could not be saved. Please try again.');
				}
			}
		}
	}
	
	public function edit($id) {
	
		$loc = $this->Location->find('first', array(
				'conditions' => array('Location.id' => $id),
				'contain' => array()
		));
			
		if($this->data) {
			$loc = $this->data;
							
			if ($this->Location->save($loc)) {
				$this->Session->setFlash('Your Location has been updated.');
				$this->redirect('/trips/index/'.$this->Location->latestTripId($id));
			} else {
				$this->Session->setFlash('Your Location could not be updated. Please try again.');
			}			
		}
		
	
		$this->set('loc', $loc);
		$this->request->data = $loc;
	}
	
	/**
	 * Delete location and all child trips
	 * Photos on fs are not deleted.
	 * @param unknown_type $id
	 */
	public function delete($id) {
	
		if ($this->Location->delete(array('id' => $id))) {
				$this->Session->setFlash('Your Location has been deleted.');
		} else {
				$this->Session->setFlash('Your Location could not be deleted.  Please try again.');
		}
			
		$this->redirect('/trips');
	}
	
	/**
	 * Change the layout field homepage_location to the id passed.
	 * @param unknown_type $id
	 */
	public function setAsHomepage($id) {
		if ($this->Text->updateAll(
				array('value' => $id),
				array('name' => 'homepage_location')
			)) {
			$this->Session->setFlash('This location has become the homepage.');			
		} else {
			$this->Session->setFlash('The homepage could not be changed.  Please try again.');
		}
		$this->redirect('/locations/index/'.$id);
	}
	
	public function headerImageUrls() {
		$trips = $this->Trip->find('list', array('fields' => array('id', 'header_background_image')));
		foreach ($trips as $id => $url) {
			if ($url) {				
				$this->HeaderImage->save(array(
						'trip_id' => $id, 
						'filename' => $url));
			}
		}
	}
		
}