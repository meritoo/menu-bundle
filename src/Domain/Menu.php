<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Domain;

use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Renderable\RenderableInterface;

/**
 * Menu. Contains items.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Menu implements RenderableInterface
{
    /**
     * Menu's items
     *
     * @var Item[]
     */
    private $items;

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
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        // Menu without items?
        if (empty($this->items)) {
            return '';
        }

        $rendered = '';

        foreach ($this->items as $item) {
            $rendered .= $item->render($templates);
        }

        $rendered = trim($rendered);

        // Items are rendered, but menu is empty?
        if ('' === $rendered) {
            return '';
        }

        $template = $templates->findTemplate(static::class);

        return $template->fill([
            'items' => $rendered,
        ]);
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
}
