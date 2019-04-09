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
 * Item of menu. Contains link.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Item extends BaseMenuPart
{
    /**
     * Item's link
     *
     * @var Link
     */
    private $link;

    /**
     * Rendered and prepared to display item's link
     *
     * @var string
     */
    private $linkRendered;

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

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        $linkRendered = $this->renderLink($templates);

        return [
            'link' => $linkRendered,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        $linkRendered = $this->renderLink($templates);

        // Item without link?
        if ('' === $linkRendered) {
            return '';
        }

        return parent::render($templates);
    }

    /**
     * Renders item's link
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return string
     */
    private function renderLink(Templates $templates): string
    {
        if (null === $this->linkRendered) {
            $this->linkRendered = $this->link->render($templates);
        }

        return $this->linkRendered;
    }
}
