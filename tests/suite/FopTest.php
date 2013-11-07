<?php

use Goetas\ApacheFopBundle\Processor\Fop;
use Goetas\ApacheFopBundle\Input\FileInput;
use Goetas\ApacheFopBundle\Input\StringInput;
class FopTest extends PHPUnit_Framework_TestCase{
    protected $fop;
    public function setUp()
    {
        $this->fop = new Fop("/usr/bin/fop");
    }
    public function testGet()
    {
        $out = $this->fop->get(new FileInput(__DIR__."/resources/simple.fo"), Fop::OTUPUT_PDF);
        $this->assertGreaterThan(10, strlen($out));

        $out = $this->fop->get(new FileInput(__DIR__."/resources/simple.fo"), Fop::OTUPUT_PDF, __DIR__."/resources/clone.xsl");
        $this->assertGreaterThan(10, strlen($out));

        $out = $this->fop->get(new FileInput(__DIR__."/resources/simple.fo"), Fop::OTUPUT_PDF, new FileInput(__DIR__."/resources/clone.xsl"));
        $this->assertGreaterThan(10, strlen($out));

        $xml = file_get_contents(__DIR__."/resources/simple.fo");
        $out = $this->fop->get(new StringInput($xml), Fop::OTUPUT_PDF, new FileInput(__DIR__."/resources/clone.xsl"));
        $this->assertGreaterThan(10, strlen($out));
    }
    /**
     * @expectedException RuntimeException
     */
    public function testGetXslNotString()
    {
        $xsl = file_get_contents(__DIR__."/resources/clone.xsl");

        $out = $this->fop->get(new FileInput(__DIR__."/resources/simple.fo"), Fop::OTUPUT_PDF, new StringInput($xsl));
        $this->assertGreaterThan(10, strlen($out));
    }
}