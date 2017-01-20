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
		$pathTool = self::COMPOSER_PATH;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail("Composer not found! $pathTool",
				Defines::EXIT_CODE_IO_ERROR);
		}
		// check for composer.json file
		$pwd = Paths::pwd();
		if (empty($pwd)) {
			fail('Failed to get pwd!',
				Defines::EXIT_CODE_IO_ERROR);
		}
		$pathConfig = "{$pwd}/composer.json";
		if (!\file_exists($pathConfig)) {
			fail("composer.json file not found in workspace! $pathConfig",
				Defines::EXIT_CODE_IO_ERROR);
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
