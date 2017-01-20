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
use pxn\phpUtils\Defines;


trait GoalShell {

	protected $process = NULL;
	protected $pid     = NULL;
	protected $result  = NULL;



	protected function runShellHex($args, $MaxCommands=16) {
		if (!\is_array($args)) {
			fail('Invalid args argument provided to runShellHex() function!',
				Defines::EXIT_CODE_INVALID_ARGUMENT);
		}
		$log = $this->getLogger();
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
			$msgDry = ($this->isDry() ? '##DRY## ' : '');
			$log->publish("{$msgDry}[ CMD $hexIndex ] $cmd");
//			$log->info("CMD [ $hexIndex ] $cmd");
			if ($this->dry) {
//				$log->publish("### Dry run, command skipped ### ");
				$log->publish(' ...  ... ');
			} else {
				$result = $this->runShellCmd($cmd);
				if ($result != 0) {
					fail("Failed to run composer command: $result - $cmd",
						Defines::EXIT_CODE_INTERNAL_ERROR);
				}
			}
			$countSuccess++;
			$log->publish();
			// max reached
			if ($countSuccess >= $MaxCommands) {
				$hexIndex = \dechex($index + 1);
				// more to go
				if (isset($args[$hexIndex])) {
					fail('Error!!! Reached max allowable commands, but more haven\'t been run!',
						Defines::EXIT_CODE_INTERNAL_ERROR);
				} else {
					$log->warning('Reached max allowable commands.');
				}
				// finished
				break;
			}
		}
		$msgS = ($countSuccess > 1 ? 's' : '');
		$log->info("Finished [ $countSuccess ] command{$msgS}!");
		return $result;
	}
	protected function runShellCmd($cmd) {
		if (Strings::StartsWith($cmd, '#')) {
			return 0;
		}
		$log = $this->getLogger();
		$this->process = NULL;
		$this->pid     = NULL;
		$this->result  = NULL;
		// new process
		$this->process = new Process($cmd);
		$this->process->setTimeout(3600);   // 1 hour active
		$this->process->setIdleTimeout(60); // 1 minute idle
		$this->process->start();
		$this->pid = $this->process->getPid();
		$log->info("PID: {$this->pid}");
		$log->publish();
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
						$log->err("ERR> $line");
					}
				// std out
				} else {
					foreach ($lines as $line) {
						if (empty($line)) continue;
						$log->out("OUT> $line");
					}
				}
			}
		);
		if ($this->result !== 0) {
			$log->warning("EXIT CODE: {$this->result}");
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



	public function getLogger() {
		return parent::getLogger();
	}



}
