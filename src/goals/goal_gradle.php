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


class goal_gradle extends Goal {

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
			fail ("Gradle not found! {$pathTool}");
			exit(1);
		}
		// check for build.gradle file
		$pwd = Paths::pwd();
		$pathConfig = "{$pwd}/build.gradle";
		if (!\file_exists($pathConfig)) {
			fail ("build.gradle file not found in workspace! {$pathConfig}");
			exit(1);
		}
//		parent::run();
fail ('Sorry, this goal is unfinished!');
	}



}
