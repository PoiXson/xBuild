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


class goal_eclipse_php extends goal_eclipse {

	// .perfs file
	const PERFS_PATH = '.settings/';
	const PERFS_FILE = 'org.eclipse.php.core.perfs';
	const PERFS_DATA = [
			'eclipse.preferences.version=1',
			'include_path=0;/phpUtils'
	];

	// core.xml file
	const COREXML_PATH = '.settings/';
	const COREXML_FILE = 'org.eclipse.wst.common.project.facet.core.xml';
	const COREXML_DATA = [
			'<?xml version="1.0" encoding="UTF-8"?>',
			'<faceted-project>',
			'  <fixed facet="php.core.component"/>',
			'  <fixed facet="php.component"/>',
			'  <installed facet="php.core.component" version="1"/>',
			'  <installed facet="php.component" version="7"/>',
			'</faceted-project>'
	];

	// .buildpath file
	const BUILDPATH_PATH = '';
	const BUILDPATH_FILE = '.buildpath';
	const BUILDPATH_DATA = [
			'<?xml version="1.0" encoding="UTF-8"?>',
			'<buildpath>',
			'\t<buildpathentry kind="src" path=""/>',
			'\t<buildpathentry kind="con" path="org.eclipse.php.core.LANGUAGE"/>',
			'</buildpath>'
	];

	// .project file
	const PROJECT_PATH = '';
	const PROJECT_FILE = '.project';
	const PROJECT_DATA = [
			'<?xml version="1.0" encoding="UTF-8"?>',
			'<projectDescription>',
			'\t<name>phpUtils</name>',
			'\t<comment></comment>',
			'\t<projects>',
			'\t</projects>',
			'\t<buildSpec>',
			'\t\t<buildCommand>',
			'\t\t\t<name>org.eclipse.wst.common.project.facet.core.builder</name>',
			'\t\t\t<arguments>',
			'\t\t\t</arguments>',
			'\t\t</buildCommand>',
			'\t\t<buildCommand>',
			'\t\t\t<name>org.eclipse.wst.validation.validationbuilder</name>',
			'\t\t\t<arguments>',
			'\t\t\t</arguments>',
			'\t\t</buildCommand>',
			'\t\t<buildCommand>',
			'\t\t\t<name>org.eclipse.dltk.core.scriptbuilder</name>',
			'\t\t\t<arguments>',
			'\t\t\t</arguments>',
			'\t\t</buildCommand>',
			'\t</buildSpec>',
			'\t<natures>',
			'\t\t<nature>org.eclipse.php.core.PHPNature</nature>',
			'\t\t<nature>org.eclipse.wst.common.project.facet.core.nature</nature>',
			'\t</natures>',
			'</projectDescription>'
	];



	public function getType() {
		return 'eclipse-php';
	}



	public function run() {
		// create .perfs file
		$this->createFile(
				self::PERFS_PATH,
				self::PERFS_FILE,
				self::PERFS_DATA
				);
		// create core.xml file
		$this->createFile(
				self::COREXML_PATH,
				self::COREXML_FILE,
				self::COREXML_DATA
				);
		// create .buildpath file
		$this->createFile(
				self::BUILDPATH_PATH,
				self::BUILDPATH_FILE,
				self::BUILDPATH_DATA
				);
		// create .project file
		$this->createFile(
				self::PROJECT_PATH,
				self::PROJECT_FILE,
				self::PROJECT_DATA
				);
	}



}
