<?php
class LoginsController extends AppController {

	var $uses = array('Login', 'Text');
	var $adminOnly = array('add','delete');
	var $paginate = array('Login' => array(
		'order' => 'date DESC',
		'limit' => 10));

	function index() {

		//to fix issue with production code not recognizing venue as a field.
		$this->paginate['Login']['fields'] = array_keys($this->Login->getColumnTypes());

		$logins = $this->paginate('Login');

		$logins = Set::extract('/Login/.', $logins);
		$this->set(compact('logins'));	
	}

	/**
	 * Called on admin login to record location and timezone information.
	 */
	function add() {

		$newLogin = $this->request->query;

		//make check-in-city and venue agree with new data from geolocation
		if ($city = $newLogin['city']) {
			$curLoc = $city;
			if ($country = $newLogin['country']) {
				$curLoc = $city . ', ' . $country;
				if ($region = $newLogin['region']) {
					$curLoc = $city . ', ' .$region.', ' . $country;
				}
			}

			$this->Text->id = $this->Text->field('id', array('name' => 'check_in_city'));
			$this->Text->save(array('value' => $curLoc));

			$mostRecent = $this->Login->findMostRecent(array('id', 'venue', 'city'));
			$mostRecent = $mostRecent['Login'];

			//assume venue is the same if the city has not changed and venue is empty.
			if (!isset($newLogin['venue']) && $newLogin['city'] == $mostRecent['city']) {
				$newLogin['venue'] = $mostRecent['venue'];
			}
			if (!empty($newLogin['venue'])) {
				$this->Text->id = $this->Text->field('id', array('name' => 'check_in_venue'));
				$this->Text->save(array('value' => $newLogin['venue']));
			}
		}

		//only update the date if location has not changed (same location means same venue, unless empty, then same location is same city).
		if ((!empty($newLogin['venue']) && $newLogin['venue'] == $mostRecent['venue']) || (empty($newLogin['venue']) && $newLogin['city'] == $mostRecent['city'])) {
			$mostRecent['date'] = date(Configure::read('DB_DATE_FORMAT'));
			$this->Login->save($mostRecent, true, array_keys($mostRecent));
		} else {
			if ($this->Login->save($newLogin, true, array_keys($newLogin))) {
				$this->Session->setFlash('Your login information was saved.');
				$this->Session->write('Login.isLoginSaved', true); //on success
			} else {
				$this->Session->setFlash('Your login information could not be saved.');	//TODO: make this be a counter to limit retries.
			}
		}

		$this->redirect('/trips/index/'.$this->Text->findHomepageTripId());
	}

	/**
	 * Delete via ajax.
	 * @return string
	 */
	public function delete() {
		$this->prepAjax();

		$id = $this->request->query['id'];

		if ($this->Login->delete(array('id' => $id))) {
			$this->Session->setFlash('Your Login has been deleted.');
			return json_encode(array('success' => true));
		} else {
			$this->Session->setFlash('Your Login could not be deleted.  Please try again.');
			return json_encode(array('success' => false));
		}
	}

}