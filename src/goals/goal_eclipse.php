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

use pxn\phpUtils\Strings;
use pxn\phpUtils\Paths;
use pxn\phpUtils\Defines;


abstract class goal_eclipse extends Goal {



	public function getType() {
		return 'eclipse';
	}
	protected function getTitlePrefix() {
		return 'Creating files for';
	}



	protected function createFile($filepath, $filename, $data) {
		if (empty($filepath)) {
			$filepath = '';
		}
		if (empty($filename)) {
			fail ('Filename argument is required!');
			exit(1);
		}
		$pwd = Paths::pwd();
		if (empty($pwd)) {
			fail ('Failed to get pwd!');
			exit(1);
		}
		if (!\is_array($data)) {
			$data = [];
		}
		$log = $this->getLogger();
		$msgDry = ($this->isDry() ? '##DRY## ' : '');
		$path = Strings::BuildPath(
			$pwd,
			$filepath,
			$filename
		);
		$pathDisplay = Strings::BuildPath(
			$filepath,
			$filename
		);
		// build output file contents
		$output = '';
		$linesCount = \count($data);
		foreach($data as $line) {
			$output .= \str_replace('\t', Defines::TAB, $line).Defines::CRLF;
		}
		$log->info("Creating file {$pathDisplay}");
		if (!$this->isDry()) {
			// create directory
			{
				$dirpath = \dirname($path);
				if (!\is_dir($dirpath)) {
					$dirpathDisplay = \substr($dirpath, \strlen($pwd)+1).'/';
					$log->info("Creating dir: {$dirpathDisplay}");
					\mkdir($dirpath);
				}
			}
			// write file
			$result = \file_put_contents(
				$path,
				$output
			);
			if ($result == FALSE) {
				fail ("Failed to write file {$pathDisplay}");
				exit(1);
			}
		}
		$log->fine("Wrote {$linesCount} lines to file {$pathDisplay}");
	}



}
