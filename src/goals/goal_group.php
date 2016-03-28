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

	const MAX_GOALS = 256;



	public function getType() {
		return 'group';
	}



	public function run() {
		$result = 0;
		for ($index = 0; $index < self::MAX_GOALS; $index++) {
			// hex step index
			$hexIndex = \dechex($index);
			// skip or break
			if (!isset($this->args[$hexIndex])) {
				if ($index == 0)
					continue;
				break;
			}
			// run goal
			$run = $this->args[$hexIndex];
			echo " GROUP [ {$hexIndex} ] {$run}\n";
			$goal = Goal::getGoalByName($run);
			if ($goal == NULL) {
				fail ("Goal not found by group! {$run}");
				exit(1);
			}
			$result = $goal->triggerRun($this->dry);
			if ($result != 0) {
				fail ("Failed to run goal: {$result} - {$run}");
				return $result;
			}
		}
		return $result;
	}



}
