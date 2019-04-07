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
 * Link
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Link implements RenderableInterface
{
    /**
     * Name of link
     *
     * @var string
     */
    private $name;

    /**
     * Url of link
     *
     * @var string
     */
    private $url;

    /**
     * Class constructor
     *
     * @param string $name Name of link
     * @param string $url  Url of link
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        // Link without name won't be rendered
        if ('' === $this->name) {
            return '';
        }

        $template = $templates->findTemplate(static::class);

        return $template->fill([
            'name' => $this->name,
            'url'  => $this->url,
        ]);
    }
}
