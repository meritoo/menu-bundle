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
     * @param array $linksNamesUrls Pairs of key-value where: key - name of link, value - url of link
     * @return Menu|null
     */
    public static function create(array $linksNamesUrls): ?Menu
    {
        if (empty($linksNamesUrls)) {
            return null;
        }

        $items = [];

        foreach ($linksNamesUrls as $name => $url) {
            $items[] = Item::create($name, $url);
        }

        return new static($items);
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
