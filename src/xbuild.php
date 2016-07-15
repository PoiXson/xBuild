#!/usr/bin/php
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

use pxn\xBuild\configs\config_xbuild;
use pxn\xBuild\configs\config_xdeploy;
use pxn\xBuild\configs\config_global;

use pxn\phpUtils\Strings;
use pxn\phpUtils\System;
use pxn\phpUtils\ConsoleShell;

use pxn\phpUtils\xLogger\xLog;
use pxn\phpUtils\xLogger\xLevel;
use pxn\phpUtils\xLogger\formatters\FullFormat;
use pxn\phpUtils\xLogger\handlers\ShellHandler;



// defines
const BUILD_CONFIG_FILE  = 'xbuild.json';
const DEPLOY_CONFIG_FILE = 'xdeploy.json';
const GLOBAL_CONFIG_FILE = 'global.json';



if (!\file_exists(__DIR__.'/../pxnloader.php')) {
	echo "\n<h2>File not found: pxnloader.php, run <i>composer update</i></h2>\n";
	exit(1);
}
require(__DIR__.'/../pxnloader.php');



// check os
System::RequireLinux();

// require shell
if (!System::isShell()) {
	fail ('This script must be run locally as a shell script!');
	exit(1);
}



$NoLogo = NULL;
if (ConsoleShell::hasFlag('--no-logo')) {
	$NoLogo = TRUE;
}
function DisplayLogo() {
	global $NoLogo;
	if ($NoLogo !== FALSE)
		return;
	echo "\n";
	echo ' ██╗  ██╗██████╗ ██╗   ██╗██╗██╗     ██████╗ '."\n";
	echo ' ╚██╗██╔╝██╔══██╗██║   ██║██║██║     ██╔══██╗'."\n";
	echo '  ╚███╔╝ ██████╔╝██║   ██║██║██║     ██║  ██║'."\n";
	echo '  ██╔██╗ ██╔══██╗██║   ██║██║██║     ██║  ██║'."\n";
	echo ' ██╔╝ ██╗██████╔╝╚██████╔╝██║███████╗██████╔╝'."\n";
	echo ' ╚═╝  ╚═╝╚═════╝  ╚═════╝ ╚═╝╚══════╝╚═════╝ '."\n";
	echo "\n";
}



// display help
if (ConsoleShell::isHelp()) {
	$help = new \pxn\phpUtils\ConsoleHelp(
		NULL,
		'goals',
		TRUE,
		TRUE
	);
	// goals
	$help->addArgument(
		'clean',
		'Cleans the project workspace, removing all temporary files.',
		TRUE, TRUE
	);
	$help->addArgument(
		'build',
		'Builds the project by calling the build sub-group.',
		TRUE, TRUE
	);
	$help->addArgument(
		'deploy',
		'Deploys the files resulting from a build.',
		TRUE, TRUE
	);
	// flags
	$help->addFlag(
		['-b', '--build-number'],
		'Sets the build number.'
	);
	$help->addFlag(
		['-t', '--dry'],
		'Dry run, test without writing anything to the filesystem.'
	);
	$help->addFlag(
		['-w', '--max-wait'],
		[
			'Max time to wait in seconds, when another instance is busy.',
			'-1 for no timeout',
			'0 to exit immediately',
			'default: 300 (5 minutes)'
		]
	);
	$help->addFlag(
		['-D', '--deploy-config-depth'],
		'Number of parent directories to ascend when searching for deploy config file.'
	);
	$help->addFlag(
		'--no-logo',
		'Disables the startup logo.'
	);
	$help->addFlag(
		['-p', '--profile'],
		'Display timing and memory usage information.'
	);
	$help->addFlag(
		'--ansi',
		'Force ANSI output.'
	);
	$help->addFlag(
		'--no-ansi',
		'Disable ANSI output.'
	);
	$help->addFlag(
		['-q', '--quiet'],
		'Do not output messages.'
	);
	$help->addFlag(
		['-v', '--verbose'],
		'Increase the verbosity of messages.'
	);
	$help->addFlag(
		['-d', '--debug'],
		'Debug mode, most verbose logging.'
	);
	$help->addFlag(
		['-h', '--help'],
		'Display this help message.'
	);
	$help->Display();
	ExitNow(1);
}



$buildNumber = NULL;
$dry         = NULL;
$profile     = NULL;
$quiet       = NULL;
$GoalArgs = array();



