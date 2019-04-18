<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Twig;

use Meritoo\Common\Collection\Templates;
use Meritoo\Menu\Item;
use Meritoo\Menu\Link;
use Meritoo\Menu\Menu;
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
     * Template for a link in menu
     *
     * @var string
     */
    private $linkTemplate;

    /**
     * Template for an item in menu (container for a link)
     *
     * @var string
     */
    private $itemTemplate;

    /**
     * Template for the whole menu (container for items)
     *
     * @var string
     */
    private $menuTemplate;

    /**
     * Class constructor
     *
     * @param string $linkTemplate Template for a link in menu
     * @param string $itemTemplate Template for an item in menu (container for a link)
     * @param string $menuTemplate Template for the whole menu (container for items)
     */
    public function __construct(string $linkTemplate, string $itemTemplate, string $menuTemplate)
    {
        $this->linkTemplate = $linkTemplate;
        $this->itemTemplate = $itemTemplate;
        $this->menuTemplate = $menuTemplate;
    }

    /**
     * Renders menu bar with given items
     *
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of item
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @return string
     */
    public function renderMenuBar(array $links, ?array $menuAttributes = null): string
    {
        $menu = Menu::create($links, $menuAttributes);

        if (null === $menu) {
            return '';
        }

        return $menu->render($this->prepareTemplates());
    }

    /**
     * Prepares templates used to render menu
     *
     * @return Templates
     */
    private function prepareTemplates(): Templates
    {
        return Templates::fromArray([
            Link::class => $this->linkTemplate,
            Item::class => $this->itemTemplate,
            Menu::class => $this->menuTemplate,
        ]);
    }
}
