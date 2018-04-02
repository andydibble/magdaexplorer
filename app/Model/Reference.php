<?php
class Reference extends AppModel {
	
	public $validate = array(			
			'url' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please enter Link url or upload a Document for this Reference.',
							'allowEmpty' => false,
							//'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
			)
	);
	
	public function afterFind($results, $primary=false) {
		return $this->formatDates($results, $primary, 'Reference', 'date_created');
	}
}