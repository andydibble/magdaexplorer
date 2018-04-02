<?php
class ReferencesController extends AppController {
	
	var $components = array('Document');
	var $adminOnly = array('add', 'edit', 'delete');
	
	/**
	 * produces pagination sets of the two kinds of references: links and documents 
	 * (for the reference index page)
	 */
	function index($tripId) {
		
		$this->loadModel('Trip');
		
		if (!$this->Trip->isMainPage($tripId)) {
			$links = $this->paginate('Reference', array('type' => 'link', 'trip_id' => $tripId));
			$docs = $this->paginate('Reference', array('type' => 'document', 'trip_id' => $tripId));
		} else {
			$links = $this->paginate('Reference', array('type' => 'link'));
			$docs = $this->paginate('Reference', array('type' => 'document'));			
		}
		
		$this->set(compact('links', 'docs', 'tripId'));
	}
	
	/**
	 * Does not yet allow editing of the files in document references.
	 * @param unknown_type $id id of the refernece record
	 */
	function edit($id) {
		
		if ($this->data) {
			$ref = $this->data;
			$tripId = $ref['Reference']['trip_id'];
						
			if ($this->Reference->save($ref)) {
				$this->Session->setFlash('Your Reference was updated.');
				$this->redirect('/references/index/'.$tripId);
			} else {
				$this->Session->setFlash('Your Reference could not be updated.  Please try again.');
			}
		}
		$ref = $this->Reference->findById($id);		
		
		$ref = $ref['Reference'];
		$this->set('ref', $ref);		
	}
	
	public function beforeRender() {
		parent::beforeRender();
		if(in_array($this->request->params['action'], array('add', 'edit'))) {
			$trips = $this->tripList();
			$this->set('trips', $trips);
		}
	}
	
	function delete($id) {
		
		$tripId = $this->Reference->field('trip_id', array('id' => $id));
		if ($this->Reference->delete($id)) {
			$this->Session->setFlash('Your Reference was deleted.');
		} else {
			$this->Session->setFlash('Your Refrence could not be deleted.  Please try again.');
		}		
		$this->redirect('/references/index/'.$tripId);
	}
	
	/**
	 * Add either a document or link reference to references table
	 */
	function add() {
		if ($this->data) {
			$ref = $this->data;
			$tripId = $ref['Reference']['trip_id'];
										
			$ref['Reference']['date_created'] = date(Configure::read('DB_DATE_FORMAT'));			
			$isSaved = false;
			if (isset($this->data['Reference']['file'])) {  //reference is a document				
				
				$result = $this->Document->uploadFiles($this->Document->FILES_ROOT.'references', $ref['Reference']);
								
				if (!empty($result['errMessage'])) {
					$this->Session->setFlash($result['errMessage']);					
				} else {
					if (isset($result['urls'][0])) {
						if (empty($ref['Reference']['name'])) {
							$ref['Reference']['name'] = $result['successFilenames'][0];
						}
												
						$ref['Reference']['url'] = $result['urls'][0];
						$ref['Reference']['type'] = 'document';
						
						$isSaved = $this->Reference->save($ref);
					}
				}
			} else {	//referene is a link
				$ref['Reference']['type'] = 'link';
				
				if (empty($ref['Reference']['name'])) {
					$ref['Reference']['name'] = $ref['Reference']['url'];
				}
				
				$isSaved = $this->Reference->save($ref);
			}
			
			if ($isSaved) {
				$this->Session->setFlash('Your Reference was saved.');
				$this->redirect('/references/index/'.$tripId);
			} else if (!$this->Session->isFlashSet()) {
				$this->Session->setFlash('Your Reference could not be saved. Please try again.');
			}
		}			
	}
	
}