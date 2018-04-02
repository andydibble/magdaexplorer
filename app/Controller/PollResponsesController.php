<?php
class PollResponsesController extends AppController {
	
	function respond($tripId) {
		if($this->data) {
			$response = $this->data;
			$response['PollResponse']['trip_id'] = $tripId;
			if($response['PollResponse']['value']) {	//only save non-empty responses.
				if($this->PollResponse->save($response)) {
					$this->Session->setFlash('Your response has been saved.');
					$this->Session->write('LoadOpen', 'poll-toggler');		//open the poll drop down on next page load.								
				} else {
					$this->Session->setFlash('Your response could not be saved.  Please try again.');
				}
			}
		}
		$this->redirect('/trips/index/'.$tripId);
	}
	
	public function edit($tripId) {
	
		if ($this->data) {
			$resps = $this->data;
			$resps = $this->deleteIfSelected($resps, $this->PollResponse);
			if(empty($resps) || $this->PollResponse->saveAll($resps)) {
				$this->Session->setFlash('Your changes were saved.');
				$this->redirect('/trips/index/'.$tripId);
			} else {
				$this->Session->setFlash('Your changes could not be saved. Please try agan.');
			}			
		}
		$resps = $this->PollResponse->find('all', array('conditions' => array('trip_id' => $tripId)));
		$this->request->data = $resps;
		$this->set('tripId', $tripId);
		$this->set('resps', $resps);
	}
}