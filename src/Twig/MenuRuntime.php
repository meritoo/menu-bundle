<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Twig;

use Meritoo\Common\Collection\Templates;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\Visitor\Visitor;
use Meritoo\MenuBundle\Visitor\Factory\DefaultVisitorFactory;
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
     * Template of link in menu
     *
     * @var string
     */
    private $linkTemplate;

    /**
     * Template of container for a link
     *
     * @var string
     */
    private $linkContainerTemplate;

    /**
     * Template of the whole menu (has containers with links)
     *
     * @var string
     */
    private $menuTemplate;

    /**
     * Class constructor
     *
     * @param string $linkTemplate          Template of link in menu
     * @param string $linkContainerTemplate Template of container for a link
     * @param string $menuTemplate          Template of the whole menu (has containers with links)
     */
    public function __construct(string $linkTemplate, string $linkContainerTemplate, string $menuTemplate)
    {
        $this->linkTemplate = $linkTemplate;
        $this->linkContainerTemplate = $linkContainerTemplate;
        $this->menuTemplate = $menuTemplate;
    }

    /**
     * Renders menu with given links
     *
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of link's container
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @return string
     */
    public function renderMenu(array $links, ?array $menuAttributes = null): string
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
            Link::class          => $this->linkTemplate,
            LinkContainer::class => $this->linkContainerTemplate,
            Menu::class          => $this->menuTemplate,
        ]);
    }
}
