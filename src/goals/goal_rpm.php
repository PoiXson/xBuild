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

use pxn\phpUtils\Strings;
use pxn\phpUtils\Paths;


class goal_rpm extends Goal {

	const RPMBUILD_PATH = '/usr/bin/rpmbuild';



	public function getType() {
		return 'rpm';
	}
	protected function getTitlePrefix() {
		return 'Building';
	}



	public function run() {
		$pathTool = self::RPMBUILD_PATH;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail ("RPM-Build not found! {$pathTool}");
			exit(1);
		}
		// check for project.spec file
		$pwd = Paths::pwd();
		if (!isset($this->args['Spec']) || empty($this->args['Spec'])) {
			fail ('Spec file field not provided in xbuild.json config!');
			exit(1);
		}
		$specFile = $this->args['Spec'];
		$pathConfig = "{$pwd}/{$specFile}";
		$pathConfig = Strings::ForceEndsWith(
				"{$pwd}/{$specFile}",
				'.spec'
		);
		if (!\file_exists($pathConfig)) {
			fail ("{$specFile} file not found in workspace! {$pathConfig}");
			exit(1);
		}
//		parent::run();
fail ('Sorry, this goal is unfinished!');
	}



}
