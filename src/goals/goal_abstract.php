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


abstract class goal_abstract {

	protected $builder;
	protected $jsonConfig;



	public function __construct($builder, $jsonConfig) {
		if ($builder == NULL) {
			echo "Invalid builder argument provided!\n";
			exit(1);
		}
		if (!\is_array($jsonConfig)) {
			echo "Invalid jsonConfig argument provided!\n";
			exit(1);
		}
		$this->builder = $builder;
		$this->jsonConfig = $jsonConfig;
	}



	public abstract function getName();

	public abstract function run();



	public function title($msg) {
		echo " [[ {$msg} ]] \n";
	}



}
