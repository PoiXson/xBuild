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


class goal_rpm extends Goal {

	const RPMBUILD_PATH = '/usr/bin/rpmbuild';



	public function getType() {
		return 'rpm';
	}
	protected function getTitlePrefix() {
		return 'Building';
	}



	public function run() {
		$path = self::RPMBUILD_PATH;
		if (!\file_exists($path)) {
			fail ("RPM-Build not found! {$path}");
			exit(1);
		}
//		parent::run();
fail ('Sorry, this goal is unfinished!');
	}



}
