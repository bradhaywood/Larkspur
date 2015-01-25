<?php
\Larkspur::module('View/Twig/Autoloader');
\Twig_Autoloader::register();

class View
{
	public static $loader = null;
	public static $template = null;
	public function __construct() {}
	public static function init()
	{
		self::$loader = new \Twig_Loader_Filesystem("app/" . \Larkspur::$app . "/views");
		self::$template = new \Twig_Environment(self::$loader, array('cache' => 'var/cache'));
	}

	public static function detach($args=array())
	{
		list(, $caller) = debug_backtrace(false);
		$newArgs = array_merge(\Session::stash(), $args);
		echo self::$template->render($caller['function'] .".html", $newArgs);
	}

	public static function json($mixed)
	{
		header('Content-Type: application/json');
		echo json_encode($mixed);
	}
}