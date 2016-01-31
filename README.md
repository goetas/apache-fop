apache-fop-bundle
==========

Symfony2 [Apache FOP](http://xmlgraphics.apache.org/fop/) (XSL-FO)  Bundle

[![Downloads](https://poser.pugx.org/goetas/apache-fop/d/total.png)](https://packagist.org/packages/goetas/apache-fop)
[![Latest Stable Version](https://poser.pugx.org/goetas/apache-fop/version.png)](https://packagist.org/packages/goetas/apache-fop)
[![Latest Unstable Version](https://poser.pugx.org/goetas/apache-fop/v/unstable.png)](https://packagist.org/packages/goetas/apache-fop)
[![Build Status](https://travis-ci.org/goetas/apache-fop.png?branch=master)](https://travis-ci.org/goetas/apache-fop)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/goetas/apache-fop/badges/quality-score.png?s=a53e4e834159a28faddac60e5b38be33db72c3f0)](https://scrutinizer-ci.com/g/goetas/apache-fop/)

Installing (composer)
--------------------

Add following lines on your **composer.json**
```
"requre":{
    "goetas/apache-fop": "1.0.*",
}
```


Configuration
--------------------

Add following lines on your **config.yml**
```
goetas_apache_fop:
    executable: /install_path_to_apache_fop/fop
    java_home: /usr/local # optional, define JAVA_HOME for fop executable
    java_options: -Xmx512M # optional, define _JAVA_OPTIONS for fop executable
    config: ../../path_to_optional_config_xml
```


Add this to **AppKernel.php**
```
new Goetas\ApacheFopBundle\GoetasApacheFopBundle();
```

Add this to your **autoloader** (only if not using composer)
```
'Goetas\ApacheFopBundle' => $vendorDir . '/goetas/apache-fop/Goetas/GoetasApacheFop/lib/'
```


Usage
--------------------

```php
$service = $container->get("goetas.fop");
// convert FO to PDF or RTF
$service->convert("source.fo", "output.pdf", "application/pdf");
$service->convert(new FileInput("source.fo"), "output.pdf", "text/rtf");

//convert to PDF using XML and XSLT and with params
$service->convert("source.xml", "output.pdf", "application/pdf", "transform.xsl", array("paramName"=>"paramValue"));
//convert to PDF using XML and XSLT (reading xsl from string)
$service->convert("source.xml", "output.pdf", "application/pdf", new StringInput(" ... xsl string ..."));

//convert reading a FOP input from a string
$service->convert(new StringInput("source.fo"), "output.pdf", "application/pdf");


//convert reading a FOP input from a string, and get the result
$service->get(new StringInput("... fo data ..."), "application/pdf");

//convert reading a FOP input from a string, and output the result
$service->out(new StringInput("... fo data ..."), "application/pdf");

//convert reading a FO data from a file, and output the result (plus xsl)
$service->out(new FileInput("... fo data ..."), "text/rtf", "transform.xsl", array("paramName"=>"paramValue"));


```


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/goetas/apache-fop/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

