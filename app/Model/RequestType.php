<?php
class RequestType extends AppModel {
	/*var $hasAndBelongsToMany = array(
			'Request' => array(
					'className' => 'Request',
					'joinTable' => 'requests_request_types',
					'foreignKey' => 'request_type_id',
					'associationForeignKey' => 'request_id',
					'unique' => true,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
					'deleteQuery' => '',
					'insertQuery' => ''
			));*/
	
	public $hasMany = array(
			'Request' => array(
					'className' => 'Request',
					'foreignKey' => 'request_type_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
}