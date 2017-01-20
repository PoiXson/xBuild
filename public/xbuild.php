#!/usr/bin/php
<?php

if (!\file_exists(__DIR__.'/../pxnloader.php')) {
	echo "\nFile not found: pxnloader.php, run composer install\n\n";
	exit(1);
}
require(__DIR__.'/../pxnloader.php');

// uncomment to force debug mode
//debug(TRUE);

$app = register_app('pxn\\xBuild\\xBuilder');
