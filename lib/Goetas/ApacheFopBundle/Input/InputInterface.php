<?php
namespace Goetas\ApacheFopBundle\Input;
use Symfony\Component\Process\Process;

use Symfony\Component\Process\ProcessBuilder;

interface InputInterface
{
    public function buildParams(ProcessBuilder $proc);
    public function setInput(Process $proc);
}
