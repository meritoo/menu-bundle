<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Twig;

use Meritoo\Common\Collection\Templates;
use Meritoo\MenuBundle\Domain\Item;
use Meritoo\MenuBundle\Domain\Link;
use Meritoo\MenuBundle\Domain\Menu;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Runtime class related to MenuExtension Twig Extension.
 * Required to create lazy-loaded Twig Extension.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MenuRuntime implements RuntimeExtensionInterface
{
    /**
     * Renders menu bar with given items
     *
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of item
     * @param array|null $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @return string
     */
    public function renderMenuBar(array $links, ?array $menuAttributes = null): string
    {
        $menu = Menu::create($links, $menuAttributes);

        if (null === $menu) {
            return '';
        }

        // todo Load templates from configuration
        $templates = Templates::fromArray([
            Link::class => '<a href="%url%"%attributes%>%name%</a>',
            Item::class => '<div%attributes%>%link%</div>',
            Menu::class => '<div%attributes%>%items%</div>',
        ]);

        return $menu->render($templates);
    }
}
