<?php
namespace Goetas\ApacheFopBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('goetas_apache_fop');
        $this->readConfiguration($rootNode);
        return $treeBuilder;
    }
    private function readConfiguration(ArrayNodeDefinition $node)
    {

    	$node
            ->children()
				->scalarNode('executable')->defaultNull()->end()
			    ->scalarNode('java')->defaultNull()->end()
			    ->scalarNode('config')->defaultNull()->end()
            ->end()
            ;
    			
    }

}
