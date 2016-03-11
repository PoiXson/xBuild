<?php
/*
 * PoiXson xBuild - Build and deploy tools
 *
 * @copyright 2015-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\xBuild;

use pxn\phpUtils\San;


class builder {

	public $config;
	public $BuildNumber = NULL;
	public $goals       = array();



	public function __construct($config) {
		$this->config = $config;
	}



	public function LoadGoals($goals) {
		if (!\is_array($goals)) {
			throw new \Exception('Invalid goals argument provided!');
		}
		$count = 0;
		foreach ($goals as $goalName) {
			// goal config
			$goalConfig = $this->config->getGoalConfig($goalName);
			if ($goalConfig === NULL) {
				echo 'Goal not configured for this project: '.$goalName."\n";
				exit(1);
			}
			// load goal object
			$result = $this->LoadGoal(
				$goalName,
				$goalConfig
			);
			if ($result !== TRUE) {
				echo "Failed to load goal: {$goalName}\n";
				exit(1);
			}
			$count++;
		}
		echo "Loaded {$count} goals\n";
		return TRUE;
	}
	public function LoadGoal($goalName, $config) {
		$goalArgs = NULL;
		// split goal arguments
		if (\strpos($goalName, ':') !== FALSE) {
			list($goalName, $goalArgs) = \explode(':', $goalName, 2);
		}
		$goalName = San::AlphaNum($goalName);
		$file = __DIR__.'/goals/goal_'.$goalName.'.php';
		// ensure goal exists
		if (!\file_exists($file)) {
			echo 'Goal type not found: '.$goalName."\n";
			exit(1);
		}
		// load goal class object
		$clss = '\\pxn\\xBuild\\goals\\goal_'.$goalName;
		$goal = new $clss(
			$this,
			$config,
			$goalArgs
		);
		$this->goals[] = $goal;
		return TRUE;
	}



	public function run() {
		$goalsString = '';
		$first = TRUE;
		foreach ($this->goals as $goal) {
			if ($first) {
				$first = FALSE;
			} else {
				$goalsString .= ', ';
			}
			$goalsString .= $goal->getName();
		}
		echo "\n\n";
		echo "RUNNING...  [ {$goalsString} ]\n";
		echo "\n\n";
		// run goals
		$first = TRUE;
		foreach ($this->goals as $goal) {
			if ($first) {
				$first = FALSE;
				echo "----------------------------------------\n";
				echo "\n\n";
			}
			$goal->run();
			echo "\n\n";
			echo "----------------------------------------\n";
			echo "\n\n";
		}
	}



	// build number
	public function getBuildNumber() {
		if (empty($this->BuildNumber)) {
			return '<Not Set>';
		}
		return (string) ((int) $this->BuildNumber);
	}



}
