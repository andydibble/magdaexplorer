<?php
class Scan extends AppModel {
	
	public $validate = array(
		
	);
	
	
	public $belongsTo = array(
			'Article' => array(
					'className' => 'Article',
					'foreignKey' => 'article_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);

}