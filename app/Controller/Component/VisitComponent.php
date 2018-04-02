<?php

App::uses('AppComponent', 'Controller/Component');

class VisitComponent extends AppComponent {

	//TODO: move this to Trip and handle Location visit in a single call!!
	/**
	 * Marks that this trip, location, etc. has been visited in the session and increments the visits by 1 for the record.
	 * This function has no effect if the user is an admin, $conds (if supplied) are not all true of the trip,
	 * or if $id is already in Trips.visited when it is called.
	 *
	 * the trip is only visited if conds is true of the trip denoted by id.
	 * @param unknown_type $id
	 */
	public function visit($id, $conds = array(), $modelName = null) {

		if ($modelName == null) {
			$modelName = Inflector::ucfirst(Inflector::singularize($this->controller->name));
		}

		if (!$this->controller->isAdmin()) {
			if ($this->controller->{$modelName}->hasAny($conds + array('id' => $id))) {
				$visited = array();

				if (!$this->controller->Session->check("$modelName.visited")) {
					$this->controller->Session->write("$modelName.visited", array());
				} else {
					$visited = $this->controller->Session->read("$modelName.visited");
				}
				//pr(get_defined_vars());
				if (!in_array($id, $visited)) {
					$this->controller->{$modelName}->increment('visits', $id);

					$visited[] = $id;
					$this->controller->Session->write("$modelName.visited", $visited);
				}
			}
		}
	}
}