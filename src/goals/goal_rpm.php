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
		$msgDry = ($this->dry ? '##DRY## ' : '');
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
			echo "Defaulting to noarch..\n";
		}
		// remove old build files
		{
			$path = "{$pwd}/{$buildroot}/";
			if (\is_dir($path)) {
				echo " Removing old dir.. {$buildroot}/\n";
				$cmd = "rm -Rvf --preserve-root '{$path}'";
				if ($this->dry) {
//					echo " {$msgDry}{$cmd}";
				} else {
					$result = $this->runShellCmd($cmd);
					if ($result != 0) {
						fail ("Failed to remove old build dir! {$result} - {$buildroot}/");
						exit(1);
					}
				}
			}
			$path = "{$pwd}/target/";
			if (\is_dir($path)) {
				echo " Removing old dir.. target/\n";
				$cmd = "rm -Rvf --preserve-root '{$path}'";
				if ($this->dry) {
//					echo " {$msgDry}{$cmd}";
				} else {
					$result = $this->runShellCmd($cmd);
					if ($result != 0) {
						fail ("Failed to remove old build dir! {$result} - target/");
						exit(1);
					}
				}
			}
		}
		// create build space
		{
			$path = "{$pwd}/{$buildroot}/";
			echo " Creating dir.. {$buildroot}/\n";
			if ($this->dry) {
//				echo " {$msgDry}mkdir '{$path}'\n";
			} else {
				$result = \mkdir($path, 0775);
				if (!$result) {
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
				echo " Creating dir.. {$dir}/\n";
				if ($this->dry) {
//					echo " {$msgDry}mkdir '{$path}'\n";
				} else {
					$result = \mkdir($path, 0775);
					if (!$result) {
						fail ("Failed to create directory {$path}");
						exit(1);
					}
				}
			}
		}
		// copy spec file
		{
			echo " Copying spec file.. {$specFile}\n";
			if ($this->dry) {
//				echo " {msgDry}copy '{$pwd}/{$specFile}' '{$pwd}/{$buildroot}/SPECS/'\n";
			} else {
				$result = \copy(
					"{$pwd}/{$specFile}",
					"{$pwd}/{$buildroot}/SPECS/"
				);
				if (!$result) {
					fail ("Failed to copy spec file! {$specFile}");
					exit(1);
				}
			}
		}
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
			echo " {$msgDry}{$msg}\n";
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
