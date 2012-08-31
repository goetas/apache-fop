<?php
namespace Goetas\ApacheFopBundle\Processor;

/**
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
	
	public function __construct($fopExecutable) {
		$this->setFopExecutable($fopExecutable);
	}
	public function convertToPdf($source, $destination, $xsl = null) {
		return $this->connvert($source, $destination, self::OTUPUT_PDF, $xsl);
	}
	public function convertToRtf($source, $destination, $xsl = null) {
		return $this->connvert($source, $destination, self::OTUPUT_RTF, $xsl);
	}
	public function connvert($source, $destination, $outputFormat, $xsl = null) {
		
		$process = new ProcessBuilder ();
		$process->add ( $this->fopExecutable );
		
		$process->add ( "-q" );
		$process->add ( "-r" );
		
		if($xsl!==null){
			$process->add ( "-xml" );
			$process->add ( $source );
			
			$process->add ( "-xsl" );
			$process->add ( $xsl );
		}else{
			$process->add ( "-fo" );
			$process->add ( $source );
		}
		
		$process->add ( "-out" );
		$process->add ( $outputFormat );
		$process->add ( $destination );
		
		if ($this->configurationFile !== null) {		
			$process->add ( "-c" );
			$process->add ( $this->configurationFile );
		}
		
		$esito = $process->getProcess ()->run (function ($a, $b) {
			//echo $b."<br/>";
		});

		return !($esito>0);
	
	}
	public function getFopExecutable() {
		return $this->fopExecutable;
	}
	
	public function getConfigurationFile() {
		return $this->configurationFile;
	}
	
	public function setFopExecutable($fopExecutable) {
		if(!is_executable($fopExecutable)){
			throw new \RuntimeException(sprintf("Can't find %s command", $fopExecutable));
		}
		$this->fopExecutable = $fopExecutable;
	}
	
	public function setConfigurationFile($configurationFile) {
		if(!is_readable($this->configurationFile)){
			throw new \RuntimeException(sprintf("Can't find configuration file named '%s'", $this->fopExecutable));
		}
			
		$this->configurationFile = $configurationFile;
	}
	public function getJavaExecutable() {
		return $this->javaExecutable;
	}

	public function setJavaExecutable($javaExecutable) {
		$this->javaExecutable = $javaExecutable;
	}


}