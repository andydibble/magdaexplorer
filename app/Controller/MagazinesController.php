<?php
class MagazinesController extends AppController {

	var $uses = array('Magazine', 'Article');
	var $adminOnly=array('add', 'edit', 'delete', 'index');
	
	function add() {
		
		if ($mag = $this->data) {			
			if ($this->Magazine->save($mag)) {
				$this->Session->setFlash('Your new magazine has been saved.');
				$this->redirect('/magazines/view/'.$this->Magazine->id);
			} else {
				$this->Session->setFlash('Your magazine could not be saved.  Please try again.');
			}
		}
	}
	
	function edit($id) {
		if ($mag = $this->data) {
			if ($this->Magazine->save($mag)) {				
				
				//delete this magazine if all articles in it were merged into another mag.
				if (($mergeToMagId = $mag['Magazine']['merge_to']) && $this->Magazine->delete($mag['Magazine']['id'])) {
					$this->Session->setFlash('Your magazine has been merged.');
					$this->redirect('/magazines/view/'.$mergeToMagId);
				}	
											
				$this->Session->setFlash('Your magazine has been saved.');
				$this->redirect('/magazines/view/'.$this->Magazine->id);
			} else {
				$this->Session->setFlash('Your magazine could not be saved.  Please try again.');
			}
		}
		
		$mags = $this->Magazine->find('list', array('conditions' => array('id !=' => $id)));
		$this->set('mags', $mags);
		
		$this->request->data = $this->Magazine->find('first', array(
				'recursive' => -1,
				'conditions' => array('id' => $id)
		));
		
		
	}
	
	function index() {
		if ($this->isAdmin()) {
			$mags = $this->Magazine->find('list');
		} else {
			$mags = $this->Magazine->find('list', array('conditions' => array('is_visible' => true)));
		}
		$this->set('mags', $mags);
	}
	
	function view($id) {
		$magName = $this->Magazine->field('name', array('id' => $id));
		
		$conds = array('magazine_id' => $id);
		if (!$this->isAdmin()) {
			$conds += array('is_visible' => true);			
		}
			
		$arts = $this->Article->find('all', array(
			'conditions' => $conds,
			'order' => array('published DESC'),
			'fields' => array('id', 'name', 'published', 'published_display', 'published_year'),
			'contain' => array()
		));
		
		$tags = $this->Article->Tag->findTagsByMagId($id);		
				
		$this->set(compact('arts', 'magName', 'tags'));				
		$this->set('magId', $id);
	}
	
	function delete($id) {
		if ($this->Magazine->delete($id)) {
			$this->Session->setFlash('Your magazine was deleted');
			$this->redirect('/magazines');
		} else {
			$this->Session->setFlash('Your magazine could not be deleted.  Please try again.');
		}
	}
}