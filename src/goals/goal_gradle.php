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
use pxn\phpUtils\Defines;


class goal_gradle extends goal_shell {

	const GRADLE_PATH = '/usr/bin/gradle/latest/bin/gradle';



	public function getType() {
		return 'gradle';
	}
	protected function getTitlePrefix() {
		return 'Building with';
	}



	public function run() {
		$pathTool = self::GRADLE_PATH;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail("Gradle not found! $pathTool",
				Defines::EXIT_CODE_IO_ERROR);
		}
		// check for build.gradle file
		$pwd = Paths::pwd();
		if (empty($pwd)) {
			fail('Failed to get pwd!',
				Defines::EXIT_CODE_IO_ERROR);
		}
		$pathConfig = "{$pwd}/build.gradle";
		if (!\file_exists($pathConfig)) {
			fail("build.gradle file not found in workspace! $pathConfig",
				Defines::EXIT_CODE_IO_ERROR);
		}
//		parent::run();
fail('Sorry, this goal is unfinished!');
	}



}
