<?php

namespace Goetas\ApacheFopBundle\Processor;

use Symfony\Component\Process\Process;

use Goetas\ApacheFopBundle\Input\FileInput;

use Goetas\ApacheFopBundle\Input\InputInterface;

/**
 *
 * @author goetas <http://www.goetas.com/>
 */
use Symfony\Component\Process\ProcessBuilder;

class Fop
{
    const OTUPUT_PDF = 'application/pdf';
    const OTUPUT_RTF = 'text/rtf';

    protected $fopExecutable;
    protected $javaExecutable;
    protected $configurationFile;
    public function __construct($fopExecutable)
    {
        $this->setFopExecutable ( $fopExecutable );
    }
    public function convertToPdf($source, $destination, $xsl = null)
    {
        return $this->convert ( $source, $destination, self::OTUPUT_PDF, $xsl );
    }
    public function convertToRtf($source, $destination, $xsl = null)
    {
        return $this->convert ( $source, $destination, self::OTUPUT_RTF, $xsl );
    }
    public function convert($source, $destination, $outputFormat, $xsl = null, array $params = array())
    {
        if (is_string($source)) {
            $source = new FileInput($source);
        }
        if (is_string($xsl)) {
            $xsl = new FileInput($xsl);
        }

        $process = $this->runProcess($source, $destination, $outputFormat, $xsl, $params);
        $process->run();
        if (!$process->isSuccessful()) {
            $e = new \Exception ( "Apache FOP exception.\n" . $process->getErrorOutput() );
            throw new \RuntimeException ( "Can't generate the document", null, $e );
        }

        return true;
    }

    public function get(InputInterface $source, $outputFormat, InputInterface $xsl = null, array $params = array())
    {
        $process = $this->runProcess(new FileInput($source), "-", $outputFormat, $xsl?new FileInput($xsl):null, $params);
        $process->run();
        if (!$process->isSuccessful()) {
            $e = new \Exception ( "Apache FOP exception.\n" . $process->getErrorOutput() );
            throw new \RuntimeException ( "Can't generate the document", null, $e );
        }

        return $process->getOutput();
    }
    public function out(InputInterface $source, $outputFormat, $xsl = null, array $params = array())
    {
        $process = $this->runProcess($source, "-", $outputFormat, $xsl?new FileInput($xsl):null, $params);
        $process->run(function ($type, $buffer) {
            if (Process::OUT === $type) {
                echo $buffer;
            }
        });
        if (!$process->isSuccessful()) {
            $e = new \Exception ( "Apache FOP exception.\n" . $process->getErrorOutput() );
            throw new \RuntimeException ( "Can't generate the document", null, $e );
        }

        return true;
    }
    public function callback(InputInterface $source, $callback, $outputFormat, $xsl = null, array $params = array())
    {
        $process = $this->runProcess($source, "-", $outputFormat, $xsl?new FileInput($xsl):null, $params);
        $process->run(function ($type, $buffer) {
            if (Process::OUT === $type) {
                $callback($buffer);
            }
        });
        if (!$process->isSuccessful()) {
            $e = new \Exception ( "Apache FOP exception.\n" . $process->getErrorOutput() );
            throw new \RuntimeException ( "Can't generate the document", null, $e );
        }

        return true;
    }

    /**
     * Build a ProcessBuilder user to run FO conversion
     * @param  string                                    $source
     * @param  string                                    $destination
     * @param  string                                    $outputFormat
     * @param  string                                    $xsl
     * @param  array                                     $params
     * @return \Symfony\Component\Process\ProcessBuilder
     */
    protected function runProcess(InputInterface $input, $output,  $outputFormat, InputInterface $xsl = null, array $params = array())
    {
        $builder = new ProcessBuilder ();
        $builder->add ( $this->fopExecutable );

        $builder->add ( "-q" );
        $builder->add ( "-r" );

        if ($xsl instanceof InputInterface) {
            $builder->add ( "-xml" );
            $input->buildParams($builder);

            $builder->add ( "-xsl" );
            $xsl->buildParams($builder);
        } else {
            $builder->add ( "-fo" );
            $input->buildParams($builder);
        }

        foreach ($params as $key => $value) {
            $builder->add ( "-param" );
            $builder->add ( $key );
            $builder->add ( $value );
        }

        $builder->add ( "-out" );
        $builder->add ( $outputFormat );
        $builder->add ( $output );

        if ($this->configurationFile !== null) {
            $builder->add ( "-c" );
            $builder->add ( $this->configurationFile );
        }

        $proc = $builder->getProcess();

        $input->setInput($proc);

        if ($xsl instanceof InputInterface) {
            $xsl->setInput($proc);
        }

        return $proc;
    }

    public function getFopExecutable()
    {
        return $this->fopExecutable;
    }
    public function getConfigurationFile()
    {
        return $this->configurationFile;
    }
    public function setFopExecutable($fopExecutable)
    {
        if (! is_executable ( $fopExecutable )) {
            throw new \RuntimeException ( sprintf ( "Can't find %s command", $fopExecutable ) );
        }
        $this->fopExecutable = $fopExecutable;
    }
    public function setConfigurationFile($configurationFile)
    {
        if (! is_readable ( $configurationFile )) {
            throw new \RuntimeException ( sprintf ( "Can't find configuration file named '%s'", $configurationFile ) );
        }
        $this->configurationFile = $configurationFile;
    }
    public function getJavaExecutable()
    {
        return $this->javaExecutable;
    }
    public function setJavaExecutable($javaExecutable)
    {
        $this->javaExecutable = $javaExecutable;
    }
}
