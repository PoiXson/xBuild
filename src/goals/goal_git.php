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


class goal_git extends goal_shell {

	const GIT_PATH = '/usr/bin/git';



	public function getType() {
		return 'git';
	}



	public function run() {
		$pathTool = self::GIT_PATH;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail ("Git not found! {$pathTool}");
			exit(1);
		}
		parent::run();
	}



}
