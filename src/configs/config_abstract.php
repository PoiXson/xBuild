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

use pxn\phpUtils\Defines;


abstract class config_abstract {

	public $json;



	public function __construct($json=[]) {
		$this->json = $json;
	}



	public function LoadFile($file) {
		if ( empty($file) || !\file_exists($file) ) {
			return FALSE;
		}
		$data = \file_get_contents($file);
		if (empty($data)) {
			fail("Failed to load file: $file",
				Defines::EXIT_CODE_IO_ERROR);
		}
		$result = self::LoadString($data);
		if (!$result) {
			fail("Failed to decode json file: $file",
				Defines::EXIT_CODE_IO_ERROR);
		}
		return TRUE;
	}
	public function LoadString($data) {
		if (empty($data)) {
			return FALSE;
		}
		$json = \json_decode($data, TRUE);
		if ($json == NULL) {
			fail("Failed to decode json data",
				Defines::EXIT_CODE_IO_ERROR);
		}
		$this->json = $json;
		return TRUE;
	}



}
