<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\DependencyInjection\Compiler;

use Meritoo\MenuBundle\Visitor\Factory\Collection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers factories of visitors
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class VisitorFactoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(Collection::class)) {
            return;
        }

        $servicesIds = $container->findTaggedServiceIds('meritoo_menu.visitor_factory');
        $definition = $container->findDefinition(Collection::class);

        foreach ($servicesIds as $id => $tags) {
            $definition->addMethodCall('addVisitorFactory', [new Reference($id)]);
        }
    }
}
