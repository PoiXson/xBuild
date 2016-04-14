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


class goal_rpm extends goal_shell {

	const RPMBUILD_PATH = '/usr/bin/rpmbuild';
	const BUILD_ROOT = 'rpmbuild-root';



	public function getType() {
		return 'rpm';
	}
	protected function getTitlePrefix() {
		return 'Building';
	}



	public function run() {
		$pathTool  = self::RPMBUILD_PATH;
		$buildroot = self::BUILD_ROOT;
		// check for tools
		if (!\file_exists($pathTool)) {
			fail ("RPM-Build not found! {$pathTool}");
			exit(1);
		}
		// check for project.spec file
		$pwd = Paths::pwd();
		if (empty($pwd)) {
			fail ('Failed to get pwd!');
			exit(1);
		}
		if (!isset($this->args['Spec']) || empty($this->args['Spec'])) {
			fail ('Spec file field not provided in xbuild.json config!');
			exit(1);
		}
		$msgDry = ($this->isDry() ? '##DRY## ' : '');
		$log = $this->getLogger();
		$specFile = $this->args['Spec'];
		$pathConfig = "{$pwd}/{$specFile}";
		$pathConfig = Strings::ForceEndsWith(
				"{$pwd}/{$specFile}",
				'.spec'
		);
		if (!\file_exists($pathConfig)) {
			fail ("{$specFile} file not found in workspace! {$pathConfig}");
			exit(1);
		}
		// build arch
		$arch = $this->args['Arch'];
		if (empty($arch)) {
			$arch = 'noarch';
			$log->info('Defaulting to noarch..');
		}
		// remove old build files
		{
			$path = "{$pwd}/{$buildroot}/";
			if (\is_dir($path)) {
				$log->info("Removing old dir.. {$buildroot}/");
				$cmd = "rm -Rvf --preserve-root '{$path}'";
				if ($this->isDry()) {
//					$log->publish(" {$msgDry}{$cmd}");
				} else {
					$result = $this->runShellCmd($cmd);
					if ($result != 0) {
						fail ("Failed to remove old build dir! {$result} - {$buildroot}/");
						exit(1);
					}
					$log->publish();
				}
			}
			$path = "{$pwd}/target/";
			if (\is_dir($path)) {
				$log->info('Removing old dir.. target/');
				$cmd = "rm -Rvf --preserve-root '{$path}'";
				if ($this->dry) {
//					$log->publish(" {$msgDry}{$cmd}");
				} else {
					$result = $this->runShellCmd($cmd);
					if ($result != 0) {
						fail ("Failed to remove old build dir! {$result} - target/");
						exit(1);
					}
					$log->publish();
				}
			}
		}
		// create build space
		{
			$path = "{$pwd}/{$buildroot}/";
			$log->info("Creating dir.. {$buildroot}/");
			if ($this->isDry()) {
//				$log->publish(" {$msgDry}mkdir '{$path}'");
			} else {
				$result = \mkdir($path, 0775);
				if ($result == FALSE) {
					fail ("Failed to create directory {$path}");
					exit(1);
				}
			}
		}
		{
			$dirs = [
				'BUILD',
				'RPMS',
				'SOURCE',
				'SOURCES',
				'SPECS',
				'SRPMS',
				'tmp'
			];
			foreach ($dirs as $dir) {
				$path = "{$pwd}/{$buildroot}/{$dir}/";
				$log->info("Creating dir.. {$dir}/");
				if ($this->isDry()) {
//					$log->publish(" {$msgDry}mkdir '{$path}'");
				} else {
					$result = \mkdir($path, 0775);
					if ($result == FALSE) {
						fail ("Failed to create directory {$path}");
						exit(1);
					}
				}
			}
		}
		$log->publish();
		// copy spec file
		{
			$log->info("Copying spec file.. {$specFile}");
			if ($this->dry) {
//				$log->publish(" {msgDry}copy '{$pwd}/{$specFile}' '{$pwd}/{$buildroot}/SPECS/'");
			} else {
				$result = \copy(
					"{$pwd}/{$specFile}",
					"{$pwd}/{$buildroot}/SPECS/{$specFile}"
				);
				if (!$result) {
					fail ("Failed to copy spec file! {$specFile}");
					exit(1);
				}
			}
		}
		$log->publish();
//TODO: download source files
//	# download source files
//	if [ ! -z $RPM_SOURCES ]; then
//		for URL in "${RPM_SOURCES[@]}"; do
//			wget -P "${BUILD_ROOT}/SOURCES/" "${URL}" || {
//				BUILD_FAILED=true
//				errcho "Failed to download source! ${URL}"
//				return 1
//			}
//			echo "URL: "$URL
//		done
//		newline
//		newline
//		newline
//	fi
//	if [ -z $RPM_SOURCE ]; then
//		_RPM_SOURCE="${PWD}"
//	else
//		_RPM_SOURCE="${PWD}/${RPM_SOURCE}"
//	fi
		// build the rpm
		$buildNumber = 'x';
		$cmd = [
				'rpmbuild -bb',
				"--target {$arch}",
				"--define='_topdir {$pwd}/{$buildroot}'",
				"--define='_tmppath {$pwd}/{$buildroot}/tmp'",
				"--define='SOURCE_ROOT {$pwd}'",
				"--define='_rpmdir {$pwd}/target'",
				"--define='BUILD_NUMBER {$buildNumber}'",
				"{$pwd}/{$buildroot}/SPECS/{$specFile}"
		];
		if ($this->dry) {
			$msg = \implode("\n {$msgDry}   ", $cmd);
			$log->publish(" {$msgDry}{$msg}");
		} else {
			$result = $this->runShellCmd(
					\implode(' ', $cmd)
			);
			if ($result != 0) {
				$msg = \implode(' ', $cmd);
				fail ("Failed to build rpm! {$result} - {$msg}");
				exit(1);
			}
		}
	}



}
