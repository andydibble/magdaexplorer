<?php
App::import('Core', 'String');

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
*/
class AppModel extends Model {

	var $actsAs = array('Containable');
	
	public function isChanged($new, $old=null) {
		
		$model=$this->name;
		if ($old==null) {
			$old = $this->find('first', array('conditions' => array('id' => $new[$model]['id'], 'recursion' => -1)));
		}	
				
		foreach($old[$model] as $field => $oldValue)
		{
			if (isset($new[$model][$field]) && $new[$model][$field] != $oldValue)
			{
				return true;
			}
		}	
		return false;
	}
	
	/**
	 * Alters results to display dates (and optionally times) according to the static date format app constant.
	 *
	 * @param unknown_type $results
	 * @param unknown_type $primary
	 * @param unknown_type $model the model to alter dates for.
	 * @param unknown_type $dateFields the date fields to alter (can be array or single field string name)
	 * @param unknown_type $parseTime optional--if provided, it must be the name of the time field to create from the date field which contains datetime data.
	 * @return unknown
	 */
	public function formatDates($results, $primary, $model, $dateFields, $parseTime=false)
	{
		if (!is_array($dateFields)) {
			$dateFields = array($dateFields);
		}
		//TODO: figure out how to display dates without datetime because on searches it will slow things down somewhat?
		if(isset($results[0][$model][$dateFields[0]]))
		{
			if ($parseTime) {
				foreach($results as $i => $result)	{
					foreach($dateFields as $dateField) {
						if (isset($results[$i][$model][$dateField])) {
							$date = $results[$i][$model][$dateField]; 
							if (Validation::datetime($date)) {
								$intDate = strtotime($date);
								$results[$i][$model][$dateField] = date(Configure::read('DISP_DATE_FORMAT'), $intDate);
								$results[$i][$model][$parseTime] = date(Configure::read('DISP_TIME_FORMAT'), $intDate);
							}
						}
					}
				}
			} else {
				foreach($results as $i => $result)	{
					foreach($dateFields as $dateField) {
						if (isset($results[$i][$model][$dateField])) {
							$date = $results[$i][$model][$dateField]; 
							if (Validation::date($date)) {
								$intDate = strtotime($date);
								$results[$i][$model][$dateField] = date(Configure::read('DISP_DATE_FORMAT'), $intDate);
							}
						}
					}
				}
			}
		}
		else
		{
			if (isset($results[$model][$dateFields[0]]))
			{
				foreach($dateFields as $dateField) {
					$intDate = strtotime($results[$model][$dateField]);
					$results[$model][$dateField] = date(Configure::read('DISP_DATE_FORMAT'), $intDate);
					if ($parseTime) {
						$results[$model][$parseTime] = date(Configure::read('DISP_TIME_FORMAT'), $intDate);
					}
				}
			}
		}
		return $results;
	}
	
	/**
	 * DEPRECATED: by use of Nic Editor
	 * Alters results to display dates (and optionally times) according to the static date format app constant.
	 *
	 * @param unknown_type $results
	 * @param unknown_type $primary
	 * @param unknown_type $model the model to alter dates for.
	 * @param unknown_type $taFields the text area fields to alter (can be array or single field string name)	 
	 * @return unknown
	 */
	public function formatTextAreasToHtml($results, $model, $taFields)
	{
		if (!is_array($taFields)) {
			$taFields = array($taFields);
		}
		if(isset($results[0][$model][$taFields[0]]))
		{
			foreach($results as $i => &$result)	{
				foreach($taFields as $field) {					
					$result[$model][$field] = String::prettyPrintToHtml($result[$model][$field]);
				}			
			}	
		}
		else
		{
			if (isset($results[$model][$taFields[0]]))
			{
				foreach($taFields as $field) {					
					$result[$model][$field] = String::prettyPrintToHtml($result[$model][$field]);
				}	
			}
		}
		return $results;
	}
	
	/**
	 * Sorts an array by the value of a particular element in that array.
	 * @param unknown_type $arr
	 * @param unknown_type $col
	 * @param unknown_type $dir
	 */
	function arraySortByColumn(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}
		array_multisort($sort_col, $dir, $arr);
	}
	
	public function increment($field, $id) {
		return $this->updateAll(
				array($this->name.'.'.$field => $this->name.'.'.$field.'+1'),
				array($this->name.'.id' => $id)
		);
	}
	
	/**
	 * Loads and instantiates models.
	 * If the model is non existent, it will throw a missing database table error, as Cake generates
	 * dynamic models for the time being.
	 *
	 * Will clear the model's internal state using Model::create()
	 *
	 * @param string $modelName Name of model class to load
	 * @param mixed $options array|string
	 *              id      Initial ID the instanced model class should have
	 *              alias   Variable alias to write the model to
	 * @return mixed true when single model found and instance created, error returned if model not found.
	 * @access public
	 */
	function loadModel($modelName, $options = array()) {
		if (is_string($options)) $options = array('alias' => $options);
		$options = array_merge(array(
				'datasource'  => 'default',
				'alias'       => false,
				'id'          => false,
		), $options);
	
		list($plugin, $className) = pluginSplit($modelName, true, null);
		if (empty($options['alias'])) $options['alias'] = $className;
	
		if (!isset($this->{$options['alias']}) || $this->{$options['alias']}->name !== $className) {
			if (!class_exists($className)) {
				if ($plugin) $plugin = "{$plugin}.";
				App::import('Model', "{$plugin}{$modelClass}");
			}
			$table = Inflector::tableize($className);
	
			if (PHP5) {
				$this->{$options['alias']} = new $className($options['id'], $table, $options['datasource']);
			} else {
				$this->{$options['alias']} =& new $className($options['id'], $table, $options['datasource']);
			}
			if (!$this->{$options['alias']}) {
				return $this->cakeError('missingModel', array(array(
						'className' => $className, 'code' => 500
				)));
			}
			$this->{$options['alias']}->alias = $options['alias'];
		}
	
		$this->{$options['alias']}->create();
		return true;
	}
}
