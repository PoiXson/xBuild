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


class config_xbuild extends config_abstract {
	use config_goals {
		config_goals::getGoals as getGoalsParent;
	}

	const KEY_NAME          = 'Name';
	const KEY_VERSION       = 'Version';
	const KEY_VERSION_FILES = 'Version Files';

	protected $configGlobal;



	public function __construct($configGlobal) {
		parent::__construct();
		$this->configGlobal = $configGlobal;
	}



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



	public function getGoals() {
		$goals = $this->getGoalsParent();
		foreach($this->configGlobal->goals as $key => $val) {
			if (!isset($goals[$key])) {
				$goals[$key] = $val;
			}
		}
		return $goals;
	}



}
