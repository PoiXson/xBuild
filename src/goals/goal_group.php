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


class goal_group extends Goal {



	public function getName() {
		return 'group';
	}



	public function run() {
		self::title('Running group..');
		echo "\n";
fail ('Sorry, this goal is unfinished!');
	}


}
