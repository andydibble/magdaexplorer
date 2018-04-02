<?php
class Photo extends AppModel {
	
	public $belongsTo = array(
		'Adventure' => array(
				'className' => 'Adventure',
				'foreignKey' => 'adventure_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		)
	);
}