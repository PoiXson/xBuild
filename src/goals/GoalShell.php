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

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


trait GoalShell {

	protected $process = NULL;



	protected function runShell($cmd) {
		$this->process = NULL;
		$this->process = new Process($cmd);
		$this->process->start();
		$this->process->wait(function ($type, $buffer) {
			$lines = \explode(
					"\n",
					\str_replace("\r", '', $buffer)
			);
			if ($type === Process::ERR) {
				foreach ($lines as $line) {
					if (empty($line)) continue;
					echo "ERR> {$line}\n";
				}
			} else {
				foreach ($lines as $line) {
					if (empty($line)) continue;
					echo "OUT> {$line}\n";
				}
			}
		});
		if (!$this->process->isSuccessful()) {
			//throw new ProcessFailedException($process);
			return FALSE;
		}
		return TRUE;
	}



}
