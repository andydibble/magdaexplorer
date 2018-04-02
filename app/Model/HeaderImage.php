<?php
class HeaderImage extends AppModel {
	
	public $belongsTo = array(
			'Trip' => array(
					'className' => 'Trip',
					'foreignKey' => 'trip_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
}