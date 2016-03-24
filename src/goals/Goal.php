<?php
/*
 * PoiXson xBuild - Build and deploy tools
 *
 * @copyright 2015-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\xBuild\goals;

use pxn\phpUtils\Defines;
use pxn\phpUtils\Paths;
use pxn\phpUtils\San;


abstract class Goal {

	public static $goals = [];

	protected $builder;
	protected $jsonConfig;
	protected $goalArgs;



	/**
	 * Load a goal by name
	 * @param string $name
	 */
	public static function LoadGoalByName($name, $args) {
		if (empty($name)) {
			fail ('name argument is required!');
			exit(1);
		}
		if (!\is_array($args)) {
			fail ('steps argument is required!');
			exit(1);
		}
		$name = San::AlphaNum($name);
		$goal = NULL;
		$file = \implode(Defines::DIR_SEP, [
				Paths::base(),
				'goals',
				"goal_{$name}.php"
				]);
		if (!\file_exists($file))
			return NULL;
		$clss = "\\pxn\\xBuild\\goals\\goal_{$name}";
		try {
			$goal = new $clss($name, $args);
		} catch (\Exception $e) {
			fail ($e->getMessage(), 1, $e);
			exit(1);
		}
		self::$goals[$name] = $goal;
		return $goal;
	}
	public static function getGoalByName($name) {
		if (isset(self::$goals[$name]))
			return self::$goals[$name];
		return NULL;
	}



	public abstract function getName();

	public abstract function run();



	public static function title($msg) {
		echo " [[ {$msg} ]] \n";
	}



}
