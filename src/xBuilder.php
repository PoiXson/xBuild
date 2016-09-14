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

use pxn\phpUtils\ShellTools;
use pxn\phpUtils\Numbers;


class xBuilder extends \pxn\phpUtils\app\ShellApp {

//	const BUILD_CONFIG_FILE  = 'xbuild.json';
//	const DEPLOY_CONFIG_FILE = 'xdeploy.json';
//	const GLOBAL_CONFIG_FILE = 'xglobal.json';

	protected $dry         = NULL;
	protected $verbose     = NULL;
	protected $quiet       = NULL;
	protected $maxWait     = NULL;
	protected $buildNumber = NULL;
	protected $profile     = NULL;

	const DEFAULT_DRY      = FALSE;
	const DEFAULT_VERBOSE  = TRUE;
	const DEFAULT_QUIET    = FALSE;
	const DEFAULT_MAX_WAIT = 300;
	const DEFAULT_DEPLOY_CONFIG_DEPTH = 3;
	const DEFAULT_PROFILE  = FALSE;

	protected $deploySearchPath  = NULL;
	protected $deploySearchDepth = NULL;



	public function __construct() {
		parent::__construct();
	}



	protected function doRender() {
		$args = ShellTools::getArgs();

		// debug
		{
			$val = ShellTools::getFlagBool('-d', '--debug');
			if ($val !== NULL) {
				\pxn\phpUtils\debug(TRUE);
			}
		}

		// dry run
		{
			$val = ShellTools::getFlagBool('-t', '--dry');
			if ($val !== NULL) {
				$this->dry = TRUE;
			}
		}

		// verbose - display more log messages
		{
			$val = ShellTools::getFlagBool('-v', '--verbose');
			if ($val !== NULL) {
				$this->verbose = TRUE;
			}
		}

		// quiet - don't display log messages
		{
			$val = ShellTools::getFlagBool('-q', '--quiet');
			if ($val !== NULL) {
				$this->quiet   = TRUE;
			}
		}

		// max wait time
		{
			$val = ShellTools::getFlag('-w', '--max-wait');
			if ($val !== NULL) {
				if (!Numbers::isNumber($val)) {
					fail("Invalid value for --max-wait falg: {$val}"); ExitNow(1);
					return FALSE;
				}
				$this->maxWait = (int) $val;
			}
		}

		// build number
		{
			$val = ShellTools::getFlag('-b', '--build-number');
			if ($val !== NULL) {
				if (!Numbers::isNumber($val)) {
					fail("Invalid value for --build-number falg: {$val}"); ExitNow(1);
					return FALSE;
				}
				$this->buildNumber = (int) $val;
			}
		}

		// path to deploy.json config file
		{
			$val = ShellTools::getFlag('-e', '--deploy-config-path');
			if ($val !== NULL) {
//TODO: san this before use!
fail(__FILE__.' - '.__LINE__.' - UNFINISHED!');
				$this->deploySearchPath = $val;
			}
		}

		// deploy.json search depth
		{
			$val = ShellTools::getFlag('-D', '--deploy-config-depth');
			if ($val !== NULL) {
				if (!Numbers::isNumber($val)) {
					fail("Invalid value for --deploy-config-depth falg: {$val}"); ExitNow(1);
					return FALSE;
				}
				$this->deploySearchDepth = (int) $val;
			}
		}

		// display timing in logs
		{
			$val = ShellTools::getFlagBool('-p', '--profile');
			if ($val !== NULL) {
				$this->profile = TRUE;
			}
		}

echo "\n\n\n";
echo 'DONE!';
echo "\n\n\n";
//TODO:
		// one or more args
		if (\count($args) >= 1) {
			$arg = \strtolower($args[0]);
			switch ($arg) {

			//
			case '':
				break;

			// unknown arg
			default:
				return FALSE;
			}
			$this->setRendered();
			return TRUE;
		}
		return FALSE;
	}



	public function isDry() {
		if ($this->dry === NULL) {
			return self::DEFAULT_DRY;
		}
		return $this->dry;
	}
	public function isVerbose() {
		if ($this->quiet === TRUE) {
			return FALSE;
		}
		if ($this->verbose === NULL) {
			return self::DEFAULT_VERBOSE;
		}
		return $this->verbose;
	}
	public function isQuiet() {
		if ($this->quiet === NULL) {
			return self::DEFAULT_QUIET;
		}
		return $this->quiet;
	}
	public function getMaxWait() {
		if ($this->maxWait === NULL) {
			return self::DEFAULT_MAX_WAIT;
		}
		return $this->maxWait;
	}
	public function getBuildNumber() {
		if ($this->buildNumber === NULL) {
			return 'x';
		}
		return $this->buildNumber;
	}
	// path to deploy.json config file
//TODO: be sure this is cleaned/san
	public function getDeployConfigPath() {
//TODO: san this before use!
fail(__FILE__.' - '.__LINE__.' - UNFINISHED!');
		if ($this->deploy_search_path === NULL) {
			return PATHS::cwd();
		}
		return $this->deploy_search_path;
	}
	public function getDeployConfigDepth() {
		if ($this->deploy_search_depth === NULL) {
			return self::DEFAULT_DEPLOY_CONFIG_DEPTH;
		}
		return $this->deploy_search_depth;
	}
	public function isProfile() {
		if ($this->profile === NULL) {
			return self::DEFAULT_PROFILE;
		}
		return $this->profile;
	}



}
