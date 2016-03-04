<?php
/*
 * PoiXson xBuild - Build and deploy tools
 *
 * @copyright 2015-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\xBuild\configs;


class config_xbuild extends config_abstract {



	public function getDefaultGoals() {
		if (isset($this->json['Default Goals'])) {
			return $this->json['Default Goals'];
		}
		return array();
	}



	public function getGoalConfig($goalName) {
		if (empty($goalName)) {
			return NULL;
		}
		if (\strpos($goalName, ':') !== FALSE) {
			list($goalName, $tmp) = \explode(':', $goalName, 2);
			unset($tmp);
		}
		if (!isset($this->json['Goals'][$goalName])) {
			echo "Goal not configured: {$goalName}\n";
			return NULL;
		}
		return $this->json['Goals'][$goalName];
	}



}