// build number
if (ConsoleShell::hasFlag('-b', '--build-number')) {
	$val = ConsoleShell::getFlag('-b', '--build-number');
	if (Numbers::isNumber($val)) {
		$buildNumber = (int) $val;
	}
}
// dry run
if (ConsoleShell::hasFlag('-t', '--dry')) {
	$dry = TRUE;
}
// max wait time
//TODO:
if (ConsoleShell::hasFlag('-w', '--max-wait')) {
	fail(__FILE__.' - '.__LINE__.' IS UNFINISHED');
}
// search parent dirs
//TODO:
if (ConsoleShell::hasFlag('-D', '--deploy-config-depth')) {
	fail(__FILE__.' - '.__LINE__.' IS UNFINISHED');
}
// display timing
if (ConsoleShell::hasFlag('-p', '--profile')) {
	$profile = TRUE;
}
// quiet - don't display messages
if (ConsoleShell::hasFlag('-q', '--quiet')) {
	$quiet = TRUE;
}
// verbose - display more messages
//TODO:
if (ConsoleShell::hasFlag('-v', '--verbose')) {
	fail(__FILE__.' - '.__LINE__.' IS UNFINISHED');
}
// debug - display extra messages
if (ConsoleShell::hasFlag('-d', '--debug')) {
//TODO:
	fail(__FILE__.' - '.__LINE__.' IS UNFINISHED');
}






// init logger
{
	$log = xLog::getRoot();
	$log->setLevel(xLevel::ALL);
	$log->setFormatter(
			new FullFormat()
	);
//	$log->setFormatter(
//			(new BasicFormatter())
//			->setPrefix(' <<xBuild>>')
//	);
	$log->setHandler(
			new ShellHandler()
	);
	xLog::HandleBuffer();
}



// ensure single instance
//TODO:



// load global config
$configGlobal = NULL;
{
	$file = GLOBAL_CONFIG_FILE;
//TODO:
	if ( ! \file_exists($file) ) {
		fail ("Config file not found: {$file}");
		exit(1);
	}
	$configGlobal = new config_global();
	if ( ! $configGlobal->LoadFile($file) ) {
		fail ("Failed to load config file: {$file}");
		exit(1);
	}
	// pre-load goals
	$configGlobal->getGoals();
}
// load build config
$configBuild = NULL;
{
	$file = BUILD_CONFIG_FILE;
	if ( ! \file_exists($file)) {
		fail ("Config file not found: {$file}");
		exit(1);
	}
	$configBuild = new config_xbuild($configGlobal);
	if ( ! $configBuild->LoadFile($file) ) {
		fail ("Failed to load config file: {$file}");
		exit(1);
	}
	// pre-load goals
	$configBuild->getGoals();
}
// load deploy config
$configDeploy = NULL;
{
	$file = DEPLOY_CONFIG_FILE;
	if (\file_exists($file)) {
		$configDeploy = new config_xdeploy();
		if ( ! $configDeploy->LoadFile($file) ) {
			fail ("Failed to load config file: {$file}");
			exit(1);
		}
	}
}
{
	$log = xLog::getRoot();
	$countGlobal  = \count($configGlobal->goals);
	$countProject = \count($configBuild->goals);
	$countTotal   = \count($configBuild->getGoals());
	$log->info("Found [ {$countGlobal} ] global goals and [ {$countProject} ] project goals, [ {$countTotal} ] in total.");
//TODO: list global/project/override goals in debug mode
}



// load builder
$builder = new Builder(
	$configBuild,
	$configDeploy,
	$configGlobal,
	$buildNumber
);
$builder->BuildNumber = $buildNumber;
$builder->dry = $dry;
$runGoals = array();



// default goals
if (\is_array($GoalArgs) && \count($GoalArgs) > 0) {
	$builder->usingDefaultGoals = UsingDefaultGoalsEnum::USING_DEFINED_GOALS;
	$runGoals = $GoalArgs;
} else {
	if ($configDeploy == NULL) {
		$builder->usingDefaultGoals = UsingDefaultGoalsEnum::USING_DEFAULT_BUILD;
		$runGoals = Builder::DEFAULT_BUILD_GOALS;
	} else {
		$builder->usingDefaultGoals = UsingDefaultGoalsEnum::USING_DEFAULT_DEPLOY;
		$runGoals = Builder::DEFAULT_DEPLOY_GOALS;
	}
}



// run the build
$builder->run($runGoals);



{
	$log = xLog::getRoot();
	$log->publish();
	$log->publish('Finished!');
	$log->publish();
}
exit(0);
