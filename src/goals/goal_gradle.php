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


class goal_gradle extends Goal {

	const GRADLE_PATH = '/usr/bin/gradle/latest/bin/gradle';



	public function getType() {
		return 'gradle';
	}
	protected function getTitlePrefix() {
		return 'Building with';
	}



	public function run() {
		$path = self::GRADLE_PATH;
		if (!\file_exists($path)) {
			fail ("Gradle not found! {$path}");
			exit(1);
		}
//		parent::run();
fail ('Sorry, this goal is unfinished!');
	}



}
