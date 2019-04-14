<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Domain;

use Meritoo\Common\Collection\Templates;
use Meritoo\MenuBundle\Domain\Base\BaseMenuPart;

/**
 * Menu. Contains items.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Menu extends BaseMenuPart
{
    /**
     * Menu's items
     *
     * @var Item[]
     */
    private $items;

    /**
     * Rendered and prepared to display menu's items
     *
     * @var string
     */
    private $itemsRendered;

    /**
     * Class constructor
     *
     * @param Item[] $items Menu's items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Creates new menu
     *
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of item
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @return null|Menu
     */
    public static function create(array $links, ?array $menuAttributes = null): ?Menu
    {
        if (empty($links)) {
            return null;
        }

        $items = [];

        foreach ($links as $link) {
            $name = $link[0] ?? '';
            $url = $link[1] ?? '';

            $linkAttributes = $link[2] ?? null;
            $itemAttributes = $link[3] ?? null;

            $items[] = Item::create($name, $url, $linkAttributes, $itemAttributes);
        }

        $menu = new static($items);

        if (null !== $menuAttributes) {
            $menu->addAttributes($menuAttributes);
        }

        return $menu;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        // Menu without items?
        if (empty($this->items)) {
            return '';
        }

        $rendered = $this->renderItems($templates);

        // Items are rendered, but menu is empty?
        if ('' === $rendered) {
            return '';
        }

        return parent::render($templates);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        $rendered = $this->renderItems($templates);

        return [
            'items' => $rendered,
        ];
    }

    /**
     * Renders menu's items
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return string
     */
    private function renderItems(Templates $templates): string
    {
        if (null === $this->itemsRendered) {
            $this->itemsRendered = '';

            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    $this->itemsRendered .= $item->render($templates);
                }

                $this->itemsRendered = trim($this->itemsRendered);
            }
        }

        return $this->itemsRendered;
    }
}
