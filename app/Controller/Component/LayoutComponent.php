<?php
class LayoutComponent extends Object {	//TODO: rename AdminComponent?
	
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
	 * Get form inputs based upon request action and controller.
	 */
	function formInputs() {
		$action = $this->controller->request->params['action'];
		if(in_array($action, array('add', 'edit'))) {
			$textsInputs = $this->controller->formInputs;
				
			if ($action=='edit') {
				$textsInputs['hugs'] = array('type' => 'text');
				$textsInputs['visits'] = array('type' => 'text');
			}
			return $textsInputs;
		}
	}
}