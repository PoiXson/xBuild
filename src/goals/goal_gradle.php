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


class goal_gradle extends goal_abstract {



	public function getName() {
		return 'gradle';
	}



	public function run() {
		$this->title('Building with gradle..');
		echo "\n";
echo ("Sorry, this goal is unfinished!\n");
exit(1);
	}



}
