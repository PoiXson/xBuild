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

use pxn\phpUtils\Paths;
use pxn\phpUtils\San;
use pxn\phpUtils\Defines;

use pxn\phpUtils\xLogger\xLog;


abstract class Goal {

	public static $goals = [];

	public $name;
	public $args;
	public $dry = NULL;



	public function __construct($name, $args) {
		$this->name = $name;
		$this->args = $args;
	}



	/**
	 * Load a goal by name
	 * @param string $name
	 */
	public static function LoadGoal($name, $type, $args) {
		// validate arguments
		$name = \trim($name);
		$type = \trim($type);
		if (empty($name)) {
			fail('name argument is required!',
				Defines::EXIT_CODE_INVALID_ARGUMENT);
		}
		if (empty($type)) {
			fail('type argument is required!',
				Defines::EXIT_CODE_INVALID_ARGUMENT);
		}
		if (!\is_array($args)) {
			fail('args argument is required!',
				Defines::EXIT_CODE_INVALID_ARGUMENT);
		}
		// sanitize name/type values
		$name = San::AlphaNumSafe($name);
		$type = San::AlphaNumSafe(
				\str_replace('-', '_', $type)
		);
		if (empty($name)) {
			fail('Invalid name argument!',
				Defines::EXIT_CODE_INVALID_ARGUMENT);
		}
		if (empty($type)) {
			fail('Invalid type argument!',
				Defines::EXIT_CODE_INVALID_ARGUMENT);
		}
		$goal = NULL;
		// class file exists
		{
			$file = \implode(Defines::DIR_SEP, [
					Paths::base(),
					'goals',
					"goal_{$type}.php"
			]);
			if (!\file_exists($file))
				return NULL;
		}
		// load goal class
		$goal = NULL;
		try {
			$clss = "\\pxn\\xBuild\\goals\\goal_{$type}";
			$goal = new $clss(
					$name,
					$args
			);
		} catch (\Exception $e) {
			fail($e->getMessage(),
				Defines::EXIT_CODE_INTERNAL_ERROR, $e);
		}
		self::$goals[$name] = $goal;
		return $goal;
	}
	public static function getGoalByName($name) {
		if (isset(self::$goals[$name]))
			return self::$goals[$name];
		return NULL;
	}



	public function isDry() {
		return ($this->dry === TRUE);
	}



	public function getName() {
		return $this->name;
	}
	protected function getTitlePrefix() {
		return 'Running';
	}
	public function displayTitle() {
		$name   = $this->getName();
		$type   = $this->getType();
		$prefix = $this->getTitlePrefix();
		if ($type == $name) {
			self::title("{$prefix} {$name}..");
		} else {
			self::title("{$prefix} {$type} {$name}..");
		}
	}
	public abstract function getType();



	public static function title($msg) {
		xLog::getRoot()
				->publish(" [[ {$msg} ]] ");
	}



	public abstract function run();
	public function triggerRun($dry=NULL) {
		if ($dry !== NULL) {
			$this->dry = ($dry != FALSE);
		}
		$name   = $this->getName();
		$type   = $this->getType();
		$prefix = $this->getTitlePrefix();
		$this->displayTitle();
		$this->getLogger()
				->publish();
		return $this->run();
	}



	public function getLogger() {
		return xLog::getRoot(
				$this->getType()
		);
	}



}
