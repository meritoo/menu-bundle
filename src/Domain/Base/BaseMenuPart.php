<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\MenuBundle\Domain\Base;

use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Renderable\RenderableInterface;

/**
 * Part of menu, e.g. link, item.
 * Base class for any of menu part.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class BaseMenuPart implements RenderableInterface
{
    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        return $this->findAndFillTemplate($templates);
    }

    /**
     * Prepares and returns values used to fill template while rendering
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return array
     */
    abstract protected function prepareTemplateValues(Templates $templates): array;

    /**
     * Finds and fills template used while rendering
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return string
     */
    private function findAndFillTemplate(Templates $templates): string
    {
        $template = $templates->findTemplate(static::class);
        $values = $this->prepareTemplateValues($templates);

        return $template->fill($values);
    }
}
