<?php
class SendableBehavior extends ModelBehavior {
	
	/**
	 * Sets the flag for an adventure or article, indicating that it has been sent.
	 * @param unknown_type $id
	 * @return Ambigous <mixed, boolean, multitype:, unknown>
	 */
	public function markAsSent(Model $Model, $id) {
		return $Model->save(array('id' => $id, 'is_sent' => 1));		
	}
}