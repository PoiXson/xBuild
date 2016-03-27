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


class UsingDefaultGoalsEnum extends \pxn\phpUtils\BasicEnum {

	const USING_DEFINED_GOALS  = 'DEFINED';
	const USING_DEFAULT_BUILD  = 'BUILD';
	const USING_DEFAULT_DEPLOY = 'DEPLOY';

}
