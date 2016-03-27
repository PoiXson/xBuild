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

use pxn\xBuild\goals\Goal;


class builder {

	public $config;
	public $deployConfig;
	public $BuildNumber = NULL;
	public $runGoals    = array();



	public function __construct($config, $deployConfig) {
		$this->config       = $config;
		$this->deployConfig = $deployConfig;
		// load goals from config
		$config->getGoals();
	}



	public function run($run=NULL) {
		// override run goals
		if (\is_array($run) && !empty($run)) {
			$this->runGoals = $run;
		}
		// default run goal
		if (!\is_array($this->runGoals) || empty($this->runGoals)) {
			if ($this->deployConfig == NULL) {
				$this->runGoals = [
					'build'
				];
			} else {
				$this->runGoals = [
					'release'
				];
			}
			$goalsStr = \implode(', ', $this->runGoals);
			echo "Running default goal [ {$goalsStr} ] ..\n";
		} else {
			$goalsStr = \implode(', ', $this->runGoals);
			echo "Running goals [ {$goalsStr} ] ..\n";
		}
		// perform goals
		foreach ($this->runGoals as $run) {
//			$args = NULL;
//			if (\strpos($run, ':', 1) !== FALSE) {
//				list($run, $args) = explode(':', $run, 2);
//			}
//			$goal = Goal::getGoalByName($run, $args);
			$goal = Goal::getGoalByName($run);
			if ($goal == NULL) {
				fail ("Goal not found! {$run}");
				exit(1);
			}
			$goal->run();
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
