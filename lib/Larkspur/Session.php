<?php

class Session {
	public static $stash = array();
	public function __construct() {}
	public static function stash($key=null) {
		if ($key === null)
			return self::$stash;
		
		if (is_array($key)) {
			self::$stash = $key;
			return self::$stash;
		}

		return self::$stash[$key];
	}
}