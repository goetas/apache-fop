apache-fop-bundle
==========

Symfony2 [Apache FOP](http://xmlgraphics.apache.org/fop/) (XSL-FO)  Bundle

Configuration
--------------------

Add following lines on your **config.yml**
```
goetas_apache_fop:
    executable: /install_path_to_apache_fop/fop
    config: ../../path_to_optional_config_xml
```


Add this to **AppKernel.php**
```
new Goetas\ApacheFopBundle\GoetasApacheFopBundle();
```

Add this to your **autoloader** (if not using composer)
```
'Goetas\ApacheFopBundle' => $vendorDir . '/goetas/apache-fop/Goetas/GoetasApacheFop/lib/'
```


Usage
--------------------

```php
$service = $container->get("goetas.fop");
// convert FO to PDF or RTF
$service->convert("source.fo", "output.pdf", "appplication/pdf");
$service->convert(new FileInput("source.fo"), "output.pdf", "text/rtf");

//convert to PDF using XML and XSLT and with params
$service->convert("source.xml", "output.pdf", "appplication/pdf", "transform.xsl", array("paramName"=>"paramValue"));

//convert reading a FOP input from a string
$service->convert(new StringInput("source.fo"), "output.pdf", "appplication/pdf");

//convert reading a FOP input from a string, and get the result
$service->get(new StringInput("... fo data ..."), "appplication/pdf");

//convert reading a FOP input from a string, and output the result
$service->out(new StringInput("... fo data ..."), "appplication/pdf");

//convert reading a FO data from a file, and output the result (plus xsl)
$service->out(new FileInput("... fo data ..."), "text/rtf", "transform.xsl", array("paramName"=>"paramValue"));


```
