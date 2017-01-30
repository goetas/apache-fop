<?php
namespace Goetas\ApacheFopBundle\Input;
use Symfony\Component\Process\Process;

use Symfony\Component\Process\ProcessBuilder;

class StringInput implements InputInterface
{
    private $resource;
    public function __construct($resource)
    {
        $this->resource = $resource;
    }
    public function buildParams(ProcessBuilder $proc)
    {
        $proc->add("-");
    }
    public function setInput(Process $proc)
    {
        $proc->setInput($this->resource);
    }
}
