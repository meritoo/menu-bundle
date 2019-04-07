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
     * @param array $linksNamesUrls Pairs of key-value where: key - name of link, value - url of link
     * @return string
     */
    public function renderMenuBar(array $linksNamesUrls): string
    {
        $menu = Menu::create($linksNamesUrls);

        if (null === $menu) {
            return '';
        }

        // todo Load templates from configuration
        $templates = Templates::fromArray([
            Link::class => '<a href="%url%">%name%</a>',
            Item::class => '<div class="item">%link%</div>',
            Menu::class => '<div class="container">%items%</div>',
        ]);

        return $menu->render($templates);
    }
}
