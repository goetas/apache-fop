<?php

namespace Goetas\ApacheFopBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class GoetasApacheFopExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration ();
        $config = $this->processConfiguration ( $configuration, $configs );

        $loader = new Loader\XmlFileLoader ( $container, new FileLocator ( __DIR__ . '/../Resources/config' ) );
        $loader->load ( 'services.xml' );
        $this->createServices ( $config, $container );
    }
    protected function createServices($config, ContainerBuilder $container)
    {
        $definition = new Definition ( '%goetas.fop.processor.class%' );

        $definition->setArguments ( array ($config ['executable'] ) );

        if ($config["config"]!==null) {
            $definition->addMethodCall("setConfigurationFile", array($config["config"]));
        }

        if ($config["java"]!==null) {
            $definition->addMethodCall("setJavaExecutable", array($config["config"]));
        }

        $definition->setPublic ( true );
        $container->setDefinition("goetas.fop", $definition);
    }

}
