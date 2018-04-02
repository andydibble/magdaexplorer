<?php
class TagsController extends AppController {
	
	var $adminOnly = array('edit');
	
	var $paginate = array(
			'Tag'=>array(										
				'limit' => 100,
				'order' => 'Tag.name ASC'
			)
	);
	
	public function autocomplete()
	{
		$this->prepAjax();
	
		$searchTerm = $this->request->query['term'];		
					
		$tags = $this->Tag->find('all',	array(
				'fields' => array('DISTINCT(Tag.name) as label'),
				'conditions' => array('Tag.name LIKE' => $searchTerm."%")));
		return json_encode(Set::extract('/Tag/.', $tags));
	}
	
	public function edit($forModel, $modelId) {
		
		if ($tags = $this->data) {								
			$tags = $this->deleteIfSelected($tags, $this->Tag);
			if(empty($tags) || $this->Tag->saveAll($tags)) {
				$this->Session->setFlash('Your changes were saved.');
				if ($forModel == 'Trip') {
					$this->redirect('/trips/index/'.$modelId);
				} else {
					$this->redirect('/magazines/view/'.$modelId);
				}
				
			} else {
				$this->Session->setFlash('Your changes could not be saved. Please try agan.');
			}						
		}

		
		$this->loadModel($forModel);
		$tripOrMagName = $this->{$forModel}->field('name', array("$forModel.id" => $modelId));
		
		if ($forModel == 'Trip') {			
			if (!$this->Trip->isMainPage($modelId)) {
				$tags = $this->paginate('Tag', array('Trip.id' => $modelId));
			} else {			
				$this->loadModel('AdventuresTag');
				$allAdvTagIds = $this->AdventuresTag->find('list', array('fields' => 'tag_id'));
				$tags = $this->paginate('Tag', array('Tag.id' => $allAdvTagIds));
			}
		} else if ($forModel == 'Magazine') {
			$tags = $this->paginate('Tag', array('Magazine.id' => $modelId));
		}
		
		$this->set('tags', $tags);
		$this->set('tripOrMagName', $tripOrMagName);
					
	}
}