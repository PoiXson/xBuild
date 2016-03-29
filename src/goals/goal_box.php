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


class goal_box extends goal_shell {

	const BOX_PATH = '/usr/bin/box';



	public function getType() {
		return 'box';
	}
	protected function getTitlePrefix() {
		return 'Building';
	}



	public function run() {
		$pathTool = self::BOX_PATH;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail ("Box not found! {$pathTool}");
			exit(1);
		}
		// check for box.json file
		$pwd = Paths::pwd();
		if (empty($pwd)) {
			fail ('Failed to get pwd!');
			exit(1);
		}
		$pathConfig = "{$pwd}/box.json";
		if (!\file_exists($pathConfig)) {
			fail ("box.json file not found in workspace! {$pathConfig}");
			exit(1);
		}
//		parent::run();
fail ('Sorry, this goal is unfinished!');
	}



}
