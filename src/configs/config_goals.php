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
use pxn\phpUtils\Defines;

use pxn\xBuild\goals\Goal;


trait config_goals {

	public $goals = NULL;



	/**
	 * Get the goals
	 * @return array[goal] or null on failure
	 */
	public function getGoals() {
		if (!\is_array($this->goals)) {
			$this->loadGoals();
		}
		if (!isset($this->json['Goals']))
			return NULL;
		return $this->goals;
	}
	public function loadGoals() {
		if (\is_array($this->goals))
			return;
		$goals = array();
		$count = 0;
		foreach ($this->json['Goals'] as $name => $args) {
			// validate args array
			if (!\is_array($args)) {
				fail('Invalid config structure! goal arguments should be a map!',
					Defines::EXIT_CODE_CONFIG_ERROR);
			}
			// validate name value
			if (empty($name)) {
				fail('Empty goal name found in config!',
					Defines::EXIT_CODE_CONFIG_ERROR);
			}
			if (!San::Validate_AlphaNumSafe($name)) {
				fail("Invalid goal name found in config! $name",
					Defines::EXIT_CODE_CONFIG_ERROR);
			}
			// default type value
			$type = isset($args['Type'])
					? $args['Type']
					: $name;
			unset($args['Type']);
			// validate type value
			if (!San::Validate_AlphaNumSafe($type)) {
				fail("Invalid goal type found in config! $type",
					Defines::EXIT_CODE_CONFIG_ERROR);
			}
			// load goal class
			{
				$g = Goal::LoadGoal(
					$name,
					$type,
					$args
				);
				if ($g == NULL) {
					fail("Failed to load goal: $name",
						Defines::EXIT_CODE_CONFIG_ERROR);
				}
				$goals[$name] = $g;
			}
			$count++;
		}
		$this->goals = $goals;
		return $goals;
	}



}
