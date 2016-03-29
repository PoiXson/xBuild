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
	public $configGlobal;
	public $buildNumber;
	public $dry = NULL;
	public $runGoals;
	public $usingDefaultGoals = NULL;



	public function __construct(
			$configBuild, $configDeploy, $configGlobal,
			$buildNumber, $runGoals=NULL) {
		$this->configBuild  = $configBuild;
		$this->configDeploy = $configDeploy;
		$this->configGlobal = $configGlobal;
		$this->buildNumber  = $buildNumber;
		$this->runGoals     = $runGoals;
	}



	// run the goals
	public function run($run=NULL) {
		// override run goals
		if (\is_array($run) && !empty($run)) {
			$this->runGoals = $run;
		}
		{
			$msgGoals = \implode(', ', $this->runGoals);
			$msgCount = \count($this->runGoals);
			$msgS     = ($msgCount > 1 ? 's' : '');
			$msgDefault = '';
			if ($this->usingDefaultGoals == UsingDefaultGoalsEnum::USING_DEFINED_GOALS) {
				$msgDefault = 'default ';
			}
			echo "\n";
			Goal::title("Running {$msgCount} {$msgDefault}goal{$msgS} -> {$msgGoals}");
		}
		return $this->doRun();
	}
	public function doRun() {
		if (!\is_array($this->runGoals)) {
			\fail ('Invalid runGoals value provided to builder!');
			exit(1);
		}
		if (\count($this->runGoals) == 0) {
			Goal::title('No goals to run.');
			return 1;
		}
		// perform goals
		$countSuccess = 0;
		$countFailed  = 0;
		foreach ($this->runGoals as $run) {
			if ($run == NULL) continue;
//echo "\n\nRUN: {$run}\n\n";
			$goal = Goal::getGoalByName($run);
//	$args = NULL;
//	if (\strpos($run, ':', 1) !== FALSE) {
//	list($run, $args) = explode(':', $run, 2);
//	}
			if ($goal == NULL) {
//print_r(Goal::$goals);
				fail ("Goal not found! {$run}");
				exit(1);
			}
			$result = $goal->triggerRun($this->dry);
			if ($result != 0) {
				$countFailed++;
				fail ("Failed to run goal: {$result} - {$run}");
				return $result;
			}
			$countSuccess++;
		}
		return $result;
	}



//	// build number
//	public function getbuildNumber() {
//		if (empty($this->buildNumber)) {
//			return '<Not Set>';
//		}
//		return (string) ((int) $this->buildNumber);
//	}



}
