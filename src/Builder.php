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


class Builder {

	const DEFAULT_BUILD_GOALS  = [ 'build'   ];
	const DEFAULT_DEPLOY_GOALS = [ 'release' ];

	public $configBuild;
	public $configDeploy;
	public $buildNumber;
	public $runGoals;
	public $usingDefaultGoals = NULL;




	public function __construct($configBuild, $configDeploy,
			$buildNumber, $runGoals=NULL) {
		$this->configBuild  = $configBuild;
		$this->configDeploy = $configDeploy;
		$this->buildNumber  = $buildNumber;
		$this->runGoals     = $runGoals;
	}



	public function run($run=NULL) {
		// override run goals
		if (\is_array($run) && !empty($run)) {
			$this->runGoals = $run;
		}
		// default run goal
		if (!\is_array($this->RunGoals) || empty($this->RunGoals)) {
			if ($this->configDeploy == NULL) {
				$this->RunGoals = [
					'build'
				];
			} else {
				$this->RunGoals = [
					'release'
				];
			}
			$goalsStr = \implode(', ', $this->RunGoals);
			echo "Running default goal [ {$goalsStr} ] ..\n";
		} else {
			$goalsStr = \implode(', ', $this->RunGoals);
			echo "Running goals [ {$goalsStr} ] ..\n";
		}
		// perform goals
		foreach ($this->RunGoals as $run) {
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
