<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration of this bundle
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('meritoo_menu');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->append($this->getTemplatesNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Returns the "templates" node.
     * Parameters to with templates used to render menu.
     *
     * @return NodeDefinition
     */
    private function getTemplatesNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('templates');

        return $treeBuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('link')
                    ->info('Template of link in menu')
                    ->defaultValue('<a href="%%url%%"%%attributes%%>%%name%%</a>')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('link_container')
                    ->info('Template of container for a link')
                    ->defaultValue('<div%%attributes%%>%%link%%</div>')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('menu')
                    ->info('Template of the whole menu (has containers with links)')
                    ->defaultValue('<div%%attributes%%>%%linksContainers%%</div>')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;
    }
}
