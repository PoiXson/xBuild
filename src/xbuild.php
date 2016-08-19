#!/usr/bin/php
<?php

if (!\file_exists(__DIR__.'/../pxnloader.php')) {
	echo "\nFile not found: pxnloader.php, run 'composer update'\n";
	exit(1);
}
require(__DIR__.'/../pxnloader.php');

//debug(TRUE);

\pxn\xBuild\xBuilder::init();
