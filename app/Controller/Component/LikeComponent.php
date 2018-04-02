<?php
App::uses('AppComponent', 'Controller/Component');
class LikeComponent extends AppComponent {
	
	/**
	 * Generic like function for the three likeables: Adventures, Comments, and Photos
	 * @return string
	 */
	public function like() {			
		$this->controller->prepAjax();
		$success = 0;
		if ($this->controller->data) {			
			$id = $this->controller->data['id'];
			
			$modelName = Inflector::singularize($this->controller->name);			
			if ($this->controller->{$modelName}->updateAll(
					array($modelName.'.likes' => $modelName.'.likes+1'),
					array($modelName.'.id' => $id)
			)) {
				$success = 1;
			
			} else {
				$success = 0;
			}			
		}
		return json_encode(array(
				'success' => $success,
		));
	}
}