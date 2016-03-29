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

use pxn\phpUtils\Strings;


trait GoalShell {

	protected $process = NULL;
	protected $pid     = NULL;
	protected $result  = NULL;



	protected function runShellHex($args, $MaxCommands=16) {
		if (!\is_array($args)) {
			fail ('Invalid args argument provided to runShellHex() function!');
			exit(1);
		}
		$result = 0;
		$index  = 0;
		$countSuccess = 0;
		while (TRUE) {
			// hex step index
			$hexIndex = \dechex($index++);
			// skip or break
			if (!isset($args[$hexIndex])) {
				if ($index == 0)
					continue;
				break;
			}
			if (empty($args[$hexIndex]))
				continue;
			// run command
			$cmd = $args[$hexIndex];
			if (Strings::StartsWith($cmd, '#')) {
				continue;
			}
			$msgDry = ($this->dry ? '##DRY## ' : '');
			echo " {$msgDry}[ CMD {$hexIndex} ] {$cmd}\n";
//			echo " CMD [ {$hexIndex} ] {$cmd}\n";
			if ($this->dry) {
//				echo " ### Dry run, command skipped ### \n";
				echo "  ...  ... \n";
			} else {
				$result = $this->runShellCmd($cmd);
				if ($result != 0) {
					fail ("Failed to run composer command: {$result} - {$cmd}");
					return $result;
				}
			}
			$countSuccess++;
			echo "\n";
			// max reached
			if ($countSuccess >= $MaxCommands) {
				$hexIndex = \dechex($index + 1);
				// more to go
				if (isset($args[$hexIndex])) {
					fail ('Error!!! Reached max allowable commands, but more haven\'t been run!');
					exit(1);
				} else {
					echo "Reached max allowable commands.\n";
				}
				// finished
				break;
			}
		}
		$msgS = ($countSuccess > 1 ? 's' : '');
		echo " Finished [ {$countSuccess} ] command{$msgS}!\n";
		return $result;
	}
	protected function runShellCmd($cmd) {
		if (Strings::StartsWith($cmd, '#')) {
			return 0;
		}
		$this->process = NULL;
		$this->pid     = NULL;
		$this->result  = NULL;
		// new process
		$this->process = new Process($cmd);
		$this->process->setTimeout(3600);   // 1 hour active
		$this->process->setIdleTimeout(60); // 1 minute idle
		$this->process->start();
		$this->pid = $this->process->getPid();
		echo " PID: {$this->pid}\n";
		echo "\n";
		// wait for finish
		$this->result = $this->process->wait(
			function ($type, $buffer) {
				$lines = \explode(
					"\n",
					\str_replace("\r", '', $buffer)
				);
				// std error
				if ($type === Process::ERR) {
					foreach ($lines as $line) {
						if (empty($line)) continue;
						echo "ERR> {$line}\n";
					}
				// std out
				} else {
					foreach ($lines as $line) {
						if (empty($line)) continue;
						echo "OUT> {$line}\n";
					}
				}
			}
		);
		if ($this->result !== 0) {
			echo " EXIT CODE: {$this->result}\n";
		}
		$this->pid = NULL;
		// fail
		if (!$this->process->isSuccessful()) {
			if ($this->result === 0) {
				$this->result = 1;
			}
			throw new ProcessFailedException($process);
		}
		return $this->result;
	}



}
