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


class xBuilder extends \pxn\phpUtils\app\ShellApp {

//	const BUILD_CONFIG_FILE  = 'xbuild.json';
//	const DEPLOY_CONFIG_FILE = 'xdeploy.json';
//	const GLOBAL_CONFIG_FILE = 'xglobal.json';



	public function __construct() {
		parent::__construct();
	}



	protected function doRender() {
		$args = ShellTools::getArgs();
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



}
