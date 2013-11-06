<?php
namespace Goetas\ApacheFopBundle\Input;
use Symfony\Component\Process\Process;

use Symfony\Component\Process\ProcessBuilder;

class FileInput implements InputInterface
{
    private $file;
    public function __construct($file)
    {
        $this->file = $file;
    }
    public function buildParams(ProcessBuilder $proc)
    {
        $proc->add($this->file);
    }
    public function setInput(Process $proc)
    {
    }
}
