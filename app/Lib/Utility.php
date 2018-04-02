<?php
class Utility {
	
	static function isAssoc($array) { 
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}
	
	static function hasNumericIndices($array) {
		$tmp = array_keys($array);
		return is_integer($tmp[0]);
	}
	
	static function reorder($array) {
		return array_merge($array);
	}
	
	static function isNullOrEmpty($question)
	{
		return (!isset($question) || is_string($question) && trim($question)==='' || is_array($question) && empty($question));
	}
	
	static function arraySortByColumn(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}
	
		array_multisort($sort_col, $dir, $arr);
	}

	/**
	 * Guarantees that the param is a list (makes it a list with one elt, if it is not).
	 * @param unknown_type $listOrSingleElt
	 * @return unknown
	 */
	static function listify(&$listOrSingleElt) {
		if (!self::hasNumericIndices($listOrSingleElt)) {
			$list[0] = $listOrSingleElt;			
			$listOrSingleElt = $list;
		}		
	}
	
	/**
	 * Creates a link to this site, using controller and action passed.
	 * @param unknown_type $controller
	 * @param unknown_type $action
	 * @param unknown_type $id
	 * @param unknown_type $fromApproot if this is set, the domain name is not included in the link url
	 * @return string
	 */
	public function constructSiteLink($controller, $action='', $id=null, $fromApproot=false, $toAdventure=null) {				
		if ($fromApproot) {
			$root = Configure::read('APPROOT');
		} else {
			$root = env('HTTP_HOST').Configure::read('APPROOT');
		}
		$url = $root.Inflector::lcfirst($controller).'/'.$action;
		if ($id!==null) {
			$url.='/'.$id;
		}
		if ($toAdventure) {
			$url.= self::toAdventure($toAdventure);
		}
		return $url;
	}	
	
	public static function toAdventure($id) {
		return '/adv'.$id.'#adv'.$id;
	}
	
	public function isSetAndEquals($check, $test=true) {
		return isset($check) && $check == $test;
	}
	
	public function randomString($length=6, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
	{	
	    $str = '';
	    $count = strlen($charset);
	    while ($length--) {
	        $str .= $charset[mt_rand(0, $count-1)];
	    }
    	return $str;
	}
	
	public static function ltrim_string($str, $prefix) {
		if (substr($str, 0, strlen($prefix)) == $prefix) {
			return substr($str, strlen($prefix));
		}
		return $str;
	}
}