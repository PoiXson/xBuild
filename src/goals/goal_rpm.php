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


class goal_rpm extends goal_abstract {



	public function getName() {
		return 'rpm';
	}



	public function run() {
		$this->title('Building rpm..');
		echo "\n";
echo ("Sorry, this goal is unfinished!\n");
exit(1);
	}



}
