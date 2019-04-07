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
 * Item of menu. Contains link.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Item implements RenderableInterface
{
    /**
     * Item's link
     *
     * @var Link
     */
    private $link;

    /**
     * Class constructor
     *
     * @param Link $link Item's link
     */
    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        $linkRendered = $this->link->render($templates);

        // Item with not rendered link won't be rendered
        if ('' === $linkRendered) {
            return '';
        }

        $template = $templates->findTemplate(static::class);

        return $template->fill([
            'link' => $linkRendered,
        ]);
    }

    /**
     * Creates new item
     *
     * @param string $linkName Name of item's link
     * @param string $linkUrl  Url of item's link
     * @return Item
     */
    public static function create(string $linkName, string $linkUrl): Item
    {
        $link = new Link($linkName, $linkUrl);

        return new static($link);
    }
}
