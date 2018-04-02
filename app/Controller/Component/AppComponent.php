<?php 

App::import('Lib', 'Utility');
class AppComponent extends Component {
	
	public function startup(Controller $controller) {
		$this->controller = $controller;					
	}
}
	