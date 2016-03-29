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


class goal_composer extends goal_shell {

	const COMPOSER_PATH = '/usr/bin/composer';



	public function getType() {
		return 'composer';
	}
	public function displayTitle() {
		$name   = $this->getName();
		$prefix = $this->getTitlePrefix();
		self::title("{$prefix} {$name}..");
	}



	public function run() {
		$path = self::COMPOSER_PATH;
		if (!\file_exists($path)) {
			fail ("Composer not found! {$path}");
			exit(1);
		}
		parent::run();
	}
// Process Exit Codes#
//  0: OK
//  1: Generic/unknown error code
//  2: Dependency solving error code
//	public function run() {
//		return $this->runShellHex($this->args);
//		$args = $this->args['Args'];
//		$cmd = "composer {$args}";
//		return $this->runShell($cmd);
// composer show -t --profile
//	}



}
