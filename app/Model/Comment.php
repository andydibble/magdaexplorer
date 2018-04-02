<?php
class Comment extends AppModel {
	
	public $validate = array();

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Adventure' => array(
			'className' => 'Adventure',
			'foreignKey' => 'adventure_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)		
	);

	public function afterFind($results, $primary=false) {
		return $this->formatDates($results, $primary, 'Comment', 'date_created');
	}
}

