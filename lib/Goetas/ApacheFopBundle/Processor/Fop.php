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
    /**
     * Convert to PDF reading a FOP input and save the result into file
     * @param  InputInterface|string $source
     * @param  string                $xsl
     * @throws \RuntimeException
     * @deprecated
     */
    public function convertToPdf($source, $destination, $xsl = null)
    {
        return $this->convert ( $source, $destination, self::OTUPUT_PDF, $xsl );
    }
    /**
     * Convert to RTF reading a FOP input and save the result into file
     * @param  InputInterface|string $source
     * @param  string                $xsl
     * @throws \RuntimeException
     * @deprecated
     */
    public function convertToRtf($source, $destination, $xsl = null)
    {
        return $this->convert ( $source, $destination, self::OTUPUT_RTF, $xsl );
    }
    /**
     * Convert reading a FOP input, saving the result into file
     * @param  InputInterface|string $source
     * @param  string                $destination
     * @param  string|const          $outputFormat self::OTUPUT_PDF or self::OTUPUT_RTF or other supported mimes by Apache FOP
     * @param  InputInterface|string $xsl
     * @param  array                 $params
     * @throws \RuntimeException
     */
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

    /**
     * Convert reading a FOP input, and get the result
     * @param  InputInterface        $source
     * @param  string                $outputFormat self::OTUPUT_PDF or self::OTUPUT_RTF or other supported mimes by Apache FOP
     * @param  InputInterface|string $xsl
     * @param  array                 $params
     * @return string
     * @throws \RuntimeException
     */
    public function get(InputInterface $source, $outputFormat, $xsl = null, array $params = array())
    {
        if (is_string($xsl)) {
            $xsl = new FileInput($xsl);
        }
        $process = $this->runProcess($source, "-", $outputFormat, $xsl, $params);
        $process->run();
        if (!$process->isSuccessful()) {
            $e = new \Exception ( "Apache FOP exception.\n" . $process->getErrorOutput() );
            throw new \RuntimeException ( "Can't generate the document", null, $e );
        }

        return $process->getOutput();
    }
    /**
     * Convert reading a FOP input, and flush to output the result
     * @param  InputInterface        $source
     * @param  string                $outputFormat self::OTUPUT_PDF or self::OTUPUT_RTF or other supported mimes by Apache FOP
     * @param  InputInterface|string $xsl
     * @param  array                 $params
     * @throws \RuntimeException
     */
    public function out(InputInterface $source, $outputFormat, $xsl = null, array $params = array())
    {
        if (is_string($xsl)) {
            $xsl = new FileInput($xsl);
        }
        $process = $this->runProcess($source, "-", $outputFormat, $xsl, $params);
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
    /**
     * Convert reading a FOP input, and flush to output the result
     * @param  InputInterface    $source
     * @param  callback          $callback     will recieve the output
     * @param  string            $outputFormat self::OTUPUT_PDF or self::OTUPUT_RTF or other supported mimes by Apache FOP
     * @param  string            $xsl
     * @param  array             $params
     * @throws \RuntimeException
     */
    public function callback(InputInterface $source, $callback, $outputFormat, $xsl = null, array $params = array())
    {
        if (is_string($xsl)) {
            $xsl = new FileInput($xsl);
        }
        $process = $this->runProcess($source, "-", $outputFormat, $xsl, $params);
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
     *
     *
     * @param  InputInterface                            $input Ony $input can use StringInput
     * @param  string                                    $destination  The place where save the result
     * @param  string                                    $outputFormat
     * @param  InputInterface                            $xsl
     * @param  array                                     $params
     * @return \Symfony\Component\Process\Process
     */
    protected function runProcess(InputInterface $input, $destination,  $outputFormat, InputInterface $xsl = null, array $params = array())
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
        $builder->add ( $destination );

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
    /**
     * Get the path for FOP executable
     * @return string
     */
    public function getFopExecutable()
    {
        return $this->fopExecutable;
    }
    /**
     * Get the path of FOP config file
     * @return string
     */
    public function getConfigurationFile()
    {
        return $this->configurationFile;
    }
    /**
     * SET the path for FOP executable
     * @return self
     */
    public function setFopExecutable($fopExecutable)
    {
        if (! is_executable ( $fopExecutable )) {
            throw new \RuntimeException ( sprintf ( "Can't find %s command", $fopExecutable ) );
        }
        $this->fopExecutable = $fopExecutable;

        return $this;
    }
    /**
     * Set the path of FOP config file
     * @param  string $configurationFile
     * @return self
     */
    public function setConfigurationFile($configurationFile)
    {
        if (! is_readable ( $configurationFile )) {
            throw new \RuntimeException ( sprintf ( "Can't find configuration file named '%s'", $configurationFile ) );
        }
        $this->configurationFile = $configurationFile;

        return $this;
    }
    /**
     * Get the path for Java executable
     * @return string
     */
    public function getJavaExecutable()
    {
        return $this->javaExecutable;
    }
    /**
     * SET the path for Java executable
     * @param  string $javaExecutable
     * @return self
     */
    public function setJavaExecutable($javaExecutable)
    {
        $this->javaExecutable = $javaExecutable;

        return $this;
    }
}
