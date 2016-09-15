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


class goal_shell extends xGoal {
	use GoalShell;



	public function getType() {
		return 'shell';
	}



	public function run() {
		return $this->runShellHex($this->args);
	}



}
