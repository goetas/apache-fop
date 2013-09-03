<?php
namespace Goetas\ApacheFopBundle\Input;
use Symfony\Component\Process\Process;

use Symfony\Component\Process\ProcessBuilder;

class StringInput implements InputInterface{
    private $resource;
    public function __construct($resource) {
    	$this->resource = $resource;
    }
	function buildParams(ProcessBuilder $proc){
	    $proc->add("-");
	}
	function setInput(Process $proc){
	    $proc->setStdin($this->resource);
	}
}