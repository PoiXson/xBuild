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


class goal_maven extends Goal {

	const MAVEN_PATH = '/usr/bin/mvn';



	public function getType() {
		return 'maven';
	}
	protected function getTitlePrefix() {
		return 'Building with';
	}



	public function run() {
		$path = self::MAVEN_PATH;
		if (!\file_exists($path)) {
			fail ("Maven not found! {$path}");
			exit(1);
		}
//		parent::run();
fail ('Sorry, this goal is unfinished!');
	}



}
