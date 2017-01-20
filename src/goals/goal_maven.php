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


class goal_maven extends xGoal {

	const MAVEN_PATH = '/usr/bin/mvn';



	public function getType() {
		return 'maven';
	}
	protected function getTitlePrefix() {
		return 'Building with';
	}



	public function run() {
		$pathTool = self::MAVEN_PATH;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail("Maven not found! $pathTool",
				Defines::EXIT_CODE_IO_ERROR);
		}
		// check for pom.xml file
		$pwd = Paths::pwd();
		if (empty($pwd)) {
			fail('Failed to get pwd!',
				Defines::EXIT_CODE_IO_ERROR);
		}
		$pathConfig = "{$pwd}/pom.xml";
		if (!\file_exists($pathConfig)) {
			fail("pom.xml file not found in workspace! $pathConfig",
				Defines::EXIT_CODE_IO_ERROR);
		}
//		parent::run();
fail('Sorry, this goal is unfinished!');
	}



}
