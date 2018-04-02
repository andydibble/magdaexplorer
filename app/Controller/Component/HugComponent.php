<?php
class HugComponent extends Object {
	
	function initialize(&$controller, $settings= array())
	{
	
	}
	
	function startup(&$controller){
		$this->controller =& $controller;
	}
	
	function beforeRender(&$controller)
	{
	
	}
	
	function shutdown(&$controller)
	{
	
	}
	
	function beforeRedirect(&$controller, $url, $status= null, $exit=true )
	{
	
	}
	
	/**
	 * Increments the hugs field for a trip or location
	 * @param unknown_type $tripId
	 */
	public function hug() {
		$this->controller->prepAjax();
	
		$modelName = Inflector::singularize($this->controller->name);
		$success = false;
		if ($id = $this->controller->request->query['id']) {
	
			if ($this->controller->{$modelName}->increment('hugs', $id)) {
				$success =  true;
			}
		}
		return json_encode(array('success' => $success, 'model' => $modelName));
	}
}