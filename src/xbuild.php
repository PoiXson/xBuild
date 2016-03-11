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

use pxn\phpUtils\Strings;
use pxn\phpUtils\Config;



// defines
const BUILD_CONFIG_FILE  = 'xbuild.json';
const DEPLOY_CONFIG_FILE = 'xdeploy.json';



// find autoloader.php
{
	$SearchPaths = [
		__DIR__,
		__DIR__.'/..',
	];
	$found = FALSE;
	foreach ($SearchPaths as $path) {
		if (empty($path)) continue;
		$path = \realpath($path.'/vendor/');
		if (empty($path)) continue;
		if (\file_exists($path.'/autoload.php')) {
			require($path.'/autoload.php');
			$found = TRUE;
			break;
		}
	}
	if (!$found) {
		echo "\nFailed to find composer autoload.php !\n\n";
		exit(1);
	}
}



// check os
\pxn\phpUtils\System::RequireLinux();

// require shell
if (!Config::isShell()) {
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
	echo '  '.$argv[0].' [options] [goals]'."\n";
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
$BuildNumber = NULL;
$GoalArgs = array();
for ($i=1; $i<count($argv); $i++) {
	switch ($argv[$i]) {
	case '-b':
	case '--build-number':
		$i++;
		if (Numbers::isNumber($argv[$i])) {
			$BuildNumber = (int) $argv[$i];
		} else {
			$BuildNumber = NULL;
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
	case '-d':
	case '--debug':
//TODO:
		break;
	case '-t':
	case '--dry':
//TODO:
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
			echo 'Unknown argument: '.$argv[$i]."\n";
			exit(1);
		}
		$GoalArgs[] = $argv[$i];
		break;
	}
}



DisplayLogo();
echo "\n\n";



// ensure single instance
//TODO:



// load build config
$config = NULL;
{
	$file = BUILD_CONFIG_FILE;
	if (! \file_exists($file)) {
		fail ("Config file not found: {$file}");
		exit(1);
	}
	$config = new config_xbuild();
	if ( ! $config->LoadFile($file) ) {
		fail ("Failed to load config file: {$file}");
		exit(1);
	}
}
// load deploy config
$deploy = NULL;
{
//	$file = DEPLOY_CONFIG_FILE;
//	if (\file_exists($file)) {
//		$deploy = new config_xbuild();
//		if ( ! $deploy->LoadFile($file) ) {
//			echo "Failed to load config file: {$file}\n";
//			exit(1);
//		}
//	}
}



// load builder
$builder = new builder(
	$config
);
$builder->BuildNumber = $BuildNumber;



// load goals
if (count($GoalArgs) == 0) {
	$defaultGoals = $config->getDefaultGoals();
	$builder->LoadGoals($defaultGoals);
} else {
	$builder->LoadGoals($GoalArgs);
}



// run goals
$builder->run();



echo "\nFinished!\n\n";
exit(0);
