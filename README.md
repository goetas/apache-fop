apache-fop
==========

Symfony2 Apache FOP Bundle

Configuration
--------------------

Add following lines on your config.xml
```
goetas_apache_fop:
    executable: /install_path_to_apache_fop/fop
    config: ../../path_to_optional_config_xml
```


Usage
--------------------

```php
$result = $container->get("goetas.fop")->convertToPdf("source.fo", "output.pdf");
```