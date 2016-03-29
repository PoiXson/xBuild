<?php
/*
 * PoiXson xBuild - Build and deploy tools
 *
 * @copyright 2015-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\xBuild\configs;

use pxn\phpUtils\San;
use pxn\xBuild\goals\Goal;


class config_global extends config_abstract {
	use config_goals;

//	protected $goals = NULL;



//	/**
//	 * Get the global goals
//	 * @return array[goal] or null on failure
//	 */
//	public function getGoals() {
//		if (!\is_array($this->goals)) {
//			$this->loadGoals();
//		}
//		if (!isset($this->json[self::KEY_GOALS]))
//			return NULL;
//			return $this->goals;
//	}
//	public function loadGoals() {
//		if (\is_array($this->goals))
//			return;
//		$goals = array();
//		$count = 0;
//		foreach ($this->json[self::KEY_GOALS] as $name => $args) {
//			// validate args array
//			if (!\is_array($args)) {
//				fail ('Invalid config structure! goal arguments should be a map!');
//				exit(1);
//			}
//			// validate name value
//			if (empty($name)) {
//				fail ('Empty goal name found in config!');
//				exit(1);
//			}
//			if (!San::Validate_AlphaNumSafe($name)) {
//				fail ("Invalid goal name found in config! {$name}");
//				exit(1);
//			}
//			// default type value
//			$type = isset($args['Type'])
//					? $args['Type']
//					: $name;
//			unset($args['Type']);
//			// validate type value
//			if (!San::Validate_AlphaNumSafe($type)) {
//				fail ("Invalid goal type found in config! {$type}");
//				exit(1);
//			}
//			// load goal class
//			{
//				$g = Goal::LoadGoal(
//						$name,
//						$type,
//						$args
//						);
//				if ($g == NULL) {
//					fail ("Failed to load goal: {$name}");
//					exit(1);
//				}
//				$goals[$name] = $g;
//			}
//			$count++;
//		}
//		echo "Found [ {$count} ] goals in config\n";
//		$this->goals = $goals;
//		return $goals;
//	}



}
