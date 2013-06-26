apache-fop-bundle
==========

Symfony2 [Apache FOP](http://xmlgraphics.apache.org/fop/) (XSL-FO)  Bundle

Configuration
--------------------

Add following lines on your **config.xml**
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

// convert FO to PDF
$result = $container->get("goetas.fop")->convertToPdf("source.fo", "output.pdf");

//convert to PDF using XML and XSLT
$result = $container->get("goetas.fop")->convertToPdf("source.xml", "output.pdf", "transform.xsl");
```
