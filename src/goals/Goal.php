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


abstract class Goal {

	protected $builder;
	protected $jsonConfig;
	protected $goalArgs;



	public function __construct($builder, $jsonConfig, $goalArgs) {
		if ($builder == NULL) {
			fail ('Invalid builder argument provided!');
			exit(1);
		}
		if (!\is_array($jsonConfig)) {
			fail ('Invalid jsonConfig argument provided!');
			exit(1);
		}
		$this->builder = $builder;
		$this->jsonConfig = $jsonConfig;
		if (\is_array($goalArgs)) {
			$this->goalArgs = $goalArgs;
		} else {
			$args = explode(':', $goalArgs);
			$this->goalArgs = array();
			foreach ($args as $arg) {
				if (\strpos($arg, '=') !== FALSE) {
					list($key, $val) = \explode('=', $arg);
					$this->goalArgs[$key] = $val;
				} else {
					$this->goalArgs[] = $arg;
				}
			}
		}
	}



	public abstract function getName();

	public abstract function run();



	public function title($msg) {
		echo " [[ {$msg} ]] \n";
	}



}
