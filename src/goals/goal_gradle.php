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



	public function getType() {
		return 'gradle';
	}
	protected function getTitlePrefix() {
		return 'Building with';
	}



	public function run() {
fail ('Sorry, this goal is unfinished!');
	}



}
