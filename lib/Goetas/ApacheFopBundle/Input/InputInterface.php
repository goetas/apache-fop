<?php
namespace Goetas\ApacheFopBundle\Input;
use Symfony\Component\Process\Process;

use Symfony\Component\Process\ProcessBuilder;

interface InputInterface{
	function buildParams(ProcessBuilder $proc);
	function setInput(Process $proc);
}