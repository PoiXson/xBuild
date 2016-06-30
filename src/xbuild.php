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



function DisplayHelp() {
	global $argv;
	echo "Usage:\n";
	echo "  {$argv[0]} [options] [goals]\n";
	echo "\n";
	echo "Goals:\n";
	echo "  clean\n";
	echo "  build\n";
	echo "  deploy\n";
	echo "\n";
	echo "Options:\n";
	echo "  -b, --build-number=n       Sets the build number\n";
	echo "  -D, --deploy-config-depth  Number of parent directories to ascend\n";
	echo "                             when searching for ".DEPLOY_CONFIG_FILE." config file\n";
	echo "\n";
	echo "  -w, --max-wait             Max wait time in seconds when another instance is busy\n";
	echo "                             set to -1 for no timeout, or 0 to fail immediately\n";
	echo "                             default: 300 (5 minutes)\n";
	echo "\n";
	echo "  -d, --debug                Debug mode, most verbose logging\n";
	echo "  -t, --dry                  Dry run, without writing files\n";
	echo "\n";
	echo "  -h, --help                 Display this help message\n";
	echo "  --no-logo                  Disables the startup logo\n";
	echo "\n";
	exit(0);
}



$NoLogo = FALSE;
$buildNumber = NULL;
$dry = FALSE;
$GoalArgs = array();
for ($i=1; $i<count($argv); $i++) {
	switch ($argv[$i]) {
	case '-b':
	case '--build-number':
		$i++;
		if (Numbers::isNumber($argv[$i])) {
			$buildNumber = (int) $argv[$i];
		} else {
			$buildNumber = NULL;
		}
		break;
	case '-D':
	case '--deploy-config-depth':
//TODO:
		break;
	case '-w':
	case '--max-wait':
//TODO:
		break;
	case '--use-local-phputils': {
		$path = __DIR__.'/../../phpUtils/vendor/autoload.php';
		echo "\n *** Using local development copy of phpUtils! *** \n\n";
		if (!\file_exists($path)) {
			echo "Local copy of phpUtils couldn't be found: {$path}\n";
			exit(1);
		}
		require($path);
	}
	case '-d':
	case '--debug':
//TODO:
		break;
	case '-t':
	case '--dry':
		$dry = TRUE;
		break;
	case '-h':
	case '--help':
		DisplayLogo();
		DisplayHelp();
		exit(0);
	case '--no-logo':
		$NoLogo = TRUE;
		break;
	default:
		if (Strings::StartsWith($argv[$i], '--')) {
			echo "Unknown argument: {$argv[$i]}\n";
			exit(1);
		}
		$GoalArgs[] = $argv[$i];
		break;
	}
}



DisplayLogo();



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
