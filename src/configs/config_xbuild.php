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


class config_xbuild extends config_abstract {

	const KEY_NAME          = 'Name';
	const KEY_VERSION       = 'Version';
	const KEY_VERSION_FILES = 'Version Files';
	const KEY_GOALS         = 'Goals';

	protected $goals = NULL;



	/**
	 * Get the project name
	 * @return string or null on failure
	 */
	public function getName() {
		// key exists
		if (isset($this->json[self::KEY_NAME]))
			return $this->json[self::KEY_NAME];
		return NULL;
	}



	/**
	 * Get the project version
	 * @return string or null on failure
	 */
	public function getVersion() {
		// key exists
		if (isset($this->json[self::KEY_VERSION]))
			return $this->json[self::KEY_VERSION];
		return NULL;
	}



	/**
	 * Get the project version files
	 * @return array[string] or null on failure
	 */
	public function getVersionFiles() {
		// key exists
		if (!isset($this->json[self::KEY_VERSION_FILES]))
			return NULL;
		$data = $this->json[self::KEY_VERSION_FILES];
		if (is_array($data))
			return $data;
		$data = (string) $data;
		if (!empty($data))
			return array($data);
		return NULL;
	}



	/**
	 * Get the project goals
	 * @return array[goal] or null on failure
	 */
	public function getGoals() {
		if (\is_array($this->goals))
			return $this->goals;
		if (!isset($this->json[self::KEY_GOALS]))
			return NULL;
		$goals = array();
		$count = 0;
		foreach ($this->json[self::KEY_GOALS] as $name => $args) {
			if (empty($name)) {
				fail ('Empty goal name found in config!');
				exit(1);
			}
			$name = San::AlphaNum($name);
			if (empty($name)) {
				fail ('Invalid goal name found in config!');
				exit(1);
			}
			if (!\is_array($args)) {
				fail ('Invalid config structure! goal arguments should be a map!');
				exit(1);
			}
			$type = $name;
			if (isset($args['Type'])) {
				$type = $args['Type'];
				unset($args['Type']);
			}
			// load goal class
			$g = Goal::LoadGoalByName($type, $args);
			if ($g == NULL) {
				fail ("Failed to load goal: {$name}");
				exit(1);
			}
			$goals[$name] = $g;
			$count++;
		}
		echo "Found [ {$count} ] goals in config\n";
		$this->goals = $goals;
		return $goals;
	}



}
