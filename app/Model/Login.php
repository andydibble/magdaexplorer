<?php
class Login extends AppModel {
	
	public function afterFind($results, $primary=false) {
	
		$results = $this->formatDates($results, $primary, 'Login', 'date');
		return $results;
	}
	
	function findMostRecent($fields) {
		if (is_array($fields)) {
			return $this->find('first', array(
				'order' => 'id DESC',
				'fields' => $fields
			));
		} else {			
			return $this->field($fields, array(), array('id DESC'));			
		}
	}
}