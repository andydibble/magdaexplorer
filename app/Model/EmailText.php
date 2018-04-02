<?php
class EmailText extends AppModel {
	
	public $validate = array(
			'body' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please provide an email body.',
							'allowEmpty' => false,
							'required' => false							
					)
			),
			'subject' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please provide a subject.',
							'allowEmpty' => false,
							'required' => false
					)
			)
	);
	
	/**
	 * Converts the app fields (in bootstrap.php) that are relevant terms on the page
	 * into a map from key to value (keys are camel-cased.)
	 * @param unknown_type $camelize
	 * @return multitype:Ambigous <mixed, multitype:, NULL>
	 */
	function appFieldsArray($camelize=false) {
		$appFieldKeys = array('APP_NAME', 'APP_ABBR','CONTACT_EMAIL', 'HTTP_HOST');
	
		$appFields = array();
		foreach($appFieldKeys as $i => $key) {
			if($camelize) {
				$appFieldKeys[$i] = Inflector::lcfirst(Inflector::camelize(strtolower($key)));
			}
			$appFields[$appFieldKeys[$i]] = Configure::read($key);
		}
		return $appFields;
	}
	
	/**
	 * Given a key for an email template in `texts` table and an array of field names to value for the template fields, this method generates an email body.  
	 * Email fields produced by appFieldsArray() need not be provided.
	 * @param unknown_type $templKey
	 * @param unknown_type $emailFields
	 * @return mixed
	 */
	public function constructEmail($emailKey, $emailFields=array()) {
		$templ = $this->find('first', array(
				'conditions' => array($this->name.'.key' => $emailKey)));
		
		$appFields = $this->appFieldsArray(true);		
		$emailFields = array_merge($emailFields, $appFields);
		
		$body = $templ['EmailText']['body'];
		foreach($emailFields as $field => $value) {
			$body = str_replace('#'.$field.'#', $value, $body);
		}
		
		$subject = $templ['EmailText']['subject'];
		foreach($emailFields as $field => $value) {
			$subject = str_replace('#'.$field.'#', $value, $subject);
		}
			
		return array($body, $subject);
	}
}